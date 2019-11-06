<?php

    use kartik\datecontrol\DateControl;
    use kartik\file\FileInput;
    use wbraganca\dynamicform\DynamicFormWidget;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    \kartik\datetime\DateTimePickerAsset::register($this);

?>
<?php

?>


<?php

    if (Yii::$app->session->hasFlash('error')) {
        echo '<p class="has-error flashes"><span class="help-block help-block-error">' . Yii::$app->session->getFlash('error') . '</span></p>';
    } elseif (Yii::$app->session->hasFlash('success')) {
        echo '<p class="has-success flashes"><span class="help-block help-block-success">' . Yii::$app->session->getFlash('success') . '</span></p>';
    }

    $form = ActiveForm::begin(
        [
            'id' => 'product-edit',
            'action' => 'update?prodId=' . $prodId,
            'options' => ['class' => 'product-edit', 'enctype' => 'multipart/form-data'],


        ]
    ); ?>


<div class="tab-content">
    <div class="tab-pane active" id="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h4><i class="glyphicon glyphicon-time"></i> <?= Yii::t('app', 'Product Details') ?>
                    <?= Html::submitButton('Update Product', ['class' => 'btn btn-primary prodUpdateBtn']) ?>
                </h4>
            </div>
            <div class="card-body">
                <?= $form->field($model, 'isStreet')
                    ->dropDownList(['yes' => 'yes', 'no' => 'no']);

                ?>


                <?= $form->field($model, 'currency')->dropDownList(array('HUF' => 'HUF', 'EUR' => 'EUR',), array('options' => array('HUF' => array('selected' => true)))); ?>
                <?= $form->field($model, 'status')->dropDownList(array('active' => 'active', 'inactive' => 'inactive',), array('options' => array('active' => array('selected' => true)))); ?>
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'description')->widget(
                    froala\froalaeditor\FroalaEditorWidget::className(), [
                    'clientOptions' => [

                        'toolbarInline' => false,

                        'theme' => 'royal', //optional: dark, red, gray, royal
                        'language' => 'en_gb' // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                    ]
                ]
                ) ?>
                <?= $form->field($model, 'short_description')->widget(
                    froala\froalaeditor\FroalaEditorWidget::className(), [
                    'clientOptions' => [
                        'toolbarInline' => false,
                        'theme' => 'royal', //optional: dark, red, gray, royal
                        'language' => 'en_gb' // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                    ]
                ]
                ) ?>
                <?= $form->field($model, 'category')->textInput(['maxlenght' => 60]) ?>
                <?= $form->field($model, 'capacity')->textInput(['maxlenght' => 60]) ?>
                <?= $form->field($model, 'duration')->textInput(['maxlenght' => 60]) ?><?= '(in minutes)' ?>
                <?= $form->field($model, 'thumbnail')->textInput(['maxlenght' => 255]) ?>

                <?= $form->field($model, 'images')->widget(
                    FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => ['allowedFileExtensions' => ['jpg', 'gif', 'png'], 'showUpload' => false]
                ]
                ) ?>

                <?php // (!$model->isNewRecord && $model->image!='')?Html::img(Yii::$app->params['productsPictures'] . $model->image, ['style'=>'max-width: 300px;']):''; ?>



                <?= $form->field($model, 'start_date')->widget(
                    DateControl::classname(), [
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'displayFormat' => 'php:Y-m-d',
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ]
                ); ?>

                <?= $form->field($model, 'end_date')->widget(
                    DateControl::classname(), [
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    /*'displayFormat' => 'php:Y-m-d H:i',
                    'type'=>DateControl::FORMAT_DATETIME,*/
                    'displayFormat' => 'php:Y-m-d',
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ]
                ); ?>


                <?= Html::submitButton('Termék Frissítése', ['class' => 'btn btn-primary']) ?>

            </div>
        </div>
    </div>

    <div class="tab-pane" id="meta">
    </div>
    <div class="tab-pane" id="prices">
        <?php

        ?>
        <?php DynamicFormWidget::begin(
            [
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 999, // the maximum times, an element can be cloned (default 999)
                'min' => 0, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelPrices[0],
                'formId' => 'product-edit',
                'formFields' => [
                    'name',
                    'description',
                ],
            ]
        ); ?>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h4>
                    <i class="fas fa-euro-sign  "></i> <?= Yii::t('app', 'Product $Prices') ?>
                    <?=
                        Html::submitButton(
                            'Update Product', [
                            'class'
                            => 'btn btn-primary prodUpdateBtn'
                        ]
                        ) ?>
                    <button type="button" class="add-item btn btn-primary "><i
                                class="fas fa-plus-circle  "></i> <?= Yii::t(
                            'app',
                            'Add'
                        )
                        ?></button>
                </h4>
            </div>


            <div class="card-body">
                <div class="container-items"><!-- widgetContainer -->
                    <?php foreach ($modelPrices as $i => $modelPrice): $uniqid = uniqid(); ?>
                        <div class="item card card-default card-primary"><!-- widgetBody -->
                            <div class="card-header">
                                <h3 class="card-title pull-left"><?= Yii::t('app', 'Price') ?></h3>
                                <div class="float-right">
                                    <button type="button" class="add-item btn btn-primary btn-xs"><i
                                                class="fas fa-plus-circle fa-2x"></i></button>
                                    <button type="button" class="remove-item btn btn-danger btn-xs"><i
                                                class="fas fa-minus fa-2x"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="tabs<?= $uniqid ?>" class="card-body">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#prices<?= $uniqid ?>" data-toggle="tab"
                                                          class="pricestab"><?php /*echo Yii::t('app', 'Tartalom')
 */?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="prices<?= $uniqid ?>">
                                        <?php
                                            // necessary for update action.

                                            echo Html::activeHiddenInput($modelPrice, "[{$i}]id");

                                        ?>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <?= $form->field($modelPrice, "[{$i}]name")->textInput(); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <?= $form->field($modelPrice, "[{$i}]description")->textInput(); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <?= $form->field($modelPrice, "[{$i}]price")->textInput(); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <?= $form->field($modelPrice, "[{$i}]hufPrice")->textInput(); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <?= $form->field($modelPrice, "[{$i}]discount")->textInput() ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <?= $form->field($modelPrice, "[{$i}]start_date")->widget
                                                (\kartik\date\DatePicker::class, [
                                    //'id' => 'products-blockoutsdates',

                                    'type' => \kartik\date\DatePicker::TYPE_COMPONENT_PREPEND,
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'autoclose' => true,
                                    ]
                                ]);?>

                                            </div>
                                            <div class="col-sm-4">
                                                <?= $form->field($modelPrice, "[{$i}]end_date")->widget
                                                (\kartik\date\DatePicker::class, [
                                                    //'id' => 'products-blockoutsdates',

                                                    'type' => \kartik\date\DatePicker::TYPE_COMPONENT_PREPEND,
                                                    'pluginOptions' => [
                                                        'format' => 'yyyy-mm-dd',
                                                        'autoclose' => true,
                                                    ]
                                                ]); ?>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>
        <?php DynamicFormWidget::end(); ?>
    </div>

    <div class="tab-pane" id="times">


        <?php

            DynamicFormWidget::begin(
                [
                    'widgetContainer' => 'dynamicform_wrapper_times', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items-times', // required: css class selector
                    'widgetItem' => '.item-times', // required: css class
                    'limit' => 10, // the maximum times, an element can be cloned (default 999)
                    'min' => 0, // 0 or 1 (default 1)
                    'insertButton' => '.add-item-times', // css class
                    'deleteButton' => '.remove-item-times', // css class
                    'model' => $modelTimes[0],
                    'formId' => 'product-edit',
                    'formFields' => [
                        'name',
                    ],
                ]
            );
        ?>

        <div class="card card-primary card-outline ">

            <div class="card-header">
                <h4><i class="fas fa-clock"></i> <?= Yii::t('app', 'Product Times') ?>
                    <?=
                        Html::submitButton(
                            'Update Product', [
                                                'class'
                                                => 'btn btn-primary prodUpdateBtn'
                                            ]
                        ) ?>
                    <button type="button" class="add-item-times btn btn-primary "><i
                                class="fas fa-plus-circle  "></i> <?= Yii::t(
                            'app',
                            'Add'
                        )
                        ?></button>

                </h4>
            </div>
            <div class="card-body">
                <div class="container-items-times"><!-- widgetContainer -->
                    <?php foreach ($modelTimes as $i => $modelTime): ?>
                        <div class="item-times card card-default card-primary"><!-- widgetBody -->
                            <div class="card-header">

                                <h3 class="card-title pull-left"><?= Yii::t('app', 'Time') ?></h3>
                                <div class="float-right">
                                    <button type="button" class="add-item-times btn btn-primary btn-xs"><i
                                                class="fas fa-plus-circle fa-2x"></i></button>
                                    <button type="button" class="remove-item-times btn btn-danger btn-xs"><i
                                                class="fas fa-minus fa-2x"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="card-body">
                                <?php
                                    // necessary for update action.

                                    echo Html::activeHiddenInput($modelTime, "[{$i}]id");

                                    echo $form->field($modelTime, "[{$i}]product_id")->hiddenInput(['value' => $model->id])->label(false);
                                ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?= $form->field($modelTime, "[{$i}]name")->textInput(['class' => 'form-control']) ?>
                                    </div>
                                    <div class="col-sm-12">

                                    </div>
                                    <div class="col-sm-6">

                                        <?= $form->field($modelTime, "[{$i}]start_date")->widget(
                                            DateControl::class, [
                                            'type' => DateControl::FORMAT_DATE,
                                            'ajaxConversion' => false,
                                            'autoWidget' => true,
                                            'displayFormat' => 'php:Y-m-d',
                                            'options' => [
                                                'pluginOptions' => [
                                                    'autoclose' => true
                                                ]
                                            ]
                                        ]
                                        ); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($modelTime, "[{$i}]end_date")->widget(
                                            DateControl::classname(), [
                                            'type' => DateControl::FORMAT_DATE,
                                            'ajaxConversion' => false,
                                            'autoWidget' => true,
                                            'displayFormat' => 'php:Y-m-d',
                                            'options' => [
                                                'pluginOptions' => [
                                                    'autoclose' => true
                                                ]
                                            ]
                                        ]
                                        ); ?>
                                    </div>
                                </div><!-- .row -->
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>
        <?php DynamicFormWidget::end(); ?>


        <?php ActiveForm::end(); ?>


        <?php
            $this->registerJs(
                '
    $(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
        console.log("beforeInsert");
    });

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        console.log("afterInsert");
        /*list=item.getElementsByTagName("input");
        for (var i = 0; i < list.length; i++) {
            console.log(list[i].id); //second console output
            //console.log(list[i].value);
        }*/
        list=item.getElementsByClassName("tab-pane");
        for (var i = 0; i < list.length; i++) {
            var arr = list[i].id.split("-");
            if(arr[0]=="pricestranslate")
                $(".pricestranslatetab").last().attr("href","#"+list[i].id);
            else
                $(".pricestab").last().attr("href","#"+list[i].id);
        }
    });

    $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
        if (! confirm("Biztos, hogy törölni akarod?")) {
            return false;
        }
        return true;
    });

    $(".dynamicform_wrapper").on("afterDelete", function(e) {
        console.log("Deleted item!");
    });

    $(".dynamicform_wrapper").on("limitReached", function(e, item) {
        alert("Limit elérve");
    });
    '
            );

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
    <div class="tab-pane" id="sources">


        <?php

            DynamicFormWidget::begin(
                [
                    'widgetContainer' => 'dynamicform_wrapper_sources', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items-sources', // required: css class selector
                    'widgetItem' => '.item-sources', // required: css class
                    'limit' => 10, // the maximum times, an element can be cloned (default 999)
                    'min' => 0, // 0 or 1 (default 1)
                    'insertButton' => '.add-item-sources', // css class
                    'deleteButton' => '.remove-item-sources', // css class
                    'model' => $modelSources[0],
                    'formId' => 'product-edit',
                    'formFields' => [
                        'name',
                    ],
                ]
            );
        ?>

        <div class="card card-primary card-outline">

            <div class="card-header">
                <h4><i class="glyphicon glyphicon-file"></i> <?= Yii::t('app', 'Product Sources') ?>
                    <?= Html::submitButton('Update Product', ['class' => 'btn btn-primary prodUpdateBtn']) ?>
                    <button type="button" class="add-item-sources btn btn-primary "><i
                                class="fas fa-plus-circle  "></i> <?= Yii::t(
                            'app',
                            'Add'
                        )
                        ?></button>

                </h4>
            </div>
            <div class="card-body">
                <div class="container-items-sources"><!-- widgetContainer -->
                    <?php foreach ($modelSources as $i => $modelSource): ?>
                        <div class="item-sources card card-default card-primary"><!-- widgetBody -->
                            <div class="card-header">
                                <h3 class="card-title pull-left"><?= Yii::t('app', 'Price') ?></h3>
                                <div class="float-right">
                                    <button type="button" class="add-item-sources btn btn-primary btn-xs"><i
                                                class="fas fa-plus-circle fa-2x"></i></button>
                                    <button type="button" class="remove-item-sources btn btn-danger btn-xs"><i
                                                class="fas fa-minus fa-2x"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="card-body">
                                <?php
                                    // necessary for update action.

                                    echo Html::activeHiddenInput($modelSource, "[{$i}]id");

                                    echo $form->field($modelSource, "[{$i}]product_id")->hiddenInput(['value' => $model->id])->label(false);
                                ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?= $form->field($modelSource, "[{$i}]name")->textInput(['class' => 'form-control']) ?>
                                    </div>
                                    <div class="col-sm-12">
                                        <?= $form->field($modelSource, "[{$i}]url")->textInput(['class' => 'form-control']) ?>
                                    </div>
                                    <div class="col-sm-12">
                                        <?= $form->field($modelSource, "[{$i}]prodIds")->textInput(['class' => 'form-control']) ?>
                                    </div>
                                    <div class="col-sm-12">
                                        <?= $form->field($modelSource, "[{$i}]color")->widget(
                                            \kartik\color\ColorInput::class, [
                                            'options' => ['placeholder' => 'Select color ...'],
                                        ]
                                        ); ?>
                                    </div>


                                </div><!-- .row -->
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>
        <?php DynamicFormWidget::end(); ?>




        <?php
            $this->registerJs(
                '
    $(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
        console.log("beforeInsert");
    });

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        console.log("afterInsert");
        /*list=item.getElementsByTagName("input");
        for (var i = 0; i < list.length; i++) {
            console.log(list[i].id); //second console output
            //console.log(list[i].value);
        }*/
        list=item.getElementsByClassName("tab-pane");
        for (var i = 0; i < list.length; i++) {
            var arr = list[i].id.split("-");
            if(arr[0]=="pricestranslate")
                $(".pricestranslatetab").last().attr("href","#"+list[i].id);
            else
                $(".pricestab").last().attr("href","#"+list[i].id);
        }
    });

    $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
        if (! confirm("Biztos, hogy törölni akarod?")) {
            return false;
        }
        return true;
    });

    $(".dynamicform_wrapper").on("afterDelete", function(e) {
        console.log("Deleted item!");
    });

    $(".dynamicform_wrapper").on("limitReached", function(e, item) {
        alert("Limit elérve");
    });
    '
            );

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

    <div class="tab-pane" id="timetable">
        <?php

        ?>


        <div class="card card-primary card-outline">
            <div class="card-header">
                <h4>
                    <i class="glyphicon glyphicon-euro"></i> <?= Yii::t('app', 'TimeTable') ?>    <?= Html::submitButton('Termék Frissítése', ['class' => 'btn btn-primary prodUpdateBtn']) ?>
                    <button type="button" class="add-item btn btn-info btn-sm float-right"><i
                                class="glyphicon glyphicon-plus"></i> <?= Yii::t('app', 'Új') ?></button>
                </h4>
            </div>
            <div class="card-body">
                <script>

                    var prodId =<?=$prodId?>;

                </script>
                <?php

                    $JSEventClick = <<<EOF
function(calEvent, jsEvent, view) {
    
    var m=new Date(calEvent.start)
    var Year=m.getUTCFullYear();
    var Month=m.getUTCMonth()+1;
    var Day=m.getUTCDate();
    if(Month<10)Month='0'+Month;
    if(Day<10)Day='0'+Day;
    dateString = Year+'-'+Month+'-'+Day;

    urlToGoTo="/Reservations/reservations/daye?date="+dateString+"&prodId="+prodId;
    //console.log(view);
    window.location.href=urlToGoTo
    //alert('Event: ' + urlToGoTo);

    //alert('Source: ' + calEvent.nonstandard['field1']);
    // change the border color just for fun
    $(this).css('border-color', 'red');
}
EOF;

                    $events = array();
                    //Testing
                    $Event = new \yii2fullcalendar\models\Event();
                    $Event->id = 1;
                    $Event->title = 'Testing';
                    $Event->start = date('Y-m-d\TH:i:s\Z');
                    $Event->nonstandard = [
                        'field1' => 'Something I want to be included in object #1',
                        'field2' => 'Something I want to be included in object #2',
                    ];
                    $events[] = $Event;

                    $Event = new \yii2fullcalendar\models\Event();
                    $Event->id = 2;
                    $Event->title = 'Testing';
                    $Event->start = date('Y-m-d\TH:i:s\Z', strtotime('tomorrow 6am'));
                    $events[] = $Event;

                ?>

                <?= \yii2fullcalendar\yii2fullcalendar::widget(
                    array(
                        'events' => $modelEvents,
                        'clientOptions' => [
                            'locale' => 'hu',
                            'eventClick' => new \yii\web\JsExpression($JSEventClick),

                        ]
                    )
                );

                ?>


            </div>


        </div>

    </div>

    <div class="tab-pane" id="add-ons">
        <?php

        ?>

        <div
                class="row">
            <div
                    class="col-12">
                <!-- interactive chart -->
                <div
                        class="card card-primary card-outline">
                    <div
                            class="card-header">
                        <h4>

                            <i class="fas fa-plus fa-lg fa-fw  "></i>Add-ons
                            <?=
                                Html::submitButton(
                                    'Update Product', [
                                                        'class'
                                                        => 'btn btn-primary prodUpdateBtn'
                                                    ]
                                ) ?>
                        </h4>
                        ​
                        <div
                                class="card-tools">

                        </div>
                    </div>
                    <div
                            class="card-body">
                        ​​<?php

                            foreach ($modelAddOns as $i => $modelAddOn): ?>
                                <div class="row item-times">
                                    <div class="col-12">
                                        <!-- interactive chart -->
                                        <?php
                                            echo $form->field(
                                                $modelAddOn, "[{$i}]id"
                                            )->checkbox([
                                                            'value' => $modelAddOn->id,
                                                            'checked' => in_array($modelAddOn->id, $selectedModelAddOns),
                                                        ])->label($modelAddOn->name);
                                        ?>
                                        ​
                                    </div>

                                    <!-- /.col -->
                                </div>

                            <?php endforeach; ?>

                    </div>
                    <!-- /.card-body-->
                </div>
                <!-- /.card -->
                ​
            </div>

            <!-- /.col -->
        </div>

    </div>



