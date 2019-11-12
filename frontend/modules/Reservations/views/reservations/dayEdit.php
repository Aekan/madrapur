<?php
    /**
     * Created by PhpStorm.
     * User: ROG
     * Date: 2019. 02. 05.
     * Time: 20:38
     */


    use backend\modules\Reservations\models\Reservations;
    use kartik\editable\Editable;
    use kartik\grid\EditableColumn;
    use kartik\grid\GridView;
    use kartik\helpers\Html;
    use kartik\form\ActiveForm;
    use yii\widgets\Pjax;
    \kartik\grid\EditableColumnAsset::register($this);
    \kartik\editable\EditableAsset::register($this);

    $title = 'Bookings of ' . '<u>' . $currentProduct->title . '</u>' . ' on ' . $currentDay;/*
$this->title=$title;
$this->params['breadcrumbs'][] = $this->title;

*/

    yii\bootstrap4\Modal::begin([
                                    'id' =>'modal2',
                                    //'headerOptions' => ['id' => 'modalHeader'],
                                    'title' => 'View Reservation',

                                ]);
    yii\bootstrap4\Modal::end();

    yii\bootstrap4\Modal::begin([
                                    'id' =>'modal',
                                    //'headerOptions' => ['id' => 'modalHeader'],
                                    'title' => 'Create a new reservation',

                                ]);
    yii\bootstrap4\Modal::end();
    Pjax::begin();
?>



<div class="row">
    <div class="col-12">
        <!-- interactive chart -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-sun"></i>
                    <?=$title?>
                </h3>

                <div class="card-tools">

                </div>
            </div>
            <div class="card-body">


                <div class="products-index">


                    <?php
                        $gridColumns = [
                            /*  'id',
                              ['class' => EditableColumn::class,
                                  'attribute'=>'bookingId'],*/
                            ['class' => 'kartik\grid\ExpandRowColumn',
                             'width' => '50px',
                             'value' => function ($model, $key, $index, $column) {
                                 return \kartik\grid\GridView::ROW_COLLAPSED;
                             },
                             // uncomment below and comment detail if you need to render via ajax
                             // 'detailUrl'=>Url::to(['/site/book-details']),
                             'detail' => function ($model, $key, $index, $column) {
                                 return Yii::$app->controller->renderPartial('assinguitimetable', ['model' => $model]);
                             },
                             'headerOptions' => ['class' => 'kartik-sheet-style'],
                             'expandOneOnly' => true,]
                            ,

                            ['class' => EditableColumn::class,
                             'attribute' => 'firstName',
                             
                             'label' => 'First Name',
                             'refreshGrid' => true,

                             'editableOptions' => function ($model, $key, $index) {
                                 $currentdate = Yii::$app->request->get('date');
                                 $ctime = $model->booking_start;
                                 $firstpart = str_replace(':', '_', $ctime);
                                 $secondpart = str_replace(' ', '_', $firstpart);

                                 return [
                                     'formOptions' => [
                                         'id' => 'gv1_' . $model->id . '_form_name',
                                         'action' => \yii\helpers\Url::to(['/Product/product/editbook'])
                                     ],
                                     'options' => [
                                         'id' => 'gv1_' . $secondpart . '_' . $model->id . rand() % 10000,
                                     ],
                                     'asPopover'=>false,
                                     'inputType'=>\kartik\editable\Editable::INPUT_TEXT,

                                 ];
                             },
                            ],
                            ['class' => EditableColumn::class,
                             'attribute' => 'lastName',
                             'label' => 'Last Name',
                             'refreshGrid' => true,


                             'editableOptions' => function ($model, $key, $index) {
                                 $currentdate = Yii::$app->request->get('date');
                                 $ctime = $model->booking_start;
                                 $firstpart = str_replace(':', '_', $ctime);
                                 $secondpart = str_replace(' ', '_', $firstpart);

                                 return [
                                     'formOptions' => [
                                         'id' => 'gv1_' . $model->id . '_form_name',
                                         'action' => \yii\helpers\Url::to(['/Product/product/editbook'])
                                     ],
                                     'options' => [
                                         'id' => 'gv1_' . $secondpart . '_' . $model->id . rand() % 10000,
                                     ],
                                     'asPopover'=>false,
                                     'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                                 ];
                             },

                            ],
                            ['class' => EditableColumn::class,
                             'attribute' => 'bookedChairsCount',
                             'label'=>'Number of tickets',
                             'pageSummary' => true,
                             'refreshGrid' => true,
                             'editableOptions' => function ($model, $key, $index) {
                                 $currentdate = Yii::$app->request->get('date');
                                 $ctime = $model->booking_start;
                                 $firstpart = str_replace(':', '_', $ctime);
                                 $secondpart = str_replace(' ', '_', $firstpart);


                                 return [
                                     'formOptions' => [
                                         'id' => 'gv1_' . $model->id . '_form_name',
                                         'action' => \yii\helpers\Url::to(['/Product/product/editbook'])
                                     ],
                                     'options' => [
                                         'id' => 'gv1_' . $secondpart . '_' . $model->id . rand() % 10000,
                                     ],
                                     'asPopover'=>false,
                                     'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                                 ];
                             },


                            ],
//
//                            /*   'productId',*/
                            ['class' => EditableColumn::class,
                             'attribute' => 'sourceName',
                             'label'=>'Seller Group Name',
                             'refreshGrid' => true,
                             'editableOptions' => function ($model, $key, $index) {
                                 $currentdate = Yii::$app->request->get('date');
                                 $ctime = $model->booking_start;
                                 $firstpart = str_replace(':', '_', $ctime);
                                 $secondpart = str_replace(' ', '_', $firstpart);

                                 return [
                                     'formOptions' => [
                                         'id' => 'gv1_' . $model->id . '_form_name',
                                         'action' => \yii\helpers\Url::to(['/Product/product/editbook'])
                                     ],
                                     'options' => [
                                         'id' => 'gv1_' . $secondpart . '_' . $model->id . rand() % 10000,
                                     ],
                                     'asPopover'=>false,
                                     'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                                 ];
                             },
                            ],
                            [
                                'attribute' => 'sellerName',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return $model->sellerName . "<br/>" .'<a class="badge bg-warning ">'
                                        .$model->iSellerName.'</a>';
                                }
                            ],
                            //                        'invoiceDate',
                            //                        'bookingDate',
                            //                        [
                            //                            'label' => 'Edit Booking',
                            //                            'format' => 'html',
                            //                            'value' => function ($model) {
                            //                                return '<a href="/Reservations/reservations/bookingedit?id=' . $model->id . '">Edit' . '</a>';
                            //                            }
                            //                        ],
                            [
                                'class' => 'kartik\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url) {

                                        return Html::a('<i class="fas fa-eye fa-lg "></i>',
                                                       $url,
                                                       [
                                                           'title' => Yii::t('backend', 'View'),'id'=>'popRes'
                                                       ]);
                                    },
                                ],
                                'visibleButtons' => [
                                    'login' => Yii::$app->user->can('administrator')
                                ]
                            ],
                            [
                                'attribute' => 'notes',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return '<a class="badge bg-success ">'
                                        .$model->notes.'</a>';
                                }
                            ],
                        ];
                        // the GridView widget (you must use kartik\grid\GridView)
                        $layout = <<< HTML
<div class="float-right">
    <span style="color:red">{summary}</span>
</div>
{custom}
<div class="clearfix"></div>
{items}
{pager}
HTML;

                    ?>


                            <!-- Tab panes -->

                            <script>
                                $('.activity-view-link').click(function() {
                                    var elementId = $(this).closest('tr').data('key');
                                });
                            </script>


                            <!--suppress ALL -->

                            <?php







                                $this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modal').modal('show').find('.modal-content')
     .load($(this).attr('href'));
   });
});");
                                $this->registerJs("$(function() {
   $('a#popRes').click(function(e) {
     e.preventDefault();
     $('#modal2').modal('show').find('.modal-content')
     .load($(this).attr('href'));
   });
});");


                                $timingbutton=Yii::$app->request->post('timing-button') ?  Yii::$app->request->post('timing-button') : null;

                                if($timingbutton){
                                    if($timingbutton!='allday') {
                                        $dataProvider = $allDataProviders[$timingbutton];
                                        $takenChairsCount=$allTakenChairs[$timingbutton];


                                    }
                                }

                                $form = ActiveForm::begin(['id' => 'day-widget','options' => ['data-pjax' => true ]]);
                                $buttonsHTML='';
                                ?>


                    <?php

                                foreach ($timesHours as $time) {

                                   $buttonsHTML.=Html::submitButton(Yii::t('backend', $time),
                                                              [
                                                                  'class' => 'btn '.($timingbutton==$time ? 'bg-teal' :
                                                                          'bg-info'),
                                                                  'name' => 'timing-button',
                                                                  'value' => $time
                                                              ]);



                                }
                                    $buttonsHTML.=Html::submitButton(Yii::t('backend', 'All Day'),
                                                            [
                                                                'class' => 'btn bg-teal btn-flat',
                                                                'name' => 'timing-button',
                                                                'value' => 'allday'
                                                            ]);


                    ?>


                                <?php
                                $gridFooter="  <h3>Total capacity left for <a class=\"btn btn-info\">$timingbutton</a>: $takenChairsCount</br></h3>
                            <!--h4>Total places bought for this day: $takenChairsCount</br></h4-->
                            <h5>Total capacity for this product: $currentProduct->capacity</br></h5>";
                                $prodId=Yii::$app->request->get('prodId');

                                echo GridView::widget([
                                                          'id' => 'wholeday',
                                                          'dataProvider' => $dataProvider,
                                                          'columns' => $gridColumns,
                                                          'layout' => $layout,
                                                          //                        'pjax' => true,
                                                          //                        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-all']],
                                                          'toolbar' => [
                                                              [
                                                                  'content' =>
                                                                      Html::a(Yii::t('app', ' {modelClass}', [
                                                                          'modelClass' => '<i class="fa fa-pencil-alt"></i>',
                                                                      ]), ['/Reservations/reservations/createfortime?prodId='.$prodId.'&date='.Yii::$app->request->get('date').'&time='.$timingbutton,
                                                                          ], ['class' => 'btn btn-info',
                                                                                           'id' => 'popupModal']),

                                                                  'options' => ['class' => 'btn-group mr-2']
                                                              ],
                                                              '{export}',
                                                          ],
                                                          // set export properties

                                                          'bordered' => true,
                                                          'striped' => true,
                                                          'panel' => [
                                                              'heading' => "

                                <div class=\"card-tools float-left\">".$buttonsHTML."</div></div>",

                                                              'footer' =>  $timingbutton&&($timingbutton!='allday') ?
                                                              $gridFooter : null,
                                                          ],

                                                      ]);
                                ActiveForm::end();

                                Pjax::end();



                            ?>


                </div>



            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->

    </div>

    <!-- /.col -->
</div>




