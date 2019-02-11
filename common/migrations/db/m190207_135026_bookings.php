<?php

use yii\db\Migration;

/**
 * Class m190207_135026_bookings
 */
class m190207_135026_bookings extends Migration {
    private $tableName = "mad_qrbase";

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
                'origId' => $this->string(255),

                'booking_cost' => $this->integer(),
                'booking_currency' => $this->string(255),
                'booking_persons' => $this->string(255),
                'booking_product_id' => $this->integer(),
                'booking_start' => $this->date(),
                'booking_end' => $this->date(),

                'billing_first_name' => $this->string(255),
                'billing_last_name' => $this->string(255),
                'billing_email' => $this->string(255),
                'billing_phone' => $this->string(255),
                'billing_date' => $this->date(),
                'billing_ip_address' => $this->string(255),

                'order_persons' => $this->integer(),

                'ticket_name' => '',
                'ticket_purchaseNumber',
                'ticket_oneCost'
            ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable($this->tableName);
    }
}
