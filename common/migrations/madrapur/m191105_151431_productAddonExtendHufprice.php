<?php

use yii\db\Schema;
use yii\db\Migration;

class m191105_151431_productAddonExtendHufprice extends Migration {
    public $tableName = "modulusProductAddons";
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
