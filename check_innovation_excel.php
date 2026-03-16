<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('/Users/manit/Documents/GitHub/nurse/innovation.xlsx');
echo "SHEETS: " . implode(', ', $spreadsheet->getSheetNames()) . "\n\n";

$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
for ($i = 1; $i <= 5; $i++) {
    if (isset($sheetData[$i])) {
        echo "ROW $i: " . json_encode($sheetData[$i], JSON_UNESCAPED_UNICODE) . "\n";
    }
}
