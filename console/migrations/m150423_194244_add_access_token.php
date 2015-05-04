<?php

use yii\db\Schema;
use yii\db\Migration;

class m150423_194244_add_access_token extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE general.user
            ADD COLUMN access_token text");
    }
    
    public function safeDown()
    {
        $this->execute("ALTER TABLE general.user
            DROP COLUMN access_token");
    }
}
