<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\AlertsByHour $model
 */

$this->title = 'Create Alerts By Hour';
$this->params['breadcrumbs'][] = ['label' => 'Alerts By Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alerts-by-hour-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
