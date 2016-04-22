<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'Login';
?>
<div class="site-login">

    <div class="row">

        <div class="col-sm-4 col-sm-offset-1">
            <?= Html::img('@web/images/splash.jpg', ['style'=>'max-width: 100%;max-height: 400px;']); ?>
        </div>

        <div class="col-sm-4 col-sm-offset-1">
            <div style="background-color: #d5ebf8;padding: 30px">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>Please fill out the following fields to login:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class'=>'form-horizontal'],
                'fieldConfig' => [
                    'template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['placeholder'=>'Username']) ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Password']) ?>

            <div class="form-group field-loginform-rememberme">
                <div class="checkbox" style="margin-left: 20px">
                    <label>
                        <input type="hidden" value="0" name="LoginForm[rememberMe]">
                        <input id="loginform-rememberme" type="checkbox" checked="" value="1" name="LoginForm[rememberMe]">
                        Remember Me
                    </label>
                    <p class="help-block"></p>
                </div>
            </div>

            <div class="form-group">
                <div style="margin-left: 20px">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>



</div>
