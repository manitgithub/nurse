<?php

use yii\helpers\Html;
use yii\helpers\Json;

/** @var yii\web\View $this */
$this->title = 'แดชบอร์ด — ระบบสถิติสำนักพยาบาล';

// Leaflet Assets
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [
    'integrity' => 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=',
    'crossorigin' => '',
]);
$this->registerCssFile('https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css');
$this->registerCssFile('https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css');

$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [
    'integrity' => 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=',
    'crossorigin' => '',
]);
$this->registerJsFile('https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js');
?>

<script>
    window.dashboardData = {
        students: <?= yii\helpers\Json::encode($allStudentsList) ?>,
        personnel: <?= yii\helpers\Json::encode($allPersonnelList) ?>,
        scholarships: <?= yii\helpers\Json::encode($allScholarshipsList) ?>,
        certifications: <?= yii\helpers\Json::encode($allCertificationsList) ?>,
        expertises: <?= yii\helpers\Json::encode($allExpertisesList) ?>,
        research: <?= yii\helpers\Json::encode($allResearchList) ?>,
        academicServices: <?= yii\helpers\Json::encode($allAcademicServiceList) ?>,
        innovations: <?= yii\helpers\Json::encode($allInnovationsList) ?>,
        budgetAllocations: <?= yii\helpers\Json::encode($allBudgetAllocationsList) ?>,
        budgetTransactions: <?= yii\helpers\Json::encode($allBudgetTransactionsList) ?>,
        licenseBatchStats: <?= yii\helpers\Json::encode($licenseBatchStats) ?>,
        deptPersonnelRatio: <?= yii\helpers\Json::encode($deptPersonnelRatio) ?>,
        budgetBreakdownByYear: <?= yii\helpers\Json::encode($budgetBreakdownByYear) ?>
    };
</script>

<div class="space-y-6" x-data="{ 
    activeTab: 'executive',
    globalBatch: 'total',
    detailModalOpen: false,
    detailModalTitle: '',
    detailModalSearch: '',
    detailModalRawItems: [],

    // Lists loaded from backend
    students: window.dashboardData.students,
    personnel: window.dashboardData.personnel,
    scholarships: window.dashboardData.scholarships,
    certifications: window.dashboardData.certifications,
    expertises: window.dashboardData.expertises,
    research: window.dashboardData.research,
    academicServices: window.dashboardData.academicServices,
    innovations: window.dashboardData.innovations,
    budgetAllocations: window.dashboardData.budgetAllocations,
    budgetTransactions: window.dashboardData.budgetTransactions,
    licenseBatchStats: window.dashboardData.licenseBatchStats,
    deptPersonnelRatio: window.dashboardData.deptPersonnelRatio,
    budgetBreakdownByYear: window.dashboardData.budgetBreakdownByYear,

    // Helper functions for executive drill-down
    getFilteredLicenseBatchStats() {
        return Object.values(this.licenseBatchStats).map(s => ({
            title: 'รหัสนักศึกษา/รุ่นการศึกษา: ' + s.batch,
            subtitle: 'เข้าสอบสะสม: ' + s.total + ' คน | สอบผ่านจริง: ' + s.passed + ' คน | อัตราการสอบผ่าน: ' + s.rate + '%',
            url: '#'
        }));
    },

    getDeptPersonnelRatioStats() {
        return this.deptPersonnelRatio.map(d => {
            let deptName = d.department_name || 'ไม่ระบุภาควิชา/สาขาวิชา';
            let academic = parseInt(d.academic_count) || 0;
            let operational = parseInt(d.operational_count) || 0;
            let total = parseInt(d.total_count) || 0;
            let academicPct = total > 0 ? Math.round((academic / total) * 100) : 0;
            return {
                title: deptName,
                subtitle: 'บุคลากรที่ปฏิบัติงานรวม: ' + total + ' คน | สายวิชาการ (ว): ' + academic + ' คน (' + academicPct + '%) | สายสนับสนุน (ป): ' + operational + ' คน',
                url: '#'
            };
        });
    },

    getBudgetBreakdownByYear(year) {
        if (!this.budgetBreakdownByYear || !this.budgetBreakdownByYear[year]) {
            return [];
        }
        return this.budgetBreakdownByYear[year].map(b => ({
            title: b.category,
            subtitle: 'งบจัดสรรสุทธิ: ' + Number(b.total).toLocaleString() + ' บาท | เบิกจ่ายจริง: ' + Number(b.spent).toLocaleString() + ' บาท | อัตราการใช้จ่าย: ' + b.percent + '%',
            amount: Number(b.remaining).toLocaleString() + ' บาท',
            amountColorClass: b.remaining < 0 ? 'text-rose-600' : 'text-emerald-600',
            url: '#'
        }));
    },

    getProjectsByYear(year) {
        let thaiYear = parseInt(year);
        let engYear = thaiYear > 2400 ? thaiYear - 543 : thaiYear;
        
        let researchList = this.research.filter(r => {
            let ry = parseInt(r.year);
            return ry === thaiYear || ry === engYear;
        }).map(r => ({
            title: 'วิจัย: ' + r.title,
            subtitle: 'แหล่งทุน: ' + (r.funding_source || '-') + ' | งบประมาณ: ' + (r.budget ? Number(r.budget).toLocaleString() + ' บาท' : '0 บาท') + ' | สถานะ: ' + (r.work_status || '-'),
            url: '<?= yii\helpers\Url::to(['research/view']) ?>&id=' + r.id
        }));

        let serviceList = this.academicServices.filter(s => {
            let sy = parseInt(s.fiscal_year);
            return sy === thaiYear || sy === engYear;
        }).map(s => ({
            title: 'บริการวิชาการ: ' + s.activity_name,
            subtitle: 'งบประมาณ: ' + (s.budget_amount ? Number(s.budget_amount).toLocaleString() + ' บาท' : '0 บาท') + ' | สถานะ: ' + (s.status || '-'),
            url: '<?= yii\helpers\Url::to(['academic-service/view']) ?>&id=' + s.id
        }));

        return [...researchList, ...serviceList];
    },

    statusLabels: {
        'active': 'กำลังศึกษา',
        'inactive': 'พักการเรียน',
        'graduated': 'สำเร็จการศึกษา',
        'dropped': 'พ้นสภาพ'
    },

    formatThaiDateShort(dateStr) {
        if (!dateStr) return '-';
        let parts = dateStr.split('-');
        if (parts.length < 3) return dateStr;
        let year = parseInt(parts[0]);
        let month = parseInt(parts[1]) - 1;
        let day = parseInt(parts[2]);
        if (isNaN(year) || isNaN(month) || isNaN(day)) return dateStr;

        const thaiMonthsShort = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        let thaiMonth = thaiMonthsShort[month] || '';
        let thaiYear = year < 2400 ? year + 543 : year;
        let shortYear = String(thaiYear).slice(-2);

        return day + ' ' + thaiMonth + ' ' + shortYear;
    },

    showDetailModal(title, items) {
        this.detailModalTitle = title;
        this.detailModalRawItems = items;
        this.detailModalSearch = '';
        this.detailModalOpen = true;
    },

    getFilteredModalItems() {
        if (!this.detailModalSearch) {
            return this.detailModalRawItems;
        }
        const search = this.detailModalSearch.toLowerCase();
        return this.detailModalRawItems.filter(item => {
            return (item.title && item.title.toLowerCase().includes(search)) ||
                   (item.subtitle && item.subtitle.toLowerCase().includes(search));
        });
    },

    getFilteredStudents(status = 'all') {
        let list = this.students;
        if (this.globalBatch && this.globalBatch !== 'total') {
            list = list.filter(s => s.batch === this.globalBatch);
        }
        if (status !== 'all') {
            list = list.filter(s => s.status === status);
        }
        return list.map(s => ({
            title: s.student_id + ' - ' + s.fullname,
            subtitle: 'รุ่น: ' + (s.batch || '-') + ' | สถานะ: ' + (this.statusLabels[s.status] || s.status),
            url: '<?= yii\helpers\Url::to(['student/view']) ?>&id=' + s.student_id
        }));
    },

    getFilteredPersonnel(filters = {}) {
        let list = this.personnel;
        list = list.filter(p => p.status == 1);
        if (filters.track) {
            list = list.filter(p => p.track === filters.track);
        }
        if (filters.department_name) {
            list = list.filter(p => (p.department_name || 'ไม่ระบุ') === filters.department_name);
        }
        if (filters.contract_type_name) {
            list = list.filter(p => (p.contract_type_name || 'ไม่ระบุ') === filters.contract_type_name);
        }
        if (filters.qualification_name) {
            list = list.filter(p => (p.qualification_name || 'ไม่ระบุ') === filters.qualification_name);
        }
        if (filters.academic_position) {
            list = list.filter(p => (p.academic_position || 'ไม่ระบุ') === filters.academic_position);
        }
        if (filters.job_position) {
            list = list.filter(p => (p.job_position || 'ไม่ระบุ') === filters.job_position);
        }
        return list.map(p => ({
            title: (p.personnel_code ? p.personnel_code + ' - ' : '') + p.fullname,
            subtitle: (p.academic_position ? p.academic_position + ' | ' : '') + (p.job_position || '-') + ' | ' + (p.department_name || 'ไม่ระบุสาขา'),
            url: '<?= yii\helpers\Url::to(['personnel/view']) ?>&id=' + p.id
        }));
    },

    getFilteredScholarships(filters = {}) {
        let list = this.scholarships;
        if (filters.qualification_name) {
            list = list.filter(s => (s.qualification_name || 'ไม่ระบุ') === filters.qualification_name);
        }
        if (filters.major) {
            list = list.filter(s => (s.major || 'ไม่ระบุ') === filters.major);
        }
        if (filters.institution) {
            list = list.filter(s => (s.institution || 'ไม่ระบุ') === filters.institution);
        }
        if (filters.year_th) {
            let targetYearEng = parseInt(filters.year_th) - 543;
            list = list.filter(s => {
                if (!s.start_date) return false;
                let startYear = parseInt(s.start_date.substring(0, 4));
                if (isNaN(startYear) || startYear > targetYearEng) return false;
                if (!s.end_date) return true;
                let endYear = parseInt(s.end_date.substring(0, 4));
                return isNaN(endYear) || endYear >= targetYearEng;
            });
        }
        return list.map(s => ({
            title: s.scholar_name,
            subtitle: (s.qualification_name ? s.qualification_name + ' | ' : '') + (s.major || '-') + ' | ' + (s.institution || '-') + ' (ปี ' + s.academic_year + ')',
            url: '<?= yii\helpers\Url::to(['scholarship/view']) ?>&id=' + s.id
        }));
    },

    getFilteredCertifications(levelName = '') {
        let list = this.certifications;
        if (levelName) {
            list = list.filter(c => (c.level_name || 'ไม่ระบุ') === levelName);
        }
        return list.map(c => ({
            title: c.personnel_name,
            subtitle: 'ใบอนุญาต/ใบรับรอง: ' + (c.level_name || '-') + (c.training_batch ? ' (รุ่น: ' + c.training_batch + ')' : ''),
            url: '<?= yii\helpers\Url::to(['personnel/view']) ?>&id=' + c.personnel_id
        }));
    },

    getFilteredExpertises(expertiseName = '') {
        let list = this.expertises;
        if (expertiseName) {
            list = list.filter(x => x.expertise_name === expertiseName);
        }
        return list.map(x => ({
            title: x.personnel_name,
            subtitle: 'ความเชี่ยวชาญ: ' + x.expertise_name,
            url: '<?= yii\helpers\Url::to(['personnel/view']) ?>&id=' + x.personnel_id
        }));
    },

    getFilteredResearch(filters = {}) {
        let list = this.research;
        if (filters.work_status) {
            list = list.filter(r => (r.work_status || 'ไม่ระบุ') === filters.work_status);
        }
        if (filters.funding_source) {
            list = list.filter(r => (r.funding_source || 'ไม่ระบุ') === filters.funding_source);
        }
        return list.map(r => ({
            title: r.title,
            subtitle: 'สถานะ: ' + (r.work_status || '-') + ' | แหล่งทุน: ' + (r.funding_source || '-') + ' | ปี: ' + (r.year || '-') + ' | งบประมาณ: ' + (r.budget ? Number(r.budget).toLocaleString() + ' บาท' : '0 บาท'),
            url: '<?= yii\helpers\Url::to(['research/view']) ?>&id=' + r.id
        }));
    },

    getFilteredAcademicServices(filters = {}) {
        let list = this.academicServices;
        if (filters.status) {
            list = list.filter(a => (a.status || 'ไม่ระบุ') === filters.status);
        }
        return list.map(a => ({
            title: a.activity_name,
            subtitle: 'สถานะ: ' + (a.status || '-') + ' | ปีงบประมาณ: ' + (a.fiscal_year || '-') + ' | งบประมาณ: ' + (a.budget_amount ? Number(a.budget_amount).toLocaleString() + ' บาท' : '0 บาท'),
            url: '<?= yii\helpers\Url::to(['academic-service/view']) ?>&id=' + a.id
        }));
    },

    getFilteredInnovations(filters = {}) {
        let list = this.innovations;
        if (filters.advisor) {
            list = list.filter(i => (i.advisor || 'ไม่ระบุ') === filters.advisor);
        }
        return list.map(i => ({
            title: i.title,
            subtitle: 'อาจารย์ที่ปรึกษา: ' + (i.advisor || '-') + ' | วันที่คิดค้น: ' + this.formatThaiDateShort(i.invention_date),
            url: '<?= yii\helpers\Url::to(['innovation/view']) ?>&id=' + i.id
        }));
    },

    getFilteredBudgetAllocations(filters = {}) {
        let list = this.budgetAllocations;
        if (filters.fiscal_year) {
            list = list.filter(a => String(a.fiscal_year) === String(filters.fiscal_year));
        }
        return list.map(a => {
            let net = Number(a.allocated_amount) - Number(a.adjustment_reduction) + Number(a.adjustment_addition);
            return {
                title: a.category_name || 'ไม่ระบุหมวด',
                subtitle: 'จัดสรร: ' + Number(a.allocated_amount).toLocaleString() + ' บาท | ปรับลด: -' + Number(a.adjustment_reduction).toLocaleString() + ' บาท | ปรับเพิ่ม: +' + Number(a.adjustment_addition).toLocaleString() + ' บาท',
                amount: net.toLocaleString() + ' บาท',
                amountColorClass: 'text-indigo-600',
                url: '<?= yii\helpers\Url::to(['budget/transactions']) ?>&allocation_id=' + a.id
            };
        });
    },

    getFilteredBudgetTransactions(filters = {}) {
        let list = this.budgetTransactions;
        if (filters.fiscal_year) {
            list = list.filter(t => String(t.fiscal_year) === String(filters.fiscal_year));
        }
        if (filters.category_name) {
            list = list.filter(t => (t.category_name || 'ไม่ระบุหมวด') === filters.category_name);
        }
        return list.map(t => ({
            title: t.activity_name || 'ไม่ระบุชื่อกิจกรรม',
            subtitle: 'ผู้เบิก: ' + (t.requester || '-') + ' | วันที่: ' + this.formatThaiDateShort(t.transaction_date) + ' | หมวด: ' + (t.category_name || 'ไม่ระบุหมวด'),
            amount: Number(t.total_cost).toLocaleString() + ' บาท',
            amountColorClass: 'text-rose-600',
            url: '<?= yii\helpers\Url::to(['budget/transactions']) ?>&allocation_id=' + t.allocation_id
        }));
    },

    getFilteredBudgetRemaining(filters = {}) {
        let list = this.budgetAllocations;
        if (filters.fiscal_year) {
            list = list.filter(a => String(a.fiscal_year) === String(filters.fiscal_year));
        }
        return list.map(a => {
            let net = Number(a.allocated_amount) - Number(a.adjustment_reduction) + Number(a.adjustment_addition);
            let spent = Number(a.total_spent);
            let remaining = net - spent;
            return {
                title: a.category_name || 'ไม่ระบุหมวด',
                subtitle: 'งบสุทธิ: ' + net.toLocaleString() + ' บาท | เบิกจ่ายจริง: ' + spent.toLocaleString() + ' บาท',
                amount: remaining.toLocaleString() + ' บาท',
                amountColorClass: remaining < 0 ? 'text-rose-600' : 'text-emerald-600',
                url: '<?= yii\helpers\Url::to(['budget/transactions']) ?>&allocation_id=' + a.id
            };
        });
    },

    getFilteredGpaxStudents() {
        let batchKey = this.globalBatch === 'total' ? 'total' : this.globalBatch;
        let list = [];
        if (window.globalAllGpaxStudents && window.globalAllGpaxStudents[batchKey]) {
            const ranges = window.globalAllGpaxStudents[batchKey];
            const labels = { r1: '3.50 - 4.00', r2: '3.00 - 3.49', r3: '2.50 - 2.99', r4: '2.00 - 2.49' };
            for (let r in ranges) {
                ranges[r].forEach(s => {
                    list.push({
                        title: s.id + ' - ' + s.name,
                        subtitle: 'เกรดเฉลี่ยสะสม: ' + labels[r] + (s.batch ? ' | รุ่น: ' + s.batch : ''),
                        url: '<?= yii\helpers\Url::to(['student/view']) ?>&id=' + s.id
                    });
                });
            }
        }
        return list;
    },

    getFilteredStudentsByGpaxRange(rangeKey) {
        let batchKey = this.globalBatch === 'total' ? 'total' : this.globalBatch;
        let list = [];
        if (window.globalAllGpaxStudents && window.globalAllGpaxStudents[batchKey] && window.globalAllGpaxStudents[batchKey][rangeKey]) {
            const labels = { r1: '3.50 - 4.00', r2: '3.00 - 3.49', r3: '2.50 - 2.99', r4: '2.00 - 2.49' };
            window.globalAllGpaxStudents[batchKey][rangeKey].forEach(s => {
                list.push({
                    title: s.id + ' - ' + s.name,
                    subtitle: 'เกรดเฉลี่ยสะสม: ' + labels[rangeKey] + (s.batch ? ' | รุ่น: ' + s.batch : ''),
                    url: '<?= yii\helpers\Url::to(['student/view']) ?>&id=' + s.id
                });
            });
        }
        return list;
    },

    initMaps() {
        if (this.activeTab === 'research' && window.researchMap) {
            setTimeout(() => window.researchMap.invalidateSize(), 100);
        }
        if (this.activeTab === 'academic_service' && window.academicServiceMap) {
            setTimeout(() => window.academicServiceMap.invalidateSize(), 150);
        }
        if (this.activeTab === 'innovation' && window.innovationYearlyChart) {
            setTimeout(() => window.innovationYearlyChart.resize(), 100);
        }
        if (this.activeTab === 'scholarship' && window.scholarshipRetentionChart) {
            setTimeout(() => window.scholarshipRetentionChart.resize(), 100);
        }
        if (this.activeTab === 'budget' && window.budgetComparisonChart) {
            setTimeout(() => window.budgetComparisonChart.resize(), 100);
        }
        if (this.activeTab === 'executive') {
            if (window.execResearchServiceChart) {
                setTimeout(() => window.execResearchServiceChart.resize(), 100);
            }
            if (window.execBudgetSpentChart) {
                setTimeout(() => window.execBudgetSpentChart.resize(), 100);
            }
        }
    }
}" x-init="window.dashboardAlpine = $data; $watch('activeTab', value => initMaps())">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            แดชบอร์ดสรุปสถิติ
        </h1>
        <p class="text-gray-500 mt-1">ภาพรวมระบบสถิติและจัดการข้อมูลสำนักพยาบาล</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div @click="activeTab = 'students'; document.getElementById('dashboard-tabs')?.scrollIntoView({ behavior: 'smooth' })"
             :class="activeTab === 'students' ? 'ring-2 ring-indigo-500 border-transparent bg-indigo-50/10' : 'hover:border-indigo-300'"
             class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">นักศึกษาทั้งหมด</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">
                        <span class="hover:underline hover:text-indigo-600 transition" @click.stop="showDetailModal(globalBatch === 'total' ? 'รายชื่อนักศึกษาทั้งหมด' : 'รายชื่อนักศึกษา รุ่น ' + globalBatch, getFilteredStudents('all'))"><?= $totalStudents ?></span>
                    </p>
                    <p class="text-sm text-green-600 mt-1">
                        <span class="hover:underline" @click.stop="showDetailModal(globalBatch === 'total' ? 'รายชื่อนักศึกษา (กำลังศึกษา)' : 'รายชื่อนักศึกษา (กำลังศึกษา) รุ่น ' + globalBatch, getFilteredStudents('active'))">กำลังศึกษา: <?= $activeStudents ?></span>
                    </p>
                </div>
                <div class="bg-indigo-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                    </svg>
                </div>
            </div>
        </div>
        <div @click="activeTab = 'personnel'; document.getElementById('dashboard-tabs')?.scrollIntoView({ behavior: 'smooth' })"
             :class="activeTab === 'personnel' ? 'ring-2 ring-emerald-500 border-transparent bg-emerald-50/10' : 'hover:border-emerald-300'"
             class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">บุคลากร (ปฏิบัติงาน)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">
                        <span class="hover:underline hover:text-emerald-600 transition" @click.stop="showDetailModal('รายชื่อบุคลากร (กำลังปฏิบัติงาน)', getFilteredPersonnel())"><?= $activePersonnel ?></span>
                    </p>
                    <div class="flex space-x-3 text-sm mt-1">
                        <span class="text-indigo-600 font-medium hover:underline" @click.stop="showDetailModal('รายชื่อบุคลากร สาย ว (วิชาการ)', getFilteredPersonnel({ track: 'สาย ว' }))">สาย ว: <?= $activeAcademic ?></span>
                        <span class="text-emerald-600 font-medium hover:underline" @click.stop="showDetailModal('รายชื่อบุคลากร สาย ป (ปฏิบัติการ)', getFilteredPersonnel({ track: 'สาย ป' }))">สาย ป: <?= $activeOperational ?></span>
                    </div>
                </div>
                <div class="bg-emerald-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
        </div>
        <div @click="activeTab = 'scholarship'; document.getElementById('dashboard-tabs')?.scrollIntoView({ behavior: 'smooth' })"
             :class="activeTab === 'scholarship' ? 'ring-2 ring-amber-500 border-transparent bg-amber-50/10' : 'hover:border-amber-300'"
             class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">นักเรียนทุน</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">
                        <span class="hover:underline hover:text-amber-600 transition" @click.stop="showDetailModal('รายชื่อนักเรียนทุนทั้งหมด', getFilteredScholarships())"><?= $totalScholarships ?></span>
                    </p>
                    <p class="text-sm text-blue-600 mt-1">ทุนทั้งหมด</p>
                </div>
                <div class="bg-amber-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493" />
                    </svg>
                </div>
            </div>
        </div>
        <div @click="activeTab = 'students'; document.getElementById('dashboard-tabs')?.scrollIntoView({ behavior: 'smooth' })"
             :class="activeTab === 'students' ? 'ring-2 ring-purple-500 border-transparent bg-purple-50/10' : 'hover:border-purple-300'"
             class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">ผลการเรียน (GPAX)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="kpiAvgGpax">
                        <span class="hover:underline hover:text-purple-600 transition" @click.stop="showDetailModal(globalBatch === 'total' ? 'รายชื่อนักศึกษาทั้งหมดตามเกรดเฉลี่ย' : 'รายชื่อนักศึกษาตามเกรดเฉลี่ย รุ่น ' + globalBatch, getFilteredGpaxStudents())"><?= number_format($avgGpax, 2) ?></span>
                    </p>
                    <div class="flex space-x-3 text-xs mt-1">
                        <span class="text-emerald-600 hover:underline" @click.stop="showDetailModal('รายชื่อนักศึกษาเกียรตินิยม (3.50 - 4.00)', getFilteredStudentsByGpaxRange('r1'))">สูงสุด: <span id="kpiMaxGpax"><?= number_format($maxGpax, 2) ?></span></span>
                        <span class="text-rose-600 hover:underline" @click.stop="showDetailModal('รายชื่อนักศึกษาต้องเฝ้าระวัง (2.00 - 2.49)', getFilteredStudentsByGpaxRange('r4'))">ต่ำสุด: <span id="kpiMinGpax"><?= number_format($minGpax, 2) ?></span></span>
                    </div>
                </div>
                <div class="bg-purple-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TABS ===== -->
    <div>
        <!-- Tab Navigation -->
        <div id="dashboard-tabs" class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-1 -mb-px" role="tablist">
                <button @click="activeTab = 'executive'"
                    :class="activeTab === 'executive' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>สรุปผู้บริหาร
                </button>
                <button @click="activeTab = 'students'"
                    :class="activeTab === 'students' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>นักศึกษา
                </button>
                <button @click="activeTab = 'personnel'"
                    :class="activeTab === 'personnel' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>บุคลากร
                </button>
                <button @click="activeTab = 'scholarship'"
                    :class="activeTab === 'scholarship' ? 'border-amber-500 text-amber-600 bg-amber-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A57.778 57.778 0 0012 15.75a57.778 57.778 0 005.25-4.425v3.675M12 13.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>นักเรียนทุน
                </button>
                <button @click="activeTab = 'research'"
                    :class="activeTab === 'research' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v1.244c0 .556-.06 1.119-.18 1.666-.12.548-.28 1.082-.475 1.597a7.597 7.597 0 00-1.398 2.227c-.276.446-.51.931-.7 1.442a12.115 12.115 0 00-.475 1.597c-.12.547-.18 1.11-.18 1.666V18c0 .828.672 1.5 1.5 1.5h10.5c.828 0 1.5-.672 1.5-1.5v-3.348c0-.556-.06-1.11-.18-1.666a12.122 12.122 0 00-.475-1.597 7.594 7.594 0 00-1.398-2.227c-.276-.446-.51-.931-.7-1.442a12.13 12.13 0 00-.475-1.597c-.12-.547-.18-1.11-.18-1.666V3.104" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 14h12M8 3h8" />
                    </svg>งานวิจัย
                </button>
                <button @click="activeTab = 'academic_service'"
                    :class="activeTab === 'academic_service' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .552-.448 1-1 1H4.75c-.552 0-1-.448-1-1v-4.25m16.5 0a1.5 1.5 0 00-1.5-1.5H4.75a1.5 1.5 0 00-1.5 1.5m16.5 0v-4.326c0-.528-.41-1.01-1.01-1.01H16.5M3.25 14.15v-4.326c0-.528.41-1.01 1.01-1.01H7.5m9 0V6.75A2.25 2.25 0 0014.25 4.5h-4.5A2.25 2.25 0 007.5 6.75v2.85m9 0H7.5" />
                    </svg>บริการวิชาการ/ทำนุบำรุงวัฒนธรรม
                </button>
                <button @click="activeTab = 'innovation'"
                    :class="activeTab === 'innovation' ? 'border-purple-500 text-purple-600 bg-purple-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v3.75m0 0h.008v.008H12v-.008zm0-3.75a6 6 0 00-6-6v-1.5m6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                    </svg>นวัตกรรม
                </button>
                <button @click="activeTab = 'budget'"
                    :class="activeTab === 'budget' ? 'border-rose-500 text-rose-600 bg-rose-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125H3.75A1.125 1.125 0 012.625 18V5.625C2.625 5.004 3.129 4.5 3.75 4.5zM9 9h.008v.008H9V9zm.008 3h-.008v-.008h.008V12zm3-3h.008v.008h-.008V9zm.008 3h-.008v-.008h.008V12zm3-3h.008v.008h-.008V9zm.008 3h-.008v-.008h.008V12z" />
                    </svg>รายรับ-รายจ่าย
                </button>
            </nav>
        </div>

        <!-- ==================== TAB: สรุปผู้บริหาร ==================== -->
        <div x-show="activeTab === 'executive'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6">

            <!-- KPI Cards Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- KPI 1: License Pass Rate -->
                <div @click="showDetailModal('สถิติผลสอบใบอนุญาตประกอบวิชาชีพแยกตามรุ่น', getFilteredLicenseBatchStats())"
                     class="bg-gradient-to-br from-violet-600 to-indigo-700 text-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-all transform hover:-translate-y-1 cursor-pointer relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition duration-300">
                        <svg class="h-28 w-28 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold tracking-wider uppercase opacity-85">อัตราสอบผ่านใบประกอบวิชาชีพ</span>
                        <span class="bg-white/20 text-white rounded-lg px-2 py-0.5 text-2xs font-semibold backdrop-blur-md">คุณภาพสะสม</span>
                    </div>
                    <p class="text-4xl font-extrabold mt-3 tracking-tight"><?= $licenseRate ?>%</p>
                    <p class="text-xs text-violet-100 mt-2 flex items-center gap-1">
                        สอบผ่าน <?= $licensePassed ?> จาก <?= $licenseTotal ?> คน
                        <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    </p>
                </div>

                <!-- KPI 2: Student Retention -->
                <div @click="activeTab = 'students'; document.getElementById('dashboard-tabs')?.scrollIntoView({ behavior: 'smooth' })"
                     class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-all transform hover:-translate-y-1 cursor-pointer relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition duration-300">
                        <svg class="h-28 w-28 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84" />
                        </svg>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold tracking-wider uppercase opacity-85">อัตราการคงอยู่ของนักศึกษา</span>
                        <span class="bg-white/20 text-white rounded-lg px-2 py-0.5 text-2xs font-semibold backdrop-blur-md">ภาพรวมสถาบัน</span>
                    </div>
                    <p class="text-4xl font-extrabold mt-3 tracking-tight"><?= $retentionRate ?>%</p>
                    <p class="text-xs text-emerald-100 mt-2 flex items-center gap-1">
                        กำลังศึกษา <?= $activeStudents ?> / สำเร็จการศึกษา <?= $graduatedStudents ?> คน
                        <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    </p>
                </div>

                <!-- KPI 3: Academic Personnel Ratio -->
                <div @click="showDetailModal('สัดส่วนบุคลากรแยกตามภาควิชา/สาขาวิชา', getDeptPersonnelRatioStats())"
                     class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-all transform hover:-translate-y-1 cursor-pointer relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition duration-300">
                        <svg class="h-28 w-28 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold tracking-wider uppercase opacity-85">สัดส่วนบุคลากรสายวิชาการ</span>
                        <span class="bg-white/20 text-white rounded-lg px-2 py-0.5 text-2xs font-semibold backdrop-blur-md">การบริหารกำลังคน</span>
                    </div>
                    <p class="text-4xl font-extrabold mt-3 tracking-tight"><?= $activePersonnel > 0 ? round(($activeAcademic / $activePersonnel) * 100, 1) : 0 ?>%</p>
                    <p class="text-xs text-blue-100 mt-2 flex items-center gap-1">
                        อาจารย์ผู้สอน (สาย ว) <?= $activeAcademic ?> จาก <?= $activePersonnel ?> คน
                        <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    </p>
                </div>

                <!-- KPI 4: Budget Execution Rate -->
                <div @click="showDetailModal('งบจัดสรรและอัตราการใช้จ่ายแยกตามหมวดหมู่', getBudgetBreakdownByYear('<?= $latestBudgetYear ?>'))"
                     class="bg-gradient-to-br from-amber-500 to-orange-600 text-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-all transform hover:-translate-y-1 cursor-pointer relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition duration-300">
                        <svg class="h-28 w-28 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125H3.75A1.125 1.125 0 0 1 2.625 18V5.625C2.625 5.004 3.129 4.5 3.75 4.5Z" />
                        </svg>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold tracking-wider uppercase opacity-85">อัตราการเบิกจ่ายงบประมาณ</span>
                        <span class="bg-white/20 text-white rounded-lg px-2 py-0.5 text-2xs font-semibold backdrop-blur-md">ประสิทธิภาพใช้เงิน</span>
                    </div>
                    <p class="text-4xl font-extrabold mt-3 tracking-tight"><?= $totalBudgetAllocated > 0 ? round(($totalBudgetSpent / $totalBudgetAllocated) * 100, 1) : 0 ?>%</p>
                    <p class="text-xs text-amber-100 mt-2 flex items-center gap-1">
                        ปีงบฯ ล่าสุดเบิกจ่ายจริง <?= number_format($totalBudgetSpent) ?> บาท
                        <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    </p>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Research vs Service Output -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">ผลงานวิจัยและการบริการวิชาการรายปี</h2>
                                <p class="text-xs text-gray-500 mt-0.5">ภาพรวมจำนวนโครงการวิชาการสะสมย้อนหลัง</p>
                            </div>
                            <span class="text-2xs font-semibold text-gray-500 bg-gray-100 rounded-full px-2 py-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>คลิกเพื่อเจาะลึก
                            </span>
                        </div>
                        <div class="h-[260px] mt-4 relative">
                            <canvas id="execResearchServiceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Financial Execution Trend -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">แนวโน้มงบประมาณสุทธิและการเบิกจ่ายจริง</h2>
                                <p class="text-xs text-gray-500 mt-0.5">เปรียบเทียบเงินงบประมาณจัดสรรสุทธิกับรายจ่ายสะสมประจำปี</p>
                            </div>
                            <span class="text-2xs font-semibold text-gray-500 bg-gray-100 rounded-full px-2 py-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>คลิกเพื่อเจาะลึก
                            </span>
                        </div>
                        <div class="h-[260px] mt-4 relative">
                            <canvas id="execBudgetSpentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Strategic Indicators Block -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Human Capital Capacity -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
                    <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253"/></svg>
                        ขีดความสามารถและคุณวุฒิการศึกษาของบุคลากร (สถิติปฏิบัติงานจริง)
                    </h3>
                    <div class="space-y-4">
                        <?php
                        $totalQual = array_sum(array_column($personnelByQualification, 'total'));
                        $colors = ['bg-indigo-600', 'bg-purple-600', 'bg-blue-500', 'bg-teal-500', 'bg-slate-400'];
                        foreach ($personnelByQualification as $index => $q):
                            $pct = $totalQual > 0 ? round(($q['total'] / $totalQual) * 100, 1) : 0;
                            $barColor = $colors[$index % count($colors)];
                        ?>
                            <div class="hover:bg-indigo-50/40 p-2 rounded-xl transition cursor-pointer group"
                                 @click="showDetailModal('รายชื่อบุคลากรที่มีคุณวุฒิ: <?= Html::encode($q['label'] ?: 'ไม่ระบุ') ?>', getFilteredPersonnel({ qualification_name: '<?= Html::encode($q['label'] ?: 'ไม่ระบุ') ?>' }))">
                                <div class="flex justify-between items-center text-sm font-semibold text-gray-700 mb-1 group-hover:text-indigo-600 transition">
                                    <span>คุณวุฒิ: <?= Html::encode($q['label'] ?: 'ไม่ระบุ') ?></span>
                                    <span><?= $q['total'] ?> คน (<?= $pct ?>%)</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3.5 overflow-hidden">
                                    <div class="<?= $barColor ?> h-full rounded-full transition-all duration-500" style="width: <?= $pct ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Academic Impact & Certificates -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            ผลงานสะสมและผลกระทบสังคม
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl hover:bg-indigo-50/35 transition cursor-pointer"
                                 @click="activeTab = 'innovation'; document.getElementById('dashboard-tabs')?.scrollIntoView({ behavior: 'smooth' })">
                                <span class="text-sm font-medium text-gray-700">ผลงานนวัตกรรมที่ประดิษฐ์ขึ้น</span>
                                <span class="text-sm font-black text-indigo-600"><?= $totalInnovation ?> ผลงาน</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl hover:bg-emerald-50/35 transition cursor-pointer"
                                 @click="activeTab = 'academic_service'; document.getElementById('dashboard-tabs')?.scrollIntoView({ behavior: 'smooth' })">
                                <span class="text-sm font-medium text-gray-700">จำนวนโครงการบริการวิชาการ</span>
                                <span class="text-sm font-black text-emerald-600"><?= $totalAcademicService ?> โครงการ</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl hover:bg-amber-50/35 transition cursor-pointer"
                                 @click="activeTab = 'scholarship'; document.getElementById('dashboard-tabs')?.scrollIntoView({ behavior: 'smooth' })">
                                <span class="text-sm font-medium text-gray-700">จำนวนนักเรียนทุนสะสม</span>
                                <span class="text-sm font-black text-amber-600"><?= $totalScholarships ?> ทุน</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl hover:bg-purple-50/35 transition cursor-pointer"
                                 @click="showDetailModal('ความเชี่ยวชาญของบุคลากรในสถาบัน', getDeptPersonnelRatioStats())">
                                <span class="text-sm font-medium text-gray-700">ความเชี่ยวชาญของบุคลากร</span>
                                <span class="text-sm font-black text-purple-600"><?= !empty($topExpertises) ? count($topExpertises) : 0 ?> ด้าน</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: นักศึกษา ==================== -->
        <div x-show="activeTab === 'students'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" x-data="{ 
                gpaxModalOpen: false,
                gpaxModalRangeTitle: '',
                currentGpaxStudents: [],
                showGpaxStudents(rangeKey, rangeTitle) {
                    this.gpaxModalRangeTitle = rangeTitle;
                    // Always show data matching current global filter
                    let batchKey = this.globalBatch === 'total' ? 'total' : this.globalBatch;
                    
                    console.log('Loading GPAX Students. Batch:', batchKey, 'Range:', rangeKey);
                    console.log('Global Object Array:', window.globalAllGpaxStudents);

                    if (window.globalAllGpaxStudents && window.globalAllGpaxStudents[batchKey] && window.globalAllGpaxStudents[batchKey][rangeKey]) {
                        this.currentGpaxStudents = window.globalAllGpaxStudents[batchKey][rangeKey];
                        console.log('Matched Students:', this.currentGpaxStudents);
                    } else {
                        console.log('No match found in globalAllGpaxStudents');
                        this.currentGpaxStudents = [];
                    }
                    this.gpaxModalOpen = true;
                }
            }">

            <!-- Global Filter Row -->
            <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-indigo-700 font-bold">🔍 ตัวกรองข้อมูลนักศึกษา:</span>
                    <select x-model="globalBatch" @change="updateAllStudentCharts(globalBatch)"
                        class="text-sm border-indigo-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-2">
                        <option value="total">ทุกรหัส (ภาพรวม)</option>
                        <?php foreach (array_keys($gpaxTrendByBatch) as $b): ?>
                            <option value="<?= Html::encode($b) ?>">รหัส <?= Html::encode($b) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="text-indigo-600 text-sm italic" x-show="globalBatch !== 'total'">
                    กำลังแสดงข้อมูลเฉพาะ <span class="font-bold">รหัส <span x-text="globalBatch"></span></span>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Retention Doughnut -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">อัตราการคงอยู่ของนักศึกษา</h2>
                    <div class="flex items-center">
                        <div class="w-1/2 h-[200px]"><canvas id="retentionChart"></canvas></div>
                        <div class="w-1/2 pl-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span
                                        class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span>กำลังศึกษา</span>
                                <span class="font-semibold cursor-pointer hover:underline text-indigo-600 hover:text-indigo-800 transition" id="retentionCountActive"
                                    @click="showDetailModal(globalBatch === 'total' ? 'รายชื่อนักศึกษา (กำลังศึกษา)' : 'รายชื่อนักศึกษา (กำลังศึกษา) รุ่น ' + globalBatch, getFilteredStudents('active'))"><?= $activeStudents ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span
                                        class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>สำเร็จการศึกษา</span>
                                <span class="font-semibold cursor-pointer hover:underline text-indigo-600 hover:text-indigo-800 transition" id="retentionCountGraduated"
                                    @click="showDetailModal(globalBatch === 'total' ? 'รายชื่อนักศึกษา (สำเร็จการศึกษา)' : 'รายชื่อนักศึกษา (สำเร็จการศึกษา) รุ่น ' + globalBatch, getFilteredStudents('graduated'))"><?= $graduatedStudents ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span
                                        class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>พักการเรียน</span>
                                <span class="font-semibold cursor-pointer hover:underline text-indigo-600 hover:text-indigo-800 transition" id="retentionCountInactive"
                                    @click="showDetailModal(globalBatch === 'total' ? 'รายชื่อนักศึกษา (พักการเรียน)' : 'รายชื่อนักศึกษา (พักการเรียน) รุ่น ' + globalBatch, getFilteredStudents('inactive'))"><?= $inactiveStudents ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span
                                        class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>พ้นสภาพ</span>
                                <span class="font-semibold cursor-pointer hover:underline text-indigo-600 hover:text-indigo-800 transition" id="retentionCountDropped"
                                    @click="showDetailModal(globalBatch === 'total' ? 'รายชื่อนักศึกษา (พ้นสภาพ)' : 'รายชื่อนักศึกษา (พ้นสภาพ) รุ่น ' + globalBatch, getFilteredStudents('dropped'))"><?= $droppedStudents ?></span>
                            </div>
                            <div class="pt-3 border-t">
                                <p class="text-sm text-gray-500">อัตราการคงอยู่</p>
                                <p class="text-2xl font-bold text-emerald-600"><span
                                        id="retentionRateVal"><?= $retentionRate ?></span>%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GPAX Trend -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">แนวโน้มผลการเรียนเฉลี่ย (GPAX)</h2>
                    <div class="h-[250px]">
                        <canvas id="gpaxChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- GPAX Grouping Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">การกระจายเกรดเฉลี่ยสะสม</h2>
                    <div class="flex items-center justify-around">
                        <div class="w-1/2 h-[220px]">
                            <canvas id="gpaxGroupChart"></canvas>
                        </div>
                        <div class="w-1/2 space-y-4 text-left pl-8">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">เกรด 3.50 - 4.00</p>
                                <p class="text-2xl font-bold text-emerald-600 cursor-pointer hover:underline"
                                    id="gpaxCountR1" @click="showGpaxStudents('r1', '3.50 - 4.00')">
                                    <?= $gpaxGroups['r1'] ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">เกรด 3.00 - 3.49</p>
                                <p class="text-2xl font-bold text-indigo-600 cursor-pointer hover:underline"
                                    id="gpaxCountR2" @click="showGpaxStudents('r2', '3.00 - 3.49')">
                                    <?= $gpaxGroups['r2'] ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">เกรด 2.50 - 2.99</p>
                                <p class="text-2xl font-bold text-amber-500 cursor-pointer hover:underline"
                                    id="gpaxCountR3" @click="showGpaxStudents('r3', '2.50 - 2.99')">
                                    <?= $gpaxGroups['r3'] ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">เกรด 2.00 - 2.49</p>
                                <p class="text-2xl font-bold text-rose-600 cursor-pointer hover:underline"
                                    id="gpaxCountR4" @click="showGpaxStudents('r4', '2.00 - 2.49')">
                                    <?= $gpaxGroups['r4'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Web Modal for GPAX Students -->
                <div x-show="gpaxModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="gpaxModalOpen" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                            @click="gpaxModalOpen = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>
                        <div x-show="gpaxModalOpen" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    รายชื่อนักศึกษาเกณฑ์ <span x-text="gpaxModalRangeTitle"
                                        class="text-indigo-600"></span>
                                </h3>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:p-6 max-h-96 overflow-y-auto">
                                <ul class="divide-y divide-gray-200">
                                    <template x-for="student in currentGpaxStudents" :key="student.id">
                                        <li class="py-3 flex justify-between items-center bg-white rounded-lg px-4 mb-2 shadow-sm border border-gray-100 hover:bg-indigo-50 transition cursor-pointer"
                                            @click="window.location.href = '<?= yii\helpers\Url::to(['student/view']) ?>&id=' + student.id">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-gray-900"
                                                    x-text="student.id"></span>
                                                <span class="text-sm text-gray-500" x-text="student.name"></span>
                                            </div>
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </li>
                                    </template>
                                    <li x-show="currentGpaxStudents.length === 0"
                                        class="text-sm text-gray-500 text-center py-4">ไม่พบข้อมูล</li>
                                </ul>
                            </div>
                            <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="gpaxModalOpen = false">
                                    ปิด
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GPAX by Batch Bar Chart (Now potentially highlightable or filtered) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">เปรียบเทียบ GPAX ทุกรหัส (ค่าเฉลี่ยทุกเทอม)
                    </h2>
                    <div class="h-[250px]">
                        <canvas id="gpaxBatchChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- License Exam Row -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">สอบใบอนุญาตประกอบวิชาชีพ</h2>
                <div class="flex items-center">
                    <div class="w-1/4 h-[150px]"><canvas id="licenseChart"></canvas></div>
                    <div class="w-3/4 pl-12 flex items-center justify-around">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 uppercase">สอบผ่าน</p>
                            <p class="text-3xl font-bold text-emerald-600"><?= $licensePassed ?></p>
                        </div>
                        <div class="text-center border-l border-gray-100 pl-12">
                            <p class="text-sm text-gray-500 uppercase">ทั้งหมด</p>
                            <p class="text-3xl font-bold text-gray-900"><?= $licenseTotal ?></p>
                        </div>
                        <div class="text-center border-l border-gray-100 pl-12">
                            <p class="text-sm text-gray-500 uppercase">อัตราการสอบผ่าน</p>
                            <p class="text-4xl font-bold text-indigo-600"><?= $licenseRate ?>%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: บุคลากร ==================== -->
        <div x-show="activeTab === 'personnel'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" style="display:none">

            <!-- Recruitment Plan (All Years) - Moved from Students -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">📋 แผนอัตรากำลัง (ทุกปีงบประมาณ)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php if (!empty($allPlans)): ?>
                        <?php foreach ($allPlans as $plan): ?>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-gray-800">ปี <?= Html::encode($plan->fiscal_year) ?></span>
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $plan->getRemainingQuota() > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' ?>">
                                        <?= $plan->getRemainingQuota() > 0 ? 'ว่าง ' . $plan->getRemainingQuota() : 'เต็ม' ?>
                                    </span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>บรรจุ <?= $plan->recruited_amount ?>/<?= $plan->quota_amount ?></span>
                                    <span><?= $plan->quota_amount > 0 ? round($plan->recruited_amount / $plan->quota_amount * 100) : 0 ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <?php $pct = $plan->quota_amount > 0 ? min(100, round($plan->recruited_amount / $plan->quota_amount * 100)) : 0; ?>
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: <?= $pct ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full py-6 text-center text-gray-400 italic">ยังไม่มีข้อมูลแผนอัตรากำลัง</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 5-Year Personnel Retention -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 จำนวนบุคลากรที่มีอัตราคงอยู่ย้อนหลัง 5 ปี</h3>
                <div class="h-[250px]">
                    <canvas id="personnelRetentionChart"></canvas>
                </div>
            </div>

            <!-- Row 1: Department & Contract Type -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🏢 บุคลากรแยกตามสาขา</h3>
                    <?php if (!empty($personnelByDept)): ?>
                        <canvas id="deptChart" height="220"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📄 บุคลากรแยกตามประเภทสัญญา</h3>
                    <?php if (!empty($personnelByContract)): ?>
                        <canvas id="contractChart" height="220"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Row 2: Qualification & Certification -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📜 บุคลากรแยกตามคุณวุฒิ</h3>
                    <?php if (!empty($personnelByQualification)): ?>
                        <div class="flex items-center">
                            <div class="w-1/2"><canvas id="qualChart"></canvas></div>
                            <div class="w-1/2 pl-4 space-y-2">
                                <?php
                                $qualColors = ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe', '#ede9fe'];
                                foreach ($personnelByQualification as $i => $row):
                                    $color = $qualColors[$i % count($qualColors)];
                                    ?>
                                    <div class="flex items-center justify-between text-sm cursor-pointer hover:text-indigo-600 transition group"
                                         @click="showDetailModal('รายชื่อบุคลากร (คุณวุฒิ: ' + <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> + ')', getFilteredPersonnel({ qualification_name: <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> }))">
                                        <span class="flex items-center"><span class="w-3 h-3 rounded-full mr-2 flex-shrink-0"
                                                style="background:<?= $color ?>"></span><span
                                                class="text-gray-700 group-hover:text-indigo-600 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span></span>
                                        <span class="font-semibold text-gray-900 ml-2 group-hover:underline"><?= $row['total'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">ใบรับรองแยกตามระดับ</h3>
                    <?php if (!empty($certByLevel)): ?>
                        <canvas id="certChart" height="220"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Row 3: Track & Academic Position -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">สายปฏิบัติการ / สายวิชาการ (ป/ว)</h3>
                    <?php if (!empty($personnelByTrack)): ?>
                        <div class="flex items-center">
                            <div class="w-1/2"><canvas id="trackChart"></canvas></div>
                            <div class="w-1/2 pl-4 space-y-2">
                                <?php
                                $trackColors = ['#f59e0b', '#3b82f6']; // Amber and Blue
                                foreach ($personnelByTrack as $i => $row):
                                    $color = $trackColors[$i % count($trackColors)];
                                    ?>
                                    <div class="flex items-center justify-between text-sm cursor-pointer hover:text-indigo-600 transition group"
                                         @click="showDetailModal('รายชื่อบุคลากร (' + <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> + ')', getFilteredPersonnel({ track: <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> }))">
                                        <span class="flex items-center"><span class="w-3 h-3 rounded-full mr-2 flex-shrink-0"
                                                style="background:<?= $color ?>"></span><span
                                                class="text-gray-700 group-hover:text-indigo-600 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span></span>
                                        <span class="font-semibold text-gray-900 ml-2 group-hover:underline"><?= $row['total'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">ตำแหน่งทางวิชาการ</h3>
                    <?php if (!empty($personnelByAcademicPosition)): ?>
                        <div class="flex items-center">
                            <div class="w-1/2">
                                <canvas id="academicPosChart"></canvas>
                            </div>
                            <div class="w-1/2 pl-4 space-y-2" id="academicPosLegend">
                                <?php
                                $totalPos = array_sum(array_column($personnelByAcademicPosition, 'total'));
                                $posColors = ['#ec4899', '#f43f5e', '#f472b6', '#fb7185', '#fda4af', '#fecdd3'];
                                foreach ($personnelByAcademicPosition as $i => $row):
                                    $color = $posColors[$i % count($posColors)];
                                    $pct = $totalPos > 0 ? round(($row['total'] / $totalPos) * 100, 1) : 0;
                                    ?>
                                    <div class="flex items-center justify-between text-sm cursor-pointer hover:text-indigo-600 transition group"
                                         @click="showDetailModal('รายชื่อบุคลากร (ตำแหน่งทางวิชาการ: ' + <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> + ')', getFilteredPersonnel({ academic_position: <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> }))">
                                        <span class="flex items-center">
                                            <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background:<?= $color ?>"></span>
                                            <span class="text-gray-700 group-hover:text-indigo-600 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                        </span>
                                        <div class="text-right">
                                            <span class="font-semibold text-gray-900 group-hover:underline"><?= $row['total'] ?></span>
                                            <span class="text-xs text-gray-400 ml-1">(<?= $pct ?>%)</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Row 4: Job Position & Expertise -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">💼 ตำแหน่งงาน</h3>
                    <?php if (!empty($personnelByJobPosition)): ?>
                        <canvas id="jobPosChart" height="200"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">ความเชี่ยวชาญยอดนิยม (Top 10)</h3>
                    <?php if (!empty($topExpertises)): ?>
                        <canvas id="expertiseChart" height="200"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: งานวิจัย ==================== -->
        <div x-show="activeTab === 'research'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" style="display:none">

            <!-- Research Map -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📍 แผนที่โครงการวิจัย</h3>
                <div id="researchMap" class="w-full h-[600px] rounded-lg border border-gray-200 z-0"></div>
            </div>

            <!-- Research Summary Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Research Projects -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">โครงการวิจัย</h3>
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold cursor-pointer hover:bg-indigo-200 transition" @click="showDetailModal('รายชื่อโครงการวิจัยทั้งหมด', getFilteredResearch())">ทั้งหมด
                            <?= $totalResearch ?></span>
                    </div>
                    <?php if (!empty($researchByStatus)): ?>
                        <div class="flex items-center">
                            <div class="w-1/2"><canvas id="researchStatusChart" height="200"></canvas></div>
                            <div class="w-1/2 pl-6 space-y-3">
                                <?php
                                $statusColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                                foreach ($researchByStatus as $i => $row):
                                    $color = $statusColors[$i % count($statusColors)];
                                    ?>
                                    <div class="flex items-center justify-between text-sm cursor-pointer hover:text-indigo-600 transition group"
                                         @click="showDetailModal('รายชื่อโครงการวิจัย (สถานะ: ' + <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> + ')', getFilteredResearch({ work_status: <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> }))">
                                        <span class="flex items-center">
                                            <span class="w-3 h-3 rounded-full mr-2" style="background:<?= $color ?>"></span>
                                            <span
                                                class="text-gray-600 group-hover:text-indigo-600 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                        </span>
                                        <span class="font-bold text-gray-900 ml-2 group-hover:underline"><?= $row['total'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>

                <!-- Funding Sources & Total Budget -->
                <div class="space-y-6 flex flex-col">
                    <div
                        class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white flex-1 flex flex-col justify-center cursor-pointer hover:shadow-xl transition"
                        @click="showDetailModal('รายชื่อโครงการวิจัยทั้งหมด', getFilteredResearch())">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณวิจัยรวม</h3>
                        <p class="text-5xl font-bold mt-2 hover:underline"><?= number_format($totalResearchBudget, 2) ?></p>
                        <p class="text-indigo-100 mt-1 text-sm">บาท (จากทุกโครงการ)</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">แหล่งทุนวิจัยยอดนิยม (TOP 5)</h3>
                        <?php if (!empty($researchByFunding)): ?>
                            <canvas id="researchFundingChart" height="150"></canvas>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Yearly Comparison Section -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Project Count Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 จำนวนโครงการวิจัยรายปี</h3>
                        <div class="h-[300px]">
                            <canvas id="researchYearlyProjectChart"></canvas>
                        </div>
                    </div>
                    <!-- Budget Sum Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">งบประมาณวิจัยรายปี</h3>
                        <div class="h-[300px]">
                            <canvas id="researchYearlyBudgetChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Summary Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-800">ตารางสรุปเปรียบเทียบรายปี</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr
                                    class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4">ปีงบประมาณ</th>
                                    <th class="px-6 py-4">จำนวนโครงการ</th>
                                    <th class="px-6 py-4 text-right">งบประมาณรวม (บาท)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($researchStats as $row): ?>
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">ปีงบประมาณ
                                            <?= Html::encode($row['label']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                <?= number_format($row['total_projects']) ?> โครงการ
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-indigo-600 text-right">
                                            <?= number_format($row['total_budget'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-gray-50/50 font-bold">
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 border-t border-gray-100">รวมทั้งหมด</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 border-t border-gray-100">
                                        <?= number_format($totalResearch) ?> โครงการ
                                    </td>
                                    <td class="px-6 py-4 text-sm text-indigo-700 text-right border-t border-gray-100">
                                        <?= number_format($totalResearchBudget, 2) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


            </div>
        </div>

        <!-- ==================== TAB: บริการวิชาการ/ทำนุบำรุงวัฒนธรรม ==================== -->
        <div x-show="activeTab === 'academic_service'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" style="display:none">

            <!-- Academic Service Map -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📍 แผนที่บริการวิชาการ/ทำนุบำรุงวัฒนธรรม</h3>
                <div id="academicServiceMap" class="w-full h-[600px] rounded-lg border border-gray-200 z-0"></div>
            </div>

            <!-- Academic Service Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">กิจกรรมบริการวิชาการ/ทำนุบำรุงวัฒนธรรม</h3>
                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold cursor-pointer hover:bg-emerald-200 transition" @click="showDetailModal('รายชื่อโครงการบริการวิชาการทั้งหมด', getFilteredAcademicServices())">ทั้งหมด
                            <?= $totalAcademicService ?></span>
                    </div>
                    <?php if (!empty($academicServiceByStatus)): ?>
                        <div class="flex items-center">
                            <div class="w-1/2"><canvas id="academicServiceStatusChart" height="200"></canvas></div>
                            <div class="w-1/2 pl-6 space-y-3">
                                <?php
                                $statusColors = ['#059669', '#34d399', '#6ee7b7', '#a7f3d0', '#ecfdf5'];
                                foreach ($academicServiceByStatus as $i => $row):
                                    $color = $statusColors[$i % count($statusColors)];
                                    ?>
                                    <div class="flex items-center justify-between text-sm cursor-pointer hover:text-emerald-600 transition group"
                                         @click="showDetailModal('รายชื่อโครงการบริการวิชาการ (สถานะ: ' + <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> + ')', getFilteredAcademicServices({ status: <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> }))">
                                        <span class="flex items-center">
                                            <span class="w-3 h-3 rounded-full mr-2" style="background:<?= $color ?>"></span>
                                            <span
                                                class="text-gray-600 group-hover:text-emerald-600 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                        </span>
                                        <span class="font-bold text-gray-900 ml-2 group-hover:underline"><?= $row['total'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>

                <div class="space-y-6 flex flex-col">
                    <div
                        class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg p-6 text-white flex-1 flex flex-col justify-center">
                        <h3 class="text-lg font-medium opacity-90">จำนวนผู้เข้ารับบริการรวม</h3>
                        <p class="text-5xl font-bold mt-2"><?= number_format($totalParticipants) ?></p>
                        <p class="text-emerald-100 mt-1 text-sm">คน (จากทุกโครงการ)</p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white flex-1 flex flex-col justify-center">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณโครงการรวม</h3>
                        <p class="text-5xl font-bold mt-2"><?= number_format($totalAcademicBudget, 2) ?></p>
                        <p class="text-blue-100 mt-1 text-sm">บาท (จากทุกโครงการ)</p>
                    </div>

                    <a href="<?= \yii\helpers\Url::to(['/academic-service/index']) ?>"
                        class="inline-block px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-sm font-medium text-white transition text-center shadow-md">
                        รายละเอียดบริการวิชาการ →
                    </a>
                </div>
            </div>


            <!-- Yearly Comparison Section -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Project Count Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈
                            จำนวนโครงการบริการวิชาการ/ทำนุบำรุงวัฒนธรรมรายปี</h3>
                        <div class="h-[300px]">
                            <canvas id="academicYearlyProjectChart"></canvas>
                        </div>
                    </div>
                    <!-- Budget Sum Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            งบประมาณบริการวิชาการ/ทำนุบำรุงวัฒนธรรมรายปี
                        </h3>
                        <div class="h-[300px]">
                            <canvas id="academicYearlyBudgetChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Summary Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-800">ตารางสรุปเปรียบเทียบรายปี</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr
                                    class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4">ปีงบประมาณ</th>
                                    <th class="px-6 py-4">จำนวนโครงการ</th>
                                    <th class="px-6 py-4 text-right">งบประมาณรวม (บาท)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($academicServiceStats as $row): ?>
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">ปีงบประมาณ
                                            <?= Html::encode($row['label']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                <?= number_format($row['total_projects']) ?> โครงการ
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-emerald-600 text-right">
                                            <?= number_format($row['total_budget'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-gray-50/50 font-bold">
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 border-t border-gray-100">รวมทั้งหมด</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 border-t border-gray-100">
                                        <?= number_format($totalAcademicService) ?> โครงการ
                                    </td>
                                    <td class="px-6 py-4 text-sm text-emerald-700 text-right border-t border-gray-100">
                                        <?= number_format($totalAcademicBudget, 2) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- ==================== TAB: นวัตกรรม ==================== -->
        <div x-show="activeTab === 'innovation'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" style="display:none">

                <!-- Innovation Summary -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Total Card -->
                    <div
                        class="bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-xl shadow-lg p-6 text-white flex flex-col justify-center cursor-pointer hover:shadow-xl transition"
                        @click="showDetailModal('รายชื่อนวัตกรรมทั้งหมด', getFilteredInnovations())">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-medium opacity-90">นวัตกรรมทั้งหมด</h3>
                        </div>
                        <p class="text-6xl font-bold mt-2 text-center"><span class="hover:underline transition"><?= number_format($totalInnovation) ?></span></p>
                        <p class="text-purple-100 mt-3 text-sm text-center">ผลงานนวัตกรรมที่ถูกบันทึก</p>
                        <a href="<?= \yii\helpers\Url::to(['/innovation/index']) ?>"
                            class="mt-6 inline-block w-full px-4 py-2 bg-purple-700 hover:bg-purple-800 rounded-lg text-sm font-medium text-white transition text-center shadow-md">
                            ดูรายการนวัตกรรมทั้งหมด →
                        </a>
                    </div>

                    <!-- Yearly Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 จำนวนนวัตกรรมรายปี</h3>
                        <div class="h-[250px] w-full relative">
                            <?php if (!empty($innovationStats)): ?>
                                <canvas id="innovationYearlyChart"></canvas>
                            <?php else: ?>
                                <p
                                    class="text-gray-400 text-sm text-center py-8 absolute inset-0 flex items-center justify-center">
                                    ยังไม่มีข้อมูลสถิติรายปี</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Detailed Stats -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top Advisors Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">อาจารย์ที่ปรึกษายอดนิยม (TOP 5)</h3>
                        <div class="h-[250px] w-full relative flex-1">
                            <?php if (!empty($topInnovationAdvisors)): ?>
                                <canvas id="innovationAdvisorChart"></canvas>
                            <?php else: ?>
                                <p
                                    class="text-gray-400 text-sm text-center py-8 absolute inset-0 flex items-center justify-center">
                                    ยังไม่มีข้อมูลอาจารย์ที่ปรึกษา</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Summary Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-800">ตารางสรุปเปรียบเทียบรายปี</h3>
                        </div>
                        <div class="overflow-x-auto flex-1">
                            <table class="w-full text-left h-full">
                                <thead class="bg-gray-50">
                                    <tr
                                        class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                        <th class="px-6 py-4">ปี</th>
                                        <th class="px-6 py-4 text-right">จำนวนนวัตกรรม (ผลงาน)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($innovationStats as $row): ?>
                                        <tr class="hover:bg-gray-50/70 transition-colors">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">ปี
                                                <?= Html::encode($row['label']) ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 text-right">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <?= number_format($row['total_projects']) ?> ผลงาน
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-gray-50/50 font-bold mt-auto border-t-2 border-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">รวมทั้งหมด</td>
                                        <td class="px-6 py-4 text-sm text-purple-700 text-right">
                                            <?= number_format($totalInnovation) ?> ผลงาน
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== TAB: นักเรียนทุน ==================== -->
            <div x-show="activeTab === 'scholarship'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="space-y-6" style="display:none">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Summary Card -->
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg p-8 text-white">
                        <h3 class="text-lg font-medium opacity-90">นักเรียนทุนทั้งหมด</h3>
                        <p class="text-5xl font-bold mt-3"><?= $totalScholarships ?></p>
                        <p class="text-amber-100 mt-2 text-sm">จำนวนทุนที่บันทึกในระบบ</p>
                        <a href="<?= \yii\helpers\Url::to(['/scholarship/index']) ?>"
                            class="inline-block mt-4 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition">ดูรายละเอียด
                            →</a>
                    </div>

                    <!-- Scholarship by Qualification -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">นักเรียนทุนแยกตามระดับคุณวุฒิ</h3>
                        <?php if (!empty($scholarByQualification)): ?>
                            <div class="space-y-3">
                                <?php
                                $maxScholar = max(array_column($scholarByQualification, 'total'));
                                $barColors = ['bg-amber-500', 'bg-orange-500', 'bg-yellow-500', 'bg-rose-500', 'bg-pink-500'];
                                foreach ($scholarByQualification as $i => $row):
                                    $pctS = $maxScholar > 0 ? round($row['total'] / $maxScholar * 100) : 0;
                                    $barColor = $barColors[$i % count($barColors)];
                                    ?>
                                    <div class="cursor-pointer group hover:text-amber-600 transition"
                                         @click="showDetailModal('รายชื่อนักเรียนทุน (ระดับคุณวุฒิ: ' + <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> + ')', getFilteredScholarships({ qualification_name: <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> }))">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span
                                                class="text-gray-700 font-medium group-hover:text-amber-600"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                            <div class="text-right">
                                                <span class="text-gray-900 font-bold group-hover:underline"><?= $row['total'] ?> คน</span>
                                                <span class="text-xs text-amber-600 ml-1">(<?= $totalScholarships > 0 ? round($row['total'] / $totalScholarships * 100, 1) : 0 ?>%)</span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-3">
                                            <div class="<?= $barColor ?> h-3 rounded-full transition-all duration-500"
                                                style="width:<?= $pctS ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 5-Year Scholarship Retention -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 จำนวนนักเรียนทุนย้อนหลัง 5 ปี (ตามอายุสัญญา)</h3>
                    <div class="h-[300px]">
                        <canvas id="scholarshipRetentionChart"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Scholarship by Major -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">นักเรียนทุนแยกตามสาขา</h3>
                        <?php if (!empty($scholarByMajor)): ?>
                            <div class="space-y-3">
                                <?php
                                $maxMajor = max(array_column($scholarByMajor, 'total'));
                                $majorColors = ['bg-indigo-500', 'bg-purple-500', 'bg-blue-500', 'bg-cyan-500', 'bg-teal-500'];
                                foreach ($scholarByMajor as $i => $row):
                                    $pctM = $maxMajor > 0 ? round($row['total'] / $maxMajor * 100) : 0;
                                    $barColor = $majorColors[$i % count($majorColors)];
                                    ?>
                                    <div class="cursor-pointer group hover:text-indigo-600 transition"
                                         @click="showDetailModal('รายชื่อนักเรียนทุน (สาขาวิชา: ' + <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> + ')', getFilteredScholarships({ major: <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> }))">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span
                                                class="text-gray-700 font-medium group-hover:text-indigo-600"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                            <div class="text-right">
                                                <span class="text-gray-900 font-bold group-hover:underline"><?= $row['total'] ?> คน</span>
                                                <span class="text-xs text-indigo-600 ml-1">(<?= $totalScholarships > 0 ? round($row['total'] / $totalScholarships * 100, 1) : 0 ?>%)</span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-3">
                                            <div class="<?= $barColor ?> h-3 rounded-full transition-all duration-500"
                                                style="width:<?= $pctM ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                        <?php endif; ?>
                    </div>

                    <!-- Scholarship by Institution -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">นักเรียนทุนแยกตามสถาบัน</h3>
                        <?php if (!empty($scholarByInstitution)): ?>
                            <div class="space-y-3">
                                <?php
                                $maxInst = max(array_column($scholarByInstitution, 'total'));
                                $instColors = ['bg-emerald-500', 'bg-green-500', 'bg-lime-500', 'bg-yellow-500', 'bg-amber-500'];
                                foreach ($scholarByInstitution as $i => $row):
                                    $pctI = $maxInst > 0 ? round($row['total'] / $maxInst * 100) : 0;
                                    $barColor = $instColors[$i % count($instColors)];
                                    ?>
                                    <div class="cursor-pointer group hover:text-emerald-600 transition"
                                         @click="showDetailModal('รายชื่อนักเรียนทุน (สถาบัน: ' + <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> + ')', getFilteredScholarships({ institution: <?= Html::encode(json_encode($row['label'] ?: 'ไม่ระบุ')) ?> }))">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span
                                                class="text-gray-700 font-medium group-hover:text-emerald-600"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                            <div class="text-right">
                                                <span class="text-gray-900 font-bold group-hover:underline"><?= $row['total'] ?> คน</span>
                                                <span class="text-xs text-emerald-600 ml-1">(<?= $totalScholarships > 0 ? round($row['total'] / $totalScholarships * 100, 1) : 0 ?>%)</span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-3">
                                            <div class="<?= $barColor ?> h-3 rounded-full transition-all duration-500"
                                                style="width:<?= $pctI ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ==================== TAB: รายรับ-รายจ่าย ==================== -->
            <div x-show="activeTab === 'budget'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="space-y-6" style="display:none">

                <!-- Budget Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl shadow-lg p-6 text-white cursor-pointer hover:shadow-xl hover:-translate-y-1 transition transform duration-200 group"
                         @click="showDetailModal('จัดสรรงบประมาณ ปีงบประมาณ <?= $latestBudgetYear ?>', getFilteredBudgetAllocations({ fiscal_year: '<?= $latestBudgetYear ?>' }))">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณที่จัดสรร (ปี <?= $latestBudgetYear ?>)</h3>
                        <p class="text-4xl font-black mt-2 group-hover:underline decoration-indigo-200"><?= number_format($currentYearTotalBudget, 2) ?></p>
                        <p class="text-indigo-100 mt-1 text-xs opacity-80">บาท (Net Budget)</p>
                    </div>
                    <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl shadow-lg p-6 text-white cursor-pointer hover:shadow-xl hover:-translate-y-1 transition transform duration-200 group"
                         @click="showDetailModal('รายจ่ายเบิกจ่ายจริง ปีงบประมาณ <?= $latestBudgetYear ?>', getFilteredBudgetTransactions({ fiscal_year: '<?= $latestBudgetYear ?>' }))">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณที่เบิกจ่าย (ปี <?= $latestBudgetYear ?>)</h3>
                        <p class="text-4xl font-black mt-2 group-hover:underline decoration-rose-200"><?= number_format($currentYearTotalSpent, 2) ?></p>
                        <p class="text-rose-100 mt-1 text-xs opacity-80">บาท (Actual Expenses)</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg p-6 text-white cursor-pointer hover:shadow-xl hover:-translate-y-1 transition transform duration-200 group"
                         @click="showDetailModal('งบประมาณคงเหลือรายกิจกรรม ปีงบประมาณ <?= $latestBudgetYear ?>', getFilteredBudgetRemaining({ fiscal_year: '<?= $latestBudgetYear ?>' }))">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณคงเหลือ (ปี <?= $latestBudgetYear ?>)</h3>
                        <p class="text-4xl font-black mt-2 group-hover:underline decoration-emerald-200"><?= number_format($currentYearTotalBudget - $currentYearTotalSpent, 2) ?></p>
                        <p class="text-emerald-100 mt-1 text-xs opacity-80">บาท (Remaining Balance)</p>
                    </div>
                </div>

                <!-- Budget vs Expense Chart -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 เปรียบเทียบรายรับ-รายจ่ายรายปี</h3>
                        <div class="h-[350px]">
                            <canvas id="budgetComparisonChart"></canvas>
                        </div>
                    </div>

                    <!-- Current Year Activity Breakdown -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">สัดส่วนการใช้จ่ายรายกิจกรรม (ปี <?= $latestBudgetYear ?>)</h3>
                            <span class="text-xs font-bold text-gray-400">เรียงตาม % การใช้จ่าย</span>
                        </div>
                        <div class="flex-1 overflow-y-auto max-h-[350px] pr-2 space-y-5">
                            <?php if (!empty($currentYearBreakdown)): ?>
                                <?php foreach ($currentYearBreakdown as $item): ?>
                                    <div class="group cursor-pointer hover:bg-gray-50 p-1.5 -mx-1.5 rounded-lg transition"
                                         @click="showDetailModal('รายการค่าใช้จ่ายหมวด: <?= Html::encode($item['category']) ?> (ปี <?= $latestBudgetYear ?>)', getFilteredBudgetTransactions({ fiscal_year: '<?= $latestBudgetYear ?>', category_name: '<?= Html::encode($item['category']) ?>' }))">
                                        <div class="flex justify-between items-end mb-1.5">
                                            <div class="flex-1 mr-4">
                                                <h4 class="text-sm font-bold text-gray-700 leading-tight group-hover:text-indigo-600 transition truncate" title="<?= Html::encode($item['category']) ?>">
                                                    <?= Html::encode($item['category']) ?>
                                                </h4>
                                                <p class="text-[10px] text-gray-400 mt-0.5">
                                                    ใช้ไป <?= number_format($item['spent'], 2) ?> / <?= number_format($item['total'], 2) ?> บาท
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-black <?= $item['percent'] > 90 ? 'text-rose-600' : ($item['percent'] > 50 ? 'text-indigo-600' : 'text-emerald-600') ?>">
                                                    <?= $item['percent'] ?>%
                                                </span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <?php 
                                            $barColor = 'bg-emerald-500';
                                            if ($item['percent'] > 90) $barColor = 'bg-rose-500';
                                            elseif ($item['percent'] > 50) $barColor = 'bg-indigo-500';
                                            ?>
                                            <div class="<?= $barColor ?> h-full rounded-full transition-all duration-1000 ease-out" style="width: <?= min(100, $item['percent']) ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="h-full flex flex-col items-center justify-center text-gray-400 italic py-10">
                                    <svg class="w-12 h-12 mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    ยังไม่มีข้อมูลการใช้จ่ายในปีนี้
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Yearly Budget Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-800">ตารางสรุปงบประมาณรายปี</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4">ปีงบประมาณ</th>
                                    <th class="px-6 py-4 text-right">งบประมาณสุทธิ (บาท)</th>
                                    <th class="px-6 py-4 text-right">เบิกจ่ายจริง (บาท)</th>
                                    <th class="px-6 py-4 text-right">คงเหลือ (บาท)</th>
                                    <th class="px-6 py-4 text-right">% การเบิกจ่าย</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($budgetStats as $row): ?>
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">ปีงบประมาณ <?= Html::encode($row['label']) ?></td>
                                        <td class="px-6 py-4 text-sm font-bold text-indigo-600 text-right cursor-pointer hover:underline hover:text-indigo-800"
                                            @click="showDetailModal('จัดสรรงบประมาณ ปีงบประมาณ <?= Html::encode($row['label']) ?>', getFilteredBudgetAllocations({ fiscal_year: '<?= Html::encode($row['label']) ?>' }))">
                                            <?= number_format($row['total_budget'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-rose-600 text-right cursor-pointer hover:underline hover:text-rose-800"
                                            @click="showDetailModal('รายจ่ายเบิกจ่ายจริง ปีงบประมาณ <?= Html::encode($row['label']) ?>', getFilteredBudgetTransactions({ fiscal_year: '<?= Html::encode($row['label']) ?>' }))">
                                            <?= number_format($row['total_spent'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-emerald-600 text-right cursor-pointer hover:underline hover:text-emerald-800"
                                            @click="showDetailModal('งบประมาณคงเหลือรายกิจกรรม ปีงบประมาณ <?= Html::encode($row['label']) ?>', getFilteredBudgetRemaining({ fiscal_year: '<?= Html::encode($row['label']) ?>' }))">
                                            <?= number_format($row['total_budget'] - $row['total_spent'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <?= $row['total_budget'] > 0 ? round(($row['total_spent'] / $row['total_budget']) * 100, 2) : 0 ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div><!-- end tabs -->

        <!-- Unified Detail Modal -->
        <div x-show="detailModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true"
            @keydown.escape.window="detailModalOpen = false">
            <div class="flex items-center justify-center min-h-screen p-4 text-center">
                <div x-show="detailModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true"
                    @click="detailModalOpen = false"></div>
                
                <div x-show="detailModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl sm:w-full border border-gray-100">
                    
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 px-6 py-4 text-white flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold" x-text="detailModalTitle"></h3>
                            <p class="text-xs text-indigo-100 mt-1">
                                พบข้อมูล <span class="font-bold text-white text-sm" x-text="getFilteredModalItems().length"></span> รายการ
                            </p>
                        </div>
                        <button @click="detailModalOpen = false" class="text-white/80 hover:text-white transition focus:outline-none p-1 rounded-lg hover:bg-white/10">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Search input -->
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="detailModalSearch" placeholder="พิมพ์เพื่อค้นหาข้อมูล..."
                                class="block w-full pl-10 pr-4 py-2 border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm rounded-lg border bg-white focus:outline-none transition">
                        </div>
                    </div>

                    <!-- Content List -->
                    <div class="bg-white px-6 py-4 max-h-[400px] overflow-y-auto">
                        <ul class="space-y-2.5">
                            <template x-for="(item, idx) in getFilteredModalItems()" :key="idx">
                                <li :class="item.url && item.url !== '#' ? 'cursor-pointer hover:border-indigo-200 hover:shadow-md hover:bg-indigo-50/20' : 'cursor-default'"
                                    class="p-3 bg-white rounded-xl shadow-sm border border-gray-100 transition flex justify-between items-center group"
                                    @click="if (item.url && item.url !== '#') window.location.href = item.url">
                                    <div class="flex-1 min-w-0 pr-4">
                                        <div class="text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition" x-text="item.title"></div>
                                        <div class="text-xs text-gray-500 mt-1 truncate" x-text="item.subtitle"></div>
                                    </div>
                                    <template x-if="item.amount">
                                        <div class="flex-shrink-0 text-right pr-4">
                                            <span class="text-base font-black" :class="item.amountColorClass || 'text-gray-950'" x-text="item.amount"></span>
                                        </div>
                                    </template>
                                    <template x-if="item.url && item.url !== '#'">
                                        <div class="flex-shrink-0 bg-indigo-50 text-indigo-600 rounded-lg p-2 opacity-60 group-hover:opacity-100 transition group-hover:bg-indigo-600 group-hover:text-white">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </template>
                                </li>
                            </template>
                            <li x-show="getFilteredModalItems().length === 0"
                                class="text-sm text-gray-500 text-center py-12 flex flex-col items-center justify-center space-y-2">
                                <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>ไม่พบข้อมูลที่ตรงกับคำค้นหา</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                        <button type="button"
                            class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg transition"
                            @click="detailModalOpen = false">
                            ปิดหน้าต่าง
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js for tabs -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <?php
    $allResearchYears = array_column($researchStats, 'label');
    $allServiceYears = array_column($academicServiceStats, 'label');
    $allYearsCombined = array_unique(array_merge($allResearchYears, $allServiceYears));
    sort($allYearsCombined);

    // Map data
    $researchCountsByYear = [];
    foreach ($researchStats as $r) {
        $researchCountsByYear[$r['label']] = (int)$r['total_projects'];
    }
    $serviceCountsByYear = [];
    foreach ($academicServiceStats as $s) {
        $serviceCountsByYear[$s['label']] = (int)$s['total_projects'];
    }

    $execYearsJson = Json::encode($allYearsCombined);
    $execResearchCountsJson = Json::encode(array_map(fn($y) => $researchCountsByYear[$y] ?? 0, $allYearsCombined));
    $execServiceCountsJson = Json::encode(array_map(fn($y) => $serviceCountsByYear[$y] ?? 0, $allYearsCombined));

    $budgetYears = array_column($budgetStats, 'label');
    $budgetAllocatedList = array_column($budgetStats, 'total_budget');
    $budgetSpentList = array_column($budgetStats, 'total_spent');

    $execBudgetYearsJson = Json::encode($budgetYears);
    $execBudgetAllocatedJson = Json::encode($budgetAllocatedList);
    $execBudgetSpentJson = Json::encode($budgetSpentList);
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var chartColors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#06b6d4', '#f43f5e', '#84cc16', '#14b8a6', '#a855f7'];

            // ===== EXECUTIVE TAB CHARTS =====

            // 1. Research & Service Chart
            window.execResearchServiceChart = new Chart(document.getElementById('execResearchServiceChart'), {
                type: 'bar',
                data: {
                    labels: <?= $execYearsJson ?>,
                    datasets: [
                        {
                            label: 'งานวิจัย',
                            data: <?= $execResearchCountsJson ?>,
                            backgroundColor: '#6366f1',
                            borderRadius: 6,
                        },
                        {
                            label: 'บริการวิชาการ',
                            data: <?= $execServiceCountsJson ?>,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: function(e, activeEls) {
                        if (activeEls.length > 0) {
                            const index = activeEls[0].index;
                            const clickedYear = e.chart.data.labels[index];
                            window.dashboardAlpine.showDetailModal(
                                'โครงการวิจัยและบริการวิชาการ ประจำปี พ.ศ. ' + clickedYear, 
                                window.dashboardAlpine.getProjectsByYear(clickedYear)
                            );
                        }
                    },
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' โครงการ';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 2. Budget vs Spent Chart
            window.execBudgetSpentChart = new Chart(document.getElementById('execBudgetSpentChart'), {
                type: 'line',
                data: {
                    labels: <?= $execBudgetYearsJson ?>,
                    datasets: [
                        {
                            label: 'งบประมาณสุทธิ',
                            data: <?= $execBudgetAllocatedJson ?>,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.03)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 4,
                            borderWidth: 3
                        },
                        {
                            label: 'เบิกจ่ายจริง',
                            data: <?= $execBudgetSpentJson ?>,
                            borderColor: '#f43f5e',
                            backgroundColor: 'rgba(244, 63, 94, 0.03)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#f43f5e',
                            pointRadius: 4,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: function(e, activeEls) {
                        if (activeEls.length > 0) {
                            const index = activeEls[0].index;
                            const clickedYear = e.chart.data.labels[index];
                            window.dashboardAlpine.showDetailModal(
                                'รายละเอียดงบประมาณประจำปี พ.ศ. ' + clickedYear, 
                                window.dashboardAlpine.getBudgetBreakdownByYear(clickedYear)
                            );
                        }
                    },
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' บาท';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return (value / 1000000) + 'M'; // Show in millions
                                }
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // ===== STUDENT TAB CHARTS =====

            // Retention Doughnut
            var retentionCtx = document.getElementById('retentionChart').getContext('2d');
            window.retentionChart = new Chart(retentionCtx, {
                type: 'bar',
                data: {
                    labels: ['กำลังศึกษา', 'สำเร็จการศึกษา', 'พักการเรียน', 'พ้นสภาพ'],
                    datasets: [{
                        data: [<?= $activeStudents ?>, <?= $graduatedStudents ?>, <?= $inactiveStudents ?>, <?= $droppedStudents ?>],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                        borderRadius: 6,
                        barThickness: 25,
                    }]
                },
                options: { 
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: function(e, activeEls) {
                        if (activeEls.length > 0) {
                            const index = activeEls[0].index;
                            const statuses = ['active', 'graduated', 'inactive', 'dropped'];
                            const statusTitles = ['กำลังศึกษา', 'สำเร็จการศึกษา', 'พักการเรียน', 'พ้นสภาพ'];
                            const batch = window.dashboardAlpine.globalBatch;
                            const batchTitle = batch === 'total' ? '' : ' รุ่น ' + batch;
                            window.dashboardAlpine.showDetailModal('รายชื่อนักศึกษา (' + statusTitles[index] + ')' + batchTitle, window.dashboardAlpine.getFilteredStudents(statuses[index]));
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        },
                        x: {
                            display: false // Hide labels on X axis as they are in the custom legend
                        }
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.parsed.y || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // GPAX Line
            var gpaxChartCtx = document.getElementById('gpaxChart').getContext('2d');
            window.gpaxChart = new Chart(gpaxChartCtx, {
                type: 'line',
                data: {
                    labels: <?= Json::encode(array_keys($gpaxByYear)) ?>,
                    datasets: [{
                        label: 'GPAX เฉลี่ยรวม',
                        data: <?= Json::encode(array_values($gpaxByYear)) ?>,
                        borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.1)',
                        tension: 0.4, fill: true, pointBackgroundColor: '#6366f1', pointRadius: 5, pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { min: 0, max: 4, ticks: { stepSize: 0.5 } } },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let val = context.parsed.y;
                                    let pct = ((val / 4.0) * 100).toFixed(1);
                                    return 'GPAX: ' + val.toFixed(2) + ' (' + pct + '% of 4.0)';
                                }
                            }
                        }
                    }
                }
            });

            // GPAX Grouping Doughnut
            var gpaxGroupCtx = document.getElementById('gpaxGroupChart').getContext('2d');
            window.gpaxGroupChart = new Chart(gpaxGroupCtx, {
                type: 'doughnut',
                data: {
                    labels: ['3.50-4.00', '3.00-3.49', '2.50-2.99', '2.00-2.49'],
                    datasets: [{
                        data: [<?= $gpaxGroups['r1'] ?>, <?= $gpaxGroups['r2'] ?>, <?= $gpaxGroups['r3'] ?>, <?= $gpaxGroups['r4'] ?>],
                        backgroundColor: ['#10b981', '#6366f1', '#f59e0b', '#f43f5e'],
                        hoverOffset: 4,
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: function(e, activeEls) {
                        if (activeEls.length > 0) {
                            const index = activeEls[0].index;
                            const ranges = ['r1', 'r2', 'r3', 'r4'];
                            const rangeTitles = ['3.50 - 4.00', '3.00 - 3.49', '2.50 - 2.99', '2.00 - 2.49'];
                            const batch = window.dashboardAlpine.globalBatch;
                            const batchTitle = batch === 'total' ? '' : ' รุ่น ' + batch;
                            window.dashboardAlpine.showDetailModal('รายชื่อนักศึกษาเกณฑ์ ' + rangeTitles[index] + batchTitle, window.dashboardAlpine.getFilteredStudentsByGpaxRange(ranges[index]));
                        }
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.parsed || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // GPAX by Batch Bar
            var gpaxBatchCtx = document.getElementById('gpaxBatchChart').getContext('2d');
            window.gpaxBatchChart = new Chart(gpaxBatchCtx, {
                type: 'bar',
                data: {
                    labels: <?= Json::encode(array_map(fn($b) => "รหัส $b", array_keys($gpaxByBatch))) ?>,
                    datasets: [{
                        label: 'GPAX เฉลี่ย',
                        data: <?= Json::encode(array_values($gpaxByBatch)) ?>,
                        backgroundColor: <?= Json::encode(array_map(fn($b) => '#8b5cf6', array_keys($gpaxByBatch))) ?>,
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 30,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let val = context.parsed.y;
                                    let pct = ((val / 4.0) * 100).toFixed(1);
                                    return 'GPAX: ' + val.toFixed(2) + ' (' + pct + '% of 4.0)';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, max: 4, ticks: { stepSize: 0.5 } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Data for dynamic updates
            var totalGpaxTrend = {
                labels: <?= Json::encode(array_keys($gpaxByYear)) ?>,
                data: <?= Json::encode(array_values($gpaxByYear)) ?>
            };
            var batchGpaxTrend = <?= Json::encode($gpaxTrendByBatch) ?>;

            var totalGpaxGroups = {
                r1: <?= $gpaxGroups['r1'] ?>,
                r2: <?= $gpaxGroups['r2'] ?>,
                r3: <?= $gpaxGroups['r3'] ?>,
                r4: <?= $gpaxGroups['r4'] ?>
            };
            var batchGpaxGroups = <?= Json::encode($gpaxGroupsByBatch) ?>;

            var totalRetention = {
                active: <?= $activeStudents ?>,
                graduated: <?= $graduatedStudents ?>,
                inactive: <?= $inactiveStudents ?>,
                dropped: <?= $droppedStudents ?>,
                rate: <?= $retentionRate ?>
            };
            var batchRetention = <?= Json::encode($retentionByBatch) ?>;

            var batchList = <?= Json::encode(array_keys($gpaxByBatch)) ?>;
            var totalGpaxStats = { avg: <?= round($avgGpax, 2) ?>, max: <?= round($maxGpax, 2) ?>, min: <?= round($minGpax, 2) ?> };
            var batchGpaxStats = <?= Json::encode($batchGpaxStats) ?>;
            window.globalAllGpaxStudents = <?= yii\helpers\Json::encode($gpaxStudentsList) ?>;

            window.updateAllStudentCharts = function (mode) {
                // 1. Update Trend Chart
                if (mode === 'total') {
                    window.gpaxChart.data.labels = totalGpaxTrend.labels;
                    window.gpaxChart.data.datasets[0].data = totalGpaxTrend.data;
                    window.gpaxChart.data.datasets[0].label = 'GPAX เฉลี่ยรวม';
                    window.gpaxChart.data.datasets[0].borderColor = '#6366f1';
                } else {
                    var trend = batchGpaxTrend[mode] || {};
                    window.gpaxChart.data.labels = Object.keys(trend);
                    window.gpaxChart.data.datasets[0].data = Object.values(trend);
                    window.gpaxChart.data.datasets[0].label = 'GPAX รหัส ' + mode;
                    window.gpaxChart.data.datasets[0].borderColor = '#8b5cf6';
                }
                window.gpaxChart.update();

                // 2. Update Grouping Chart & Counters
                var groups = (mode === 'total') ? totalGpaxGroups : (batchGpaxGroups[mode] || { r1: 0, r2: 0, r3: 0, r4: 0 });
                window.gpaxGroupChart.data.datasets[0].data = [groups.r1, groups.r2, groups.r3, groups.r4];
                window.gpaxGroupChart.update();

                document.getElementById('gpaxCountR1').innerText = groups.r1;
                document.getElementById('gpaxCountR2').innerText = groups.r2;
                document.getElementById('gpaxCountR3').innerText = groups.r3;
                document.getElementById('gpaxCountR4').innerText = groups.r4;

                // 3. Update Retention Chart & Counters
                var ret = (mode === 'total') ? totalRetention : (batchRetention[mode] || { active: 0, graduated: 0, inactive: 0, dropped: 0 });
                if (mode !== 'total') {
                    var total = ret.active + ret.graduated + ret.inactive + ret.dropped;
                    ret.rate = total > 0 ? Math.round((ret.active + ret.graduated) / total * 100) : 0;
                }
                window.retentionChart.data.datasets[0].data = [ret.active, ret.graduated, ret.inactive, ret.dropped];
                window.retentionChart.update();

                document.getElementById('retentionCountActive').innerText = ret.active;
                document.getElementById('retentionCountGraduated').innerText = ret.graduated;
                document.getElementById('retentionCountInactive').innerText = ret.inactive;
                document.getElementById('retentionCountDropped').innerText = ret.dropped;
                document.getElementById('retentionRateVal').innerText = ret.rate;

                // 4. Update Batch Chart (Highlight selected)
                window.gpaxBatchChart.data.datasets[0].backgroundColor = batchList.map(function (b) {
                    return (b == mode) ? '#f59e0b' : '#8b5cf6'; // Orange for selected, Purple for others
                });
                window.gpaxBatchChart.update();

                // 5. Update KPI Cards (GPA)
                var gStats = (mode === 'total') ? totalGpaxStats : (batchGpaxStats[mode] || { avg: 0, max: 0, min: 0 });
                document.getElementById('kpiAvgGpax').innerText = gStats.avg.toFixed(2);
                document.getElementById('kpiMaxGpax').innerText = gStats.max.toFixed(2);
                document.getElementById('kpiMinGpax').innerText = gStats.min.toFixed(2);
            };

            // License Doughnut
            new Chart(document.getElementById('licenseChart'), {
                type: 'doughnut',
                data: {
                    labels: ['สอบผ่าน', 'ยังไม่ผ่าน'],
                    datasets: [{ data: [<?= $licensePassed ?>, <?= max(0, $licenseTotal - $licensePassed) ?>], backgroundColor: ['#10b981', '#e5e7eb'], borderWidth: 0 }]
                },
                options: { cutout: '70%', plugins: { legend: { display: false } }, responsive: true }
            });

            // ===== PERSONNEL TAB CHARTS =====

            <?php if (!empty($personnelByDept)): ?>
                window.deptChart = new Chart(document.getElementById('deptChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByDept)) ?>,
                        datasets: [{ label: 'จำนวน', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByDept)) ?>, backgroundColor: chartColors, borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        responsive: true, 
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อบุคลากร (สาขา: ' + label + ')', window.dashboardAlpine.getFilteredPersonnel({ department_name: label }));
                            }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByContract)): ?>
                window.contractChart = new Chart(document.getElementById('contractChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByContract)) ?>,
                        datasets: [{ label: 'จำนวน', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByContract)) ?>, backgroundColor: ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe'], borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        indexAxis: 'y', 
                        responsive: true, 
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อบุคลากร (ประเภทสัญญา: ' + label + ')', window.dashboardAlpine.getFilteredPersonnel({ contract_type_name: label }));
                            }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.x || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByQualification)): ?>
                window.qualChart = new Chart(document.getElementById('qualChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByQualification)) ?>,
                        datasets: [{ data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByQualification)) ?>, backgroundColor: ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe', '#ede9fe'], borderWidth: 0, hoverOffset: 6 }]
                    },
                    options: { 
                        cutout: '60%', 
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อบุคลากร (คุณวุฒิ: ' + label + ')', window.dashboardAlpine.getFilteredPersonnel({ qualification_name: label }));
                            }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        responsive: true 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($certByLevel)): ?>
                window.certChart = new Chart(document.getElementById('certChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $certByLevel)) ?>,
                        datasets: [{ label: 'จำนวน', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $certByLevel)) ?>, backgroundColor: ['#f59e0b', '#f97316', '#ef4444', '#ec4899', '#8b5cf6'], borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        responsive: true, 
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อบุคลากรที่มีใบรับรองระดับ: ' + label, window.dashboardAlpine.getFilteredCertifications(label));
                            }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($topExpertises)): ?>
                window.expertiseChart = new Chart(document.getElementById('expertiseChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $topExpertises)) ?>,
                        datasets: [{ label: 'จำนวนบุคลากร', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $topExpertises)) ?>, backgroundColor: chartColors, borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        indexAxis: 'y', 
                        responsive: true, 
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อบุคลากรที่มีความเชี่ยวชาญ: ' + label, window.dashboardAlpine.getFilteredExpertises(label));
                            }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.x || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByTrack)): ?>
                window.trackChart = new Chart(document.getElementById('trackChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByTrack)) ?>,
                        datasets: [{ data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByTrack)) ?>, backgroundColor: ['#f59e0b', '#3b82f6'], borderWidth: 0, hoverOffset: 6 }]
                    },
                    options: { 
                        cutout: '60%', 
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อบุคลากร (' + label + ')', window.dashboardAlpine.getFilteredPersonnel({ track: label }));
                            }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        responsive: true 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByAcademicPosition)): ?>
                window.academicPosChart = new Chart(document.getElementById('academicPosChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByAcademicPosition)) ?>,
                        datasets: [{ 
                            data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByAcademicPosition)) ?>, 
                            backgroundColor: ['#ec4899', '#f43f5e', '#f472b6', '#fb7185', '#fda4af', '#fecdd3'], 
                            borderWidth: 0, 
                            hoverOffset: 6 
                        }]
                    },
                    options: { 
                        cutout: '60%', 
                        responsive: true, 
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อบุคลากร (ตำแหน่งทางวิชาการ: ' + label + ')', window.dashboardAlpine.getFilteredPersonnel({ academic_position: label }));
                            }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByJobPosition)): ?>
                window.jobPosChart = new Chart(document.getElementById('jobPosChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByJobPosition)) ?>,
                        datasets: [{ label: 'จำนวนบุคลากร', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByJobPosition)) ?>, backgroundColor: ['#10b981', '#34d399', '#059669', '#6ee7b7'], borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        indexAxis: 'y', 
                        responsive: true, 
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อบุคลากร (ตำแหน่งงาน: ' + label + ')', window.dashboardAlpine.getFilteredPersonnel({ job_position: label }));
                            }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.x || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            // Data for dynamic updates (Personnel)
            var personnelStatsByTrack = <?= Json::encode($personnelStatsByTrack) ?>;

            window.updateAllPersonnelCharts = function (trackKey) {
                var data = personnelStatsByTrack[trackKey] || {};

                // 1. Dept Chart
                if (window.deptChart) {
                    window.deptChart.data.labels = data.dept.map(r => r.label || 'ไม่ระบุ');
                    window.deptChart.data.datasets[0].data = data.dept.map(r => parseInt(r.total));
                    window.deptChart.update();
                }

                // 2. Contract Chart
                if (window.contractChart) {
                    window.contractChart.data.labels = data.contract.map(r => r.label || 'ไม่ระบุ');
                    window.contractChart.data.datasets[0].data = data.contract.map(r => parseInt(r.total));
                    window.contractChart.update();
                }

                // 3. Qual Chart
                if (window.qualChart) {
                    window.qualChart.data.labels = data.qualification.map(r => r.label || 'ไม่ระบุ');
                    window.qualChart.data.datasets[0].data = data.qualification.map(r => parseInt(r.total));
                    window.qualChart.update();
                }

                // 4. Cert Chart
                if (window.certChart) {
                    window.certChart.data.labels = data.cert.map(r => r.label || 'ไม่ระบุ');
                    window.certChart.data.datasets[0].data = data.cert.map(r => parseInt(r.total));
                    window.certChart.update();
                }

                // 5. Academic Position Chart
                if (window.academicPosChart) {
                    const labels = data.academicPosition.map(r => r.label || 'ไม่ระบุ');
                    const values = data.academicPosition.map(r => parseInt(r.total));
                    const total = values.reduce((a, b) => a + b, 0);
                    
                    window.academicPosChart.data.labels = labels;
                    window.academicPosChart.data.datasets[0].data = values;
                    window.academicPosChart.update();

                    // Update Legend
                    const legendContainer = document.getElementById('academicPosLegend');
                    if (legendContainer) {
                        const colors = ['#ec4899', '#f43f5e', '#f472b6', '#fb7185', '#fda4af', '#fecdd3'];
                        let html = '';
                        data.academicPosition.forEach((row, i) => {
                            const color = colors[i % colors.length];
                            const pct = total > 0 ? ((row.total / total) * 100).toFixed(1) : 0;
                            html += `
                                <div class="flex items-center justify-between text-sm">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background:${color}"></span>
                                        <span class="text-gray-700 truncate">${row.label || 'ไม่ระบุ'}</span>
                                    </span>
                                    <div class="text-right">
                                        <span class="font-semibold text-gray-900">${row.total}</span>
                                        <span class="text-xs text-gray-400 ml-1">(${pct}%)</span>
                                    </div>
                                </div>`;
                        });
                        legendContainer.innerHTML = html;
                    }
                }

                // 6. Job Position Chart
                if (window.jobPosChart) {
                    window.jobPosChart.data.labels = data.jobPosition.map(r => r.label || 'ไม่ระบุ');
                    window.jobPosChart.data.datasets[0].data = data.jobPosition.map(r => parseInt(r.total));
                    window.jobPosChart.update();
                }

                // 7. Expertise Chart
                if (window.expertiseChart) {
                    window.expertiseChart.data.labels = data.expertise.map(r => r.label || 'ไม่ระบุ');
                    window.expertiseChart.data.datasets[0].data = data.expertise.map(r => parseInt(r.total));
                    window.expertiseChart.update();
                }
            };

            // ===== WORK TAB CHARTS =====

            <?php if (!empty($researchByStatus)): ?>
                new Chart(document.getElementById('researchStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $researchByStatus)) ?>,
                        datasets: [{
                            data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $researchByStatus)) ?>,
                            backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                            borderWidth: 0, hoverOffset: 6,
                        }]
                    },
                    options: { 
                        cutout: '60%', 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        responsive: true 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($academicServiceByStatus)): ?>
                new Chart(document.getElementById('academicServiceStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $academicServiceByStatus)) ?>,
                        datasets: [{
                            data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $academicServiceByStatus)) ?>,
                            backgroundColor: ['#059669', '#34d399', '#6ee7b7', '#a7f3d0', '#ecfdf5'],
                            borderWidth: 0, hoverOffset: 6,
                        }]
                    },
                    options: { 
                        cutout: '60%', 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        responsive: true 
                    }
                });
            <?php endif; ?>

            // Academic Yearly Stats Charts
            <?php if (!empty($academicServiceStats)): ?>
                // Academic Yearly Project Chart
                new Chart(document.getElementById('academicYearlyProjectChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_column($academicServiceStats, 'label')) ?>,
                        datasets: [{
                            label: 'จำนวนโครงการ',
                            data: <?= Json::encode(array_column($academicServiceStats, 'total_projects')) ?>,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });

                // Academic Yearly Budget Chart
                new Chart(document.getElementById('academicYearlyBudgetChart'), {
                    type: 'line',
                    data: {
                        labels: <?= Json::encode(array_column($academicServiceStats, 'label')) ?>,
                        datasets: [{
                            label: 'งบประมาณรวม',
                            data: <?= Json::encode(array_column($academicServiceStats, 'total_budget')) ?>,
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#059669',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return 'งบประมาณ: ' + context.parsed.y.toLocaleString() + ' บาท';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            <?php if (!empty($researchByFunding)): ?>
                new Chart(document.getElementById('researchFundingChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $researchByFunding)) ?>,
                        datasets: [{ label: 'จำนวนโครงการ', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $researchByFunding)) ?>, backgroundColor: '#6366f1', borderRadius: 8 }]
                    },
                    options: { 
                        responsive: true, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            // Research Yearly Stats Charts
            <?php if (!empty($researchStats)): ?>
                // Research Yearly Project Chart
                new Chart(document.getElementById('researchYearlyProjectChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_column($researchStats, 'label')) ?>,
                        datasets: [{
                            label: 'จำนวนโครงการ',
                            data: <?= Json::encode(array_column($researchStats, 'total_projects')) ?>,
                            backgroundColor: '#6366f1', // Indigo color
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });

                // Research Yearly Budget Chart
                new Chart(document.getElementById('researchYearlyBudgetChart'), {
                    type: 'line',
                    data: {
                        labels: <?= Json::encode(array_column($researchStats, 'label')) ?>,
                        datasets: [{
                            label: 'งบประมาณรวม',
                            data: <?= Json::encode(array_column($researchStats, 'total_budget')) ?>,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return 'งบประมาณ: ' + context.parsed.y.toLocaleString() + ' บาท';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            // Innovation Yearly Stats Charts
            <?php if (!empty($innovationStats)): ?>
                // Innovation Yearly Project Chart
                new Chart(document.getElementById('innovationYearlyChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_column($innovationStats, 'label')) ?>,
                        datasets: [{
                            label: 'จำนวนนวัตกรรม',
                            data: <?= Json::encode(array_column($innovationStats, 'total_projects')) ?>,
                            backgroundColor: '#a855f7', // Purple color
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            // Innovation Advisor Chart
            <?php if (!empty($topInnovationAdvisors)): ?>
                new Chart(document.getElementById('innovationAdvisorChart'), {
                    type: 'bar', // Horizontal bar chart is better for long names
                    data: {
                        labels: <?= Json::encode(array_column($topInnovationAdvisors, 'label')) ?>,
                        datasets: [{
                            label: 'ผลงานที่เป็นที่ปรึกษา',
                            data: <?= Json::encode(array_column($topInnovationAdvisors, 'total')) ?>,
                            backgroundColor: ['#c084fc', '#a855f7', '#9333ea', '#7e22ce', '#6b21a8'],
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Horizontal bars
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อนวัตกรรม (อาจารย์ที่ปรึกษา: ' + label + ')', window.dashboardAlpine.getFilteredInnovations({ advisor: label }));
                            }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.x || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
                            y: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            // Personnel 5-Year Retention Chart
            <?php if (!empty($personnelRetentionStats)): ?>
                new Chart(document.getElementById('personnelRetentionChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_keys($personnelRetentionStats)) ?>,
                        datasets: [{
                            label: 'บุคลากรที่คงอยู่ (คน)',
                            data: <?= Json::encode(array_values($personnelRetentionStats)) ?>,
                            backgroundColor: '#10b981', // Emerald
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        let val = context.parsed.y;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return 'จำนวน: ' + val + ' คน (' + pct + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>
            
            // Scholarship 5-Year Retention Chart
            <?php if (!empty($scholarshipRetentionStats)): ?>
                window.scholarshipRetentionChart = new Chart(document.getElementById('scholarshipRetentionChart'), {
                    type: 'line',
                    data: {
                        labels: <?= Json::encode(array_keys($scholarshipRetentionStats)) ?>,
                        datasets: [{
                            label: 'จำนวนนักเรียนทุน (คน)',
                            data: <?= Json::encode(array_values($scholarshipRetentionStats)) ?>,
                            borderColor: '#f59e0b', // Amber
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#f59e0b',
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: function(e, activeEls) {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = e.chart.data.labels[index];
                                window.dashboardAlpine.showDetailModal('รายชื่อนักเรียนทุนประจำปี พ.ศ. ' + label, window.dashboardAlpine.getFilteredScholarships({ year_th: label }));
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        let val = context.parsed.y;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return 'จำนวน: ' + val + ' คน (' + pct + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0, stepSize: 1 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            // ===== MAPS INITIALIZATION =====

            // Research Map
            var researchLocations = <?= Json::encode($researchLocations) ?>;
            if (researchLocations.length > 0) {
                window.researchMap = L.map('researchMap').setView([13.7367, 100.5231], 6); // Default to Thailand center
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(window.researchMap);

                var researchCluster = L.markerClusterGroup();
                researchLocations.forEach(function (loc) {
                    if (loc.latitude && loc.longitude) {
                        var lat = parseFloat(loc.latitude);
                        var lng = parseFloat(loc.longitude);
                        var url = '<?= yii\helpers\Url::to(['research/view']) ?>&id=' + loc.id;
                        var popupContent = '<div class="p-1">' +
                            '<h4 class="font-semibold text-gray-900 mb-2" style="font-family: inherit; margin: 0 0 8px 0;">' + loc.title + '</h4>' +
                            '<a href="' + url + '" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors" style="color: white; text-decoration: none; display: inline-block;">' +
                            'รายละเอียดโครงการ &rarr;' +
                            '</a>' +
                            '</div>';
                        var marker = L.marker([lat, lng])
                            .bindPopup(popupContent);
                        researchCluster.addLayer(marker);
                    }
                });
                window.researchMap.addLayer(researchCluster);
                if (researchCluster.getBounds().isValid()) {
                    window.researchMap.fitBounds(researchCluster.getBounds(), { padding: [50, 50], maxZoom: 8 });
                }
            }

            // Academic Service Map
            var academicLocations = <?= Json::encode($academicServiceLocations) ?>;
            if (academicLocations.length > 0) {
                window.academicServiceMap = L.map('academicServiceMap').setView([13.7367, 100.5231], 6);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(window.academicServiceMap);

                var academicCluster = L.markerClusterGroup();
                academicLocations.forEach(function (loc) {
                    if (loc.latitude && loc.longitude) {
                        var lat = parseFloat(loc.latitude);
                        var lng = parseFloat(loc.longitude);
                        var url = '<?= yii\helpers\Url::to(['academic-service/view']) ?>&id=' + loc.id;
                        var popupContent = '<div class="p-1">' +
                            '<h4 class="font-semibold text-gray-900 mb-2" style="font-family: inherit; margin: 0 0 8px 0;">' + loc.activity_name + '</h4>' +
                            '<a href="' + url + '" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors" style="color: white; text-decoration: none; display: inline-block;">' +
                            'รายละเอียดโครงการ &rarr;' +
                            '</a>' +
                            '</div>';
                        var marker = L.marker([lat, lng])
                            .bindPopup(popupContent);
                        academicCluster.addLayer(marker);
                    }
                });
                window.academicServiceMap.addLayer(academicCluster);
                if (academicCluster.getBounds().isValid()) {
                    window.academicServiceMap.fitBounds(academicCluster.getBounds(), { padding: [50, 50], maxZoom: 8 });
                }
            }

            // ===== BUDGET TAB CHARTS =====
            <?php if (!empty($budgetStats)): ?>
                new Chart(document.getElementById('budgetComparisonChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_column($budgetStats, 'label')) ?>,
                        datasets: [
                            {
                                label: 'งบประมาณสุทธิ',
                                data: <?= Json::encode(array_column($budgetStats, 'total_budget')) ?>,
                                backgroundColor: '#6366f1',
                                borderRadius: 6,
                            },
                            {
                                label: 'เบิกจ่ายจริง',
                                data: <?= Json::encode(array_column($budgetStats, 'total_spent')) ?>,
                                backgroundColor: '#f43f5e',
                                borderRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let val = context.parsed.y;
                                        let otherVal = context.datasetIndex === 0 ? context.chart.data.datasets[1].data[context.dataIndex] : context.chart.data.datasets[0].data[context.dataIndex];
                                        let label = context.dataset.label + ': ' + val.toLocaleString() + ' บาท';
                                        
                                        if (context.datasetIndex === 1) { // เบิกจ่ายจริง
                                            let budget = context.chart.data.datasets[0].data[context.dataIndex];
                                            let pct = budget > 0 ? ((val / budget) * 100).toFixed(2) : 0;
                                            label += ' (' + pct + '% ของงบประมาณ)';
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>
        });
    </script>