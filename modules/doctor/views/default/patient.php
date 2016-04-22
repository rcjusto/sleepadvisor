<?php
use app\models\Doctor;

use app\models\Patient;
use yii\helpers\Url;

/* @var $this yii\web\View */
/** @var $patient Patient */
/** @var $doctor Doctor */

$this->registerJsFile('@web/js/jquery.flot.min.js', ['\yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/jquery.flot.time.min.js', ['\yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/patient.js', ['\yii\web\JqueryAsset']);

$this->params['breadcrumbs'][] = ['label' => 'My Patients', 'url' => ['patients']];
$this->params['breadcrumbs'][] = 'Patient: ' . $patient->name;

$patLastDate = $patient->getLastDate();

?>
<div class="doctor-default-patient">

    <div class="doctor-default-index">
        <div class="row">
            <div class="col-sm-6">
                <div class="well">
                    <div style="background: url('<?= \yii\helpers\Url::base() ?>/files/no-picture.gif') no-repeat;padding-left: 130px;">
                        <h2><?= $patient->name ?></h2>

                        <p><span class="glyphicon glyphicon-envelope"></span> <?= $patient->email ?></p>
                        <?php if (!empty($patient->phone)) { ?>
                            <p><span class="glyphicon glyphicon-earphone"></span> <?= $patient->phone ?></p>
                        <?php } ?>
                        <?php $m1 = $patient->getFirstDate();
                        if (!is_null($m1) && $m1 > 0) {
                            ?>
                            <p><span class="glyphicon glyphicon-calendar"></span> <?= gmdate('M d, H:i', $m1 / 1000) ?></p>
                        <?php } ?>
                        <?php $m2 = $patient->getLastDate();
                        if (!is_null($m2) && $m2 > 0) {
                            ?>
                            <p><span class="glyphicon glyphicon-calendar"></span> <?= gmdate('M d, H:i', $m2 / 1000) ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="well">
                    <h2>Last Patient Activities</h2>
                </div>
            </div>
        </div>
    </div>

    <?php if (!is_null($m2) && $m2 > 0) {  ?>
        <div class="well">
            <h2 id="plot_1" style="text-align: right" data-url="<?=Url::to(['patient-chart-data','id'=>$patient->id_patient])?>", data-date="<?=gmdate('Y-m-d', $m2 / 1000)?>">
                <a class="plot_1_link" href="#" data-dir="1"><span class="glyphicon glyphicon-chevron-left"></span></a>
                <span id="plot_1_title">Loading charts</span>
                <a class="plot_1_link" href="#" data-dir="-1"><span class="glyphicon glyphicon-chevron-right"></span></a>
            </h2>
            <h3>Sleep Time in Bed ASLEEP (Hrs)</h3>
            <div class="cf row">
                <div id="chart_1" style="height: 200px;"></div>
            </div>
            <h3>Caffeine (mg)</h3>
            <div class="cf row">
                <div id="chart_2" style="height: 200px;"></div>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($data['ini'])) { ?>

        <div class="well">
            <h2>Last Month Activities</h2>
            <div class="cf row">
                <div id="chart_div" style="height: 300px;width: 70%;float: left;"></div>
                <div style="width: 30%;float: right;">
                    <ul id="choices"></ul>
                </div>
            </div>
            <script type="text/javascript">
                var months = {'0': 'Jan', '1': 'Feb', '2': 'Mar', '3': 'Apr', '4': 'May', '5': 'Jun', '6': 'Jul', '7': 'Ago', '8': 'Sep', '9': 'Oct', '10': 'Nov', '11': 'Dec'};
                var datasets = <?php echo $patient->getLogByActivities() ?>;
                var colors = ['#edc240', '#afd8f8', '#cb4b4b', '#4da74d', '#9440ed', '#bd9b33', '#8cacc6', '#a23c3c', '#3d853d', '#7633bd', '#ffe84c'];
            </script>

        </div>

        <div class="well">
            <h2>
                <strong>Activities:</strong> <?= gmdate('M d, Y', $data['ini']) ?> - <?= gmdate('M d, Y', $data['end']) ?>
                <span style="float: right;font-size: 14pt;display: inline-block;margin-top: 12px;">
                <?php if ($data['prev'] > 0) { ?>
                    <a href="<?= \yii\helpers\Url::to(['patient', 'id' => $patient->id_patient, 'date' => $data['prev']]) ?>"><span style="margin-right: 4px;" class="glyphicon glyphicon-chevron-left"></span>Previous Week</a>
                <?php } ?>
                    <?php if ($data['prev'] > 0 && $data['next'] > 0) echo '&nbsp;|&nbsp;' ?>
                    <?php if ($data['next'] > 0) { ?>
                        <a href="<?= \yii\helpers\Url::to(['patient', 'id' => $patient->id_patient, 'date' => $data['next']]) ?>">Next Week<span style="margin-left: 4px;" class="glyphicon glyphicon-chevron-right"></span></a>
                    <?php } ?>
            </span>
            </h2>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Time</th>
                    <th>Activity</th>
                    <th>Duration</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                <?php $oldDate = '';
                /** @var $row \app\models\SleepLog */
                foreach ($data['data'] as $row) {
                    $date = gmdate('Y-m-d', $row->getAdjustedDate());
                    if ($oldDate != $date) {
                        $oldDate = $date;
                        ?>
                        <tr class="info">
                            <td colspan="4" style="background-color: #3C578C; color: #ffffff;font-size: 14pt;"><?= gmdate('l, M d, Y', $row->getAdjustedDate()) ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td><?= $row->getHourIni('M d, H:i') ?></td>
                        <td><img src="<?= $row->getImage() ?>" style="max-height: 24px;margin-right: 8px;"><?= $row->getActivityData('name') ?></td>
                        <td><?= $row->getDuration() ?></td>
                        <td><?= $row->getDetails() ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>

    <?php } ?>

</div>
