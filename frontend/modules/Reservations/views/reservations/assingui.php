<?php
    /**
     * Created by PhpStorm.
     * User: ROG
     * Date: 2019. 02. 05.
     * Time: 20:38
     */

    use backend\components\extra;
    use backend\modules\Product\models\Product;
    use backend\modules\Product\models\ProductAdminSearchModel;
    use kartik\date\DatePicker;
    use kartik\helpers\Html;
    use kartik\select2\Select2;
    use yii\web\JsExpression;
    use yii\web\View;
    use yii\widgets\ActiveForm;

?>




<?php

    $format = <<< SCRIPT

function format(state) {
    if (!state.id) return state.text; // optgroup
    src =productimages[state.id.toLowerCase()]
    return '<img class="flag" style="width:20px;height:20px;" src="' + src + '"/>' + state.text;
}

SCRIPT;

    $escape = new JsExpression("function(m) { return m; }");
    $this->registerJs($format, View::POS_HEAD);

?>
<?php

?>
<!--suppress ALL -->



    <!--suppress ALL -->
    <div class="reservations-info-index">
        <style>
            td {
                border: 1px solid black;
                padding: 3px;
            }
            select {
                display: block !important;
            }
        </style>
        <div class="panel">
            <div class="panel-body">


                <?php
                    $productModel = new Product();
                    $searchModel = new ProductAdminSearchModel();
                    $userModel = new \common\models\User();
                    $allUsers = $userModel->find()->all();
                    $streetSellers = [];
                    $hotelSellers = [];

                    foreach ($allUsers as $user) {
                        $authManager = \Yii::$app->getAuthManager();
                        $hasStreetBool = $authManager->getAssignment('streetSeller', $user->id) ? true : false;
                        $hasHotelBool = $authManager->getAssignment('hotelSeller', $user->id) ? true : false;
                        if ($hasStreetBool) {
                            $streetSellers[] = $user;

                        };
                        if ($hasHotelBool) {
                            $hotelSellers[]  = $user;

                        };
                    }
                    $data = [];
                    $data2 = [];
                    $images = [];
                    foreach ($streetSellers as $user) {
                        $data[$user->id] = $user->username;
                        $images[$user->id] = $user->userProfile->avatar;
                    }
                    foreach ($hotelSellers as $user) {
                        $data2[$user->id] = $user->username;
                        $images[$user->id] = $user->userProfile->avatar;
                    }

                ?>





                Selling for somebody else?


                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <?php
                                echo Html::hiddenInput('reservation', $model->id);
                                echo Select2::widget( [
                                                          'name' => 'anotherSeller',
                                                          'data' => $data,
                                                          'id' => rand(),
                                                          'options' => ['placeholder' => 'Select a seller...'],
                                                          'pluginOptions' => [
                                                              'escapeMarkup' => $escape,
                                                              'allowClear' => true
                                                          ],
                                                      ]);?>
                        </div>
                        <div class="col-lg-4">

                            <?php




                                echo DatePicker::widget([
                                                            'name' => 'sellerCustomDate',
                                                            'type' => DatePicker::TYPE_BUTTON,
                                                            'value' => date('Y-m-d', time()),

                                                            'pluginOptions' => [
                                                                'format' => 'yyyy-mm-dd',
                                                                'autoclose'=>true
                                                            ]
                                                        ]);
                            ?>

                        </div>


                    </div>
                </div>



            </div>

        </div>
    </div>