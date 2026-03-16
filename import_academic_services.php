<?php
// Load Yii2 bootstrap
define('YII_DEBUG', true);
define('YII_ENV', 'dev');
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/web.php';
(new yii\web\Application($config));

use app\models\AcademicService;
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = '/Users/manit/Documents/GitHub/nurse/project.xlsx';

function parseThaiDateExcel($dateStr)
{
    if (empty($dateStr) || trim((string) $dateStr) == 'NULL')
        return null;

    $months = [
        'มค' => '01',
        'ม.ค.' => '01',
        'กพ' => '02',
        'ก.พ.' => '02',
        'มีค' => '03',
        'มี.ค.' => '03',
        'เมย' => '04',
        'เม.ย.' => '04',
        'พค' => '05',
        'พ.ค.' => '05',
        'มิย' => '06',
        'มิ.ย.' => '06',
        'กค' => '07',
        'ก.ค.' => '07',
        'สค' => '08',
        'ส.ค.' => '08',
        'กย' => '09',
        'ก.ย.' => '09',
        'ตค' => '10',
        'ต.ค.' => '10',
        'พย' => '11',
        'พ.ย.' => '11',
        'ธค' => '12',
        'ธ.ค.' => '12'
    ];

    $dateStr = trim((string) $dateStr);
    $parts = explode(' ', $dateStr);
    if (count($parts) == 3) {
        $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
        $month = $months[$parts[1]] ?? '01';
        $yearPart = $parts[2];
        if (strlen($yearPart) == 2) {
            $year = 2500 + (int) $yearPart - 543;
        } else {
            $year = (int) $yearPart - 543;
        }
        return "$year-$month-$day";
    }
    return null;
}

function cleanParticipants($val)
{
    if (empty($val))
        return 0;
    return (int) preg_replace('/[^0-9]/', '', (string) $val);
}

try {
    echo "Clearing existing academic services data...\n";
    Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0;")->execute();
    AcademicService::deleteAll();
    Yii::$app->db->createCommand("TRUNCATE TABLE academic_services;")->execute();
    Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 1;")->execute();

    echo "Loading Excel file...\n";
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getSheetByName('project');
    if (!$sheet) {
        throw new Exception("Sheet 'project' not found.");
    }

    $data = $sheet->toArray(null, true, true, true);
    $successCount = 0;
    $errorCount = 0;

    foreach ($data as $index => $row) {
        // Skip header if any
        if (empty($row['B']) || $row['B'] == 'กิจกรรม')
            continue;

        $model = new AcademicService();
        $model->activity_name = trim((string) ($row['B'] ?? ''));
        $model->fiscal_year = (string) ($row['C'] ?? '');
        $model->project_type = (string) ($row['D'] ?? '');
        $model->project_focus = (string) ($row['E'] ?? '');
        $model->budget_amount = (float) str_replace(',', '', (string) ($row['F'] ?? '0'));
        $model->start_date = parseThaiDateExcel($row['G'] ?? null);
        $model->end_date = parseThaiDateExcel($row['H'] ?? null);
        $model->responsible_person = (string) ($row['K'] ?? '');
        $model->target_group = (string) ($row['L'] ?? '');
        $model->participants_count = cleanParticipants($row['M'] ?? null);
        $model->budget_source = (string) ($row['N'] ?? '');
        $model->strategy_link = (string) ($row['J'] ?? '');

        $model->status = 'ดำเนินการเสร็จสิ้นแล้ว';

        if ($model->save()) {
            $successCount++;
        } else {
            echo "Error saving row $index: " . implode(', ', $model->getFirstErrors()) . "\n";
            $errorCount++;
        }
    }

    echo "Import Complete: $successCount imported, $errorCount errors.\n";

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
