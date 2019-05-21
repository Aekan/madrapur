<?php

namespace backend\modules\Reservations\models;

use backend\modules\MadActiveRecord\models\MadActiveRecord;
use backend\modules\Product\models\Product;
use Yii;
use yii\db\ActiveRecord;

class Reservations extends MadActiveRecord
{

    public static function tableName()
    {
        return 'modulusBookings'; //TODO: dynamic table name
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['bookingId'], 'integer'],
            [['source'], 'string', 'max' => 255],
            [['data'], 'string', 'max' => 1000],
            [['productId'], 'string', 'max' => 32],
            [['invoiceDate'], 'date', 'format' => 'yyyy-MM-dd'],
            [['bookingDate'], 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }

    public function getFields() {
        return [
            'id',
            'bookingId',
            'source',
            'data',
            'productId',
            'invoiceDate',
            'bookingDate',
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bookingId' => Yii::t('app', 'BookingID'),
            'source' => Yii::t('app', 'Source'),
            'data' => Yii::t('app', 'JSON adat'),
            'productId' => Yii::t('app', 'Product Id'),
            'invoiceDate' => Yii::t('app', 'Invoice  date'),
            'bookingDate' => Yii::t('app', 'Booking date'),
        ];
    }
    public static function primaryKey()
    {
        return ["id"];
    }

    public function attributes() {
        $attributes = parent::attributes();
        return array_merge($attributes, [
            'firstName','lastName','bookedChairsCount','bookingCost','orderCurrency','sourceName'
        ]);
    }

    public function afterFind() {
        parent::afterFind();

        $myjson=json_decode($this->data);

        if($myjson && isset($myjson->orderDetails)){
            if(isset($myjson->orderDetails->edited_first_name)){
                $this->setAttribute("firstName",$myjson->orderDetails->edited_first_name);
            }else{

                }
            if(isset($myjson->orderDetails->edited_last_name)){
                $this->setAttribute("lastName",$myjson->orderDetails->edited_last_name);
            }else{

            }
            if(isset($myjson->orderDetails->edited_AllPersons)){
                $this->setAttribute("bookedChairsCount",$myjson->orderDetails->edited_AllPersons);
            }else{

            }
            if(isset($myjson->boookingDetails->edited_booking_cost)){
                $this->setAttribute("bookingCost",$myjson->boookingDetails->edited_booking_cost);
            }else{
                if(isset($myjson->boookingDetails)){$this->setAttribute("bookingCost",$myjson->boookingDetails->booking_cost);
                }

            }
            if(isset($myjson->orderDetails->edited_order_currency)){
                $this->setAttribute("orderCurrency",$myjson->orderDetails->edited_order_currency);
            }else{

            }
            if(isset($myjson->orderDetails->edited_source_name)){
                $this->setAttribute("sourceName",$myjson->orderDetails->edited_source_name);
            }else{
                if(isset($myjson->boookingDetails->booking_product_id)){
                    $mySourceName=Product::searchSourceName($myjson->boookingDetails->booking_product_id,$this->source);
                 //   Yii::error('Mysourcename'.$mySourceName);
                    if($mySourceName!=null){

                        $this->setAttribute("sourceName",$mySourceName);
                    }
                    else{
                       // Yii::error('Mysourcename'.$mySourceName);
                        $this->setAttribute("sourceName",$this->source);
                    }
                }


            }

        } else if (is_array($myjson)){
            foreach ($myjson as $index => $json) {

            }
        }


    }


    public function beforeSave($insert) {

        /*Yii::$app->log($insert); Turn on for debug*/
        if (is_string($this->data)) {
            $myjson=json_decode($this->data);
            $editableAttribute=Yii::$app->request->post('editableAttribute');

            switch ($editableAttribute){
                case 'firstName':
                    $mystuff=Yii::$app->request->post('ReservationsAdminSearchModel');
                    $myjson->orderDetails->edited_first_name=$mystuff[0]['firstName'];
                    break;
                case 'lastName':
                    $mystuff=Yii::$app->request->post('ReservationsAdminSearchModel');
                    $myjson->orderDetails->edited_last_name=$mystuff[0]['lastName'];
                    break;
                case 'bookedChairsCount':

                    $mystuff=Yii::$app->request->post('ReservationsAdminSearchModel');
                    $myjson->orderDetails->edited_AllPersons=$mystuff[0]['bookedChairsCount'];
                    break;
                case 'bookingCost':

                    $mystuff=Yii::$app->request->post('ReservationsAdminSearchModel');
                    $myjson->boookingDetails->edited_booking_cost=$mystuff[0]['bookingCost'];
                    break;
                case 'orderCurrency':
                    $mystuff=Yii::$app->request->post('ReservationsAdminSearchModel');
                    $myjson->orderDetails->edited_order_currency=$mystuff[0]['order_currency'];
                    break;
                case 'sourceName':

                    $mystuff=Yii::$app->request->post('ReservationsAdminSearchModel');
                    $myjson->orderDetails->edited_source_name=$mystuff[0]['sourceName'];
                    break;

                #Yii::warning('My posted Searchmodel'.\GuzzleHttp\json_encode($mystuff)->firstName);
            }



            $this->data=json_encode($myjson);
            #var_dump($this->data);

            unset($this->firstName);
            unset($this->sourceName);
            unset($this->bookingCost);
            unset($this->orderCurrency);
            unset($this->lastName);
            unset($this->bookedChairsCount);
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function getData(){
        return $this->data;

    }

    public static function getList()
    {
        return ArrayHelper::map(self::find()->all(), 'bookingId', 'bookingDate');
    }

    public static function getReservationById($id) {
        $reservation = Reservations::aSelect(Reservations::class, '*', Reservations::tableName(), 'id=' . $id);

        return $reservation->one();
    }

    public static function getReservationsByIds($ids) {
        $reservation = Reservations::aSelect(Reservations::class, '*', Reservations::tableName(), 'id  IN (' . implode(",",$ids) . ')');

        return $reservation->all();
    }

}