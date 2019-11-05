<?php
/**
 * @author Eugene Terentev <eugene@terentev.net>
 */

use common\components\keyStorage\FormWidget;

/**
 *
 * @var $model \common\components\keyStorage\FormModel
 */


?>
    
    <div class="row">
        <div class="col-12">
            <!-- interactive chart -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs  "></i>
                        System Settings
                    </h3>
    
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
    
                    </div>
                </div>
                <div class="card-body">
                    <?php

                        echo FormWidget::widget(
                            [
                                'model' => $model,
                                'formClass' => '\yii\bootstrap\ActiveForm',
                                'submitText' => Yii::t('backend', 'Save'),
                                'submitOptions' => [
                                    'class' => 'btn btn-primary'
                                ],
                            ]) ?>
                   
                </div>
                <!-- /.card-body-->
            </div>
            <!-- /.card -->
    
        </div>   
     
        <!-- /.col -->
    </div>

