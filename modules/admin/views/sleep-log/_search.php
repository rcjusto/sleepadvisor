<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\search\SleepLogSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="sleep-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'deleted') ?>

    <?= $form->field($model, 'modified') ?>

    <?= $form->field($model, 'versionNumber') ?>

    <?= $form->field($model, 'timeIni') ?>

    <?php // echo $form->field($model, 'timeEnd') ?>

    <?php // echo $form->field($model, 'activity') ?>

    <?php // echo $form->field($model, 'option') ?>

    <?php // echo $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
