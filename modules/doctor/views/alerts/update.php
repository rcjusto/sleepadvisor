<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Alerts $model
 */

$this->title = 'Update Alert By Consumption';
$this->params['breadcrumbs'][] = ['label' => 'Alerts By Consumption', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="alerts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
