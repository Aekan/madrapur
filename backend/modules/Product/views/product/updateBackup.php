<?php

/* @var $this yii\web\View */

use kartik\form\ActiveForm;
use kartik\helpers\Html;

/* @var $model backend\modules\Products\models\Products */

//$this->title = Yii::t('app', 'Edit product');

//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Termékek'), 'url' => ['admin']];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Szerkesztés');
?>


<div class="row">
    <div class="col-12">
        <!-- interactive chart -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-pencil-alt fa-fw fa-lg "></i>

                    <?= '<u>' . $model->title . '</u>'?>
                </h3>
                <?php
                    $referrer = Yii::$app->request->referrer;

                    $form = ActiveForm::begin([
                                                  'id' => 'product-edit',
                                                  'action' => 'update?prodId=' . $prodId,
                                                  'options' => ['class' => 'product-edit', 'enctype' => 'multipart/form-data'],

                                              ]);



                ?>
                <div class="card-tools">




                    <?php

                        echo \yii\helpers\Html::a( 'Back', $referrer, [
                            'class' => "btn btn-primary float-right  " . ($referrer ? "" : " disabled"),
                        ]).' '.Html::submitButton('Update Product', ['class' => ' btn btn-primary prodUpdateBtn float-right']);

                    ?>
                </div>
            </div>
            <div class="card-body">
                <div class="products-update">

                    <?php




                    switch ($updateResponse) {
                        case 1:
                            $updateResponse = sessionSetFlashAlert('success','Product Update Successful');
                            break;

                    }




                    echo $this->render('editForm', [
                        'model' => $model,
                        'backenddata' => $backendData,
                        'prodId' => $prodId,
                        'modelTimes' => $modelTimes,
                        'modelPrices' => $modelPrices,
                        'modelEvents' => $modelEvents,
                        'modelSources' => $modelSources,
                        'modelAddOns' => $modelAddOns,
                        'selectedModelAddOns' => $selectedModelAddOns,
                        'form' => $form,
                    ]);


                    ActiveForm::end();

                    ?>

                </div>
               
            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->

    </div>   
 
    <!-- /.col -->
</div>

