<?php
$this->title = Yii::t('app', 'Termék létrehozása');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Termékek'), 'url' => ['admin']];

?>

<div class="product-default-index">
    <h2><?= $this->title ?></h2>

    <?= $this->render('createForm'); ?>
    <?php
    echo $updateResponse;
    ?>

</div>
