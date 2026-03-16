<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
(new yii\web\Application($config));

use app\models\AcademicService;

$services = AcademicService::find()->all();

$provinces = [
    'นครศรีธรรมราช' => [8.4333, 99.9667],
    'ท่าศาลา' => [8.6667, 99.9333],
    'สุราษฎร์ธานี' => [9.1333, 99.3167],
    'ทุ่งสง' => [8.1667, 99.6833],
    'สิชล' => [9.0167, 99.9],
    'ขนอม' => [9.2, 99.8667],
    'หัวไทร' => [8.0333, 100.2833],
    'สงขลา' => [7.2, 100.5833],
    'ตรัง' => [7.55, 99.6],
    'พัทลุง' => [7.6167, 100.0833],
    'ภูเก็ต' => [7.8833, 98.4],
    'กระบี่' => [8.0667, 98.9167],
    'สมุย' => [9.5, 100.0],
    'พะงัน' => [9.7167, 100.0167],
    'ชุมพร' => [10.4833, 99.1833],
    'ระนอง' => [9.9667, 98.6333],
    'พังงา' => [8.45, 98.5333],
    'ชลบุรี' => [13.3667, 100.9833],
    'กรุงเทพ' => [13.7563, 100.5018],
    'ขอนแก่น' => [16.4333, 102.8333],
    'เชียงใหม่' => [18.7833, 98.9833],
    'ปัตตานี' => [6.8667, 101.25],
    'ยะลา' => [6.5333, 101.2833],
    'นราธิวาส' => [6.4333, 101.8167],
    'สตูล' => [6.6167, 100.0667],
    'นครราชสีมา' => [14.9667, 102.1],
];

// Default coordinate: Walailak University area
$defaultLat = 8.6455;
$defaultLng = 99.8966;

$updated = 0;
$missing = 0;

foreach ($services as $service) {
    if (!$service->latitude || !$service->longitude) {
        $missing++;
        $matched = false;

        foreach ($provinces as $name => $coords) {
            // Check both activity_name and target_group
            if (mb_strpos($service->activity_name, $name) !== false || mb_strpos((string) $service->target_group, $name) !== false) {
                // Add tiny random jitter (approx up to ~1km) to avoid overlapping exactly
                $lat = $coords[0] + (mt_rand(-100, 100) / 10000);
                $lng = $coords[1] + (mt_rand(-100, 100) / 10000);

                $service->latitude = $lat;
                $service->longitude = $lng;
                $matched = true;
                echo "Matched: {$name} -> {$service->activity_name}\n";
                break;
            }
        }

        if (!$matched) {
            // Default with random jitter around Walailak so they don't all stack
            // 0.05 is roughly 5km
            $lat = $defaultLat + (mt_rand(-500, 500) / 10000);
            $lng = $defaultLng + (mt_rand(-500, 500) / 10000);

            $service->latitude = $lat;
            $service->longitude = $lng;
            echo "Defaulted: {$service->activity_name}\n";
        }

        if ($service->save(false)) {
            $updated++;
        }
    }
}

echo "Total updated: $updated from $missing missing coordinates.\n";
