<?php
/**
 * Created by PhpStorm.
 * User: ROG
 * Date: 2019. 02. 05.
 * Time: 20:38
 */

use kartik\helpers\Html;
use backend\components\extra;
use yii\widgets\ActiveForm;

use kartik\datecontrol\DateControl;

$this->title = Yii::t('app', 'New Reservation');
$this->params['breadcrumbs'][] = $this->title;
?>

<!--suppress ALL -->
<div class="products-index">



    <?php
    if($disableForm!=1){
    if($newReservation){

        echo $newReservation;
    }
    $form = ActiveForm::begin(['id' => 'product-form']);


    ?>


    <?php
    $allMyProducts=\backend\modules\Product\models\Product::find()->all();
    echo $form->field($model, 'title')

        ->dropDownList(\yii\helpers\ArrayHelper::map($allMyProducts, 'id', 'title'),
            ['prompt'=>'Please select a product']);

    ?>
    <?= $form->field($model, "start_date")->widget(\dosamigos\datepicker\DatePicker::class, [
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
    ]); ?>
    <?= $form->field($model, 'times')

        ->dropDownList(  ['prompt'=>'Please select a time'] );

    ?>
    <?php
    // echo $form->field($model, 'id')->widget(\kartik\touchspin\TouchSpin::class,['options' => ['placeholder' => 'Adjust ...'],]);

    ?>


    <div id="myTimes"></div>
    <div id="myPrices"></div>
    <?= Html::submitButton('Create Reservation', ['class' => 'btn btn-primary prodUpdateBtn']) ?>


    <?php ActiveForm::end(); }
    else{
        $form = ActiveForm::begin(['id' => 'product-form']);
        $model=new \backend\modules\Product\models\ProductPrice();
       # var_dump($myPrices);
        echo '</br>';
        foreach($myPrices as $i=>$price){
            echo $price->name;
            $currentProdId=(Yii::$app->request->post('Product'))['title'];
            echo $form->field($model, "description[$i]")->widget(\kartik\touchspin\TouchSpin::class,['options' => ['placeholder' => 'Adjust ...','data-priceid'=>$price->id,'autocomplete'=>'off','type'=>'number']]);


        }
        echo $form->field($model,'product_id')->hiddeninput(['value' => $currentProdId])->label(false);
        echo $form->field($model,'booking_date')->hiddeninput(['autocomplete' => 'off', 'autocapitalize' => 'off', 'autocorrect' => 'off', 'value' => (Yii::$app->request->post('Product'))['start_date']])->label(false);
        echo $form->field($model,'time_name')->hiddeninput(['value' => (Yii::$app->request->post('Product'))['times']])->label(false);

        echo $form->field($model,'discount')->hiddeninput()->label(false);

        echo 'Total Price:<div id="total_price"></div>';
        echo Html::submitButton('Create Reservation', ['class' => 'btn btn-primary prodUpdateBtn']);
         ActiveForm::end();

    }



//

    ?>

</div>


    <script>
        var countPrices=<?=$countPrices?>;

        function myFunction(item, index) {


            $('#product-times').html($('#product-times').html()+'<option>'+item['name']+'</option>');

        }


        function gatherPrices() {
            var PricesObj = {}; // note this
            var i =0;
            while(i<countPrices){
                PricesObj[$('#productprice-description-'+i).attr('data-priceid')] =  $('#productprice-description-'+i).val();
                i=i+1
            }

            return PricesObj;

        }


        $('#product-title').change(function(){
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl. '/Reservations/reservations/gettimes' ?>',
                type: 'post',
                data: {
                   id:$(this).val(),

                },
                success: function (data) {
                    console.log(data.search);
                    mytimes=data.search
                    $('#myTimes').html('');
                    $('#product-times').html('');
                    mytimes.forEach(myFunction)


                }
            });
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl. '/Reservations/reservations/getprices' ?>',
                type: 'post',
                data: {
                    id:$(this).val(),

                },
                success: function (data) {
                    console.log(data.search);
                    mytimes=data.search
                    $('#myPrices').html(mytimes);



                }
            });
        });
        function myFunction(item, index) {


            $('#product-times').html($('#product-times').html()+'<option>'+item['name']+'</option>');

        }
        <?php

        $currentProdId=(Yii::$app->request->post('Product'))['title'];
        if(!$currentProdId){
            $currentProdId=0;

        }
        ?>

        function gatherPrices() {
            var PricesObj = {}; // note this
            var i =0;
            while(i<countPrices){
                PricesObj[$('#productprice-description-'+i).attr('data-priceid')] =  $('#productprice-description-'+i).val();
                i=i+1
            }

            return PricesObj;

        }

        var Total=0;
        $('#product-form').change(function(){
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl. '/Reservations/reservations/calcprice' ?>',
                type: 'post',
                data: {
                    prices:gatherPrices(),
                    productId:<?=$currentProdId?>,

                },
                success: function (data) {
                    console.log(data.search);
                    mytimes=data.search
                    $('#total_price').html(mytimes);
                    $('#productprice-discount').val(mytimes);



                }
            });
        });

        $().ready(() => {
            $('#product-start_date').attr('autocomplete', 'off');
        });


    </script>

    <style>
        #product-form {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome and Opera */
        }
    </style>

</div>

<div id="app-container"></div>