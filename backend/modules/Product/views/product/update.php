<?php

    /* @var $this yii\web\View */

    /* @var $model backend\modules\Products\models\Products */



?>


<div class="row">
    <div class="col-12">
        <!-- interactive chart -->
        <div class="card card-primary card-outline">
            <div class="card-header">

                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a class="nav-link active"
                           href="#content"
                           data-toggle="tab"><?= Yii::t('app', 'Details') ?></a>
                    </li>
                    <!--    <li class="nav-item">-->
                    <!--        <a class="nav-link"-->
                    <!--           href="#meta"-->
                    <!--           data-toggle="tab">--><? //= Yii::t('app', 'Meta') ?><!--</a>-->
                    <!--    </li>-->
                    <li lass="nav-item">
                        <a class="nav-link"
                           href="#prices"
                           data-toggle="tab"><?= Yii::t('app', '$Prices') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="#sources"
                           data-toggle="tab"><?= Yii::t('app', 'Sources') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="#times"
                           data-toggle="tab"><?= Yii::t('app', 'Times') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="#add-ons"
                           data-toggle="tab"><?= Yii::t('app', 'Add-ons') ?></a>
                    </li>
                    <!--    <li class="nav-item">-->
                    <!--        <a class="nav-link"-->
                    <!--           href="#timetable"-->
                    <!--           data-toggle="tab">--><? //= Yii::t('app', 'TimeTable') ?><!--</a>-->
                    <!--    </li>-->
                </ul>


                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>

                </div>
            </div>
            <div class="card-body">
                <div class="products-update">


                    <h1><?= $this->title ?></h1>

                    <?php

                        switch ($updateResponse) {
                            case 1:
                                $updateResponse = sessionSetFlashAlert('success','Successful Product update');

                                break;
                            case 0:

                        }

                    ?>


                    <?=

                        $this->render('editForm', [
                            'model' => $model,
                            'backenddata' => $backendData,
                            'prodId' => $prodId,
                            'modelTimes' => $modelTimes,
                            'modelPrices' => $modelPrices,
                            'modelEvents' => $modelEvents,
                            'modelSources' => $modelSources,
                            'modelAddOns'=>$modelAddOns,
                            'selectedModelAddOns'=>$selectedModelAddOns

                            /*
                                    'modelTranslations' => $modelTranslations,

                                    'modelPrices' => $modelPrices,
                                    'modelTimes' => $modelTimes

                            */
                        ]) ?>


                </div>

            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->

    </div>

    <!-- /.col -->
</div>

