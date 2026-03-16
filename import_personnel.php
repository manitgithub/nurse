<?php
// Load Yii2 bootstrap
define('YII_DEBUG', true);
define('YII_ENV', 'dev');
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/web.php';
(new yii\web\Application($config));

use app\models\Personnel;
use app\models\Qualification;
use app\models\ContractType;
use yii\db\Expression;

$filePath = '/Users/manit/Desktop/หน้าหลัก-รายงานสรุปข้อมูลบุคลากรสำนักวิชาพยาบาลศาสตร์_ข้อมูลพื้นฐาน_ตาราง (1).csv';

// Helper function to parse Thai date (e.g., "17 ต.ค. 2540")
function parseThaiDate($dateStr)
{
    if (empty($dateStr))
        return null;
    $months = [
        'ม.ค.' => '01',
        'ก.พ.' => '02',
        'มี.ค.' => '03',
        'เม.ย.' => '04',
        'พ.ค.' => '05',
        'มิ.ย.' => '06',
        'ก.ค.' => '07',
        'ส.ค.' => '08',
        'ก.ย.' => '09',
        'ต.ค.' => '10',
        'พ.ย.' => '11',
        'ธ.ค.' => '12'
    ];
    $parts = explode(' ', trim($dateStr));
    if (count($parts) == 3) {
        $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
        $month = $months[$parts[1]] ?? '01';
        $year = (int) $parts[2] - 543; // Convert Buddhist year to AD
        return "$year-$month-$day";
    }
    return null;
}

// Helper to parse slash date (e.g., "01/10/2567")
function parseSlashDate($dateStr)
{
    if (empty($dateStr))
        return null;
    $parts = explode('/', trim($dateStr));
    if (count($parts) == 3) {
        $day = $parts[0];
        $month = $parts[1];
        $year = (int) $parts[2] - 543;
        return "$year-$month-$day";
    }
    return null;
}

try {
    echo "Clearing existing personnel data...\n";
    Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0;")->execute();
    Personnel::deleteAll();
    Yii::$app->db->createCommand("TRUNCATE TABLE personnels;")->execute();
    Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 1;")->execute();

    if (($handle = fopen($filePath, "r")) !== FALSE) {
        // Skip BOM if present
        $bom = fread($handle, 3);
        if ($bom != "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $headers = fgetcsv($handle, 1000, ",", "\"", "\\");
        $successCount = 0;
        $errorCount = 0;

        while (($row = fgetcsv($handle, 1000, ",", "\"", "\\")) !== FALSE) {
            $code = trim($row[0]);
            $genderLabel = trim($row[1]);
            $fullname = trim($row[2]);
            $qualLabel = trim($row[3]);
            $contractLabel = trim($row[4]);
            $startDateStr = trim($row[5]);
            $endDateStr = trim($row[7]);

            if (empty($code))
                continue;

            $model = new Personnel();
            $model->personnel_code = $code;
            $model->fullname = $fullname;
            $model->gender = ($genderLabel == 'ชาย' ? 'male' : 'female');

            // Qualification Lookup/Create
            if (!empty($qualLabel)) {
                $qual = Qualification::findOne(['name' => $qualLabel]);
                if (!$qual) {
                    $qual = new Qualification(['name' => $qualLabel, 'status' => 1]);
                    $qual->save();
                }
                $model->qualification_id = $qual->id;
            }

            // Contract Type Lookup/Create
            if (!empty($contractLabel)) {
                $ct = ContractType::findOne(['name' => $contractLabel]);
                if (!$ct) {
                    $ct = new ContractType(['name' => $contractLabel, 'status' => 1]);
                    $ct->save();
                }
                $model->contract_type_id = $ct->id;
            }

            $model->start_date = parseThaiDate($startDateStr);
            $model->contract_end_date = parseSlashDate($endDateStr);
            $model->status = 1; // Active

            if ($model->save()) {
                $successCount++;
            } else {
                echo "Error saving $fullname ($code): " . implode(', ', $model->getFirstErrors()) . "\n";
                $errorCount++;
            }
        }
        fclose($handle);
        echo "Import Complete: $successCount imported, $errorCount errors.\n";
    }
} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
