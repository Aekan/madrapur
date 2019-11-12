<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\Reservations\models\ReservationsAdminSearchModel */
/* @var $form ActiveForm */
?>
<div class="bookingEdit">
    <?php echo \yii\helpers\Html::a( 'Back', Yii::$app->request->referrer); ?>

    <?php

    foreach (array_keys($model->attributes) as $attribute) {
        $model[$attribute] = $backenddata[$attribute];
    }

    ?>
    <?php $form = ActiveForm::begin(); ?>
    <?php
    foreach (array_keys($model->attributes) as $attribute) {
        ?>
        <?= $form->field($model, $attribute) ?>
        <?php
    }
    ?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- bookingEdit -->
