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

    $form = ActiveForm::begin(['id' => 'product-form']);


    ?>


    <?php
    $allMyProducts=\backend\modules\Product\models\Product::find()->all();
    echo $form->field($model, 'title')

        ->dropDownList(\yii\helpers\ArrayHelper::map($allMyProducts, 'id', 'title'),
            ['prompt'=>'Please select a product']);

    ?>
    <?= $form->field($model, "start_date")->widget(Datecontrol::class, [
        'type' => DateControl::FORMAT_DATE,
        'ajaxConversion' => false,
        'autoWidget' => true,
        'displayFormat' => 'php:Y-m-d',
        'options' => [
            'pluginOptions' => [
                'autoclose' => true
            ]
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
        var_dump($myPrices);
        echo '</br>';
        foreach($myPrices as $i=>$price){
            echo $price->name;
            echo $form->field($model, "description[$i]")->widget(\kartik\touchspin\TouchSpin::class,['options' => ['placeholder' => 'Adjust ...'],]);


        }


         ActiveForm::end();
    }





    ?>

</div>


    <script>
        function myFunction(item, index) {


            $('#product-times').html($('#product-times').html()+'<option>'+item['name']+'</option>');

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

    </script>

</div>