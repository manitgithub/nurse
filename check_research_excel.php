<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('/Users/manit/Documents/GitHub/nurse/วิจัย.xlsx');
echo "SHEETS: " . implode(', ', $spreadsheet->getSheetNames()) . "\n\n";

$sheet = $spreadsheet->getSheetByName('Sheet1');
if ($sheet) {
    $sheetData = $sheet->toArray(null, true, true, true);
    for ($i = 1; $i <= 5; $i++) {
        if (isset($sheetData[$i])) {
            echo "ROW $i: " . json_encode($sheetData[$i], JSON_UNESCAPED_UNICODE) . "\n";
        }
    }
} else {
    echo "Sheet1 not found.\n";
}
