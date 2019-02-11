<?php
/**
 * Created by PhpStorm.
 * User: ROG
 * Date: 2019. 02. 05.
 * Time: 21:02
 */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Foglalások');

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <h1><?= Html::encode($this->title) ?></h1>

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_gridview',
                'layout' => '{items}<div class="clear"></div>{pager}',
            ]); ?>
        </div>
    </div>
</div>

