<?php

namespace backend\modules\Product\models;

use backend\modules\MadActiveRecord\models\MadActiveRecord;
use Yii;
use yii\db\ActiveRecord;

/**
 * Default model for the `Product` module
 */
class ProductPrice extends MadActiveRecord{

    public static function tableName()
    {
        return 'modulusProductPrice';
    }

    public function rules()
    {
        return [

            [['name'], 'required'],
            [['product_id','id'], 'integer'],
            [['name','discount'], 'string', 'max' => 5000],
            [['description'], 'string', 'max' => 50000],
            [['start_date', 'end_date'], 'date','format' => 'yyyy-m-d'],


        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'name' => Yii::t('app', 'Name'),
            'price' => Yii::t('app', 'Price'),
            'discount' => Yii::t('app', 'Discount'),
            'description' => Yii::t('app', ''),
        ];
    }

    public function init() {
        parent::init();



        return true;
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function afterFind() {
        parent::afterFind();
        return true;
    }

}
