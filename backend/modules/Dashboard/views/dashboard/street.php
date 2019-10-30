<?php

    use backend\modules\Modevent\models\Modevent;
    use backend\modules\Modevent\models\Workshift;
    use backend\modules\Tickets\models\TicketBlock;
    use yii\bootstrap4\ActiveForm;
    use yii\bootstrap4\Html;

    \frontend\assets\MdbButtonsAsset::register($this);

?>
    <div class="row">
        <div class="col-12">

            <!-- interactive chart -->


            <div class="row">
                <div class="col-12">


                    <div class="row">

                        <!-- /.col -->
                        <div class="col-sm-4">

                            <div id="TicketWidget" class="card card-info bg-info">
                                <div class="card-header ui-sortable-handle" style="cursor: move;">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Tickets
                                    </h3>
                                    <div class="card-tools">

                                    </div>
                                </div><!-- /.card-header -->
                                <div class="card-body-cascade">
                                    <div class="tab-content">
                                        <!-- Morris chart - Sales -->


                                        <div class=" tab-pane active">
                                            <div class="col-lg-12 ">
                                                <div class="description-block">
                                                    <div class="small-box bg-info ">

                                                        <div class="inner">
                                                            <h3><?= TicketBlock::userNextTicketId() ?>
                                                            </h3>

                                                            <p>Next Ticket ID</p>
                                                            <?= Html::a(
                                                                'Change Ticket Block <i class="fas fa-book  "></i>',
                                                                '/Dashboard/dashboard/manager',
                                                                [
                                                                    'title' => Yii::t('backend', 'Login'),
                                                                    'class' => 'btn btn-outline-light'
                                                                ]
                                                            )
                                                            ?>
                                                            <?= Html::a(
                                                                'Skip Ticket <i class="fas fa-arrow-circle-right "></i>',
                                                                '/Dashboard/dashboard/admin?skip-ticket=' . TicketBlock::userNextTicketId(),
                                                                [
                                                                    'title' => Yii::t('backend', 'Login'),
                                                                    'class' => 'btn btn-outline-light'
                                                                ]
                                                            )
                                                            ?>
                                                        </div>


                                                        <div class="icon">
                                                            <i class="fas fa-ticket-alt  "></i>
                                                        </div>


                                                    </div>

                                                </div>
                                                <!-- /.description-block -->
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.card-body -->
                            </div>


                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div id="workshiftwidget" class="card card-info bg-info">
                                <div class="card-header ui-sortable-handle" style="cursor: move;">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Workshift
                                    </h3>
                                    <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link bg-gradient-info" href="#nextwork"
                                                   data-toggle="tab">Next</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link  bg-gradient-info " href="#lastwork"
                                                   data-toggle="tab">Last</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- /.card-header -->
                                <div class="card-body-cascade">
                                    <div class="tab-content">
                                        <!-- Morris chart - Sales -->
                                        <?php
                                            $activeNext = '';
                                            $activeLast = '';
                                            $lastWork = Modevent::userLastWork();
                                            $nextWork = Modevent::userNextWork();

                                            if(isset($lastWork->startDate)){


                                                if ($lastWork->startDate == date('Y-m-d', strtotime('today'))) {
                                                    $activeLast = 'active';
                                                } else {
                                                    $activeNext = 'active';
                                                }
                                            }
                                            else{
                                                $activeNext='active';
                                            }
                                        ?>
                                        <div class=" tab-pane <?= $activeNext ?>" id="nextwork">
                                            <div class="col-lg-12  ">
                                                <div class="description-block">
                                                    <div class="small-box bg-info ">
                                                        <div class="inner">


                                                            <?php

                                                                switch ($nextWork) {

                                                                    case null:
                                                                        echo ' <h4>None, please subscribe first</h4>';
                                                                        echo '  <p>Next Work</p>' . Html::a(
                                                                                'Subscribe for Work <i class="fas fa-pen-fancy  
                                                    "></i>',
                                                                                '/Modevent/modevent/subscribe',
                                                                                [
                                                                                    'title' => Yii::t('backend', 'Login'),
                                                                                    'class' => 'btn btn-outline-light'
                                                                                ]
                                                                            );
                                                                        break;

                                                                    case !(null):

                                                                        $workShift = Workshift::findOne($nextWork->place);

                                                                        echo '<h3>' . $workShift->place
                                                                            . '</h3>';
                                                                        echo '<p><span class="badge-pill badge-primary">' . $workShift->role . '</span><span class="badge-pill badge-primary">'
                                                                            . $nextWork->startDate . ' ' . date(
                                                                                'H:i',
                                                                                strtotime
                                                                                (
                                                                                    $workShift->startTime
                                                                                )
                                                                            ) . ' - ' . date
                                                                            (
                                                                                'H:i', strtotime($workShift->endTime)
                                                                            ) . '</span></p>';

                                                                        $form = ActiveForm::begin(
                                                                            [
                                                                                'action' =>
                                                                                    ['/Modevent/modevent/mywork']
                                                                            ]
                                                                        );

                                                                        if ($nextWork->title == 'arranged' and $nextWork->status == 'notyet') {
                                                                            echo Html::submitButton(
                                                                                Yii::t('backend', 'Start working' . '<i class="fas fa-running fa-fw "></i>'),
                                                                                [
                                                                                    'class' => 'btn btn-outline-light',
                                                                                    'name' => 'work',
                                                                                    'value' => $nextWork->id,

                                                                                ]
                                                                            );
                                                                                    echo Html::a('Subscribe for Work <i class="fas fa-pen-fancy  
                                                    "></i>',
                                                                                    '/Modevent/modevent/subscribe',
                                                                                    [
                                                                                        'title' => Yii::t('backend', 'Subscribe for work'),
                                                                                        'class' => 'btn btn-outline-light'
                                                                                    ]
                                                                                );
                                                                        } elseif ($nextWork->status == 'working') {
                                                                            echo Html::submitButton(
                                                                                Yii::t(
                                                                                    'backend', 'Let\'s finish working' . '<i class="fas fa-running fa-fw
                                            "></i>'
                                                                                ),
                                                                                [
                                                                                    'class' => 'btn btn-success btn-flat',
                                                                                    'name' => 'work-end',
                                                                                    'value' => $nextWork->id
                                                                                ]
                                                                            );
                                                                        } elseif ($nextWork->status == 'worked') {
                                                                            echo Html::submitButton(
                                                                                Yii::t('backend', 'Job Done, Good work!' . '<i class="fas fa-thumbs-up fa-fw "></i>'),
                                                                                [
                                                                                    'class' => 'disabled btn btn-success btn-flat',
                                                                                    'name' => 'dayover',
                                                                                    'value' => 'dayover'
                                                                                ]
                                                                            );
                                                                        }

                                                                        ActiveForm::end();
                                                                }

                                                            ?>


                                                        </div>
                                                        <div class="icon">
                                                            <i class="fas fa-ticket-alt  "></i>
                                                        </div>


                                                    </div>

                                                </div>
                                                <!-- /.description-block -->
                                            </div>
                                        </div>
                                        <div class=" tab-pane <?= $activeLast ?>" id="lastwork">
                                            <div class="col-lg-12 ">
                                                <div class="description-block">
                                                    <div class="small-box bg-info ">
                                                        <div class="inner">


                                                            <?php

                                                                $lastWork = Modevent::userLastWork();

                                                                switch ($lastWork) {

                                                                    case null:
                                                                        echo ' <h4>None, please subscribe first</h4>';
                                                                        echo '  <p>Next Work</p>' . Html::a(
                                                                                'Subscribe for Work <i class="fas fa-pen-fancy  
                                                    "></i>',
                                                                                '/Modevent/modevent/subscribe',
                                                                                [
                                                                                    'title' => Yii::t('backend', 'Login'),
                                                                                    'class' => 'btn btn-outline-light'
                                                                                ]
                                                                            );
                                                                        break;

                                                                    case !(null):

                                                                        $workShift = Workshift::findOne($lastWork->place);

                                                                        echo '<h3>' . $workShift->place
                                                                            . '</h3>';
                                                                        echo '<p><span class="badge-pill badge-primary">' . $workShift->role . '</span><span class="badge-pill badge-primary">'
                                                                            . $lastWork->startDate . ' ' . date(
                                                                                'H:i',
                                                                                strtotime
                                                                                (
                                                                                    $workShift->startTime
                                                                                )
                                                                            ) . ' - ' . date
                                                                            (
                                                                                'H:i', strtotime($workShift->endTime)
                                                                            ) . '</span></p>';

                                                                        $form = ActiveForm::begin(
                                                                            [
                                                                                'action' =>
                                                                                    ['/Modevent/modevent/mywork']
                                                                            ]
                                                                        );

                                                                        echo Html::submitButton(
                                                                            Yii::t('backend', 'Job Done, Good work!' . '<i class="fas fa-thumbs-up fa-fw "></i>'),
                                                                            [
                                                                                'class' => 'disabled btn btn-success btn-flat',
                                                                                'name' => 'dayover',
                                                                                'value' => 'dayover'

                                                                            ]
                                                                        );

                                                                        ActiveForm::end();
                                                                }

                                                            ?>


                                                        </div>
                                                        <div class="icon">
                                                            <i class="fas fa-ticket-alt  "></i>
                                                        </div>


                                                    </div>

                                                </div>
                                                <!-- /.description-block -->
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.description-block -->
                        </div>

                        <div class="col-sm-4">

                            <div id="MyTransactions" class="card card-info bg-info">
                                <div class="card-header ui-sortable-handle" style="cursor: move;">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Bookings
                                    </h3>
                                    <div class="card-tools">

                                    </div>
                                </div><!-- /.card-header -->
                                <div class="card-body-cascade">
                                    <div class="tab-content">
                                        <!-- Morris chart - Sales -->
                                        <?php

                                        ?>

                                        <div class=" tab-pane active">
                                            <div class="col-lg-12 ">
                                                <div class="description-block">
                                                    <div class="small-box bg-info ">

                                                        <div class="inner">
                                                            <h4 class="btn btn-purple text-white"><?=
                                                                    $eurCashToday ?>
                                                                € Cash </h4>
                                                            <h6 class="btn btn-purple  text-white   "><?= $eurCardToday ?>
                                                                € Card </h6>

                                                            <h6 class="btn btn-purple  text-white   "><?= $hufCashToday ?>
                                                                Ft Cash</h6>
                                                            <h6 class="btn btn-purple text-white"><?= $hufCardToday ?>
                                                                Ft Card </h6>

                                                            <p> </p>
                                                            <?= Html::a(
                                                                'Transaction Center <i class="fas fa-hand-holding-usd  "></i>',
                                                                '/Reservations/reservations/mytransactions',
                                                                [
                                                                    'title' => Yii::t('backend', 'My Transactions'),
                                                                    'class' => 'btn btn-outline-light'
                                                                ]
                                                            )
                                                            ?>

                                                        </div>


                                                        <div class="icon">
                                                            <i class="fas fa-ticket-alt  "></i>
                                                        </div>


                                                    </div>

                                                </div>
                                                <!-- /.description-block -->
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.card-body -->
                            </div>


                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <!-- /.card-body-->

                <!-- /.col -->
            </div>
            <div class="card ">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">


                </div>

                <div class="card-footer">

                    <!-- /.row -->
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tree  "></i>
                        My work

                    </h3>

                    <div class="card-tools">


                    </div>
                </div>
                <div id="" class="card-body">

                    <?php

                        echo $this->render('mywork', ['date' => date('Y-m-d', time())]);
                    ?>

                </div>
                <!-- /.card-body-->
            </div>
            <!-- /.card -->

        </div>

        <!-- /.col -->
    </div>
    <style>
        .btn-floating-right-bottom {
            position: fixed !important;
            right: 20px;
            bottom: 20px;
            border-radius: 20px;
        }
    </style>

    <a href="<?= \yii\helpers\Url::to("/Reservations/reservations/create2") ?>" class="btn btn-floating-right-bottom
    bg-purple"><i class="fa fa-2x fa-plus-square"></i></a>
<?php

?>