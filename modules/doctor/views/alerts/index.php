<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\AlertsSearch $searchModel
 */

$this->title = 'Alerts By Consumption';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alerts-index">

    <div class="row">
        <div class="col-sm-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-sm-6" style="text-align: right">
            <?= Html::a('Create Alert By Consumption', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?=
    GridView::widget([
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
            'cond_lt',
            'cond_gt',
            'message:ntext',
            ['class' => 'yii\grid\ActionColumn',],
        ],
    ]); ?>

</div>
