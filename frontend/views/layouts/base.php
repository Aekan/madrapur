<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@frontend/views/layouts/_clear.php')?>
<div class="wrap">
   <?= $content ?>
</div>

<?php $this->endContent() ?>