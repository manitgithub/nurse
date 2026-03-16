<?php

use yii\db\Migration;

/**
 * Handles adding academic_position to table `{{%personnels}}`.
 */
class m260312_023500_add_academic_position_to_personnels extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%personnels}}', 'academic_position', $this->string(100)->after('fullname'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%personnels}}', 'academic_position');
    }
}
