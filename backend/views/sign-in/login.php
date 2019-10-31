<?php

    use aryelds\sweetalert\SweetAlert;
    use lavrentiev\widgets\toastr\Notification;
    use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
\backend\assets\MaterializeAsset::register($this);
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */


$this->params['body-class'] = 'login-page';

?>

<div class="login-box">
	<div class="login-logo">
        <?php echo Html::encode($this->title) ?>
    </div>
    <div class="row">
        <div class="col-12">
            <!-- interactive chart -->
            <div class="card card-info ">
                <div class="card-header">
                    <h3 class="card-title">
                    <?='Sign in'?>
                    <?php

                    if(isset($invalid)){
                        echo SweetAlert::widget([
                                                    'options' => [
                                                        'title' => "Invalid Creditentials",

                                                    ]
                                                ]);
                    }

                    ?>
                    </h3>

                    <div class="card-tools">


                    </div>
                </div>
                <div class="card-body">

                    <div class="login-box-body">
                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                        <div class="body">
                            <?php echo $form->field($model, 'username') ?>
                            <?php echo $form->field($model, 'password')->passwordInput() ?>


                            <p>
                                <label>
                                    <input name="LoginForm[rememberMe]" type="checkbox" class="filled-in" checked="checked" />
                                    <span>Remember me</span>
                                </label>

                            </p>
                        </div>
                        <div class="footer">
                            <?php

                                echo Html::submitButton(Yii::t('backend', 'Sign me in'),
                                    [
                                        'class' => 'btn btn-info btn-flat btn-block',
                                        'name' => 'login-button'
                                    ])?>
                        </div>
                        <?php ActiveForm::end() ?>


                    </div>


                </div>
                <!-- /.card-body-->
            </div>
            <!-- /.card -->

        </div>

        <!-- /.col -->
    </div>


	<!-- /.login-logo -->
	<div class="header"></div>


</div>