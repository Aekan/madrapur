<?php

namespace backend\modules\Product\models;

use backend\modules\MadActiveRecord\models\MadActiveRecord;
use Psr\Log\NullLogger;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Default model for the `Product` module
 */
class ProductReservation extends Product{


 

//TODO
    public function rules() {
        return [
            [['id'], 'integer'],
            [['currency'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 20000],
            [['short_description'], 'string', 'max' => 5000],
            [['thumbnail'], 'string', 'max' => 255],
            [['images'], 'string', 'max' => 255],
            [['category'], 'string', 'max' => 255],
            [['start_date'], 'string', 'max' => 255],
            [['end_date'], 'string', 'max' => 255],
            [['capacity'], 'string', 'max' => 255],
            [['duration'], 'string', 'max' => 255],
             [['slug'], 'string', 'max' => 255],
        ];
    }
//TODO
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'currency' => Yii::t('app', 'Currency'),
            'randomDate' => Yii::t('app', 'Véletlenszerű dátum'),
        ];
    }
    public function attributes() {
        $attributes = parent::attributes();
        return array_merge($attributes, [
            'times','prices'
        ]);
    }



    public function getPricesDetails() {
        return $this->prices;
    }
    public function setPricesDetails($orderDetails) {
        $this->prices = $orderDetails;
    }
    public function getTimesDetails() {
        return $this->times;
    }
    public function setTimesDetails($orderDetails) {
        $this->times = $orderDetails;
    }

    public function getProdUrl() {
        return $this->times;
    }
    public function setProdUrl($orderDetails) {
        $this->times = $orderDetails;
    }



    public static function getProdById($id){

//TODO get product from ID

        $query = Product::aSelect(Product::class, '*', Product::tableName(), 'id=' . $id);
        $queryTimes = Product::aSelect(ProductTime::class, '*', ProductTime::tableName(), '1');
        $queryPrices = Product::aSelect(ProductPrice::class, '*', ProductPrice::tableName(), '1');
        $allTimes=$queryTimes->all();
        $allPrices=$queryPrices->all();


        $prodInfo=new Product();
        try {
            $prodInfo = $query->one();


            $thisProdTimes=[];
            foreach ($allTimes as $time){
                 if($time->product_id==$id){

                     $thisProdTimes[]=$time;

                 }
            }
            $prodInfo->setAttribute("times", $thisProdTimes);
            $thisProdPrice=[];
            foreach ($allPrices as $price){
                if($price->product_id==$id){
                    $thisProdPrice[]=$price;
                    }
            }
            $prodInfo->setAttribute("prices", $thisProdPrice);



        } catch (Exception $e) {
        }

        return $prodInfo;

    }

    public static function getAllProducts() {
        $query = Product::aSelect(Product::class, '*', Product::tableName(), '1');


        $prodInfo = 0;


        try {
            $prodInfo = $query->all();
            foreach ($prodInfo as $i => $prod){
                $prodInfo[$i]=Product::getProdById($prod->id);

            }

        } catch (Exception $e) {

        }

        return $prodInfo;

    }

    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }


    public static function searchSourceName($sourceID,$sourceUrl){
        /**
         * Returns source name
         */
        $what = ['*'];
        $from = ProductSource::tableName();
        $wheres=[];
            $wheres[]=['prodIds', '=', $sourceID];
        $wheres[]=['url', '=',Html::encode($sourceUrl) ];

        $where = self::andWhereFilter($wheres);


        $rows = self::aSelect(ProductSource::class, $what, $from, $where);

        $myreturned=$rows->one();
        if(isset($myreturned->name)){

            return $myreturned->name;
        }
        else {
            return null;
        }



    }

}
