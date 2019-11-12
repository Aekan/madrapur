<div class="row">
    <div class="col-12">
        <!-- interactive chart -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-glasses fa-lg "></i>
                    Reservation Details
                </h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>

                </div>
            </div>
            <div class="card-body">


                <?php

                    use backend\modules\Product\models\Product;
                                 use kartik\detail\DetailView;
                    use kartik\helpers\Html;

                   $settings=[
                                                'model'=>$model,
                                                'condensed'=>true,
                                                'hover'=>true,
                                                'mode'=>DetailView::MODE_VIEW,
                                                'panel'=>[
                                                    'heading'=>'Reservation # ' . $model->id,
                                                    'type'=>DetailView::TYPE_INFO,
                                                ],
                                                'deleteOptions'=>[ // your ajax delete parameters
                                                                   'params' => ['id' => $model->id, 'kvdelete'=>true],
                                                ],

                                                'attributes'=>[
                                                    ['attribute'=>'sellerName'],
                                                    ['attribute'=>'billing_last_name',
                                                     'label'=>'Last Name'],
                                                    ['attribute'=>'billing_first_name',
                                                     'label'=>'First Name',
                                                    ],
                                                    ['attribute'=>'invoiceDate',
                                                    ],
                                                    ['attribute'=>'productId',
                                                          'value'=> (Product::findOne($model->productId))->title,
                                                          'label'=>'Product'
                                                         ],

                                                    ['attribute'=>'booking_start',
                                                     'value'=>substr($model->booking_start,0,-3),

                                                    ],
                                                    ['attribute'=>'notes',
                                                     'type'=>DetailView::INPUT_TEXTAREA,
                                                    ],
                                                    ['attribute'=>'allPersons',
                                                     'format'=>['integer'],
                                                    ],
                                                    ['attribute'=>'booking_cost',
                                                     'type'=>DetailView::INPUT_TEXTAREA,
                                                     'columns'=>[
                                                         ['attribute'=>'booking_cost',
                                                          'format'=>'html',
                                                          'type'=>DetailView::INPUT_TEXTAREA,
                                                          'value'=>$model->booking_cost.' '.'<span class="badge  badge-info">EUR</span>'.'<span class="badge  badge-warning">
'.$model->status.'</span>'

                                                         ],
                                                         ['attribute'=>'ticketId',
                                                          'type'=>DetailView::INPUT_TEXTAREA,
                                                          'format'=>'html',
                                                          'value'=>Html::a($model->ticketId, [
                                                '/Tickets/tickets/view-ticket',
                                                'id' => $model->ticketId,
                                                'blockId' => $model->ticketId,
                                            ])
                                                         ],
                                                     ]
                                                    ],
                                                ]
                                            ];

//                    $settings['attributes'][]=['attribute'=>'status'];
//
//                    foreach(json_decode($model->data) as  $key=>$attribute){
//                        echo $key.json_encode($attribute);
//                    }


                    echo DetailView::widget($settings);

                ?>
                 <div class="row">
                     <div class="col-12">
                         <!-- interactive chart -->
                         <div class="card  card-outline">
                             <div class="card-header bg-info text-white">
                                 <h3 class="card-title">
                                     <i class="far fa-chart-bar"></i>
                                     Assign History
                                 </h3>
                 
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                     </button>
                 
                                 </div>
                             </div>
                             <div class="card-body">
                                 <div class="direct-chat-messages">
                                     <!-- Message. Default to the left -->
                                      <!-- /.direct-chat-msg -->

                                     <!-- Message to the right -->



                                     <?php
                                         $reservationData = $model->data;
                                         if ($assignLog = json_decode($reservationData)) {
                                             if (isset($assignLog->assignments)) {

                                                 foreach ($assignLog->assignments as $i => $log) {
                                                    ?>
                                                     <div class="direct-chat-msg right">
                                                         <div class="direct-chat-infos clearfix">
                                                             <span class="direct-chat-name float-right">Madrapur</span>
                                                             <span class="direct-chat-timestamp
                                                             float-left"><?=$log->time?></span>
                                                         </div>
                                                         <!-- /.direct-chat-infos -->
                                                         <i class="fab fa-dev direct-chat-img fa-2x  "></i>

                                                         <!-- /.direct-chat-img -->
                                                         <?php


                                                             if(isset($log->from)) {

                                                                 ?>
                                                                 <div class="direct-chat-text">
                                                                     Reservation assigned from <?= $log->from ?>
                                                                     to <?= $log->to ?>
                                                                     (issued
                                                                     by user <?= $log->by ?>
                                                                     <?php






                                                                         if(isset($log->from)&&isset($log->message)){
                                                                             echo "<span class=\"badge badge-info\">"
                                                                                 .$log->message
                                                                                 ."</span>";
                                                                         }
                                                                     ?>
                                                                 </div>
                                                                 <?php
                                                             }

                                                                 ?>

                                                         <?php
                                                             if(!isset($log->from)) {

                                                                 ?>
                                                                 <div class="direct-chat-text">
                                                                     <?=$log->message?>
                                                                 </div>
                                                                 <?php
                                                             }

                                                         ?>


                                                         <!-- /.direct-chat-text -->
                                                     </div>

                                     <?php

                                                 }
                                                 echo '</table>';
                                             }
                                         }

                                     ?>








                                     <!-- /.direct-chat-msg -->
                                 </div>
                                
                             </div>
                             <!-- /.card-body-->
                         </div>
                         <!-- /.card -->
                 
                     </div>   
                  
                     <!-- /.col -->
                 </div>   
                    
                    













               
            </div>
            <!-- /.card-body-->
        </div>
        <!-- /.card -->

    </div>   
 
    <!-- /.col -->
</div>