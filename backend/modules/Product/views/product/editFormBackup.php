<?php

    use yii\widgets\ActiveForm;

    \kartik\datetime\DateTimePickerAsset::register($this);

?>
<?php

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
                    <li class="nav-item">
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


                <div class="tab-content">

                    <div class="tab-pane active"
                         id="content">

                        <?= $this->render(
                            "forms/content", [
                            "form" => $form,
                            "model" => $model,
                        ]
                        ); ?>

                    </div>

                    <div class="tab-pane"
                         id="meta">

                        <?= $this->render(
                            "forms/meta", [
                            "form" => $form,
                            "model" => $model,
                        ]
                        ); ?>

                    </div>

                    <div class="tab-pane"
                         id="prices">

                        <?= $this->render(
                            "forms/prices", [
                            "form" => $form,
                            "model" => $model,
                            "modelPrices" => $modelPrices
                        ]
                        ); ?>

                    </div>

                    <div class="tab-pane"
                         id="times">

                        <?= $this->render(
                            "forms/times", [
                            "form" => $form,
                            "model" => $model,
                            "modelTimes" => $modelTimes
                        ]
                        ); ?>

                    </div>

                    <div class="tab-pane"
                         id="sources">

                        <?= $this->render(
                            "forms/sources", [
                            "form" => $form,
                            "model" => $model,
                            "modelSources" => $modelSources
                        ]
                        ); ?>

                    </div>

                    <div class="tab-pane"
                         id="add-ons">
                        <?= $this->render(
                            "forms/add-ons", [
                            "form" => $form,
                            "model" => $model,
                            "prodId" => $prodId,
                            "modelAddOns" => $modelAddOns,
                            "selectedModelAddOns" => $selectedModelAddOns,
                        ]
                        ); ?>
                    </div>

                    <div class="tab-pane"
                         id="timetable">

                        <?= $this->render(
                            "forms/timetable", [
                            "form" => $form,
                            "model" => $model,
                            "prodId" => $prodId,
                            "modelEvents" => $modelEvents
                        ]
                        ); ?>

                    </div>

                    <?php
                        $this->registerJs(
                            '
        $(".dynamicform_wrapper_times").on("beforeInsert", function(e, item) {
            console.log("beforeInsert");
        });
    
        $(".dynamicform_wrapper_times").on("afterInsert", function(e, item) {
            console.log("afterInsert");
        });
    
        $(".dynamicform_wrapper_times").on("beforeDelete", function(e, item) {
            if (! confirm("Biztos, hogy törölni akarod?")) {
                return false;
            }
            return true;
        });
    
        $(".dynamicform_wrapper_times").on("afterDelete", function(e) {
            console.log("Deleted item!");
        });
    
        $(".dynamicform_wrapper_times").on("limitReached", function(e, item) {
            alert("Limit elérve");
        });
    '
                        );
                    ?>

                </div>

            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->

    </div>

    <!-- /.col -->
</div>
