<?php

use yii\db\Migration;

/**
 * Class m190211_111338_eTickets
 */
class m190211_111338_eTickets extends Migration {
    private $tableName = "eTickets";

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName,
            [
                'id' => $this->primaryKey(),
                'barcodeId' => $this->string(8)
                    ->notNull()
                    ->unique(),
                'salesDate' => $this->date()
                    ->notNull(),
                'departureDate' => $this->date()
                    ->notNull(),
                'departureTime' => $this->date()
                    ->notNull(),
                'eventCode' => $this->string(3)
                    ->notNull(),
                'paid' => $this->date()
                    ->notNull(),
                'persons' => $this->string(3)
                    ->notNull(),
                'totalPrice' => $this->string(12)
                    ->notNull(),
                'currency' => $this->string(12)
                    ->notNull(),
                'buyerName' => $this->string(12)
                    ->notNull(),
            ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable($this->tableName);
    }
}
