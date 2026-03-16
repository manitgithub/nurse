<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';

$config = require 'config/console.php';
(new yii\console\Application($config));

use PhpOffice\PhpSpreadsheet\IOFactory;
use app\models\Innovation;
use app\models\InnovationFile;

$inputFileName = '/Users/manit/Documents/GitHub/nurse/innovation.xlsx';

echo "Loading $inputFileName...\n";
$spreadsheet = IOFactory::load($inputFileName);
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

echo "Clearing existing innovations and innovation_files...\n";
InnovationFile::deleteAll();
Innovation::deleteAll();

$successCount = 0;
$errorCount = 0;

echo "Starting import...\n";
foreach ($sheetData as $rowIndex => $row) {
    // Skip empty rows and headers (first 3 rows based on our inspection, or row where C is empty/header)
    if ($rowIndex <= 3 || empty($row['C']) || $row['C'] == 'ชื่อนวัตกรรม') {
        continue;
    }

    $model = new Innovation();

    // Date format example: '11/11/2563' -> DD/MM/YYYY
    $rawDate = trim((string) $row['C']);
    $parsedDate = null;
    if (!empty($rawDate)) {
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $rawDate, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            if ($day == '00')
                $day = '01';
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            if ($month == '00')
                $month = '01';
            $yearPart = (int) $matches[3];
            if ($yearPart == 0) {
                $parsedDate = '1970-01-01';
            } else {
                $year = $yearPart > 2500 ? $yearPart - 543 : $yearPart;
                $parsedDate = "$year-$month-$day";
            }
        } elseif (preg_match('/^(\d{4})$/', $rawDate, $matches)) {
            $yearPart = (int) $matches[1];
            if ($yearPart == 0) {
                $parsedDate = '1970-01-01';
            } else {
                $year = $yearPart > 2500 ? $yearPart - 543 : $yearPart;
                $parsedDate = "$year-01-01";
            }
        } elseif (preg_match('/^(\d{1,2})\/(\d{4})$/', $rawDate, $matches)) { // mm/yyyy
            $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            if ($month == '00')
                $month = '01';
            $yearPart = (int) $matches[2];
            if ($yearPart == 0) {
                $parsedDate = '1970-01-01';
            } else {
                $year = $yearPart > 2500 ? $yearPart - 543 : $yearPart;
                $parsedDate = "$year-$month-01";
            }
        } else {
            $parsedDate = null;
        }
    }

    if (empty($parsedDate) || $parsedDate == '0-00-00' || strpos($parsedDate, '0000') !== false) {
        $parsedDate = '1970-01-01';
    }
    $model->invention_date = $parsedDate;

    $model->name = (string) $row['B'];
    $model->problem = (string) $row['D'];
    $model->process = (string) $row['E'];
    $model->results = (string) $row['F'];
    $model->advisor = (string) $row['G'];
    $model->developers = (string) $row['H'];

    echo "Row $rowIndex parsedDate: " . $model->invention_date . " | raw: $rawDate\n";

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
