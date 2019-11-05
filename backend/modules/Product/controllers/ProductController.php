<?php

namespace backend\modules\Product\controllers;

use backend\controllers\Controller;
use backend\modules\Modmail\models\Modmail;
use backend\modules\Product\models\AddOn;
use backend\modules\Product\models\Product;
use backend\modules\Product\models\ProductAddOn;
use backend\modules\Product\models\ProductAdminSearchModel;
use backend\modules\Product\models\ProductBlockout;
use backend\modules\Product\models\ProductBlockoutTime;
use backend\modules\Product\models\ProductEdit;
use backend\modules\Product\models\ProductOverview;
use backend\modules\Product\models\ProductPrice;
use backend\modules\Product\models\ProductSource;
use backend\modules\Product\models\ProductTime;
use backend\modules\Product\models\ProductUpdate;
use backend\modules\Reservations\models\Reservations;
use common\models\User;
use kartik\grid\EditableColumnAction;
use League\Uri\PublicSuffix\CurlHttpClient;
use PhpParser\Node\Expr\Yield_;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Controller for the `Product` module
 */
class ProductController extends Controller {
    /**
     * Renders the admin view for the module
     *
     * @return string
     */
    public function actionAdmin() {
        $searchModel = new ProductAdminSearchModel();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $gotId = Yii::$app->request->get('id');
        if (Yii::$app->request->get('action') == 'delete') {
            $model = new Product();
            $query = Product::aSelect(Product::class, '*', Product::tableName(), 'id=' . $gotId);
            try {
                $prodInfo = $query->one();
            } catch (Exception $e) {
            }
            $values = [
                'isDeleted' => 'yes',
            ];
            $result = Product::insertOne($prodInfo, $values);
            Yii::error(Yii::$app->user->id . 'deleted ' . $prodInfo->id);
        }

        return $this->render('admin', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

    public function actionUiblock() {

        $postedID = (Yii::$app->request->post('Product'))['id'];
        $postedAction = Yii::$app->request->post('blocking-button');
        if ($postedID && $postedAction) {
            if ($postedAction == 'timeBlocking') {
                $this->redirect('/Product/product/blockedtimes?prodId=' . $postedID);
            }
            if ($postedAction == 'dayBlocking') {
                $this->redirect('/Product/product/blocked?prodId=' . $postedID);
            }
            if ($postedAction == 'timeTable') {
                $this->redirect('/Product/product/timetable?prodId=' . $postedID);
            }
        }

        $searchModel = new ProductAdminSearchModel();
        $model = new Product();
        $allproducts = $searchModel->searchAllProducts(Yii::$app->request->queryParams);
        $data = [];
        $images = [];
        foreach ($allproducts as $product) {
            $data[$product->id] = $product->title;
            $images[$product->id] = $product->thumbnail;
        }

        return $this->render('uiblock', ['data' => $data, 'searchModel' => $searchModel, 'model' => $model, 'images' => $images]);
    }

    public function actionAccesstimetable() {

        $postedID = (Yii::$app->request->post('Product'))['id'];
        $postedAction = Yii::$app->request->post('blocking-button');
        if ($postedID && $postedAction) {
            if ($postedAction == 'timeBlocking') {
                $this->redirect('/Product/product/blockedtimes?prodId=' . $postedID);
            }
            if ($postedAction == 'dayBlocking') {
                $this->redirect('/Product/product/blocked?prodId=' . $postedID);
            }
            if ($postedAction == 'timeTable') {
                $this->redirect('/Product/product/timetable?prodId=' . $postedID);
            }
        }

        $searchModel = new ProductAdminSearchModel();
        $model = new Product();
        $allproducts = $searchModel->searchAllProducts(Yii::$app->request->queryParams);
        $data = [];
        $images = [];
        foreach ($allproducts as $product) {
            $data[$product->id] = $product->title;
            $images[$product->id] = $product->thumbnail;
        }

        return $this->render('accesstimetable', ['data' => $data, 'searchModel' => $searchModel, 'model' => $model, 'images' => $images]);
    }

    public function actionCreate() {
        $model = new ProductUpdate();
        $request = YII::$app->request;

        $productUpdate = $request->post('ProductUpdate');
        $values = [
            'currency' => $productUpdate['currency'],
            'status' => $productUpdate['status'],
            'title' => $productUpdate['title'],

            'short_description' => $productUpdate['short_description'],
            'desctiption' => $productUpdate['description'],
            'category' => $productUpdate['category'],
            'capacity' => $productUpdate['capacity'],
            'duration' => $productUpdate['duration'],
            'image' => $productUpdate['image'],
            'start_date' => $productUpdate['start_date'],
            'end_date' => $productUpdate['end_date'],
            'isDeleted'=>'no'
        ];
        $updateResponse = '';
        if ($productUpdate) {
            $newProduct = new Product();

            if (Product::insertOne($newProduct, $values)) {

                $updateResponse = '<span style="color:green">Product Update Successful</span>';
            } else {
                $updateResponse = '<span style="color:red">Product Update Failed</span>';
                //show an error message
            }
        }
        return $this->render('create', ['model' => $model, 'updateResponse' => $updateResponse]);
    }

    public function actionUpdate() {

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
                'thumbnail' => $productEdit['thumbnail'],
                'start_date' => $productEdit['start_date'],
                'end_date' => $productEdit['end_date'],
                'slug' => $productEdit['slug'],
                'isStreet'=>$productEdit['isStreet']
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

        /*******************AddOns Rész /TODO bring this to manly form*********************/

        $request = YII::$app->request;
        $productPostedAddOns = $request->post('AddOn');
        $modelAddOns = AddOn::find()->all();

        if ($productPostedAddOns) {
            $queryGetAddOns = ProductAddOn::aSelect(ProductAddOn::class, 'id', ProductAddOn::tableName(), 'prodId=' . $prodId)->all();

            $queryGetAddOns = ArrayHelper::map($queryGetAddOns, 'id', 'id');

            ProductAddOn::deleteAll(['id' => $queryGetAddOns]);

            foreach ($productPostedAddOns as $postedAddOn):
                $isEnabled = ProductAddOn::findOne(['id' => 1]);

                if ('0' !== ($addOnId = $postedAddOn['id'])) {
                    if (!$isEnabled) {
                        ProductAddOn::insertOne(
                            new ProductAddOn(),
                            [
                                'prodId' => $prodId,
                                'addOnId' => $addOnId
                            ]
                        );
                    }
                }
            endforeach;
        }

        $rowsAll = ProductAddOn::find()->andFilterWhere(['=', 'prodId', $prodId])->all();
        $rowsArray = ArrayHelper::toArray($rowsAll);
        $selectedModelAddOns = array_filter(ArrayHelper::map($rowsArray, 'addOnId', 'addOnId'));

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
                    'price' => intval($postedPrice['price']),
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
                    'color' => $postedSources['color']

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
            $sourceRows = [];

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

        $tempsource = new ProductSource();
        $tempsource2 = new ProductSource();
        $tempsource->url = 'Hotel';
        $tempsource->prodIds = Yii::$app->request->get('prodId');
        $tempsource2->url = 'Street';
        $tempsource2->prodIds = Yii::$app->request->get('prodId');
        $sourceRows[] = $tempsource;
        $sourceRows[] = $tempsource2;

        foreach ($sourceRows as $source):

            $queryGetReservatios = Product::aSelect(Reservations::class, '*', Reservations::tableName(), 'source="' . $source->url . '"and productId="' . $source->prodIds . '"');
            try {
                $rowsAll = $queryGetReservatios->all();
            } catch (Exception $e) {
            }

            if (isset($rowsAll)) {
                foreach ($rowsAll as $reservation) {
                    $event = new \yii2fullcalendar\models\Event();
                    $event->id = $reservation->id;
                    $reservationData = $reservation->data;
                    $reservationJsondata = json_decode($reservationData);
                    if (isset($reservationJsondata->orderDetails->billing_first_name)) {
                        $reservationName = $reservationJsondata->orderDetails->billing_first_name . ' ' . $reservationJsondata->orderDetails->billing_last_name;
                    } else {
                        $reservationName = $reservation->sellerName;
                    }

                    $event->title = $reservationName;
                    $event->start = $reservation->bookingDate;
                    $event->nonstandard = ['field1' => $source->name, 'field2' => $reservation->id];
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

        $queryGetReservatios = Product::aSelect(Reservations::class, '*', Reservations::tableName(), 'productId=!0');
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
                if (isset($reservationData->orderDetails->billing_first_name) && isset($reservationData->orderDetails->billing_first_name)) {
                    $reservationName = $reservationData->orderDetails->billing_first_name . ' ' . $reservationData->orderDetails->billing_last_name;
                } else {
                    $reservationName = $reservation->sellerName;
                }
                $event->title = $reservationName;
                $event->start = $reservation->bookingDate;
                $event->nonstandard = ['field1' => $reservation->source];
                $event->color = "purple";
                $modelEvents[] = $event;
            }
        }

        if ($model->slug == 'testSlug' || $model->slug == '') {
            $model->slug = '/product/' . $this->slugify($model->title);
        }

        return $this->render(
            'update', ['model' => $model, 'backendData' => $backendData,
                'updateResponse' => $updateResponse,
                'prodId' => $prodId,
                'modelSources' => ((empty($modelSources)) ? [new ProductSource()] : $modelSources),
                'modelEvents' => $modelEvents2,
                'modelTimes' => ((empty($modelTimes)) ? [new ProductTime()] : $modelTimes),
                'modelPrices' => ((empty($modelPrices)) ? [new ProductPrice()] : $modelPrices),
                'modelAddOns' => ((empty($modelAddOns)) ? [new AddOn()] : $modelAddOns),
                'selectedModelAddOns' => $selectedModelAddOns,
            ]
        );
    }

    /**
     * @return string
     */

    function slugify($text) {
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

    public function actionTimetable() {

        $prodId = Yii::$app->request->get('prodId');

        $modelEvents2 = [];
        $queryGetSources = ProductSource::aSelect(ProductSource::class, '*', ProductSource::tableName(), 'product_id=' . $prodId);
        try {
            $sourceRows = $queryGetSources->all();
        } catch (Exception $e) {
        }

        if (isset($sourceRows)) {

            $modelSources = $sourceRows;
        } else {
            $sourceRows = [];

            $modelSources[] = new ProductSource();
            $modelSources = Product::createMultiple(ProductSource::class, $modelSources);
            $modelSources[0] = new ProductSource();
            $modelSources[0]->name = 'asd';
        }

        $tempsource = new ProductSource();
        $tempsource2 = new ProductSource();
        $tempsource->url = 'Hotel';
        $tempsource->prodIds = Yii::$app->request->get('prodId');
        $tempsource2->url = 'Street';
        $tempsource2->prodIds = Yii::$app->request->get('prodId');
        $sourceRows[] = $tempsource;
        $sourceRows[] = $tempsource2;

        foreach ($sourceRows as $source):

            $queryGetReservatios = Product::aSelect(Reservations::class, '*', Reservations::tableName(), 'source="' . $source->url . '"and productId="' . $source->prodIds . '"');
            try {
                $rowsAll = $queryGetReservatios->all();
            } catch (Exception $e) {
            }

            if (isset($rowsAll)) {
                foreach ($rowsAll as $reservation) {
                    $event = new \yii2fullcalendar\models\Event();
                    $event->id = $reservation->id;
                    $reservationData = $reservation->data;
                    $reservationJsondata = json_decode($reservationData);
                    if (isset($reservationJsondata->orderDetails->billing_first_name)) {
                        $reservationName = $reservationJsondata->orderDetails->billing_first_name . ' ' . $reservationJsondata->orderDetails->billing_last_name;
                    } else {
                        $reservationName = $reservation->sellerName;
                    }

                    $event->title = $reservationName;
                    $event->start = $reservation->bookingDate;
                    $event->nonstandard = ['field1' => $source->name, 'field2' => $reservation->id];
                    $event->color = $source->color;
                    $modelEvents2[] = $event;
                }
            }
        endforeach;

        return $this->render(
            'timetable', [
                'model' => ((empty($model)) ? [new ProductEdit()] : $model),
                'prodId' => $prodId,
                'modelEvents' => $modelEvents2,
                'modelTimes' => ((empty($modelTimes)) ? [new ProductTime()] : $modelTimes),
                'modelPrices' => ((empty($modelPrices)) ? [new ProductPrice()] : $modelPrices),

            ]
        );
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionDaye() {

        $currentProductId = Yii::$app->request->get('prodId');

        $searchModel = new Reservations();

        if ($currentProductId) {

            $currentProduct = Product::getProdById($currentProductId);
            $sourcesRows = ProductSource::getProductSourceIds($currentProductId);

            $selectedDate = \Yii::$app->request->get("date");
            $dataProvider = $searchModel->searchDay(Yii::$app->request->queryParams, $selectedDate, $sourcesRows, $currentProductId);
            $timeHours = Reservations::getProductTimeshours($currentProductId);

            $allDataproviders = [];

            foreach ($timeHours as $time) {

                $tmpdataProvider = $searchModel->searchDayTime(Yii::$app->request->queryParams, $selectedDate, $sourcesRows, $currentProductId, $time);
                $allDataproviders[$time] = $tmpdataProvider;
            }

            $takenChairsCount = Reservations::countTakenChairsOnDay($selectedDate, $sourcesRows);
            $availableChairsOnDay = $searchModel->availableChairsOnDay(Yii::$app->request->queryParams, $selectedDate, $sourcesRows, $currentProductId);
        }

        $passignedId = (Yii::$app->request->post('User'))['id'];
        $preservatinId = Yii::$app->request->post('reservation');
        if ($passignedId && $preservatinId) {
            $foundReservation = Reservations::find()->where(['id' => $preservatinId])->one();

            $assignedUser = User::find()->where(['id' => $preservatinId])->one();
            $assigneduser = User::findIdentity($passignedId);

            $assignData = [];
            $assignData['time'] = date('Y-m-d H:i:s', time());
            $assignData['by'] = Yii::$app->getUser()->identity->username;
            $assignData['from'] = $foundReservation->sellerName;
            $assignData['to'] = $assigneduser->username;

            if ($foundReservation) {
                $Reservationobject = json_decode($foundReservation->data);
                if (isset($Reservationobject->assignments) && is_array($Reservationobject->assignments)) {

                    array_unshift($Reservationobject->assignments, $assignData);
                } else {
                    $Reservationobject->assignments[] = $assignData;
                }

                $foundReservation->data = json_encode($Reservationobject);
                $foundReservation->sellerName = $assigneduser->username;
                $foundReservation->save(false);
//                echo \GuzzleHttp\json_encode($foundReservation->data);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Successful assignment<u>' . $preservatinId . '</u> reservation to ' . $foundReservation->sellerName));
            }
        }

        return $this->render('dayEdit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => Reservations::class,
            'currentProduct' => $currentProduct,
            'currentDay' => Yii::$app->request->get('date'),
            'takenChairsCount' => $takenChairsCount,
            'availableChairs' => $availableChairsOnDay,
            'timesHours' => $timeHours,
            'allDataProviders' => $allDataproviders,
            'sources' => $sourcesRows,
            'selectedDate' => $selectedDate

        ]);
    }

    public function actionBlocked() {
        $returnMessage = 'Currently no modification initiated';
        $currentProductId = Yii::$app->request->get('prodId');
        if ($currentProductId) {
            $currentProduct = Product::getProdById($currentProductId);
            $sourcesRows = ProductSource::getProductSourceIds($currentProductId);
            $sourcesUrls = ProductSource::getProductSourceUrls($currentProductId);
            $sources = ProductSource::getProductSources($currentProductId);
        }

        $productPostedBlockouts = Yii::$app->request->post('ProductBlockout');

        #$modelPrices[] = new ProductPrice();
        #$returnMessage=$productPostedBlockouts;

        if ($productPostedBlockouts["dates"]) {

            $query = ProductBlockout::aSelect(ProductBlockout::class, '*', ProductBlockout::tableName(), 'product_id=' . $currentProductId);

            try {
                $rowsOne = $query->one();
            } catch (Exception $e) {
            }

            if (isset($rowsOne)) {

                $model = $rowsOne;
                $a = explode(',', $model->dates);
                $b = explode(',', $productPostedBlockouts["dates"]);
                $deletedTimesIds = array_diff($a, $b);
                #var_dump($deletedTimesIds);


                if (!empty($deletedTimesIds)) {
                    foreach ($deletedTimesIds as $date) {

                        foreach ($sources as $source) {

                            $myurl = $source['url'];
                            $myprodid = $source['prodIds'];

                            $curlUrl = $myurl . '/wp-json/unblock/v1/start/' . $date . '/end/' . $date . '/id/' . $myprodid;
                            /*$curl=curl_init($curlUrl);
                            curl_setopt($curl, CURLOPT_HEADER, 0);
                            curl_setopt($curl, CURLOPT_VERBOSE, 0);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);*/
                            $curl = new CurlHttpClient();
                            $response = $curl->getContent($curlUrl);
                            if ($response != 0) {
                                $response = $curl->getContent($curlUrl);
                            }
                        }
                        // var_dump($response.$url); find UNBLOCK URL HERE

                    }
                }

                $dates = explode(',', $productPostedBlockouts["dates"]);

                foreach ($dates as $i => $date) {
                    if (in_array($date, $deletedTimesIds)) {
                        unset($dates[$i]);
                    }
                }

                $veglegesdates = $dates;

                foreach ($veglegesdates as $i => $date) {

                    foreach ($sources as $source) {

                        $myurl = $source['url'];
                        $myprodid = $source['prodIds'];
                        /**
                         * Először Vizsgálom hogy már fel van e küldve
                         */
                        $askURL = $myurl . '/wp-json/getav/v1/id/' . $myprodid;
                        $curlAsk = curl_init($askURL);
                        curl_setopt($curlAsk, CURLOPT_HEADER, 0);
                        curl_setopt($curlAsk, CURLOPT_VERBOSE, 0);
                        curl_setopt($curlAsk, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($curlAsk);
                        $alreadyblocked = json_decode($response)[0];
                        $alreadyBlockedArray = [];

                        if (!$alreadyblocked) {
                            Yii::$app->session->setFlash('alert',
                                [
                                    'options' => [
                                        'class' => 'alert-error'
                                    ],
                                    'body' => Yii::t('backend', "
                                            Something went wrong!<br>
                                            Ask URL: <a href='$askURL' target='_blank'>$askURL</a><br>
                                            Product edit URL: <a 
                                            href='$myurl/wp-admin/post.php?post=$myprodid&action=edit' target='_blank'>$myurl/wp-admin/post.php?post=$myprodid&action=edit</a><br>
                                            ProdId: $myprodid
                                        ")
                                ]);

                            Yii::error("Oops.. $askURL");
                        }

                        if ($alreadyblocked) {
                            foreach ($alreadyblocked as $blockedDate) {
                                if ($blockedDate->bookable == 'no' && $blockedDate->from == $blockedDate->to) {
                                    $alreadyBlockedArray[] = $blockedDate->from;
                                }
                            }
                        }

                        if (!in_array($date, $alreadyBlockedArray)) {
                            $curlUrl = $myurl . '/wp-json/block/v1/start/' . $date . '/end/' . $date . '/id/' . $myprodid;
                            $curl = curl_init($curlUrl);
                            curl_setopt($curl, CURLOPT_HEADER, 0);
                            curl_setopt($curl, CURLOPT_VERBOSE, 0);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            $response = curl_exec($curl);
                        }
                    }
                }
            } else {

                $model = new ProductBlockout();
            }
            $values = [
                'product_id' => $currentProductId,
                'dates' => $productPostedBlockouts['dates'],

            ];

            if (ProductBlockout::insertOne($model, $values)) {
                $returnMessage = 'Successfully Saved';
            } else {
                $returnMessage = 'Save not Succesful';
            }
        }

        $queryGetPrices = ProductBlockout::aSelect(ProductBlockout::class, '*', ProductBlockout::tableName(), 'product_id=' . $currentProductId);
        try {
            $rowsOne = $queryGetPrices->one();
        } catch (Exception $e) {
        }

        if (isset($rowsOne)) {

            $model = $rowsOne;
        } else {
            $model = new ProductBlockout();
        }

        /*update sources*/

        return $this->render('blocked', [
            'currentProduct' => $currentProduct,
            'model' => $model,
            'returnMessage' => $returnMessage,

        ]);
    }

    public function actionBlockedtimes() {
        $returnMessage = 'Currently no modification initiated';
        $currentProductId = Yii::$app->request->get('prodId');

        if ($currentProductId) {
            $currentProduct = Product::getProdById($currentProductId);

            $sources = ProductSource::getProductSources($currentProductId);
        }

        $postedBlockout = Yii::$app->request->post('ProductBlockoutTime');
        $postedBlockoutDelete = Yii::$app->request->get('delete');

        #$modelPrices[] = new ProductPrice();
        #$returnMessage=$productPostedBlockouts;

        $model = new ProductBlockoutTime();
        $searchModel = new ProductBlockoutTime();

        if ($postedBlockout["date"]) {
            $values = [
                'product_id' => $currentProductId,
                'date' => $postedBlockout['date'],
            ];

            if (ProductBlockout::insertOne($model, $values)) {

                foreach ($sources as $source) {
                    $myurl = $source['url'];
                    $myprodid = $source['prodIds'];

                    $returnMessage = $this->blockDateTime($postedBlockout['date'], $myurl, $myprodid);
                }

                /**
                 * Let's send E mail notification
                 *
                 */
                $currentUser = Yii::$app->user->identity->username;
                $mailModel = new Modmail();

                $bootstrap = '<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
                $bootstrap .= '<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >';

                $productName = $currentProduct->title;
                $timeBlockDate = $postedBlockout['date'];
                $username = $currentUser;
                $blockedOn = date('Y-m-d H:i:s');

                $newblockHTML = '';
                include_once('VvvebJs/mail-templates/newblock.php');
                $txt = $bootstrap . $newblockHTML; //this is from

                $values2 = [
                    'from' => 'info@budapestrivercruise.co.uk',
                    'to' => 'web@silver-line.hu',
                    'subject' => 'New timeBlock on ' . $_SERVER['HTTP_HOST'] . ' ' . $blockedOn . ' by ' . $currentUser,
                    'date' => date('Y-m-d H:i'),
                    'type' => 'new timeBlock',
                    'status' => 'sent',
                    'body' => $txt

                ];

                //the 2 above go together

                $headers = "From: " . $values2['from'] . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                if (mail($values2['to'], $values2['subject'], $values2['body'], $headers)) {

                    Yii::warning('Elkuldom a mailt');

                    Modmail::insertOne($mailModel, $values2);
                }
            } else {
                $returnMessage = 'Save not Succesful';
            }
        }
        if ($postedBlockoutDelete) {
            $blockoutToDelete = ProductBlockoutTime::meById(new ProductBlockoutTime(), $postedBlockoutDelete);

            if ($blockoutToDelete) {
                foreach ($sources as $source) {

                    $myurl = $source['url'];
                    $myprodid = $source['prodIds'];
                    if ($myurl == 'https://budapestrivercruise.eu') {

                        $this->unblockDateTime($blockoutToDelete['date'], $myurl, $myprodid);
                        $returnMessage = 'Successful Timeunblock!';
// todo : sleep

                        $blockoutToDelete->delete();
                    }
                }
            } else {
                $returnMessage = 'Already deleted or non-existent.';
            }
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $currentProductId);

        /*update sources*/

        return $this->render('blockedtimes', [
            'currentProduct' => $currentProduct,
            'model' => $model,
            'returnMessage' => $returnMessage,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,

        ]);
    }

    public function blockDateTime($date, $url, $product_id) {

        $myurl = $url;
        $myprodid = $product_id;
        /**
         * Először Vizsgálom hogy már fel van e küldve
         */
        $askURL = $myurl . '/wp-json/getav/v1/id/' . $myprodid;
        $curlAsk = curl_init($askURL);
        curl_setopt($curlAsk, CURLOPT_HEADER, 0);
        curl_setopt($curlAsk, CURLOPT_VERBOSE, 0);
        curl_setopt($curlAsk, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlAsk);
        $alreadyblocked = json_decode($response)[0];
        $alreadyBlockedArray = [];

        if (is_array($alreadyblocked)) {
            foreach ($alreadyblocked as $blockedDate) {
                if ($blockedDate->bookable == 'no' && $blockedDate->from == date('H:i', strtotime($date))) {
                    if (isset($blockedDate->from_date) && isset($blockedDate->to_date)) {
                        if ($blockedDate->from_date == $blockedDate->from_date) {
                            $alreadyBlockedArray[] = $blockedDate->from_date . ' ' . $blockedDate->from;
                        }
                    }
                }
            }
        } else {
            Yii::error('not array', 'blockDateTime');
            Yii::error($askURL, 'blockDateTime');
        }

        if (!in_array(date('Y-m-d H:i', strtotime($date)), $alreadyBlockedArray)) {

            $curlUrl = $myurl . '/wp-json/blocktime/v1/date/' . date('Y-m-d', strtotime($date)) . '/time/' . date('H:i', strtotime($date)) . '/id/' . $myprodid;

            echo '<input type="hidden" value="' . $curlUrl . '"  name="currentCurlUrl"/>';

            Yii::warning('blockUrl:' . $curlUrl);
            $curl = curl_init($curlUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_VERBOSE, 0);

            $response = curl_exec($curl);
            $responseMessage = 'Succesful timeblock<br/>';
            $responseMessage .= $response . $curlUrl;
        } else {

            $responseMessage = 'Operation Succesful';
        }

        return $responseMessage;
    }

    public function unblockDateTime($date, $url, $product_id) {

        $myurl = $url;
        $myprodid = $product_id;
        /**
         * Először Vizsgálom hogy már fel van e küldve
         */
        $askURL = $myurl . '/wp-json/getav/v1/id/' . $myprodid;
        $curlAsk = curl_init($askURL);
        curl_setopt($curlAsk, CURLOPT_HEADER, 0);
        curl_setopt($curlAsk, CURLOPT_VERBOSE, 0);
        curl_setopt($curlAsk, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlAsk);
        $alreadyblocked = json_decode($response)[0];

        foreach ($alreadyblocked as $blockedDate) {
            if ($blockedDate->bookable == 'no' && $blockedDate->from == date('H:i', strtotime($date))) {
                if (isset($blockedDate->from_date) && isset($blockedDate->to_date)) {
                    if ($blockedDate->from_date == $blockedDate->to_date) {

                        $alreadyBlockedArray[] = $blockedDate->from;
                    }
                }
            }
        }
        $shouldIcurL = false;

        if (isset($alreadyBlockedArray) && in_array(date('H:i', strtotime($date)), $alreadyBlockedArray)) {
            $shouldIcurL = true;
        }

        if ($shouldIcurL) {
            Yii::error('date:' . $date);
            $curlUrl = $myurl . '/wp-json/unblocktime/v1/date/' . date('Y-m-d', strtotime($date)) . '/time/' . date('H:i', strtotime($date)) . '/id/' . $myprodid;
            Yii::error('unblockUrl:' . $curlUrl);
            $curl = curl_init($curlUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_VERBOSE, 0);

            $response = curl_exec($curl);
            $responseMessage = 'Succesful timeunblock';
            $responseMessage .= $response;
        } else {
            $responseMessage = $response;
        }

        return $responseMessage;
    }

    public function actions() {
        return ArrayHelper::merge(parent::actions(), [
            'editbook' => [                                       // identifier for your editable column action
                'class' => EditableColumnAction::class,     // action class name
                'modelClass' => Reservations::class,                // the model for the record being edited

                'showModelErrors' => true,

                'errorOptions' => ['header' => ''],          // error summary HTML options
                // 'postOnly' => true,
                // 'ajaxOnly' => true,
                //'findModel' => function($id, $action) {},
                // 'checkAccess' => function($action, $model) {}
            ]
        ]);
    }

    public function actionAddOnsSummary() {
        $model = new ProductUpdate();
        $request = YII::$app->request;

        $productUpdate = $request->post('ProductUpdate');
        $values = [
            'currency' => $productUpdate['currency'],
            'status' => $productUpdate['status'],
            'title' => $productUpdate['title'],

            'short_description' => $productUpdate['short_description'],
            'desctiption' => $productUpdate['description'],
            'category' => $productUpdate['category'],
            'capacity' => $productUpdate['capacity'],
            'duration' => $productUpdate['duration'],
            'image' => $productUpdate['image'],
            'start_date' => $productUpdate['start_date'],
            'end_date' => $productUpdate['end_date'],
            'isDeleted'=>'no'
        ];
        $updateResponse = '';
        if ($productUpdate) {
            $newProduct = new Product();

            if (Product::insertOne($newProduct, $values)) {

                $updateResponse = '<span style="color:green">Product Update Successful</span>';
            } else {
                $updateResponse = '<span style="color:red">Product Update Failed</span>';
                //show an error message
            }
        }
        return $this->render('create', ['model' => $model, 'updateResponse' => $updateResponse]);
    }
}
