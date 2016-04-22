<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 9/18/2015
 * Time: 5:55 PM
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$model = new \app\models\PasswordForm();

?>
<div class="doctor-profile">
    <h1>My Profile</h1>

    <div class="row">
        <div class="col-sm-5">
            <h3>Update Name</h3>
            <?php $form = ActiveForm::begin(); ?>

            <div class="form-group field-doctor-login has-success">
                <label class="control-label">Login</label>
                <input class="form-control" type="text" readonly="readonly" value="<?= $doctor->login ?>">

                <p class="help-block"></p>
            </div>

            <?= $form->field($doctor, 'name')->textInput(['maxlength' => 512]) ?>

            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-sm-5 col-sm-offset-1">

            <h3>Update Password</h3>

            <?php if ($password_updated) { ?>

                <div class="alert alert-success" role="alert" style="margin-top: 30px">
                    <h4>Password Updated Successfully</h4>
                    <p>Your password was updated successfully. The next time you access this site you must use the new password to log in.</p>
                </div>

            <?php } else { ?>

            <?php $form = ActiveForm::begin(['action' => ['change-password']]); ?>

            <?= $form->field($model, 'password')->textInput(['maxlength' => 512]) ?>

            <?= $form->field($model, 're_password')->textInput(['maxlength' => 512]) ?>

            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php } ?>

        </div>
    </div>
</div>