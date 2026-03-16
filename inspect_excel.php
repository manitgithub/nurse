<?php
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = '/Users/manit/Desktop/67.xlsx';
try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    echo "Sheet Data (First 5 rows):\n";
    for ($i = 0; $i < min(5, count($data)); $i++) {
        echo "Row $i: " . json_encode($data[$i]) . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
