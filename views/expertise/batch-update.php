<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'จัดการความเชี่ยวชาญบุคลากร';
?>

<div x-data="expertiseBatch()" class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-gray-900">🧬
            <?= Html::encode($this->title) ?>
        </h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div x-show="loading" class="p-20 text-center text-gray-400">
            ⏳ กำลังโหลดข้อมูล...
        </div>

        <form x-show="!loading" method="post">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

            <div class="overflow-x-auto min-h-[500px]">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-emerald-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">บุคลากร</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">สาขา</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">ความเชี่ยวชาญ
                                (Master Data)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="p in searchResults" :key="p.id">
                            <tr
                                class="hover:bg-emerald-50 transition-colors border-l-4 border-transparent hover:border-emerald-500">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900" x-text="p.fullname"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-medium px-2 py-1 bg-gray-100 rounded text-gray-600"
                                        x-text="p.department_name"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        <template x-for="(name, id) in expertiseList" :key="id">
                                            <label
                                                class="flex items-center space-x-2 text-xs text-gray-600 bg-white border border-gray-100 p-1.5 rounded cursor-pointer hover:border-emerald-300">
                                                <input type="checkbox" :name="'PersonnelExpertise[' + p.id + '][]'"
                                                    :value="id" :checked="p.selected_expertises.includes(id)"
                                                    class="rounded text-emerald-600 focus:ring-emerald-500">
                                                <span x-text="name"></span>
                                            </label>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-6 bg-gray-50 border-t border-gray-200 flex justify-between items-center sticky bottom-0">
                <div class="flex items-center space-x-4">
                    <input type="text" x-model="search" placeholder="🔍 ค้นหาชื่อบุคลากร..."
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:ring-emerald-500 focus:border-emerald-500">
                    <span class="text-sm text-gray-500 italic">แสดง <span x-text="searchResults.length"></span>
                        ลำดับ</span>
                </div>
                <div class="flex space-x-3">
                    <?= Html::a('ยกเลิก', ['/personnel/index'], ['class' => 'px-6 py-2.5 text-gray-500 font-medium hover:text-gray-700 transition']) ?>
                    <button type="submit"
                        class="px-10 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-xl hover:shadow-emerald-200 transition transform active:scale-95">
                        💾 บันทึกความเชี่ยวชาญทั้งหมด
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function expertiseBatch() {
        return {
            personnel: [],
            expertiseList: {},
            loading: true,
            search: '',
            get searchResults() {
                if (!this.search) return this.personnel;
                return this.personnel.filter(p => p.fullname.toLowerCase().includes(this.search.toLowerCase()));
            },
            async init() {
                try {
                    const res = await fetch('<?= Url::to(['ajax-get-personnel']) ?>');
                    const data = await res.json();
                    this.personnel = data.personnel;
                    this.expertiseList = data.expertiseList;
                } catch (e) { console.error(e); }
                this.loading = false;
            }
        }
    }
</script>