<?php

namespace backend\modules\Reservations\models;

use backend\modules\MadActiveRecord\models\MadActiveRecord;

class DateImport extends MadActiveRecord {
    public $dateFrom;
    public $dateTo;
    public $source;

    public static function tableName() {
        return 'modulusBookings'; //TODO: dynamic table name
    }

    public function rules() {
        return [
            // define validation rules here
        ];
    }
}
