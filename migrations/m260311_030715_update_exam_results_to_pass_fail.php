<?php

use yii\db\Migration;

class m260311_030715_update_exam_results_to_pass_fail extends Migration
{
    public function safeUp()
    {
        // Change columns to CHAR(1) for 'P' (Pass) or 'F' (Fail)
        for ($i = 1; $i <= 8; $i++) {
            $this->alterColumn('{{%exam_results}}', "subject_{$i}_score", $this->char(1)->defaultValue(null)->comment('P=Pass, F=Fail'));
        }

        // Drop additional columns
        $this->dropColumn('{{%exam_results}}', 'subject_9_score');
        $this->dropColumn('{{%exam_results}}', 'subject_10_score');
    }

    public function safeDown()
    {
        // Re-add dropped columns
        $this->addColumn('{{%exam_results}}', 'subject_9_score', $this->decimal(5, 2));
        $this->addColumn('{{%exam_results}}', 'subject_10_score', $this->decimal(5, 2));

        // Revert columns back to decimal
        for ($i = 1; $i <= 8; $i++) {
            $this->alterColumn('{{%exam_results}}', "subject_{$i}_score", $this->decimal(5, 2));
        }
    }
}
