<?php

use yii\db\Migration;

class m260310_050001_add_track_to_personnels extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%personnels}}', 'track', $this->string(20)->after('gender')->comment('สาย เช่น สาย ป, สาย ว'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%personnels}}', 'track');
    }
}
