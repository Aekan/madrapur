<?php

use yii\db\Schema;
use yii\db\Migration;

class m191105_135537_productPrice extends Migration {
    public $tableName = "modulusProductPrice";
    public $columnName = "hufPrice";

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
