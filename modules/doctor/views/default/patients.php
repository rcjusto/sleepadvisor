<?php
use app\models\Doctor;

use yii\grid\GridView;

/** @var $doctor Doctor */

$this->params['breadcrumbs'][] = 'My Patients';

?>
<div class="doctor-default-patients">

    <h1>My Patients</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'name',
            'email',
            'phone',
            [
                'label' => 'First Date',
                'format' => 'raw',
                'value' => function($data)
                    {
                        $m = $data->getFirstDate();
                        if (!is_null($m) && $m>0) {
                            return gmdate('M d, H:i', $m/1000);
                        }
                        return '<span class="not-set">(never)</span>';
                    },
            ],
            [
                'label' => 'Last Date',
                'format' => 'raw',
                'value' => function($data)
                    {
                        $m = $data->getLastDate();
                        if (!is_null($m) && $m>0) {
                            return gmdate('M d, H:i', $m/1000);
                        }
                        return '<span class="not-set">(never)</span>';
                    },
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function($data)
                    {
                        return \yii\helpers\Html::a('Patient Details', ['patient','id'=>$data->id_patient]);
                    },
            ],
        ],
        'tableOptions' => ['class' => 'table table-striped']
    ]); ?>

</div>
