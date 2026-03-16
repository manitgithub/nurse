<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('/Users/manit/Documents/GitHub/nurse/innovation.xlsx');
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
$row = $sheetData[4];
$rawDate = trim((string) $row['C']);
$parsedDate = null;

if (!empty($rawDate)) {
    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $rawDate, $matches)) {
        $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
        $yearPart = (int) $matches[3];
        $year = $yearPart > 2500 ? $yearPart - 543 : $yearPart;
        $parsedDate = "$year-$month-$day";
        echo "Matched format 1: $parsedDate\n";
    } elseif (preg_match('/^(\d{4})$/', $rawDate, $matches)) {
        $yearPart = (int) $matches[1];
        $year = $yearPart > 2500 ? $yearPart - 543 : $yearPart;
        $parsedDate = "$year-01-01";
        echo "Matched format 2: $parsedDate\n";
    } elseif (preg_match('/^(\d{1,2})\/(\d{4})$/', $rawDate, $matches)) { // mm/yyyy
        $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        $yearPart = (int) $matches[2];
        $year = $yearPart > 2500 ? $yearPart - 543 : $yearPart;
        $parsedDate = "$year-$month-01";
        echo "Matched format 3: $parsedDate\n";
    } else {
        $parsedDate = null;
        echo "Did not match. Raw: '$rawDate'\n";
    }
}

if (empty($parsedDate)) {
    $parsedDate = '1970-01-01';
}

echo "Final date: $parsedDate\n";
