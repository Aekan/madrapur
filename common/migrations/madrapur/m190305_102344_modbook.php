<?php

use yii\db\Schema;
use yii\db\Migration;

class m190305_102344_modbook extends Migration {
    public $tableName = "modulusBookings";
    public $tableOptions = null;

    public function safeUp() {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            $this->tableName, [
            'id' => $this->primaryKey(),
            'bookingId' => $this->string(32),
            'productId' => $this->string(32),
            'source' => $this->string(255)
                ->notNull(),
            'data' => $this->text(),
            'invoiceDate' => $this->date()
                ->notNull(),
            'bookingDate' => $this->date()
                ->notNull(),

            ], $this->tableOptions
        );
        $this->insert($this->tableName, [
            'id' => "1",
            'bookingId'=>"15",
            'productId'=>"3",
            'source' => "budapestrivercruise.eu",
            'data' => "{none}",
            'invoiceDate' => "2018-02-05",
            'bookingDate' => "2018-08-20"
        ]);

    }

    public function safeDown() {
        $this->dropTable($this->tableName);
    }
}
