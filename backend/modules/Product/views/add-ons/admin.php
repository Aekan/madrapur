<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $gridColumns
 */

use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

$gridColumns = [
    'id',
    'name',
    'type',
    'icon',
    'price',
    [
        'class' => 'kartik\grid\ActionColumn',
        'visible' => Yii::$app->user->can('administrator'),

        'template' => '{edit} {delete}',
        'buttons' => [
            'delete' => function ($url, $searchModel, $key) {

                return Html::a(
                    '<span class="fa fa-lg fa-trash"></span>',
                    Url::to([
                        'add-ons/admin',
                        'id' => $searchModel->id,
                        'action' => 'delete'
                    ]),
                    [
                        'title' => Yii::t('app', 'Delete'),
                        'data-pjax' => '1',
                        'data' => [
                            'method' => 'post',
                            'confirm' => Yii::t('app', 'Are you sure you want to delete?'),
                            'pjax' => 1,
                        ],
                    ]
                );

            },
            'edit' => function ($url, $model, $key) {

                return Html::a(
                    '<span class="fa fa-lg fa-pencil-alt"></span>',
                    Url::to([
                        'add-ons/update',
                        'prodId' => $model->id,
                        'id'=>$model->id,
                        'action' => 'update'
                    ]),
                    [
                        'title' => Yii::t('app', 'Update'),
                        'data-pjax' => '1',
                        'data' => [
                            'method' => 'post',
                            'pjax' => 1,
                        ],
                    ]
                );
            },
        ]
    ],
];
?>

<div class="row">
    <div class="col-12">
        <!-- interactive chart -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="far fa-chart-bar"></i>
                    Add-ons Manager
                </h3>
                ​
                <div class="card-tools">

                </div>
            </div>
            <div class="card-body">
                ​<?php

                echo Html::a("+", Url::to(["add-ons/create"]), [
                    "class" => "btn btn-primary pull-right", "id" => "modal-button"
                ]);

                Pjax::begin([
                    'id' => 'grid-pjax'
                ]);

                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'layout' => '{items}{pager}'
                ]);

                Pjax::end();

                Modal::begin([
                    'id' => 'modal',
                    'size' => 'modal-lg',
                ]);

                Pjax::begin([
                    'id' => 'modal-inner-pjax'
                ]);

                ?>
                <div id="modal-content">
                    <div class="row add-ons-create" id="add-ons-create">
                        <div class="col-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="far fa-chart-bar"></i>
                                        Create Add-on
                                    </h3>
                                    ​
                                    <div class="card-tools">

                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php

                                    $form = ActiveForm::begin([
                                        'id' => 'create-add-on',
                                        'class' => 'form-inline',
                                        'options' => ['data-pjax' => true],
                                    ]);

                                    echo $form->field($searchModel, 'name')
                                        ->textInput([
                                            'name' => 'name',
                                            'id' => 'name',
                                        ]);

                                    echo $form->field($searchModel, 'icon')
                                        ->textInput([
                                            'name' => 'icon',
                                            'id' => 'icon',
                                        ]);

                                    echo $form->field($searchModel, 'type')
                                        ->widget(Select2::class, [
                                            'name' => 'type',
                                            'id' => 'type',
                                            'data' => [
                                                'simple' => 'Simple add-on',
                                                'sub-capacity' => 'Sub-capacity add-on',
                                            ],
                                            'options' => [
                                                'placeholder' => 'Choose an add-on type...'
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ]
                                        ]);

                                    echo $form->field($searchModel, 'price')
                                        ->textInput([
                                            'name' => 'price',
                                            'id' => 'price',
                                        ]);

                                    echo Html::submitButton("Create add-on", [
                                        'class' => 'btn btn-primary',
                                        'name' => 'create-add-on',
                                        'id' => 'create-add-on',
                                        'value' => 'save'
                                    ]);

                                    ActiveForm::end();

                                    ?>
                                </div>
                                <!-- /.card-body-->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>

                <?php

                Pjax::end();

                Modal::end();

                $this->registerJs("$(function() {
                    $('#modal-button').click(function(e) {
                        e.preventDefault();
                        $('#modal').modal('show');
                    });
                    
                    $('#modal').on('hidden.bs.modal', function() {
                        $.pjax.reload({container:'#grid-pjax'});
                    });
                    
                    $('#modal').on('shown.bs.modal', function() {
                        $('input#name').trigger('focus');
                    });
                    
                    $(document).on('pjax:end', function(e) {
                        $('#modal').modal('hide');
                    });
                });");

                ?>
            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>