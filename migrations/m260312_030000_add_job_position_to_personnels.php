<?php

use yii\db\Migration;

/**
 * Handles adding job_position to table `{{%personnels}}`.
 */
class m260312_030000_add_job_position_to_personnels extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%personnels}}', 'job_position', $this->string(100)->after('academic_position'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%personnels}}', 'job_position');
    }
}
