<?php
/**
 * Created by PhpStorm.
 * User: ROG
 * Date: 2019. 02. 05.
 * Time: 20:38
 */

use backend\components\extra;
    use backend\modules\Product\models\Product;
    use insolita\adminlte3\Lte;
use insolita\adminlte3\LteSmallBox;
    use kartik\dynagrid\DynaGrid;
    use kartik\grid\GridView;
    use kartik\helpers\Html;
    use kartik\icons\Icon;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;

?>

<!--suppress ALL -->
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-xs-12 col-md-3">
            <?php
            LteSmallBox::begin([
                'type' => Lte::COLOR_MAROON,
                'title' => $monthlySold . 'EUR',
                'icon' => 'fa fa-list-alt',
                'text' => 'Monthly sold'
            ]) ?>
            <?php LteSmallBox::end() ?>
        </div>
        <div class="col-xs-12 col-md-3">
            <?php
            LteSmallBox::begin([
                'type' => Lte::COLOR_BLUE,
                'title' => $todaySold . ' EUR',
                'text' => 'Today Sold',
                'icon' => 'fa fa-list-alt',
            ]) ?>
            <?php LteSmallBox::end() ?>
        </div> <div class="col-xs-12 col-md-3">
            <?php
                LteSmallBox::begin([
                                       'type' => Lte::COLOR_TEAL,
                                       'title' => "$startTicketId",
                                       'icon' => 'fa fa-ticket',
                                       'text' => 'Next ticked ID',
                                       'footer' => 'View ticket block',
                                       'link' => Url::to("/Tickets/tickets/admin")
                                   ]) ?>
            <?php LteSmallBox::end() ?>
        </div>
        <div class="col-xs-12 col-md-3">
            <?php /*
            LteSmallBox::begin([
                'type' => Lte::COLOR_OLIVE,
                'title' => "Batthyány tér",
                'text' => 'Current assigned location',
                'icon' => 'fa fa-map-marker',
            ]) ?>
            <?php LteSmallBox::end() */?>
        </div>

    </div>
    <?php
        $gridColumns = [
            [
                'label' => 'Ticket Id',
                'attribute' => 'ticketId',

            ],
            [
                'label' => 'Product',
                'attribute' => 'productId',
                'format' => 'html',
                'value' => function ($model) {
                    return (Product::getProdById($model->productId))->title;
                },

            ],

            [
                'label' => 'Persons',
                'attribute' => 'bookedChairsCount',
                'format' => 'html',
                'value' => function ($model) {
                    $sellerBadge = '';
                    if (isset($model->iSellerName)) {

                        $sellerBadge = " <span class=\" badge bg-yellow\">" . $model->iSellerName . "</span>";
                    }

                    return $model->bookedChairsCount . ' ' . Icon::show(
                            'users', [
                                       'class' => 'fa-lg', 'framework'
                                       => Icon::FA
                                   ]
                        ) . $sellerBadge;
                }
            ],
            [
                'label' => 'Cost',
                'attribute' => 'bookingCost',

                'format' => 'html',
                'value' => function ($model) {

                    if ($model->orderCurrency == 'EUR') {
                        $currencySymbol = '<i class="fas fa-euro-sign  "></i>';
                    } else {
                        $currencySymbol = 'Ft';
                    }
                    if ($model->status == 'unpaid') {
                        $currencySymbol .= '<span class="badge badge-pill badge-warning">unpaid</span>';
                    }
                    return $model->bookingCost . ' ' . $currencySymbol;
                },


            ],
            [
                'label' => 'Partner',
                'attribute' => 'sellerName',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->sellerName === Yii::$app->user->getIdentity()->username) {
                        return '';
                    }

                    return $model->sellerName;
                }

            ],
            [
                'label' => 'Paid Method',
                'attribute' => 'paidMethod',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->paidMethod;
                }
            ],
            [
                'label' => 'Notes',
                'attribute' => 'notes',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->notes;
                }
            ],
            [
                'label' => 'Invoice date',
                'attribute' => 'invoiceDate',

                'value' => function ($model) {
                    return $model->invoiceDate;
                },
                'filterType'=>GridView::FILTER_DATE,
                'format'=>'raw',
                'width'=>'170px',
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['format'=>'yyyy-mm-dd']
                ],
              
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a(
                            '<i class="fas fa-eye fa-lg "></i>',
                            $url,
                            [
                                'title' => Yii::t('backend', 'View')
                            ]
                        );
                    },

                ],

            ],

        ];



        $dynagrid = DynaGrid::begin([
                                        'columns' => $gridColumns,
                                        'theme'=>'panel-info',
                                        'showPersonalize'=>true,
                                        'storage' => 'session',
                                        'gridOptions'=>[
                                            'dataProvider'=>$dataProvider,
                                            'filterModel'=>new \backend\modules\Reservations\models\Reservations(),
                                            'showPageSummary'=>true,
                                            'floatHeader'=>false,
                                            'pjax'=>true,
                                            'responsiveWrap'=>false,
                                            'panel'=>[
                                                'heading'=>'<h5 class="panel-title"><i class="fas fa-book"></i>  My Bookings</h5>',
                                                'before' =>  '<div style="padding-top: 7px;"><em>* The table header sticks to the top in this demo as you scroll</em></div>',
                                                'after' => false
                                            ],
                                            'toolbar' =>  [
                                                ['content'=>
                                                     Html::button('<i class="fas fa-plus"></i>', ['type'=>'button', 'title'=>'Add Book', 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                                                     Html::a('<i class="fas fa-repeat"></i>', ['dynagrid-demo'], ['data-pjax'=>0, 'class' => 'btn btn-outline-secondary', 'title'=>'Reset Grid'])
                                                ],
                                                ['content'=>'{dynagridFilter}{dynagridSort}{dynagrid}'],
                                                '{export}',
                                            ]
                                        ],
                                        'options'=>['id'=>'dynagrid-1'] // a unique identifier is important
                                    ]);
        if (substr($dynagrid->theme, 0, 6) == 'simple') {
            $dynagrid->gridOptions['panel'] = false;
        }
        DynaGrid::end();

    ?>


</div>