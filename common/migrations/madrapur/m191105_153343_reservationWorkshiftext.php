<?php

use yii\db\Schema;
use yii\db\Migration;

class m191105_153343_reservationWorkshiftext extends Migration {
    public $tableName = "modulusBookings";
    public $columnName = "workshiftId";

    public function safeUp() {
        $this->addColumn(
            $this->tableName,
            $this->columnName,
            $this->bigInteger(13)->defaultValue(null)
        );

    }

    public function safeDown() {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}
