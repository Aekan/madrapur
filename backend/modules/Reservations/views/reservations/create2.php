
<?php
    /**
     * Created by PhpStorm.
     * User: ROG
     * Date: 2019. 02. 05.
     * Time: 20:38
     */

    use backend\assets\MaterializeWidgetsAsset;
use backend\modules\Product\models\AddOn;
use backend\modules\Product\models\Product;
use backend\modules\Product\models\ProductAddOn;
use backend\modules\Product\models\ProductPrice;
    use backend\modules\Reservations\models\Reservations;
    use backend\modules\Tickets\models\TicketSearchModel;
    use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
    use kartik\icons\Icon;
    use kartik\switchinput\SwitchInput;
    use kartik\touchspin\TouchSpin;
    use lo\widgets\Toggle;
    use yii\helpers\ArrayHelper;
    use yii\web\JsExpression;
    use yii\web\View;

    use yii\widgets\Pjax;
    MaterializeWidgetsAsset::register($this);



    //$this->params['breadcrumbs'][] = $this->title;

    $huf = Yii::$app->keyStorage->get('currency.huf-value') ? Yii::$app->keyStorage->get('currency.huf-value') : null;
    $oldTicketId=Yii::$app->request->get('ticketId');
?>

<!--suppress ALL -->
<style>
    .wrapper {
        overflow-y: hidden;
    }
</style>

<div class="row">
    <div class="col-12">
        <!-- interactive chart -->
        <?=$oldTicketId ? '<div class="card card-danger ">' : '<div class="card card-primary ">' ?>
            <div class="card-header">
                <i class="fas fa-ticket-alt  "></i>
                <?= $oldTicketId ? $oldTicketId : $a=TicketSearchModel::userNextTicketId(); ?>
            </div>

            <div class="card-body">
                <div class="products-index">

                    <?php
                        Pjax::begin();
                        if ($disableForm != 1) {
                            if ($newReservation) {

                                echo $newReservation;
                            }
                            $form = ActiveForm::begin(['id' => 'product-form','options' => ['data-pjax' => true ]]);

                            ?>


                            <?php
                            echo $form->field($model, 'title')
                                ->dropDownList(ArrayHelper::map($allMyProducts, 'id', 'title'),
                                               ['prompt' => 'Please select a product'])->label(false);


                            ?>
                            <!-- Compiled and minified JavaScript -->


                            <?= $form->field($model, "start_date")->widget(
                                DatePicker::class, [
                                'type' => DatePicker::TYPE_INLINE
                                , 'options' => [
                                'value' => date('Y-m-d', time()),
                                'class' => 'bg-gradient-primary '

                                ],
                                'pluginOptions' => [

                                    'autoclose' => true,

                                    'format' => 'yyyy-mm-dd',
                                    'startDate' => date(time()),

                                ],


                            ]); ?>









                            <?= $form->field($model, 'times')
                                ->dropDownList(['prompt' => 'Please select a time']);

                            ?>

                            <div class="panel">
                                <?=Toggle::widget(
                                    [
                                        'name' => 'paid_status', // input name. Either 'name', or 'model' and 'attribute'
                                        // properties must be specified.

                                        'checked' => true,
                                        'id'=>'paid_status',

                                        'options' => [
                                            'data-on'=>'Paid',
                                            'data-off'=>'Unpaid',
                                            'data-width'=>'100px',
                                            'data-onstyle'=>'info'
                                        ],

                                        // checkbox options. More data html options [see here](http://www.bootstraptoggle.com)
                                    ]
                                );?>



                                <?=   Toggle::widget(
                                    [
                                        'name' => 'paid_currency', // input name. Either 'name', or 'model' and 'attribute' properties must be specified.
                                        'checked' => false,
                                        'options' => [
                                            'data-on'=>'EUR',
                                            'data-off'=>'HUF',
                                            'data-width'=>'100px',
                                            'data-onstyle'=>'info'
                                        ],
                                        // checkbox options. More data html options [see here](http://www.bootstraptoggle.com)
                                    ]
                                );?>
                                <?=Toggle::widget(
                                    [
                                        'name' => 'paid_method', // input name. Either 'name', or 'model' and 'attribute' properties must be specified.
                                        'checked' => false,
                                        'id'=>'currency_selector',
                                        'options' => [
                                            'data-on'=>'Card',
                                            'data-off'=>'Cash',
                                            'data-width'=>'100px',
                                            'data-onstyle'=>'info'
                                        ],
                                        // checkbox options. More data html options [see here](http://www.bootstraptoggle.com)
                                    ]
                                );?>

                                <?php



                                ?>


                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-4">

                                        </div>

                                    </div>
                                </div>
                                <?=Html::hiddenInput('ticketId', $oldTicketId);?>


                            </div>


                            <div id="myTimes"></div>
                            <div id="myPrices"></div>
                            <?= Html::submitButton('Create Reservation', ['class' => 'create btn btn-block bg-aqua btn-lg btn-primary prodUpdateBtn']) ?>


                            <?php ActiveForm::end();

                            Pjax::end();

                        } else {
                            if (isset($_POST['paid_status'])) {
                                $paid_status = 'paid';

                            } else {
                                $paid_status = 'unpaid';
                            }

                            if (isset($_POST['paid_method'])) {
                                $paid_method = 'card';
                            } else {
                                $paid_method = 'cash';
                            }
                            if (isset($_POST['paid_currency'])) {
                                $paid_currency = 'EUR';
                            } else {
                                $paid_currency = 'HUF';
                            }
                            if(isset($_POST['ticketId'])){
                                $oldTicketId=$_POST['ticketId'];

                            }else{
                                $oldTicketId=null;

                            }

                            $form = ActiveForm::begin(['id' => 'product-form']);
                            $model = new ProductPrice();
                            # var_dump($myPrices);
                            echo '</br>';

                            // TODO fix this nonsense másmodelenátpushingolni egy Reservation
                            ?>
                            <?=Html::hiddenInput('paid_currency', $paid_currency);?>
                            <?=Html::hiddenInput('paid_status', $paid_status);?>
                            <?=Html::hiddenInput('paid_method', $paid_method);?>
                            <?=$oldTicketId ? Html::hiddenInput('ticketId', $oldTicketId) : null;?>


                            <?php





                            foreach ($myPrices as $i => $price) {

                                if ($paid_currency == 'HUF') {
                                    $price = ProductPrice::eurtohuf($price);
                                }

                                echo $price->name;

                                $currentProdId = (Yii::$app->request->post('Product'))['title'];
                                echo $form->field($model, "description[$i]")->widget(TouchSpin::class,
                                                                                     ['options' =>
                                                                                          [

                                                                                              'placeholder' => 'Adjust ...',
                                                                                              'data-priceid' => $price->id,
                                                                                              'autocomplete' => 'off',
                                                                                              'type'   => 'number'
                                                                                          ],
                                                                                      'pluginOptions' => [
                                                                                          'buttonup_txt'=>Icon::show('caret-square-up', ['class'=>'fa-lg
                                                    bg-info','framework'
                                                                                          =>Icon::FAS]),
                                                                                          'buttondown_txt'=>Icon::show('caret-square-down',
                                                                                                                       ['class'=>'fa-lg  bg-info','framework'
                                                                                                                       =>Icon::FAS]) ,

                                                                                          'max'=>'9999999'
                                                                                      ]
                                                                                     ]   )->label(false);

                            }
                            echo $form->field($model, 'product_id')->hiddeninput(['value' => $currentProdId])->label(false);
                            echo $form->field($model, 'booking_date')->hiddeninput(['autocomplete' => 'off', 'autocapitalize' => 'off', 'autocorrect' => 'off', 'value' => (Yii::$app->request->post('Product'))['start_date']])->label(false);
                            echo $form->field($model, 'time_name')->hiddeninput(['value' => (Yii::$app->request->post('Product'))['times']])->label(false);

                            echo $form->field($model, 'discount')->hiddeninput()->label(false);
                            ?>



                            <!-- interactive chart -->
                            <div class="card bg-primary  card-outline collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Seller Tools
                                    </h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool"
                                                data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body bg-gradient-white">
                                    <div class="col-lg-12">
                                        Custom Price
                                        <?= TouchSpin::widget(
                                            [   'name'=>'customPrice',
                                                'options' =>
                                                    [

                                                        'placeholder' => 'Adjust ...',
                                                        'data-priceid' => $price->id,
                                                        'autocomplete' => 'off',
                                                        'type'   => 'number',

                                                    ],
                                                'pluginOptions' => [
                                                    'buttonup_txt'=>Icon::show('caret-square-up', ['class'=>'fa-lg 
                                                    bg-info','framework'
                                                    =>Icon::FAS]),
                                                    'buttondown_txt'=>Icon::show('caret-square-down',
                                                                                 ['class'=>'fa-lg fa-lg bg-info','framework'
                                                    =>Icon::FAS]) ,

                                                    'max'=>'9999999'
                                                ]
                                            ]   );
                                        ?>

                                    </div>

                                    <div class="col-lg-12">

                                        <?php
                                            // echo Yii::$app->runAction('Reservations/assigner');

                                            echo $this->render('assingui',['model'=>new Reservations()]);

                                        ?>
                                    </div>

                                </div>
                                <!-- /.card-body-->
                            </div>

                            <div class="card bg-primary  card-outline collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-user-friends  "></i>
                                        Customer Details
                                    </h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool"
                                                data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body bg-gradient-white">
                                    <div class="col-lg-12">

                                                <input type="text" name="firstName" id="" class="form-control"
                                                       placeholder="First Name"
                                                       aria-describedby="helpId">
                                                <input type="text" name="lastName" id="" class="form-control"
                                                       placeholder="Last Name"
                                                       aria-describedby="helpId">
                                                <textarea name="orderNote" id="orderNote" class="form-control"
                                                       placeholder="orderNote"
                                                          aria-describedby="helpId" form="product-form"></textarea>


                                    </div>



                                </div>
                                <!-- /.card-body-->
                            </div>
                            <!-- /.card -->

                            <div class="card bg-primary  card-outline collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Add-ons
                                    </h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool"
                                                data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body bg-gradient-white">
                                    <div class="col-lg-12">
                                        <?php
                                            $addOnLinks = ProductAddOn::find()
                                                ->andFilterWhere(['=', 'prodId', $currentProdId])
                                                ->all();
                                            foreach ($addOnLinks as $i => $addOnLink) {
                                                $addOn = AddOn::findOne(['id' => $addOnLink->addOnId, 'type' => 'simple']);
                                                if ($addOn) {
                                                    $addOnPrice = $addOn->price;

                                                    if ($paid_currency == 'HUF') {
                                                        $addOnPrice = ProductPrice::eurtohufValue($addOnPrice);
                                                    }

                                                    echo $form->field(
                                                        $model, "[{$i}]id")->checkbox([
                                                        'value' => $addOnPrice,
                                                        'checked' => false,
                                                        'data-id' => $addOnLink->addOnId,
                                                        'data-add-on' => true,
                                                    ])->label("$addOn->name ($addOnPrice $paid_currency)");
                                                }
                                            }
                                        ?>

                                    </div>



                                </div>
                                <!-- /.card-body-->
                            </div>
                            <!-- /.card -->



                            <div class="col-lg-12 customPrice " contentEditable="true" editable="true">
                                <div class="box box-default box-solid ">
                                    <div class="box-header  bg-blue-gradient with-border">
                                        <h3 class="box-title"></h3>
                                        <div class="box-tools float-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                                <?=Icon::show('plus-circle', ['class'=>'fa-lg','framework'=>Icon::FA
                                                                              ,'style'=>'color:white'
                                                ])?>
                                            </button>
                                        </div>
                                        <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- /.box-body -->
                                </div>
                                <div class="col-lg-12">
                                    <?= \insolita\adminlte3\LteInfoBox::widget([

                                                                                   'bgColor' => 'white',
                                                                                   'number' => "<h4><div id=\"total_price\">0</div></h4>",
                                                                                   'text' => 'Total Price' . ' <strong>(' . $paid_currency . ')</strong>',
                                                                                   //                        'description'=>'asd',
                                                                                   'icon' => 'fa fa-cart-plus',
                                                                                   'showProgress' => true,
                                                                                   'progressNumber' => 100,


                                                                               ]);
                                    ?>
                                </div>'
                            </div>



                            <?php
                            echo'<div class="col-lg-12">';
                            echo Html::submitButton('Create Reservation', ['class' => 'btn btn-block bg-aqua btn-lg btn-primary prodUpdateBtn']);
                            echo'</div>';
                            ActiveForm::end();
                        }

                        //

                    ?>
                </div>


            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->

    </div>

    <!-- /.col -->
</div>






<?php
    $toggledisplay = <<< SCRIPT

         function toggleshowcPrice(){
        if($('.customPrice').css('display')!='none'){
            $('.customPrice').hide();
        }
        else{
            $('.customPrice').show();
        }

    }
    
     $().ready(() => {
     
        $('.bootstrap-switch-label').on('click',toggleshowcPrice);
        $('.bootstrap-switch-handle-on').on('click',toggleshowcPrice);
 
     })



SCRIPT;


    $this->registerJs($toggledisplay, View::POS_HEAD);

?>


<script>
    var countPrices =<?=$countPrices?>;

    function myFunction(item, index) {


        $('#product-times').html($('#product-times').html() + '<option>' + item['name'] + '</option>');
        $('#product-times option:eq(2)').attr('selected', 'selected');
        $('#product-times option:eq(1)').attr('selected', 'selected');


    }


    function gatherPrices() {
        var PricesObj = {}; // note this
        var i = 0;
        while (i < countPrices) {
            PricesObj[$('#productprice-description-' + i).attr('data-priceid')] = $('#productprice-description-' + i).val();
            i = i + 1
        }

        return PricesObj;

    }


    $().ready(() => {






        $('#product-title').change(function () {
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl . '/Reservations/reservations/gettimes' ?>',
            type: 'post',
            data: {
                id: $(this).val(),

            },
            success: function (data) {
                console.log(data.search);

                mytimes = data.search
                $('#myTimes').html('');
                $('#product-times').html('<option>Please select a time</option>')
                mytimes.forEach(myFunction)


            }
        });
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl . '/Reservations/reservations/getprices' ?>',
            type: 'post',
            data: {
                id: $(this).val(),

            },
            success: function (data) {
                console.log(data.search);
                mytimes = data.search
                $('#myPrices').html(mytimes);


            }
        });
    });
    })
    ;

    function myFunction(item, index) {


        $('#product-times').html($('#product-times').html() + '<option>' + item['name'] + '</option>');

    }
    <?php

    $currentProdId = (Yii::$app->request->post('Product'))['title'];
    if (!$currentProdId) {
        $currentProdId = 0;
    }
    ?>

    function gatherPrices() {
        var PricesObj = {}; // note this
        var i = 0;
        while (i < countPrices) {
            PricesObj[$('#productprice-description-' + i).attr('data-priceid')] = $('#productprice-description-' + i).val();
            i = i + 1
        }

        return PricesObj;

    }

    function gatherAddOns() {
        var addOnsObj = {};
        var countAddOns = $("input[data-add-on]");

        countAddOns.each(function(idx, element) {
            if (element.checked) {
                addOnsObj[$(element).attr("data-id")] = element.value;
            }
        });

        console.log(addOnsObj);

        return addOnsObj;
    }

    var Total = 0;
    $('#product-form').change(function () {
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl . '/Reservations/reservations/calcprice' ?>',
            type: 'post',
            data: {
                prices: gatherPrices(),
                productId: $('#product-title').val(),
                date: $('#product-start_date').val(),
                time: $('#product-times').val(),
                prodid: <?=(Yii::$app->request->post('Product'))['title'] ? (Yii::$app->request->post('Product'))['title'] : 999 ?>,
                currency: '<?=isset($paid_currency) ? $paid_currency : 0 ?>',
                customPrice:$('input[name=customPrice]').val(),
                addOns: gatherAddOns(),
            },
            success: function (data) {
                console.log(data.buttonEnable);

                if(data.buttonEnable=='false'){
                    $('.create').prop( "disabled", true );
                }
                else{
                    $('.create').prop( "disabled", false );


                }

                mytimes = data.search;

                if(data.customPrice){

                    $('#total_price').html(data.customPrice);
                    mytimes = data.customPrice
                }
                $('#total_price').html( $('#total_price').html()+mytimes);
                $('#total_price').html(mytimes);

                // To be continued
                $('#productprice-discount').val(mytimes);
                if (data.response == 'places') {
                    $('#myPrices').html(mytimes);
                }


            }
        });
    });

    $().ready(() => {
        $('#product-start_date'
    ).attr('autocomplete', 'off');
    })
    ;


</script>

<style>
    #product-form {
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none;
        /* Non-prefixed version, currently
                                         supported by Chrome and Opera */
    }
</style>


