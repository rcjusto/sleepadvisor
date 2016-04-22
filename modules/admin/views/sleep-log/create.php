<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\SleepLog $model
 */

$this->title = 'Create Sleep Log';
$this->params['breadcrumbs'][] = ['label' => 'Sleep Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sleep-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
