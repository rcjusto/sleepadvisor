<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\AlertsByHourSearch $searchModel
 */

$this->title = 'Alerts By Hours Before Sleep Time';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alerts-by-hour-index">

    <div class="row">
        <div class="col-sm-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-sm-4" style="text-align: right">
            <?= Html::a('Create Alert By Hour', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped'],
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id_activity',
                'value' => function ($data) {
                        return \app\models\Activities::getActivity($data->id_activity)['name'];
                    }
            ],

            'hours_before',
            'hours_after',
            'message:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
