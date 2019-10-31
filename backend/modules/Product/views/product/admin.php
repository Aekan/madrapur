<?php

use backend\modules\Product\models\ProductSource;

    use kartik\dynagrid\DynaGrid;
    use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\icons\Icon;

?>

<div class="product-default-index">


</div>

<div class="row">
    <div class="col-12">
        <!-- interactive chart -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="far fa-chart-bar"></i>
                    Product Manager
                </h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>

                </div>
            </div>
            <div class="card-body">
                <?php

                    $gridColumns = [
                        'id' ,
                        'title' ,
                        'capacity' ,
                        'currency' ,
                        [
                            'class' => 'kartik\grid\ActionColumn' ,
                            'visible' => Yii::$app->user->can('administrator') ,

                            'template' => '{update} {delete}' ,
                            'buttons' => [
                                'delete' => function ($url , $model , $key) {

                                    return Html::a('<span class="fa fa-lg fa-trash"></span>' ,
                                                   Url::to(['product/admin' ,
                                                            'id' => $model->id ,
                                                            'action' => 'delete']) ,
                                                   [
                                                       'title' => Yii::t('app' , 'Delete') ,
                                                       'data-pjax' => '1' ,
                                                       'data' => [
                                                           'method' => 'post' ,
                                                           'confirm' => Yii::t('app' , 'Are you sure you want to delete?') ,
                                                           'pjax' => 1 ,] ,
                                                   ]
                                    );

                                } ,
                                'update' => function ($url , $model , $key) {

                                    return Html::a(
                                        '<span class="fa fa-lg fa-pencil-alt"></span>',
                                        Url::to([
                                                    'product/update' ,
                                                    'prodId' => $model->id,
                                                ]),
                                        [
                                            'title' => Yii::t('app' , 'Delete'),
                                            'data-pjax' => '1',
                                            'data' => [
                                                'method' => 'post',
                                                'pjax' => 1,
                                            ],
                                        ]
                                    );

                                } ,


                            ]
                        ] ,

                        //        ['attribute'=>'isDeleted','visible'=>Yii::$app->user->can('administrator')]


                    ];

                    echo GridView::widget([

                                              'dataProvider' => $dataProvider ,
                                              'columns' => $gridColumns ,
                                              'layout' => '{items}{pager}'
                                          ]);
                    // $prodInfo=Product::getProdById(43); //With this method you get every information about a product with $id

                    //        $response = $client->yell($selectedDate,$currentProduct);
                    //        echo $response;
                    //
                    $dynagrid = DynaGrid::begin([
                                                    'columns' => $gridColumns,
                                                    'theme'=>'panel-info',
                                                    'showPersonalize'=>true,
                                                    'storage' => 'session',
                                                    'gridOptions'=>[
                                                        'dataProvider'=>$dataProvider,
                                                        'filterModel'=>$searchModel,
                                                        'showPageSummary'=>true,
                                                        'floatHeader'=>true,
                                                        'pjax'=>true,
                                                        'responsiveWrap'=>false,
                                                        'panel'=>[
                                                            'heading'=>'<h3 class="panel-title"><i class="fas fa-book"></i>  Library</h3>',
                                                            'before' =>  '<div style="padding-top: 7px;"><em>* The table header sticks to the top in this demo as you scroll</em></div>',
                                                            'after' => false
                                                        ],
                                                        'toolbar' =>  [
                                                            ['content'=>
                                                                 Html::button('<i class="fas fa-plus"></i>', ['type'=>'button', 'title'=>'Add Book', 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                                                                 Html::a('<i class="fas fa-repeat"></i>', ['dynagrid-demo'], ['data-pjax'=>0, 'class' => 'btn btn-outline-secondary', 'title'=>'Reset Grid'])
                                                            ],
                                                            ['content'=>'{dynagridFilter}{dynagridSort}{dynagrid}'],
                                                            '{export}',
                                                        ]
                                                    ],
                                                    'options'=>['id'=>'dynagrid-1'] // a unique identifier is important
                                                ]);
                    if (substr($dynagrid->theme, 0, 6) == 'simple') {
                        $dynagrid->gridOptions['panel'] = false;
                    }
                    DynaGrid::end();

                ?>

            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->

    </div>

    <!-- /.col -->
</div>
