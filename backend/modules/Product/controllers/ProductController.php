<?php

namespace backend\modules\Product\controllers;


use backend\modules\Product\models\Product;
use backend\modules\Product\models\ProductBlockout;
use backend\modules\Product\models\ProductEdit;

use backend\modules\Product\models\ProductOverview;
use backend\modules\Product\models\ProductPrice;

use backend\modules\Product\models\ProductSource;

use backend\modules\Products\controllers\BlockoutsController;
use backend\modules\Reservations\models\Reservations;
use backend\modules\Reservations\models\ReservationsAdminInfoSearchModel;
use backend\modules\Reservations\models\ReservationsAdminSearchModel;
use backend\modules\Reservations\models\ReservationsInfo;
use kartik\grid\EditableColumnAction;
use League\Uri\PublicSuffix\CurlHttpClient;
use Mpdf\Tag\B;
use Yii;
use backend\controllers\Controller;
use backend\modules\Product\models\ProductUpdate;
use backend\modules\Product\models\ProductTime;

use backend\modules\Product\models\ProductAdminSearchModel;
use himiklab\jqgrid\actions\JqGridActiveAction;


use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Controller for the `Product` module
 */
class ProductController extends Controller {
    /**
     * Renders the admin view for the module
     * @return string
     */
    public function actionAdmin() {
        $searchModel = new ProductAdminSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('admin',['dataProvider'=>$dataProvider,'searchModel'=>$searchModel]);

    }
    public function actionCreate() {
        $model= new ProductUpdate();
        $request=YII::$app->request;

        $productUpdate = $request->post('ProductUpdate');
        $values=[
            'currency'=>$productUpdate['currency'],
            'status'=>$productUpdate['status'],
            'title'=>$productUpdate['title'],

            'short_description'=>$productUpdate['short_description'],
            'desctiption'=>$productUpdate['description'],
            'category'=>$productUpdate['category'],
            'capacity'=>$productUpdate['capacity'],
            'duration'=>$productUpdate['duration'],
            'image'=>$productUpdate['image'],
            'start_date'=>$productUpdate['start_date'],
            'end_date'=>$productUpdate['end_date'],
            ];
        $updateResponse='';
        if($productUpdate) {
            $newProduct=new Product();

            if (Product::insertOne($newProduct, $values)) {

                $updateResponse = '<span style="color:green">Product Update Successful</span>';

            } else {
                $updateResponse = '<span style="color:red">Product Update Failed</span>';

                //show an error message
            }
        }
        return $this->render('create',['model'=>$model,'updateResponse'=>$updateResponse]);
    }

    /**
     * @return string
     */


    function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function actionUpdate()
    {

        $model = new ProductEdit();
        $request = Yii::$app->request;
        $prodId = $request->get('prodId');

        $query = Product::aSelect(Product::class, '*', Product::tableName(), 'id=' . $prodId);

        try {
            $prodInfo = $query->one();
        } catch (Exception $e) {
        }
        $backendData = $prodInfo;

        //here I update my model to contain info from the DB to populate the FORM but it's important that you use a Model like Product at the selection so you don't redeclare stuff
        $model = $backendData;


        $request = YII::$app->request;

        $productEdit = $request->post('Product');

        $updateResponse = 'Empty Response';

        if ($productEdit) {

            $values = [
                'currency' => $productEdit['currency'],
                'status' => $productEdit['status'],
                'title' => $productEdit['title'],
                'short_description' => $productEdit['short_description'],
                'description' => $productEdit['description'],
                'category' => $productEdit['category'],
                'capacity' => $productEdit['capacity'],
                'duration' => $productEdit['duration'],
                'images' => $productEdit['images'],
                'start_date' => $productEdit['start_date'],
                'end_date' => $productEdit['end_date'],
                'slug'=>$productEdit['slug'],
            ];


            $query = Product::aSelect(Product::class, '*', Product::tableName(), 'id=' . $prodId);

            try {
                $rows = $query->one();
            } catch (Exception $e) {
            }
            if (isset($rows)) {
                $newProduct = $rows;
                //letezao productot updatelunk
            } else {
                $newProduct = new Product();

            }


            if (Product::insertOne($newProduct, $values)) {
                $updateResponse = 1;

            } else {
                $updateResponse = 0;

                //show an error message
            }
        }
        if ($updateResponse == 1) {
            $query = Product::aSelect(Product::class, '*', Product::tableName(), 'id=' . $prodId);
            try {
                $prodInfo = $query->one();

                $model = $prodInfo;
            } catch (Exception $e) {
            }


        }


        /*******************Times Rész /TODO bring this to manly form*********************/


        $request = YII::$app->request;
        $productPostedTimes = $request->post('ProductTime');
        $modelTimes[] = new ProductTime();


        //$deletedtimesIDs = array_diff($rowsArray, ));
        //  var_dump($deletedtimesIDs);


        if ($productPostedTimes) {
            $queryGetTimes = ProductTime::aSelect(ProductTime::class, '*', ProductTime::tableName(), 'product_id=' . $prodId);
            try {
                $rowsAll = $queryGetTimes->all();
            } catch (Exception $e) {
            }

            $rowsArray = ArrayHelper::toArray($rowsAll);
            $a = array_filter(ArrayHelper::map($rowsArray, 'id', 'id'));
            $b = array_filter(ArrayHelper::map($productPostedTimes, 'id', 'id'));


            $deletedTimesIds = array_diff($a, $b);
            if (!empty($deletedTimesIds)) {
                ProductTime::deleteAll(['id' => $deletedTimesIds]);
            }
            foreach ($productPostedTimes as $postedTime):


                if ($postedTime['start_date'] == 'NULL' || $postedTime['start_date'] == '') {
                    $postedTime['start_date'] = date("Y-m-d");
                }
                if ($postedTime['end_date'] == 'NULL' || $postedTime['end_date'] == '') {
                    $postedTime['end_date'] = date("Y-m-d");
                }
                $values = [
                    'name' => $postedTime['name'],
                    'start_date' => $postedTime['start_date'],
                    'end_date' => $postedTime['end_date'],
                    'product_id' => $prodId,
                    'id' => $postedTime['id']
                ];


                $query = ProductTime::aSelect(ProductTime::class, '*', ProductTime::tableName(), 'product_id=' . $prodId . ' and id="' . $values['id'] . '"');

                try {
                    $rows = $query->one();

                } catch (Exception $e) {
                }
                if (isset($rows)) {
                    $newTimes = $rows;
                    //letezao productot updatelunk
                } else {
                    $newTimes = new ProductTime();


                }


                if (Product::insertOne($newTimes, $values)) {
                    $updateResponse = 1;

                } else {
                    $updateResponse = 0;

                    //show an error message
                }


            endforeach;


        }

        $queryGetTimes = ProductTime::aSelect(ProductTime::class, '*', ProductTime::tableName(), 'product_id=' . $prodId);
        try {
            $rowsAll = $queryGetTimes->all();
        } catch (Exception $e) {
        }

        if (isset($rowsAll)) {

            if (!$productPostedTimes && $productEdit) {
                if (isset($rowsAll[0])) {
                    $deletedTimesIds[$rowsAll[0]['id']] = $rowsAll[0]['id'];
                    var_dump($deletedTimesIds);
                    $resultofdeletingTimes = ProductTime::deleteAll(['id' => $deletedTimesIds]);
                    var_dump($resultofdeletingTimes);

                    $rowsAll = $queryGetTimes->all();
                }
            }
            $modelTimes = $rowsAll;


        } else {
            $modelTimes[] = new ProductTime();
            $modelTimes = Product::createMultiple(ProductTime::className(), $modelTimes);
            $modelTimes[0] = new ProductTime();
            $modelTimes[0]->start_date = date("Y-m-d");
        }

        /*******************Prices Rész /TODO bring this to manly form*********************/


        $request = YII::$app->request;
        $productPostedPrices = $request->post('ProductPrice');
        $modelPrices[] = new ProductPrice();


        if ($productPostedPrices) {
            $queryGetPrices = ProductPrice::aSelect(ProductPrice::class, '*', ProductPrice::tableName(), 'product_id=' . $prodId);
            try {
                $rowsAll = $queryGetPrices->all();
            } catch (Exception $e) {
            }

            $rowsArray = ArrayHelper::toArray($rowsAll);
            $a = array_filter(ArrayHelper::map($rowsArray, 'id', 'id'));
            $b = array_filter(ArrayHelper::map($productPostedPrices, 'id', 'id'));


            $deletedPricesIds = array_diff($a, $b);


            $result = ProductPrice::deleteAll(['id' => $deletedPricesIds]);

            foreach ($productPostedPrices as $postedPrice):
                if ($postedPrice['start_date'] == 'NULL' || $postedPrice['start_date'] == '') {
                    $postedPrice['start_date'] = date("Y-m-d");
                }
                if ($postedPrice['end_date'] == 'NULL' || $postedPrice['end_date'] == '') {
                    $postedPrice['end_date'] = date("Y-m-d");
                }


                $values = [
                    'name' => $postedPrice['name'],
                    'description' => $postedPrice['description'],
                    'start_date' => $postedPrice['start_date'],
                    'end_date' => $postedPrice['end_date'],
                    'discount' => $postedPrice['discount'],
                    'price' => $postedPrice['price'],
                    'product_id' => $prodId,
                    'id' => $postedPrice['id']
                ];


                $query = ProductPrice::aSelect(ProductPrice::class, '*', ProductPrice::tableName(), 'product_id=' . $prodId . ' and id="' . $values['id'] . '"');

                try {
                    $rows = $query->one();

                } catch (Exception $e) {
                }
                if (isset($rows)) {
                    $newPrice = $rows;
                    //letezao productot updatelunk
                } else {
                    $newPrice = new ProductPrice();


                }


                if (Product::insertOne($newPrice, $values)) {
                    $updateResponse = 1;

                } else {
                    $updateResponse = 0;

                    //show an error message
                }


            endforeach;


        }

        $queryGetPrices = ProductPrice::aSelect(ProductPrice::class, '*', ProductPrice::tableName(), 'product_id=' . $prodId);
        try {
            $rowsAll = $queryGetPrices->all();
        } catch (Exception $e) {
        }

        if (isset($rowsAll)) {


            if (!$productPostedPrices && $productEdit) {
                if (isset($rowsAll[0])) {
                    $deletedPricesIds[$rowsAll[0]['id']] = $rowsAll[0]['id'];
                    var_dump($deletedPricesIds);
                    $resultofdeletingPrices = ProductPrice::deleteAll(['id' => $deletedPricesIds]);
                    var_dump($resultofdeletingPrices);

                    $rowsAll = $queryGetPrices->all();
                }
            }
            $modelPrices = $rowsAll;
        } else {
            $modelPrices[] = new ProductPrice();
            $modelPrices = Product::createMultiple(ProductPrice::class, $modelTimes);
            $modelPrices[0] = new ProductPrice();
            $modelPrices[0]->name = 'asd';

        }


        /*******************Source Rész /TODO bring this to manly form*********************/


        $request = YII::$app->request;
        $productPostedSources = $request->post('ProductSource');
        $modelSources[] = new ProductSource();


        if ($productPostedSources) {
            $queryGetSources = ProductSource::aSelect(ProductSource::class, '*', ProductSource::tableName(), 'product_id=' . $prodId);
            try {
                $rowsAll = $queryGetSources->all();
            } catch (Exception $e) {
            }

            $rowsArray = ArrayHelper::toArray($rowsAll);
            $a = array_filter(ArrayHelper::map($rowsArray, 'id', 'id'));
            $b = array_filter(ArrayHelper::map($productPostedSources, 'id', 'id'));


            $deletedSourcesIds = array_diff($a, $b);


            $result = ProductSource::deleteAll(['id' => $deletedSourcesIds]);

            foreach ($productPostedSources as $postedSources):


                $values = [
                    'name' => $postedSources['name'],
                    'url' => $postedSources['url'],
                    'prodIds' => $postedSources['prodIds'],
                    'product_id' => $prodId,
                    'id' => $postedSources['id'],
                    'color'=>$postedSources['color']

                ];


                $query = ProductSource::aSelect(ProductSource::class, '*', ProductSource::tableName(), 'product_id=' . $prodId . ' and id="' . $values['id'] . '"');

                try {
                    $sourceRows = $query->one();

                } catch (Exception $e) {
                }
                if (isset($sourceRows)) {
                    $newSources = $sourceRows;
                    //letezao productot updatelunk
                } else {
                    $newSources = new ProductSource();


                }


                if (Product::insertOne($newSources, $values)) {
                    $updateResponse = 1;

                } else {
                    $updateResponse = 0;

                    //show an error message
                }


            endforeach;


        }


        $queryGetSources = ProductSource::aSelect(ProductSource::class, '*', ProductSource::tableName(), 'product_id=' . $prodId);
        try {
            $sourceRows = $queryGetSources->all();
        } catch (Exception $e) {
        }



        if (isset($sourceRows)) {


            if (!$productPostedSources && $productEdit) {
                if (isset($sourceRows[0])) {
                    $deletedSourcesIds[$sourceRows[0]['id']] = $sourceRows[0]['id'];
                    var_dump($deletedSourcesIds);
                    $resultofdeletingSources = ProductSource::deleteAll(['id' => $deletedSourcesIds]);
                    var_dump($resultofdeletingSources);

                    $sourceRows = $queryGetSources->all();
                }
            }
            $modelSources = $sourceRows;
        } else {
            $modelSources[] = new ProductSource();
            $modelSources = Product::createMultiple(ProductSource::class, $modelSources);
            $modelSources[0] = new ProductSource();
            $modelSources[0]->name = 'asd';

        }

        /**
         * This is for the booking Table
         *  TODO Ajax this
         *
         *
         */
        $modelEvents2 = [];
        foreach ($sourceRows as $source):
            $queryGetReservatios = Product::aSelect(Reservations::class, '*', Reservations::tableName(), 'source="' . $source->url.'"and productId="'.$source->prodIds.'"');
            try {
                $rowsAll = $queryGetReservatios->all();
            } catch (Exception $e) {
            }

            if (isset($rowsAll)) {
                foreach ($rowsAll as $reservation) {
                    $event = new \yii2fullcalendar\models\Event();
                    $event->id = $reservation->id;
                    $reservationData = $reservation->data;
                    $reservationData = json_decode($reservationData);
                    $reservationName = $reservationData->orderDetails->billing_first_name . ' ' . $reservationData->orderDetails->billing_last_name;
                    $event->title = $reservationName;
                    $event->start = $reservation->bookingDate;
                    $event->nonstandard = ['field1' => $source->name,'field2'=>$reservation->id];
                    $event->color = $source->color;
                    $modelEvents2[] = $event;
                }

            }
        endforeach;
            /**
             * This is for the booking Table
             *  TODO Ajax this
             *
             *
             */

            $queryGetReservatios = Product::aSelect(Reservations::class, '*', Reservations::tableName(), 'productId=250');
            try {
                $rowsAll = $queryGetReservatios->all();
            } catch (Exception $e) {
            }

            $modelEvents = [];

            if (isset($rowsAll)) {
                foreach ($rowsAll as $reservation) {
                    $event = new \yii2fullcalendar\models\Event();
                    $event->id = $reservation->id;
                    $reservationData = $reservation->data;
                    $reservationData = json_decode($reservationData);
                    $reservationName = $reservationData->orderDetails->billing_first_name . ' ' . $reservationData->orderDetails->billing_last_name;
                    $event->title = $reservationName;
                    $event->start = $reservation->bookingDate;
                    $event->nonstandard = ['field1' => $reservation->source];
                    $event->color = "purple";
                    $modelEvents[] = $event;
                }
            }

    if($model->slug=='testSlug' || $model->slug==''){
        $model->slug='/product/'.$this->slugify($model->title);
    }


            return $this->render(
                'update', ['model' => $model, 'backendData' => $backendData,
                    'updateResponse' => $updateResponse,
                    'prodId' => $prodId,
                    'modelSources' => ((empty($modelSources)) ? [new ProductSource()] : $modelSources),
                    'modelEvents' => $modelEvents2,
                    'modelTimes' => ((empty($modelTimes)) ? [new ProductTime()] : $modelTimes),
                    'modelPrices' => ((empty($modelPrices)) ? [new ProductPrice()] : $modelPrices),
                ]
            );

        }




    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionDaye(){
        $currentProductId=Yii::$app->request->get('prodId');

        $searchModel = new ReservationsAdminSearchModel();

        if($currentProductId){

            $currentProduct=Product::getProdById($currentProductId);
            $sourcesRows=ProductSource::getProductSourceIds($currentProductId);
            $selectedDate=\Yii::$app->request->get("date");
            $dataProvider = $searchModel->searchDay(Yii::$app->request->queryParams,$selectedDate,$sourcesRows,$currentProductId);
            $takenChairsCount=$searchModel->countTakenChairsOnDay(Yii::$app->request->queryParams,$selectedDate,$sourcesRows,$currentProductId);
            $availableChairsOnDay=$searchModel->availableChairsOnDay(Yii::$app->request->queryParams,$selectedDate,$sourcesRows,$currentProductId);

        }




        return $this->render('dayEdit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model'=>Reservations::class,
            'currentProduct'=>$currentProduct,
            'currentDay'=>Yii::$app->request->get('date'),
            'takenChairsCount'=>$takenChairsCount,
            'availableChairs'=>$availableChairsOnDay,

        ]);
    }


    public function actionBlocked(){
        $returnMessage='Currently no modification initiated';
        $currentProductId=Yii::$app->request->get('prodId');
        if($currentProductId) {
            $currentProduct = Product::getProdById($currentProductId);
            $sourcesRows = ProductSource::getProductSourceIds($currentProductId);
            $sourcesUrls= ProductSource::getProductSourceUrls($currentProductId);
            $sources=ProductSource::getProductSources($currentProductId);
        }

        $productPostedBlockouts = Yii::$app->request->post('ProductBlockout');

        #$modelPrices[] = new ProductPrice();
        #$returnMessage=$productPostedBlockouts;

        if($productPostedBlockouts["dates"]){



            $query = ProductBlockout::aSelect(ProductBlockout::class, '*', ProductBlockout::tableName(), 'product_id=' . $currentProductId);

            try {
                $rowsOne = $query->one();

            } catch (Exception $e) {
            }

            if(isset($rowsOne)){

                $model=$rowsOne;
                $a=explode(',',$model->dates);
                $b=explode(',',$productPostedBlockouts["dates"]);
                $deletedTimesIds = array_diff($a, $b);
                #var_dump($deletedTimesIds);

                if (!empty($deletedTimesIds)) {
                    foreach($deletedTimesIds as $date){

                        foreach ($sources as $source ){

                            $myurl=$source['url'];
                            $myprodid=$source['prodIds'];
                            if($source['url']=='https://budapestrivercruise.eu'){
                            $curlUrl=$myurl.'/wp-json/unblock/v1/start/'.$date.'/end/'.$date.'/id/'.$myprodid;
                            /*$curl=curl_init($curlUrl);
                            curl_setopt($curl, CURLOPT_HEADER, 0);
                            curl_setopt($curl, CURLOPT_VERBOSE, 0);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);*/
                            $curl=new CurlHttpClient();
                            $response=$curl->getContent($curlUrl);
                            if($response!=0){
                                $response=$curl->getContent($curlUrl);
                            }




                            }//ToDo not only eu
                        }



                       // var_dump($response.$url); find UNBLOCK URL HERE

                    }
                }

                $dates=explode(',',$productPostedBlockouts["dates"]);

                foreach ($dates as $i =>$date){
                    if(in_array($date,$deletedTimesIds)){
                        unset($dates[$i]);
                    }
                }

                $veglegesdates=$dates;




                foreach($veglegesdates as $i=>$date){


                    foreach ($sources as $source){
                        if($source['url']=='https://budapestrivercruise.eu') {



                            $myurl = $source['url'];
                            $myprodid = $source['prodIds'];
                            /**
                             * Először Vizsgálom hogy már fel van e küldve
                             */
                            $askURL = $myurl . '/wp-json/getav/v1/id/'. $myprodid;
                            $curlAsk = curl_init($askURL);
                            curl_setopt($curlAsk, CURLOPT_HEADER, 0);
                            curl_setopt($curlAsk, CURLOPT_VERBOSE, 0);
                            curl_setopt($curlAsk, CURLOPT_RETURNTRANSFER, true);
                            $response = curl_exec($curlAsk);
                            $alreadyblocked=json_decode($response)[0];
                            $alreadyBlockedArray=[];

                            foreach($alreadyblocked as $blockedDate){
                                if($blockedDate->bookable=='no' && $blockedDate->from==$blockedDate->to){
                                    $alreadyBlockedArray[]=$blockedDate->from;
                                }

                            }

                            if(!in_array($date,$alreadyBlockedArray)){
                                $curlUrl = $myurl . '/wp-json/block/v1/start/' . $date . '/end/' . $date . '/id/' . $myprodid;
                                $curl = curl_init($curlUrl);
                                curl_setopt($curl, CURLOPT_HEADER, 0);
                                curl_setopt($curl, CURLOPT_VERBOSE, 0);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($curl);
                            }






                        }//Todo no only eu
                    }
                }

            }
            else{

                $model=new ProductBlockout();
            }
            $values = [
                'product_id' => $currentProductId,
                'dates' => $productPostedBlockouts['dates'],

            ];

            if(ProductBlockout::insertOne($model,$values)){
                $returnMessage='Successfully Saved';

            }
            else{
                $returnMessage='Save not Succesful';

            }













        }







        $queryGetPrices = ProductBlockout::aSelect(ProductBlockout::class, '*', ProductBlockout::tableName(), 'product_id=' . $currentProductId);
        try {
            $rowsOne = $queryGetPrices->one();
        } catch (Exception $e) {
        }

        if (isset($rowsOne)) {

            $model=$rowsOne;

        } else {
            $model=new ProductBlockout();


        }

        /*update sources*/



        return $this->render('blocked',[
            'currentProduct'=>$currentProduct,
            'model'=>$model,
            'returnMessage'=>$returnMessage,

        ]);




    }
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'editbook' => [                                       // identifier for your editable column action
                'class' => EditableColumnAction::class,     // action class name
                'modelClass' => ReservationsAdminSearchModel::class,                // the model for the record being edited


                'showModelErrors' => true,

                'errorOptions' => ['header' => '']      ,          // error summary HTML options
                // 'postOnly' => true,
                // 'ajaxOnly' => true,
                 //'findModel' => function($id, $action) {},
                // 'checkAccess' => function($action, $model) {}
            ]
        ]);
    }


}
