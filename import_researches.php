<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';

$config = require 'config/console.php';
(new yii\console\Application($config));

use PhpOffice\PhpSpreadsheet\IOFactory;
use app\models\Research;
use app\models\ResearchFile;

$inputFileName = '/Users/manit/Documents/GitHub/nurse/วิจัย.xlsx';

echo "Loading $inputFileName...\n";
$spreadsheet = IOFactory::load($inputFileName);
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

echo "Clearing existing researches and research_files...\n";
ResearchFile::deleteAll();
Research::deleteAll();

$successCount = 0;
$errorCount = 0;

$workStatusMap = [
    '1' => 'เผยแพร่แล้ว',
    '2' => 'อยู่ระหว่างดำเนินการ'
];

$fundingStatusMap = [
    '1' => 'ภายใน',
    '2' => 'ภายนอก'
];

$publishLevelMap = [
    '1' => 'ระดับชาติ',
    '2' => 'ระดับนานาชาติ'
];

echo "Starting import...\n";
foreach ($sheetData as $rowIndex => $row) {
    // Skip header or empty rows (if A is empty and C is empty, skip)
    if (empty($row['C']) || $row['C'] == 'ชื่อผลงาน') {
        continue;
    }

    $model = new Research();
    $model->title = (string) $row['C'];
    $model->year = (string) $row['D'];

    $ws = (string) $row['E'];
    $model->work_status = isset($workStatusMap[$ws]) ? $workStatusMap[$ws] : $ws;

    $model->authors = (string) $row['F'];

    $fs = (string) $row['H'];
    $model->funding_status = isset($fundingStatusMap[$fs]) ? $fundingStatusMap[$fs] : $fs;

    $model->funding_source = (string) ($row['I'] ?? '');
    $model->budget = (float) str_replace(',', '', $row['J'] ?? 0);
    $model->duration = (string) ($row['K'] ?? '');
    $model->tier = (string) ($row['L'] ?? '');

    $pl = (string) $row['M'];
    $model->publish_level = isset($publishLevelMap[$pl]) ? $publishLevelMap[$pl] : $pl;

    $model->result_publication = (string) ($row['N'] ?? '');

    if ($model->save()) {
        $successCount++;
    } else {
        $errorCount++;
        echo "Error Row $rowIndex: " . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE) . "\n";
    }
}

echo "\nImport finished.\n";
echo "Success: $successCount\n";
echo "Errors: $errorCount\n";
