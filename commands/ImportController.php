<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\models\AcademicService;

class ImportController extends Controller
{
    public function actionAcademicService($filePath = '/Users/manit/Desktop/project.xlsx', $clear = true)
    {
        if (!file_exists($filePath)) {
            echo "File not found: $filePath\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }

        if ($clear) {
            echo "Clearing existing data...\n";
            AcademicService::deleteAll();
        }

        echo "Loading file: $filePath\n";
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        // Header is on the first row
        $header = array_shift($data);
        
        $count = 0;
        $errors = 0;

        foreach ($data as $row) {
            if (empty($row['B'])) continue; // Skip empty rows (Activity name)

            $model = new AcademicService();
            $model->activity_name = $row['B'];
            $model->fiscal_year = $this->normalizeThaiNumerals($row['C']);
            $model->project_type = $row['D'];
            $model->budget_source = $row['E'] !== null ? mb_substr((string)$row['E'], 0, 100) : null;
            $model->budget_amount = $this->parseNumber($row['F']);
            $model->start_date = $this->parseThaiDate($row['G']);
            $model->end_date = $this->parseThaiDate($row['H']);
            $model->strategic_number = $row['I'];
            $model->qa_indicator_1 = $row['J'];
            $model->responsible_person = $row['K'];
            $model->target_group = $row['L'];
            $model->participants_count = $this->parseNumber($row['M']);
            $model->budget_category = $row['N'];
            $model->project_focus = $row['O'];
            $model->qa_indicator_2 = $row['P'] ?? null;
            $model->qa_indicator_3 = $row['Q'] ?? null;
            $model->qa_indicator_4 = $row['R'] ?? null;
            $model->qa_indicator_5 = $row['S'] ?? null;
            
            // Integration mapping
            $model->integration_teaching = ($row['AF'] == '1');
            $model->integration_teaching_subject = $row['AG'];
            $model->integration_teaching_semester = $row['AH'];
            $model->integration_student_activity = ($row['AI'] == '1');
            $model->integration_student_activity_desc = $row['AJ'] !== null ? mb_substr((string)$row['AJ'], 0, 500) : null;
            $model->integration_academic_service = ($row['AK'] == '1');
            $model->integration_academic_service_desc = $row['AL'] !== null ? mb_substr((string)$row['AL'], 0, 500) : null;
            $model->integration_research = ($row['AM'] == '1');
            $model->integration_research_desc = $row['AN'] !== null ? mb_substr((string)$row['AN'], 0, 500) : null;

            // Geocoding based on target_group or activity_name
            $coords = $this->geocode($model->target_group . ' ' . $model->activity_name);
            if ($coords) {
                $model->latitude = $coords['lat'];
                $model->longitude = $coords['lng'];
            }

            if ($model->save()) {
                $count++;
            } else {
                echo "Error saving row " . ($count + $errors + 2) . ": " . $model->activity_name . "\n";
                print_r($model->getErrors());
                $errors++;
            }
        }

        echo "Imported $count records successfully. Fails: $errors.\n";
        return ExitCode::OK;
    }

    private function geocode($text)
    {
        if (empty($text)) return null;

        $locations = [
            'วัดโคกเหล็ก' => ['lat' => 8.6472, 'lng' => 99.9075],
            'โรงเรียนวัดโคกเหล็ก' => ['lat' => 8.6472, 'lng' => 99.9075],
            'บ้านโคกเหล็ก' => ['lat' => 8.6472, 'lng' => 99.9075],
            'รพ.สต.บ้านหาร' => ['lat' => 8.7891, 'lng' => 99.9328],
            'มหาวิทยาลัยวลัยลักษณ์' => ['lat' => 8.6379, 'lng' => 99.8917],
            'มวล.' => ['lat' => 8.6379, 'lng' => 99.8917],
            'รพ.ท่าศาลา' => ['lat' => 8.6667, 'lng' => 99.9167],
            'วัดคลองดิน' => ['lat' => 8.6300, 'lng' => 99.9200],
            'ท่าสูง' => ['lat' => 8.6800, 'lng' => 99.9500],
            'สิชล' => ['lat' => 9.0000, 'lng' => 99.9000],
            'มหาราช' => ['lat' => 8.4167, 'lng' => 99.9500],
            'นครศรีธรรมราช' => ['lat' => 8.4333, 'lng' => 99.9667],
            'ตำบลไทยบุรี' => ['lat' => 8.6379, 'lng' => 99.8917],
            'หอผู้ป่วย' => ['lat' => 8.6667, 'lng' => 99.9167], // Assume Tha Sala hospital area
        ];

        foreach ($locations as $key => $coord) {
            if (mb_stripos($text, $key) !== false) {
                return $coord;
            }
        }

        return null;
    }

    private function parseNumber($val)
    {
        if ($val === null || $val === '') return null;
        $val = $this->normalizeThaiNumerals($val);
        if (is_numeric($val)) return $val;
        return preg_replace('/[^0-9.]/', '', $val);
    }

    private function normalizeThaiNumerals($str)
    {
        if ($str === null) return null;
        $thai = ['๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'];
        $arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($thai, $arabic, (string)$str);
    }

    private function parseThaiDate($dateStr)
    {
        if (empty($dateStr)) return null;

        $dateStr = $this->normalizeThaiNumerals($dateStr);

        $months = [
            'มค' => '01', 'ม.ค.' => '01',
            'กพ' => '02', 'ก.พ.' => '02',
            'มีค' => '03', 'มี.ค.' => '03',
            'เมย' => '04', 'เม.ย.' => '04',
            'พค' => '05', 'พ.ค.' => '05',
            'มิย' => '06', 'มิ.ย.' => '06',
            'กค' => '07', 'ก.ค.' => '07',
            'สค' => '08', 'ส.ค.' => '08',
            'กย' => '09', 'ก.ย.' => '09',
            'ตค' => '10', 'ต.ค.' => '10',
            'พย' => '11', 'พ.ย.' => '11',
            'ธค' => '12', 'ธ.ค.' => '12',
            'มกราคม' => '01', 'กุมภาพันธ์' => '02', 'มีนาคม' => '03', 'เมษายน' => '04',
            'พฤษภาคม' => '05', 'มิถุนายน' => '06', 'กรกฎาคม' => '07', 'สิงหาคม' => '08',
            'กันยายน' => '09', 'ตุลาคม' => '10', 'พฤศจิกายน' => '11', 'ธันวาคม' => '12'
        ];

        $parts = preg_split('/\s+/', trim($dateStr));
        if (count($parts) < 3) return null;

        $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
        $month = $months[$parts[1]] ?? '01';
        $year = (int)$parts[2];

        // Handle Thai Year (B.E.) - assuming it's 2 digits or 4 digits
        if ($year < 100) {
            $year += 2500;
        }
        
        if ($year > 2400) {
            $year -= 543; // Convert to A.D.
        }

        return "$year-$month-$day";
    }
}
