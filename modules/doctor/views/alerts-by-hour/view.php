<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\AlertsByHour $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Alerts By Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alerts-by-hour-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'deleted',
            'modified',
            'versionNumber',
            'id_activity',
            'hours_before',
            'hours_after',
            'message:ntext',
            'level',
        ],
    ]) ?>

</div>
