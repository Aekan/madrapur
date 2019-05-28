<?php

use yii\db\Schema;
use yii\db\Migration;

class m190528_091048_modulusAddSellerIdToReservations extends Migration {
    public $tableName = "modulusBookings";
    public $columnName1 = "sellerId";
    public $columnName2 = "sellerName";

    public function safeUp() {
        $this->addColumn(
            $this->tableName,
            $this->columnName1,
            'varchar(255)'
        );
        $this->addColumn(
            $this->tableName,
            $this->columnName2,
            'varchar(255)'
        );


    }

    public function safeDown() {
        $this->dropColumn($this->tableName, $this->columnName1);
        $this->dropColumn($this->tableName, $this->columnName2);
    }
}
