<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\AlertsByHour $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="alerts-by-hour-form">

    <?php $form = ActiveForm::begin(); ?>

    <span style="display: none"><?= $form->field($model, 'id')->hiddenInput() ?></span>

    <div class="row">

        <div class="col-sm-6">
            <?= $form->field($model, 'id_activity')->dropDownList(\app\models\Activities::getActivitiesDropDown()) ?>
        </div>

        <div class="col-sm-3">
            <?= $form->field($model, 'hours_before')->textInput() ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'hours_after')->textInput() ?>
        </div>

        <div class="col-sm-12">
            <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
