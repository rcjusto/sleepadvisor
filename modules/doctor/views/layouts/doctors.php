<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
$moduleName = '';
switch (Yii::$app->controller->module->id) {
    case 'doctor':
        $moduleName = ' - Doctor\'s Module';
        break;
}
$doctor = \Yii::$app->user->identity->getDoctor();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" type="image/png" href="<?= Url::to('@web/images')?>/favicon.png" />
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'SleepAdvisor' . $moduleName,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'My Patients', 'url' => ['/doctor/default/patients']],
            ['label' => 'Alerts', 'items' => [
                ['label' => 'Alerts By Consumption', 'url' => ['/doctor/alerts/index']],
                ['label' => 'Alerts By Hour', 'url' => ['/doctor/alerts-by-hour/index']],
            ]],
            ['label' => 'Doctors', 'url' => ['/doctor/doctors/index'], 'visible' => !is_null($doctor) && $doctor->admin],
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                ['label' => Yii::$app->user->identity->name, 'items'=>[
                    ['label' => 'Profile', 'url' => ['/doctor/default/profile'], 'linkOptions' => ['data-method' => 'post']],
                    '<li class="divider"></li>',
                    ['label' => 'Logout', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                ]],
            ['label' => 'Help', 'items'=>[
                ['label' => 'Help', 'url' => ['/doctor/default/index']],
                '<li class="divider"></li>',
                ['label' => 'Contact Us', 'url' => ['/doctor/default/contact']],
                ['label' => 'About Us', 'url' => 'http://stmulapps.com/about.html'],
            ]],
        ],
    ]);
    NavBar::end();
    ?>
    <div class="container">
        <?=
        Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; StmulApps.com <?= date('Y') ?></p>
        <p class="pull-right"><a href="http://stmulapps.com" target="_blank"><span class="glyphicon glyphicon-globe"></span> http://stmulapps.com</a></p>
    </div>
</footer>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
