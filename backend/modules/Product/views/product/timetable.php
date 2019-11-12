<?php

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

$form = ActiveForm::begin([
    'id' => 'product-edit',
    'action' => 'update?prodId=' . $prodId,
    'options' => ['class' => 'product-edit', 'enctype' => 'multipart/form-data'],

]); ?>


<div class="tab-pane" id="timetable">
    <?php

    ?>


    <div class="panel panel-default">

        <div class="panel-body">
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
            $JSDayClick = <<<EOF
function(date, jsEvent, view) {
    var m=new Date(date)
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
            <div class="row">
                <div class="col-12">
                    <!-- interactive chart -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-table"></i>
                                TimeTable
                            </h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>

                            </div>
                        </div>
                        <div class="card-body">
                            <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
                                                                               'events' => $modelEvents,

                                                                               'clientOptions' => [
                                                                                   'locale' => 'hu',


                                                                                   'themeSystem'=>'standard',
                                                                                   'eventClick' => new 
                                                                                   \yii\web\JsExpression
                                                                                   ($JSEventClick),
                                                                                   'dayClick' => new
                                                                                   \yii\web\JsExpression
                                                                                   ($JSDayClick),
                                                                                   ''

                                                                               ]
                                                                           ));

                            ?>

                        </div>
                        <!-- /.card-body-->
                    </div>
                    <!-- /.card -->

                </div>

                <!-- /.col -->
            </div>



        </div>


    </div>

</div>



