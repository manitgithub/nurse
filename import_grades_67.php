<?php
// Load Yii2 bootstrap
define('YII_DEBUG', true);
define('YII_ENV', 'dev');
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/web.php';
(new yii\web\Application($config));

use app\models\Student;
use app\models\StudentGrade;
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = '/Users/manit/Desktop/67.xlsx';
try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    $headers = $data[0];
    $successCount = 0;
    $errorCount = 0;

    for ($i = 1; $i < count($data); $i++) {
        $row = $data[$i];
        $studentId = trim($row[0]);
        if (empty($studentId))
            continue;

        // Check if student exists
        $student = Student::findOne($studentId);
        if (!$student) {
            echo "Warning: Student $studentId not found. Skipping.\n";
            $errorCount++;
            continue;
        }

        // Columns 1 to end are terms/years
        for ($j = 1; $j < count($headers); $j++) {
            $academicYear = $headers[$j]; // e.g., "1/2566"
            $gpax = $row[$j];

            if ($gpax !== null && $gpax !== '') {
                $model = StudentGrade::findOne(['student_id' => $studentId, 'academic_year' => $academicYear]) ?: new StudentGrade();
                $model->student_id = $studentId;
                $model->academic_year = $academicYear;
                $model->gpax = (float) $gpax;

                if ($model->save()) {
                    $successCount++;
                } else {
                    echo "Error: Failed to save grade for $studentId ($academicYear): " . implode(', ', $model->getFirstErrors()) . "\n";
                    $errorCount++;
                }
            }
        }
    }
    echo "Import Complete: $successCount grades imported/updated. $errorCount errors/skipped.\n";
} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
