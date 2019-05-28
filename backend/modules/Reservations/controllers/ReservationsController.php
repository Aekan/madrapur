<?php
namespace backend\modules\Reservations\controllers;

use backend\controllers\Controller;
use backend\modules\MadActiveRecord\models\MadActiveRecord;
use backend\modules\Product\models\Product;
use backend\modules\Product\models\ProductPrice;
use backend\modules\Product\models\ProductTime;
use backend\modules\Reservations\models\Reservations;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Yii;
use backend\modules\Reservations\models\ReservationsAdminSearchModel;
use backend\modules\Reservations\models\DateImport;

use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * ReservationsController implements the CRUD actions for ReservationsModel.
 */
class ReservationsController extends Controller {


    public function actionAddReservation() {
        $searchModel = new ReservationsAdminSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $chartData=$searchModel->searchChart(Yii::$app->request->queryParams);
        $connection=YII::$app->db;
        $dateImportModel= new DateImport();


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'connection'=>$connection,
            'dateImportModel'=>$dateImportModel

        ]);
    }

    /**
     * Lists all Products models.
     * @return mixed
     */

    public function importDateRange($source,$dateFrom, $dateTo){

        $dateFrom=date('Y-m-d',strtotime($dateFrom));
        $dateTo=date('Y-m-d',strtotime($dateTo));
        $url=$source.'/wp-json/bookings/v1/start/'.$dateFrom.'/end/'.$dateTo;

        $curl=curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response=curl_exec($curl);

        curl_close($curl);

        $jsonResponse=json_decode(utf8_decode($response));





        return $jsonResponse;



    }

    public function actionAdmin() {
        $searchModel = new ReservationsAdminSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $chartDataProvider = $searchModel->searchChart(Yii::$app->request->queryParams);
        $connection=YII::$app->db;
        $dateImportModel= new DateImport();
        $request=Yii::$app->request;
        $dateImport = $request->post('DateImport');
        $response= $this->importDateRange($dateImport['source'],$dateImport['dateFrom'],$dateImport['dateTo']);
        $importResponse='No Import initiated';

        if($response) {
            $updateCounter=0;
            $newRecordCounter=0;

            foreach ($response as $booking) {
                if(isset($booking->orderDetails->paid_date)) {

                    if (!isset($booking->personInfo)) $booking->personInfo = '';
                    if (!isset($booking->orderDetails->paid_date)) $booking->orderDetails->paid_date = '2019-01-01';

                    # if(!isset($booking->orderDetails->paid_date)) $booking->orderDetails->paid_date=;

                    $data = ['boookingDetails' => $booking->bookingDetails, 'orderDetails' => $booking->orderDetails, 'personInfo' => $booking->personInfo, 'updateDate' => date("Y-m-d H:i:s")];

                    $data = json_encode($data);
                    if($booking->bookingDetails->booking_cost>200000 &&  $booking->orderDetails->order_currency=='EUR') continue;

                    $values = [
                        'bookingId' => $booking->bookingId,
                        'productId' => $booking->bookingDetails->booking_product_id,
                        'source' => $dateImport['source'],
                        'invoiceDate' => $booking->orderDetails->paid_date,
                        'bookingDate' => $booking->bookingDetails->booking_start,
                        'data' => $data
                    ];

                    /**
                     * $bookingVerifyComd verifies if booing alreadz exists if so it will be updated, else new record creted
                     * TODO Needs Speed Improvement @palius
                     */
                    $query = Reservations::aSelect(Reservations::class, '*', Reservations::tableName(), 'bookingId=' . $booking->bookingId);

                    try {
                        $rows = $query->one();
                    } catch (Exception $e) {
                    }

                    if (isset($rows)) {
                        $updateCounter += 1;
                        $model = $rows;

                    } else {
                        $model = new Reservations();
                        $newRecordCounter += 1;


                    }

                    if (Reservations::insertOne($model, $values)) {
                        $importResponse = 'Import Completed <br/><strong>' . $updateCounter . ' </strong>updated <br/><strong>' . $newRecordCounter . '</strong> imported ';

                    } else {
                        $importResponse = 'Import failed';
                        //show an error message
                    }

                    #$importResponse=$rows;


                }
            }


        }

        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'chartDataProvider' => $chartDataProvider,
            'connection'=>$connection,
            'dateImportModel'=>$dateImportModel,
            'response'=>$response,
            'importResponse'=>$importResponse

        ]);
    }

    public function actionIndex() {
        $searchModel = new ReservationsAdminSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCreate() {
        $allProduct=Product::getAllProducts();

        $searchModel = new ReservationsAdminSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $product=Yii::$app->request->post('Product');
        $productPrice=Yii::$app->request->post('ProductPrice');
        $totalprice=0;

        $disableForm=0;
        $myprices=[];
        if($product){
            $disableForm=1;
            $query=ProductPrice::aSelect(ProductPrice::class,'*',ProductPrice::tableName(),'product_id='.$product['title']);
            $myprices=$query->all();
            $countPrices=$query->count();
        }
        if(!isset($countPrices)){
            $countPrices=0;

        }
        if($productPrice){


            $newReservarion= new Reservations();

             $data=new \stdClass();
             $data->boookingDetails=new \stdClass();
             $data->orderDetails=new \stdClass();

             $data->personInfo=[];
             $data->updateDate=date('Y-m-d h:m:s');

             $query=ProductPrice::aSelect(ProductPrice::class,'*',ProductPrice::tableName(),'product_id='.$productPrice["product_id"]);
             $myprices=$query->all();
             foreach ($myprices as $i=>$price){
                 if($productPrice['description'][$i]){
                     $newObj=new \stdClass();
                     $newObj->name=$price->name;
                     $newObj->purchaseNumber=$productPrice['description'][$i];
                     $newObj->oneCost=$price->price;
                     $data->personInfo[]=$newObj;


                 }
             }





            $countPersons=0;
             foreach ($productPrice['description'] as $price){
                 if($price){
                     $countPersons+=$price;
                 }
             }
             #echo $countPersons;



            #var_dump($data);
             $data->boookingDetails->booking_cost=$productPrice["discount"];
             $data->boookingDetails->booking_product_id=$productPrice["product_id"];
             $data->boookingDetails->booking_start=$productPrice['booking_date'].' '.$productPrice['time_name'].':00';
             $data->boookingDetails->booking_end=$productPrice['booking_date'].' '.$productPrice['time_name'].':00';
             $data->orderDetails->paid_date=date('Y-m-d');
             $data->orderDetails->allPersons=$countPersons;
             $data->orderDetails->order_currency='EUR';








           # $data=['boookingDetails'=> $booking->bookingDetails,'orderDetails'=>$booking->orderDetails,'personInfo'=>$booking->personInfo,'updateDate'=>date("Y-m-d H:i:s")];

            $data=json_encode($data);

            $imaStreetSeller=Yii::$app->authManager-> getAssignment('streetSeller',Yii::$app->user->getId()) ;

            $source='unset';
            $imaStreetSeller=Yii::$app->authManager-> getAssignment('streetSeller',Yii::$app->user->getId()) ;
            $imaHotelSeller=Yii::$app->authManager-> getAssignment('hotelSeller',Yii::$app->user->getId()) ;

            if($imaStreetSeller){$source='Street';}
            if($imaHotelSeller){$source='Hotel';}


            $values=[
                'invoiceDate'=>date('Y-m-d'),
                'bookingDate'=>$productPrice['booking_date'],
                'source'=>$source,
                'productId'=>$productPrice['product_id'],
                'bookingId'=>'tmpMad1',
                'data'=>$data,
                'sellerId'=>Yii::$app->user->getId(),
                'sellerName'=>Yii::$app->user->identity->username,
            ];
                $insertReservation=Reservations::insertOne($newReservarion, $values);

                if ($insertReservation) {
                    $findBooking=Reservations::aSelect(Reservations::class,'*',Reservations::tableName(),'bookingId="tmpMad1"');
                    $booking=$findBooking->one();
                    $values=['bookingId'=>$booking->id];
                    Reservations::insertOne($booking,$values);


                    $updateResponse = '<span style="color:green">Reservation Successful</span>';
                } else {
                    $updateResponse = '<span style="color:red">Reservation Failed</span>';
                    //show an error message
                }




        }
        if(!isset($updateResponse)){
            $updateResponse='';
        }
        return $this->render('create', [
            'model'=>new Product(),
            'allProduct'=>$allProduct,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'disableForm'=>$disableForm,
            'myPrices'=>$myprices,
            'countPrices'=>$countPrices,
            'newReservation'=>$updateResponse,

        ]);
    }



    public function actionGettimes(){
        if (Yii::$app->request->isAjax) {
        $data=Yii::$app->request->post();
        $id=$data['id'];
        $query=ProductTime::aSelect(ProductTime::class,'*',ProductTime::tableName(),'product_id='.$id);
        $mytimes=$query->all();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
            'search' => $mytimes,
        ];
        }

    }
    public function actionCalcprice(){
        if (Yii::$app->request->isAjax) {
            $data=Yii::$app->request->post();
            $currID=$data['productId'];
            $query=ProductPrice::aSelect(ProductPrice::class,'*',ProductPrice::tableName(),'product_id='.$currID);
            $myprices=$query->all();

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $productsBought=[];
            foreach ($data['prices'] as $priceId=>$price){
                if($price){
                    $productsBought[$priceId]=$price;
                }
            }


            $fullTotal=0;

            foreach ($productsBought as $priceId=>$priceAmount){

                foreach ($myprices as $remotePrice){
                   if($remotePrice->id==$priceId){
                       $currentPrice=(int)$remotePrice->price;
                       $fullTotal=$fullTotal+($currentPrice*$priceAmount);
                   }
                }
            }










            return [
                'search' => $fullTotal,
            ];
        }

    }
    public function actionGetprices(){
        if (Yii::$app->request->isAjax) {
            $data=Yii::$app->request->post();
            $id=$data['id'];
            $query=ProductPrice::aSelect(ProductPrice::class,'*',ProductPrice::tableName(),'product_id='.$id);
            $myprices=$query->all();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial('prices');
        }

    }
    public function actionBookingedit(){

        $model = new DateImport();
        $request=Yii::$app->request;
        $id=$request->get('id');
        $query = Reservations::aSelect(Reservations::class, '*', Reservations::tableName(), 'id=' . $id);

        try {
            $bookingInfo = $query->one();
        } catch (Exception $e) {
        }

        $backendData=$bookingInfo;
        return $this->render('bookingEdit',['model'=>$model,'backenddata'=>$backendData]);


    }
    public function actionMyreservations(){


        $searchModel=new ReservationsAdminSearchModel();
        $dataProvider = $searchModel->searchMyreservations(Yii::$app->request->queryParams);
        $dataProvider->setSort([

            'defaultOrder' => [
                'id' => SORT_DESC
            ]
        ]);







        return $this->render('myreservations',['dataProvider'=>$dataProvider,'searchModel'=>$searchModel]);
    }

    public function actionAllreservations(){
            $searchModel=new ReservationsAdminSearchModel();
            $dataProvider = $searchModel->searchMyreservations(Yii::$app->request->queryParams);




            return $this->render('myreservations',['dataProvider'=>$dataProvider,'searchModel'=>$searchModel]);
        }

}