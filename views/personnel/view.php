<?php
use yii\helpers\Html;
$this->title = $model->fullname;
$genderLabels = \app\models\Personnel::getGenderList();

$thaiDate = function($date) {
    if (empty($date) || $date == '0000-00-00') return '-';
    try {
        $dt = new \DateTime($date);
        $thai_months = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
        return $dt->format('j') . ' ' . $thai_months[(int)$dt->format('n')] . ' ' . ((int)$dt->format('Y') + 543);
    } catch (\Exception $e) {
        return '-';
    }
};
?>
<?php
// Small helper to avoid using strtotime() on platforms where epoch may overflow (32-bit)
if (!function_exists('_isDatePast')) {
    function _isDatePast($dateStr)
    {
        if (empty($dateStr)) return false;
        $s = trim((string)$dateStr);
        // ISO yyyy-mm-dd
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $s, $m)) {
            $y = (int)$m[1]; $mo = (int)$m[2]; $d = (int)$m[3];
        } elseif (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $s, $m)) {
            // dd/mm/yyyy
            $d = (int)$m[1]; $mo = (int)$m[2]; $y = (int)$m[3];
        } elseif (preg_match('/(19|20|25)\d{2}/', $s, $m)) {
            $y = (int)$m[0]; if ($y > 2400) $y -= 543; $mo = 1; $d = 1;
        } else {
            try {
                $dt = new DateTime($s);
                $y = (int)$dt->format('Y'); $mo = (int)$dt->format('n'); $d = (int)$dt->format('j');
            } catch (Exception $e) {
                return false;
            }
        }
        $nowY = (int)date('Y'); $nowM = (int)date('n'); $nowD = (int)date('j');
        if ($y < $nowY) return true;
        if ($y > $nowY) return false;
        if ($mo < $nowM) return true;
        if ($mo > $nowM) return false;
        return $d < $nowD;
    }
}
$isExpired = $model->license_expire_date ? _isDatePast($model->license_expire_date) : false;
?>

<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">👤
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="space-x-2">
        <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium shadow-sm']) ?>
        <?= Html::a('🗑 ลบ', ['delete', 'id' => $model->id], ['class' => 'px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium shadow-sm', 'data' => ['confirm' => 'ลบ?', 'method' => 'post']]) ?>
        <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
    </div>
</div>
<div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">รหัสบุคลากร</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->personnel_code) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">ตำแหน่งทางวิชาการ</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->academic_position ?: '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">ตำแหน่งงาน</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->job_position ?: '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">ชื่อ-นามสกุล</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->fullname) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">เพศ</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $genderLabels[$model->gender] ?? '-' ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">อีเมล</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->email) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">โทรศัพท์</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->phone) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">วันเกิด</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $thaiDate($model->birth_date) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">วันเริ่มงาน</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $thaiDate($model->start_date) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">วันสิ้นสุดสัญญา</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $thaiDate($model->contract_end_date) ?>
            </dd>
        </div>
    </dl>

    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4 mt-8 pt-6 border-t border-gray-100">
        <div>
            <dt class="text-sm font-medium text-gray-500">คุณวุฒิ</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->qualification->name ?? '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">ประเภทสัญญา</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->contractType->name ?? '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">สาขา</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->department->name ?? '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">สาขาตามโครงสร้าง</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->subjectGroup->name ?? '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">สถานะ</dt>
            <dd class="text-sm mt-1">
                <span
                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $model->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                    <?= $model->status ? 'ปฏิบัติงาน' : 'ไม่ปฏิบัติงาน' ?>
                </span>
            </dd>
        </div>
        <?php if ($model->status == 0 && !empty($model->resignation_year)): ?>
            <div class="col-span-1 sm:col-span-2 lg:col-span-1 border-l-4 border-red-500 pl-4">
                <dt class="text-sm font-black text-red-500 uppercase tracking-wider">ปีที่ลาออก (พ.ศ.)</dt>
                <dd class="text-xl font-bold text-red-700 mt-1">
                    <?= Html::encode($model->resignation_year) ?>
                </dd>
            </div>
        <?php endif; ?>
    </dl>

    <!-- ความเชี่ยวชาญ -->
    <?php if (!empty($model->expertises)): ?>
        <div class="mt-8 pt-6 border-t border-gray-100">
            <h3 class="text-sm font-bold text-indigo-700 uppercase tracking-wider mb-4">🔬 ความเชี่ยวชาญ</h3>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($model->expertises as $expertise): ?>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                        <?= Html::encode($expertise->name) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-8 pt-6 border-t border-gray-100">
        <h3 class="text-sm font-bold text-indigo-700 uppercase tracking-wider mb-4">🪪 ข้อมูลใบอนุญาตประกอบวิชาชีพ</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <span
                    class="block text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">เลขที่ใบอนุญาตฯ</span>
                <span class="text-sm font-bold text-gray-900"><?= Html::encode($model->license_no ?: '-') ?></span>
            </div>
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <span
                    class="block text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">เลขที่สมาชิกสภาฯ</span>
                <span
                    class="text-sm font-bold text-gray-900"><?= Html::encode($model->council_member_no ?: '-') ?></span>
            </div>
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <span
                    class="block text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">วันหมดอายุ</span>
                <span
                    class="text-sm font-bold <?= $isExpired ? 'text-red-500' : 'text-gray-900' ?>">
                    <?= $thaiDate($model->license_expire_date) ?>
                    <?php if ($isExpired): ?>
                        <span class="ml-2 text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded">EXPIRED</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">ใบอนุญาตประกอบวิชาชีพ</dt>
                <?php if ($model->license_file): ?>
                    <a href="<?= \yii\helpers\Url::to('@web/' . $model->license_file) ?>" target="_blank"
                        class="flex items-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition group w-fit">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm font-bold text-indigo-700">ดูเอกสารใบอนุญาต</span>
                    </a>
                <?php else: ?>
                    <span class="text-sm text-gray-400 italic">ไม่มีข้อมูลไฟล์</span>
                <?php endif; ?>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">บัตรสมาชิก</dt>
                <?php if ($model->member_card_file): ?>
                    <div
                        class="relative group w-48 aspect-[3/2] overflow-hidden rounded-xl shadow-sm border border-gray-200">
                        <img src="<?= \yii\helpers\Url::to('@web/' . $model->member_card_file) ?>"
                            class="w-full h-full object-cover">
                        <a href="<?= \yii\helpers\Url::to('@web/' . $model->member_card_file) ?>" target="_blank"
                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-white text-xs font-bold">ขยายภาพ</a>
                    </div>
                <?php else: ?>
                    <span class="text-sm text-gray-400 italic">ไม่มีข้อมูลภาพบัตร</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>