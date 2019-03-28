<?php
/* @var $this \yii\web\View */
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;

/* @var $content string */

$this->beginContent('@frontend/views/layouts/base.php')?>
<div class="container">
    <?= $content ?>
</div>
<?php $this->endContent() ?>