<?php

namespace backend\modules\Tickets\models;

use backend\modules\MadActiveRecord\models\MadActiveRecord;
use Yii;
use yii\db\ActiveRecord;

/**
 * Default model for the `Tickets` module
 */
class Tickets extends MadActiveRecord {

    public const ACCESS_TICKETS_ADMIN = 'accessTicketsAdmin';
    public const ADD_TICKET_BLOCK = 'addTicketBlock';
    public const ASSIGN_TICKET_BLOCK = 'assignTicketBlock';
    public const VIEW_TICKET_BLOCKS = 'viewTicketBlockS';
    public const VIEW_OWN_TICKET_BLOCKS = 'viewOwnTicketBlockS';

    public static function tableName() {
        return 'modulus_ticket_blocks';
    }

    public function rules() {
        return [
            [['id'], 'integer'],
            [['startId'], 'string'],
            [['assignedBy'], 'string'],
            [['assignedTo'], 'string'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'startId' => Yii::t('app', ''),
            'assignedBy' => Yii::t('app', ''),
            'assignedTo' => Yii::t('app', ''),
        ];
    }
}