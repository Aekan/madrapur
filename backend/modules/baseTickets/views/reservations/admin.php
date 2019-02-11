<?php
/**
 * Created by PhpStorm.
 * User: ROG
 * Date: 2019. 02. 05.
 * Time: 20:38
 */

use kartik\helpers\Html;
use backend\components\extra;

$this->title = Yii::t('app', 'Foglalások');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        $gridColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            'uuid',
            'source',
            'data',
            'invoice_date',
            'reservation_date'
        ];

        echo \yii\grid\GridView::widget([
            'pager' => [
                'firstPageLabel' => Yii::t('app','Első oldal'),
                'lastPageLabel'  => Yii::t('app','Utolsó oldal'),
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
        ]);
    ?>

    <div id="jsonPre">

    </div>

    <script>
        $().ready(
            function() {
                $.ajax({
                    type: 'GET',
                    url: 'https://budapestrivercruise.eu/wp-json/bookings/v1/start/2019-02-01/end/2019-02-28',
                    success: function (data) {
                        let innerHTML = "";
                        let bookingKeys = [];

                        Object.keys(data).forEach(
                            (bookingId) => {
                                innerHTML += "<p>BookingId: " + bookingId + "</p>";

                                if (!bookingKeys.length) bookingKeys = Object.keys(data[bookingId]);

                                bookingKeys.forEach(
                                    function (key) {
                                        innerHTML += "<p>" + key + " => " + data[bookingId][key] +  "</p>";
                                    }
                                );
                            }
                        );

                        $("#jsonPre").html(innerHTML);
                    }
                });
            }
        );
    </script>

</div>