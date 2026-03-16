<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('/Users/manit/Documents/GitHub/nurse/innovation.xlsx');
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
echo "Row 4 data:\n";
print_r($sheetData[4]);
echo "\nValue of C: '" . $sheetData[4]['C'] . "'\n";
