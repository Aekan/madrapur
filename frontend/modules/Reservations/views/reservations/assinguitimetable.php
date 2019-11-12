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

                $form = ActiveForm::begin([
                                              'id' => 'assign-reservation-form' . rand() % 10,
                                              'options' => ['class' => 'form-horizontal' . rand() % 10],
                                          ]) ?>

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
                    if ($hasStreetBool) {
                        $streetSellers[] = $user;
                    };
                }
                $data = [];
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






            <?php
                echo Html::hiddenInput('reservation', $model->id);
                echo $form->field($userModel, 'id')->widget(Select2::classname(), [
                    'name' => 'users' . rand(),
                    'data' => $data,
                    'id' => rand(),
                    'pluginOptions' => [
                        'escapeMarkup' => $escape,
                        'allowClear' => true
                    ],
                ])->label('Assing reservation to a street seller');

            ?>


            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11">
                    <?= Html::submitButton('Assign to this user', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>

            <?php

                $form = ActiveForm::begin([
                                              'id' => 'assign-reservation-form' . rand() % 10,
                                              'options' => ['class' => 'form-horizontal' . rand() % 10],
                                          ]) ?>

            <?php
                $productModel = new Product();
                $searchModel = new ProductAdminSearchModel();

                $userModel = new \common\models\User();
                $allUsers = $userModel->find()->all();
                $hotelSellers = [];

                foreach ($allUsers as $user) {
                    $authManager = \Yii::$app->getAuthManager();
                    $hasHotelBool = $authManager->getAssignment('hotelEditor', $user->id) ? true : false;
                    if ($hasHotelBool) {
                        $hotelSellers[] = $user;
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



            <?php
                echo Html::hiddenInput('reservation', $model->id);

                echo $form->field($userModel, 'id')->widget(Select2::classname(), [
                    'name' => 'users' . rand(),

                    'data' => $data2,

                    'pluginOptions' => [
                        'escapeMarkup' => $escape,
                        'allowClear' => true
                    ],
                ])->label('Assing reservation to a hotelSeller');

            ?>


            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11">
                    <?= Html::submitButton('Assign to this user', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end() ?>


            <?php
                $reservationData = $model->data;
                if ($assignLog = json_decode($reservationData)) {
                    if (isset($assignLog->assignments)) {
                        echo '<table>';
                        echo '<th><tr><td>Timestamp</td><td>From</td><td>To</td><td>By <b>user</b></td></tr></th>';
                        foreach ($assignLog->assignments as $i => $log) {

                            echo '<tr>'
                                . '<td>' . $log->time . '</td>'
                                . '<td>' . $log->from . '</td>'
                                . '<td>' . $log->to . '</td>'
                                . '<td>' . $log->by . '</td>'

                                . '</tr>';
                        }
                        echo '</table>';
                    }
                }

            ?>


        </div>
    </div>
