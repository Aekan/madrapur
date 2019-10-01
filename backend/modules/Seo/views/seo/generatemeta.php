<div class="row">
    <div class="col-12">
        <!-- interactive chart -->
        <div class="card card-info ">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-yin-yang fa-lg "></i>
                    Generated meta

                </h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>

                </div>
            </div>
    <style>
        .inputGroup {
            background-color: #fff;
            display: block;
            margin: 10px 0;
            position: relative;
        }
        .inputGroup label {
            padding: 12px 30px;
            width: 100%;
            display: block;
            text-align: left;
            color: #3C454C;
            cursor: pointer;
            position: relative;
            z-index: 2;
            transition: color 200ms ease-in;
            overflow: hidden;
        }
        .inputGroup label:before {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            content: '';
            background-color: #5562eb;
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%) scale3d(1, 1, 1);
            transform: translate(-50%, -50%) scale3d(1, 1, 1);
            transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            z-index: -1;
        }
        .inputGroup label:after {
            width: 32px;
            height: 32px;
            content: '';
            border: 2px solid #D1D7DC;
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5.414 11L4 12.414l5.414 5.414L20.828 6.414 19.414 5l-10 10z' fill='%23fff' fill-rule='nonzero'/%3E%3C/svg%3E ");
            background-repeat: no-repeat;
            background-position: 2px 3px;
            border-radius: 50%;
            z-index: 2;
            position: absolute;
            right: 30px;
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            cursor: pointer;
            transition: all 200ms ease-in;
        }
        .inputGroup input:checked ~ label {
            color: #fff;
        }
        .inputGroup input:checked ~ label:before {
            -webkit-transform: translate(-50%, -50%) scale3d(56, 56, 1);
            transform: translate(-50%, -50%) scale3d(56, 56, 1);
            opacity: 1;
        }
        .inputGroup input:checked ~ label:after {
            background-color: #54E0C7;
            border-color: #54E0C7;
        }
        .inputGroup input {
            width: 32px;
            height: 32px;
            order: 1;
            z-index: 2;
            position: absolute;
            right: 30px;
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            cursor: pointer;
            visibility: hidden;
        }

        .form {
            padding: 0 16px;
            max-width: 550px;
            margin: 50px auto;
            font-size: 18px;
            font-weight: 600;
            line-height: 36px;
        }

        body {
            background-color: #D1D7DC;
            font-family: 'Fira Sans', sans-serif;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

        html {
            box-sizing: border-box;
        }

        code {
            background-color: #9AA3AC;
            padding: 0 8px;
        }


    </style>


            <div class="card-body">

                <?php

                    use backend\modules\Seo\models\Seo;
                    use kartik\form\ActiveForm;
                    use kartik\helpers\Html;

                    use yii\widgets\Pjax;

                    Pjax::begin(['id'=>'genpjax',]);
                    $form = ActiveForm::begin(['id'=>'choosemeta','options' => [
                        'data-pjax'=>true,
                    ],'action' => Yii::$app->urlManager->createUrl(['Seo/seo/update?id='])
                        .Yii::$app->request->get('id')
                                              ]);
                    $model=new Seo();
                    ?>

                <?php
                    if(!$posted) {

                        echo $form->field($model, 'meta+name+description')
                            ->radioList(
                               ($content),
                                [
                                    'item' => function ($index, $label, $name, $checked, $value) {

                                        $opcio=Html::encode(str_replace('\r\n', '',  utf8_encode
                                        ($label)));

                                        $return = '<div class="inputGroup">';
                                        $return .= '<input type="radio" name="' . $name . '" id="' . $name
                                            . $value . '" value="' .$opcio. '" tabindex="3">';
                                        $return .= '<label for="' . $name . $value . '" >' .$opcio. '</label>';
                                        $return .= '</div>';

                                        return $return;
                                    }
                                ]

                            );
                        echo($form->field($model, 'postId')->hiddenInput(['value' => Yii::$app->request->get('id')]))
                            ->label(false);
                        echo Html::submitButton(Yii::t('backend', 'Submit'),
                                                [
                                                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
                                                ]) ;
                        ActiveForm::end();
                        Pjax::end();
                    }
                    else{
                    echo 'posted';


                    }

                    ?>


            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->

    </div>   
 
    <!-- /.col -->
</div>

<style>
    label {
        line-height: 1.57;
        word-wrap: break-word;
        color: #545454;
        font-family: arial, sans-serif;
        font-size: 14px;
    }
    .inputGroup {
        background-color: #fff;
        display: block;
        margin: 10px 0;
        position: relative;
    }
    .inputGroup label {
        padding: 12px 30px;
        width: 100%;
        display: block;
        text-align: left;
        color: #3C454C;
        cursor: pointer;
        position: relative;
        z-index: 2;
        transition: color 200ms ease-in;
        overflow: hidden;
    }
    .inputGroup label:before {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        content: '';
        background-color: #5562eb;
        position: absolute;
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%) scale3d(1, 1, 1);
        transform: translate(-50%, -50%) scale3d(1, 1, 1);
        transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
        z-index: -1;
    }
    .inputGroup label:after {
        width: 32px;
        height: 32px;
        content: '';
        border: 2px solid #D1D7DC;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5.414 11L4 12.414l5.414 5.414L20.828 6.414 19.414 5l-10 10z' fill='%23fff' fill-rule='nonzero'/%3E%3C/svg%3E ");
        background-repeat: no-repeat;
        background-position: 2px 3px;
        border-radius: 50%;
        z-index: 2;
        position: absolute;
        right: 30px;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        cursor: pointer;
        transition: all 200ms ease-in;
    }
    .inputGroup input:checked ~ label {
        color: #fff;
    }
    .inputGroup input:checked ~ label:before {
        -webkit-transform: translate(-50%, -50%) scale3d(200, 200, 1);
        transform: translate(-50%, -50%) scale3d(200, 200, 1);
        opacity: 1;
    }
    .inputGroup input:checked ~ label:after {
        background-color: #54E0C7;
        border-color: #54E0C7;
    }
    .inputGroup input {
        width: 32px;
        height: 32px;
        order: 1;
        z-index: 2;
        position: absolute;
        right: 30px;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        cursor: pointer;
        visibility: hidden;
    }

    .form {
        padding: 0 16px;
        max-width: 550px;
        margin: 50px auto;
        font-size: 18px;
        font-weight: 600;
        line-height: 36px;
    }

    body {
        background-color: #D1D7DC;
        font-family: 'Fira Sans', sans-serif;
    }

    *,
    *::before,
    *::after {
        box-sizing: inherit;
    }

    html {
        box-sizing: border-box;
    }

    code {
        background-color: #9AA3AC;
        padding: 0 8px;
    }

</style>
<script>
    $('input[type=radio]').on('change', function() {
        $("#choosemeta").submit();
    });
</script>