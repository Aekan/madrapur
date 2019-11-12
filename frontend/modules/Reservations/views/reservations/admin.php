<?php
/**
 * Created by PhpStorm.
 * User: ROG
 * Date: 2019. 02. 05.
 * Time: 20:38
 */

use backend\components\extra;
use kartik\date\DatePicker;
use kartik\helpers\Html;
use yii\widgets\ActiveForm;

?>

<!--suppress ALL -->
<div class="row">
    <div class="col-12">
        <!-- interactive chart -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chair fa-lg "></i>
                    <?=Yii::t('app', 'Reservations')?>
                </h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>

                </div>
            </div>
            <div class="card-body">

                <div class="products-index">

                    <h1><?= Html::encode($this->title) ?></h1>
                    <?php
                        $chartProvider = $chartDataProvider->query->all();
                        $series2 = array();
                        $price = array();
                        $sources = array();

                        /**
                         * Visualise Chart on Bookings data TODO: Sort, Import Refunded Bookings + Voucher Orders
                         */
                        foreach ($chartProvider as $data) {

                            if (!in_array($data->source, $sources)) {
                                $sources[] = $data->source;
                            }
                            $data->data = json_decode($data->data);

                            $cBookingId = $data->bookingId;
                            if (isset($data->data->orderDetails)) {
                                $cBookingPaidDate = date('Y-m-d', strtotime($data->invoiceDate));

                                if (!isset($data->data->orderDetails->order_currency)) {
                                    $cBookingCurrency = 'EUR';
                                } else {
                                    $cBookingCurrency = $data->data->orderDetails->order_currency;
                                }

                                if (isset($data->data->boookingDetails->booking_cost)) {
                                    $cBookingTotal = $data->data->boookingDetails->booking_cost;
                                } else {
                                    $cBookingTotal = '0';//**Todo  update $data booking cost;

                                }
                                if ($cBookingCurrency != 'EUR') {
                                    $cBookingTotal = intval($cBookingTotal) / 314;
                                }

                                /**
                                 * A fenti if azt az esetet vizsgálja ha az order total mégse annyi mint a booking total pl kupon/teszt vásárlás
                                 */
                                if (isset($price[$cBookingPaidDate][$data->source])) {
                                    if (isset($data->data->orderDetails->order_total)) {
                                        if ($data->data->orderDetails->order_total == '0') {
                                            $cBookingTotal = 0;
                                        }
                                    }

                                    $price[$cBookingPaidDate][$data->source] += intval($cBookingTotal);
                                } else {
                                    if (isset($data->data->orderDetails->order_total)) {
                                        if ($data->data->orderDetails->order_total == '0') {
                                            $cBookingTotal = 0;
                                        }
                                    }
                                    $price[$cBookingPaidDate][$data->source] = intval($cBookingTotal);
                                }
                            }
                        }

                        foreach ($sources as $source) {
                            $entity['name'] = $source;
                            $entity['data'] = array();
                            foreach ($price as $pDate => $pValue) {
                                if (isset($pValue[$source])) {
                                    $oneDate = array(0 => date('Y-m-d', strtotime($pDate)), 1 => $pValue[$source]);
                                    $entity['data'][] = $oneDate;
                                }
                            }

                            /**
                             * $array[] is 3x faster than array push
                             */
                            $series2[] = $entity;
                        }
                        function sortFunction($a, $b) {
                            return strtotime($a["0"]) - strtotime($b["0"]);
                        }

                        /**
                         * Sorba teszem itt a dátumokat
                         */
                        $finalSeries = [];

                        foreach ($series2 as $serie) {
                            #  echo '<br/><br/>Szeria sorting előtt';
                            #  var_dump($serie);

                            usort($serie["data"], "sortFunction");
                            $finalSeries[] = $serie;
                            # echo '<br/><br/>Szeria sorting után';
                            # var_dump($serie);

                        }

                        #echo(json_encode($series2));

                        if (Yii::$app->user->getIdentity()->username !== "manager") {

                            echo \onmotion\apexcharts\ApexchartsWidget::widget([

                                                                                   'type' => 'bar', // default area

                                                                                   'height' => '450', // default 350

                                                                                   'chartOptions' => [

                                                                                       'chart' => [

                                                                                           'toolbar' => [
                                                                                               'show' => true,
                                                                                               'autoSelected' => 'zoom'
                                                                                           ],
                                                                                       ],

                                                                                       'xaxis' => [
                                                                                           'type' => 'datetime',
                                                                                           // 'categories' => $categories,
                                                                                       ],
                                                                                       /*   'markers'=>[
                                                                                              'markers'=> [
                                                                                                  'size'=> '0',
                                                                                                  'strokeColor'=> "#fff",
                                                                                                  'strokeWidth'=> '3',
                                                                                                  'strokeOpacity'=> '1',
                                                                                                  'fillOpacity'=> '1',
                                                                                                  'hover'=> [
                                                                                                      'size'=> '6',
                                                                                                  ],
                                                                                              ],
                                                                                          ],*/

                                                                                       'dataLabels' => [
                                                                                           'enabled' => false,
                                                                                       ],
                                                                                       'stroke' => [
                                                                                           'show' => true,

                                                                                           'curve' => 'smooth',
                                                                                       ],
                                                                                       'legend' => [
                                                                                           'verticalAlign' => 'top',
                                                                                           'horizontalAlign' => 'left',
                                                                                       ],
                                                                                   ],
                                                                                   'series' => $finalSeries
                                                                               ]);
                        }

                    ?>


                    <?php
                        $gridColumns = [
                            'id',
                            'bookingId',
                            'productId',
                            [
                                'attribute' => 'source',
                                'filter' => array("https://silver-line.hu" => "https://silver-line.hu", "https://budapestrivercruise.eu" => "https://budapestrivercruise.eu", 'Street' => 'Street', 'Hotel' => 'Hotel'),
                            ],

                            'invoiceDate',
                            [
                                'attribute' => 'bookingDate',
                                'filter' => DatePicker::widget([
                                                                   'model' => $searchModel,
                                                                   'attribute' => 'bookingDate',
                                                                   'name' => 'dp_2',
                                                                   'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                                                   'pluginOptions' => [
                                                                       'autoclose' => true,
                                                                       'format' => 'yyyy-mm-dd'

                                                                   ]
                                                               ]),
                            ],  [
                                'attribute' => 'sellerName',

                            ],
                            [
                                'label' => 'Edit Booking',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return '<a href="/Reservations/reservations/bookingedit?id=' . $model->returnId() . '">Edit' . '</a>';
                                }
                            ],
                            [
                                'class' => 'kartik\grid\ActionColumn',
                                'template' => '{view}{bookingedit}',
                                'buttons' => [
                                    'view' => function ($url) {
                                        return Html::a('<i class="fas fa-eye fa-lg "></i>',
                                                       $url,
                                                       [
                                                           'title' => Yii::t('backend', 'Login')
                                                       ]);
                                    },
                                    'bookingedit' => function ($url) {
                                        return Html::a('<i class="fas fa-pencil-alt fa-lg "></i>',
                                                       $url,
                                                       [
                                                           'title' => Yii::t('backend', 'Login')
                                                       ]);
                                    },
                                ],
                                'visibleButtons' => [
                                    'login' => Yii::$app->user->can('administrator')
                                ]
                            ],

                        ];

                        echo \kartik\grid\GridView::widget([
                                                               'pager' => [
                                                                   'firstPageLabel' => Yii::t('app', 'Első oldal'),
                                                                   'lastPageLabel' => Yii::t('app', 'Utolsó oldal'),
                                                               ],
                                                               'dataProvider' => $dataProvider,
                                                               'filterModel' => new \backend\modules\Reservations\models\Reservations(),
                                                               'columns' => $gridColumns,
                                                               'toolbar' => [
                                                                   [
                                                                       'options' => ['class' => 'btn-group mr-2']
                                                                   ],
                                                                   '{export}',
                                                                   '{toggleData}',
                                                               ],
                                                               'toggleDataContainer' => ['class' => 'btn-group mr-2'],

                                                               'panel' => [
                                                                   'heading' => '<i class="fa fa-summary-alt"></i>',
                                                               ],
                                                           ]);

                    ?>

                    <div id="jsonPre">


                    </div>

                    <?php

                        $form = ActiveForm::begin([
                                                      'id' => 'date-import',
                                                      'action' => 'admin',
                                                      'options' => ['class' => 'modImp'],
                                                  ]) ?>

                    <style>
                        .dateImportWidget {
                            background-color: #eee;
                            padding: 10px;
                        }

                        .dateImportWidget input {
                            width: 20%;

                        }


                    </style>

                    <div class="box">
                        <div class="box-title"><h2>Importálás</h2></div>

                        <?php

                            # foreach ($response as $booking){
                            if (isset($importResponse)) {
                                // echo 'Import Státusza: ' . $importResponse . '<br/>';
                                if (YII_ENV_DEV) {
                                    echo($importResponse);
                                }
                            }

                            #}

                        ?>


                        <div class="dateImportWidget">
                            <?php

                            ?>
                            <?= $form->field($dateImportModel, 'dateFrom')->widget(\yii\jui\DatePicker::class, [         //'language' => 'ru',
                                                                                                                         //'dateFormat' => 'yyyy-MM-dd',
                            ]) ?>
                            <?= $form->field($dateImportModel, 'dateTo')->widget(\yii\jui\DatePicker::class, [         //'language' => 'ru',
                                                                                                                       //'dateFormat' => 'yyyy-MM-dd',
                            ]) ?>
                            <?= $form->field($dateImportModel, 'source')->dropDownList(array('https://budapestrivercruise.eu' => 'https://budapestrivercruise.eu', 'https://silver-line.hu' => 'https://silver-line.hu'), array('options' => array('https://budapestrivercruise.eu' => array('selected' => true)))); ?>

                            <?php
                                echo Html::submitButton(Yii::t('backend', 'Importálás'),
                                                        [
                                                            'class' => 'btn btn-primary btn-flat btn-block',
                                                            'name' => 'import-button'
                                                        ])

                            ?>


                            <?php ActiveForm::end() ?>
                        </div>
                        <div class="dateImportStatus">
                            <strong>Log</strong>
                            <span id="currLog">

            </span>
                        </div>


                    </div>


                    <script>


                    </script>

                </div>


            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->

    </div>

    <!-- /.col -->
</div>