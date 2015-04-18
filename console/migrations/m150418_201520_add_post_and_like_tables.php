<?php

use yii\db\Schema;
use yii\db\Migration;

class m150418_201520_add_post_and_like_tables extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE general.post (
            id serial PRIMARY KEY,
            title text NOT NULL,
            body text NOT NULL,
            created_at timestamp without time zone NOT NULL DEFAULT NOW(),
            updated_at timestamp without time zone NOT NULL DEFAULT NOW(),
            user_id integer NOT NULL REFERENCES general.user(id)
        )');

        $this->execute('CREATE TABLE general.user_likes_post (
            user_id integer NOT NULL REFERENCES general.user(id),
            post_id integer NOT NULL REFERENCES general.post(id),
            created_at timestamp without time zone NOT NULL DEFAULT NOW(),
            PRIMARY KEY (user_id, post_id)
        )');
    }
    
    public function safeDown()
    {
        $this->execute('DROP TABLE general.user_likes_post');
        $this->execute('DROP TABLE general.post');
    }
}
