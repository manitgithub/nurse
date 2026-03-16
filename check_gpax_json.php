<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
(new yii\web\Application($config));

use app\models\StudentGrade;
$avgGpaxByStudent = StudentGrade::find()
    ->select(['student_grades.student_id', 's.student_id as s_id', 's.fullname', 's.batch', 'AVG(student_grades.gpax) as avg_gpax'])
    ->leftJoin('students s', 'student_grades.student_id = s.student_id')
    ->groupBy(['student_grades.student_id', 's.student_id', 's.fullname', 's.batch'])
    ->asArray()
    ->all();

$gpaxStudentsList = ['total' => ['r1' => [], 'r2' => [], 'r3' => [], 'r4' => []]]; // Hold actual students

foreach ($avgGpaxByStudent as $row) {
    if (empty($row['s_id']))
        continue; // Skip if no matching student record

    $val = (float) $row['avg_gpax'];
    $batch = $row['batch'];
    $studentData = [
        'id' => $row['s_id'],
        'name' => $row['fullname'] ?? 'ไม่ระบุชื่อ'
    ];

    $range = null;
    if ($val >= 3.5)
        $range = 'r1';
    elseif ($val >= 3.0)
        $range = 'r2';
    elseif ($val >= 2.5)
        $range = 'r3';
    elseif ($val >= 2.0)
        $range = 'r4';

    if ($range) {
        $gpaxStudentsList['total'][$range][] = $studentData; // Add to total list

        if ($batch) {
            if (!isset($gpaxStudentsList[$batch])) {
                $gpaxStudentsList[$batch] = ['r1' => [], 'r2' => [], 'r3' => [], 'r4' => []];
            }
            $gpaxStudentsList[$batch][$range][] = $studentData; // Add to batch list
        }
    }
}

echo json_encode($gpaxStudentsList);
