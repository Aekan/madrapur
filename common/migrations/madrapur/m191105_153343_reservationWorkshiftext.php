<?php

use yii\db\Schema;
use yii\db\Migration;

class m191105_153343_reservationWorkshiftext extends Migration {
    public $tableName = "tableName";
    public $tableOptions = "";

    public function safeUp() {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            $this->tableName, [
                'id' => $this->primaryKey(),
                'title' => $this->string(12)->notNull()->unique(),
                'body' => $this->text(),
            ], $this->tableOptions
        );
    }

    public function safeDown() {
        $this->dropTable($this->tableName);
    }
}
