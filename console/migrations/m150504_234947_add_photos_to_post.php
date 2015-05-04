<?php

use yii\db\Schema;
use yii\db\Migration;

class m150504_234947_add_photos_to_post extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->execute("ALTER TABLE general.post
            ADD COLUMN photo_original text,
            ADD COLUMN photo_160 text,
            ADD COLUMN photo_320 text,
            ADD COLUMN photo_640 text");
    }
    
    public function safeDown()
    {
        $this->execute("ALTER TABLE general.post
            DROP COLUMN photo_original,
            DROP COLUMN photo_160,
            DROP COLUMN photo_320,
            DROP COLUMN photo_640");
    }
}
