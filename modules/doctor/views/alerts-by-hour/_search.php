<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\search\AlertsByHourSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="alerts-by-hour-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'deleted') ?>

    <?= $form->field($model, 'modified') ?>

    <?= $form->field($model, 'versionNumber') ?>

    <?= $form->field($model, 'id_activity') ?>

    <?php // echo $form->field($model, 'hours_before') ?>

    <?php // echo $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'level') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
