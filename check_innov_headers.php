<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('/Users/manit/Documents/GitHub/nurse/innovation.xlsx');
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
echo "Headers (Row 2/3):\n";
print_r($sheetData[2]);
print_r($sheetData[3]);
