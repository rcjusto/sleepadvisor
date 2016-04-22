<?php
use app\models\Doctor;
use yii\helpers\Url;

/** @var $doctor Doctor */

?>
<div class="doctor-default-index">
    <div class="row">
        <div class="col-sm-6">
            <div class="well">
                <h2><?= $doctor->name ?></h2>
                <h4><?= $doctor->getNumPatients() ?> Registered Patients</h4>
                <div>
                    <a href="<?= Url::to(['profile']) ?>">Change my profile</a>
                    <span>|</span>
                    <a href="<?= Url::to(['patients']) ?>">See all patients</a>
                </div>
            </div>
            <div class="well">
                <h2>Search Patient</h2>
                <form action="<?=Url::to(['search-patients'])?>" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="query" placeholder="Search by name, email or user id">
                          <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span> Search</button>
                          </span>
                    </div>
                </form>
            </div>

        </div>
        <div class="col-sm-6">
            <div class="well">
                <h2>Last Patient Activities</h2>
                <?php
                $lastActivities = $doctor->getLastPatientActivities(10);
                if (!empty($lastActivities)) {
                ?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Activity</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($lastActivities as $sLog) { ?>
                            <tr>
                                <td><a href="<?= Url::to(['patient', 'id'=>$sLog->id_patient]) ?>"><?= $sLog->patient->name ?></a></td>
                                <td><?= $sLog->getActivityData('name') ?></td>
                                <td><?= $sLog->getHourIni('M d, h:i A') ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
