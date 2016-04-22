<?php
use app\models\Doctor;

use app\models\Patient;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $doctor Doctor */
/** @var $list Patient[] */

$this->params['breadcrumbs'][] = 'Search';
$this->registerJsFile('@web/js/bootbox.min.js', ['\yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/search.js', ['\yii\web\JqueryAsset']);

?>
<div class="doctor-default-search">

    <h1>Patient Search</h1>

    <?php $form = ActiveForm::begin(['id' => 'search-form', 'method'=>'GET']); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= Html::textInput('query', isset($_REQUEST['query']) ? $_REQUEST['query'] : '', ['class'=>'form-control','placeholder'=>'Search by name, email or user id']) ?>
        </div>
        <div class="col-sm-6">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?php if (isset($list) && !is_null($list)) { ?>
    <div style="margin-top: 30px;">

        <?php if (empty($list)) { ?>
            <div class="alert alert-danger" role="alert">No patients found.</div>
        <?php } else { ?>
        <table id="table-patients" class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Doctor</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($list as $row) { ?>
                <tr>
                    <td><a href="<?=Url::to(['patient','id'=>$row->id_patient])?>"><?=$row->username?></a></td>
                    <td><?=$row->name?></td>
                    <td><?=$row->email?></td>
                    <td><?=$row->getDoctorName()?></td>
                    <td>
                        <?php if (empty($row->id_doctor)) { ?>
                            <a class="assign-patient" href="#" data-name="<?= $row->name?>" data-url="<?= Url::to(['default/assign-patient', 'id'=>$row->id_patient]) ?>"><span class="glyphicon glyphicon-ok"></span> Assign Patient</a>
                        <?php } elseif ($row->id_doctor==$doctor->id) { ?>
                            <a class="unassign-patient" href="#" data-name="<?= $row->name?>" data-url="<?= Url::to(['default/unassign-patient', 'id'=>$row->id_patient]) ?>"><span class="glyphicon glyphicon-remove"></span> Unassign Patient</a>
                        <?php } elseif ($doctor->admin) { ?>
                            <a class="reassign-patient" href="#" data-name="<?= $row->name?>" data-url="<?= Url::to(['default/assign-patient', 'id'=>$row->id_patient]) ?>"><span class="glyphicon glyphicon-ok"></span> Reassign Patient</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php }?>

    </div>
    <?php } ?>

</div>
