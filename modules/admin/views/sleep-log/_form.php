<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\SleepLog $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="sleep-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'timeIni')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'activity')->textInput() ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <?= $form->field($model, 'modified')->textInput() ?>

    <?= $form->field($model, 'versionNumber')->textInput() ?>

    <?= $form->field($model, 'timeEnd')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'option')->textInput() ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
