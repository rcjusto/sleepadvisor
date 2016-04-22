<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\DoctorSearch $searchModel
 */

$this->title = 'Doctors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-index">

    <div class="row">
        <div class="col-sm-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-sm-6" style="text-align: right">
            <?= Html::a('Configure New Doctor', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            'id',
            'name',
            'login',
            [
                'attribute' => 'admin',
                'value' => function($row){ return $row->getAdminDesc();}
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function($row) {
                    return Html::tag('div', Html::a('<span class="glyphicon glyphicon-edit"></span> Edit',['update','id'=>$row->id]), ['style'=>'text-align:center']);
                }
            ]
        ],
    ]); ?>

</div>
