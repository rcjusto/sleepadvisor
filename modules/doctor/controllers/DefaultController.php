<?php

namespace app\modules\doctor\controllers;

use app\models\ContactForm;
use app\models\Doctor;
use app\models\PasswordForm;
use app\models\Patient;
use app\models\search\PatientSearch;
use Yii;
use yii\helpers\Json;

class DefaultController extends BaseDoctorController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $doctor = \Yii::$app->user->identity->getDoctor();
        return $this->render('index', ['doctor'=>$doctor]);
    }

    public function actionProfile()
    {
        $doctor = \Yii::$app->user->identity->getDoctor();

        if ($doctor->load(Yii::$app->request->post()) && $doctor->save()) {
            $this->redirect(['profile']);
        }

        return $this->render('profile', ['doctor' => $doctor, 'password_updated' => false]);
    }

    public function actionChangePassword()
    {
        $doctor = \Yii::$app->user->identity->getDoctor();

        $model = new PasswordForm();
        $password_updated = false;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $doctor->password = $model->password;
            $doctor->save();
            $password_updated = true;
        }

        return $this->render('profile', ['doctor'=>$doctor, 'password_updated' => $password_updated]);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionPatients()
    {
        $doctor = \Yii::$app->user->identity->getDoctor();

        $searchModel = new PatientSearch;
        $searchModel->id_doctor = $doctor->id;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('patients', [
            'doctor'=>$doctor,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionPatient()
    {
        $doctor = \Yii::$app->user->identity->getDoctor();

        /** @var Patient $patient */
        $patient = isset($_REQUEST['id']) ? Patient::findOne($_REQUEST['id']) : null;
        if (!is_null($patient) && $patient->id_doctor=$doctor->id) {

            return $this->render('patient', [
                'doctor'=>$doctor,
                'patient'=>$patient,
                'data'=> $this->getPatientLogFor($patient, isset($_REQUEST['date']) ? $_REQUEST['date'] : null),
            ]);
        } else {
            return $this->redirect(['patients']);
        }

    }

    public function actionPatientChartData()
    {
        $doctor = \Yii::$app->user->identity->getDoctor();

        /** @var Patient $patient */
        $patient = isset($_REQUEST['id']) ? Patient::findOne($_REQUEST['id']) : null;
        if (!is_null($patient) && $patient->id_doctor=$doctor->id) {
            $month = isset($_REQUEST['months']) ? $_REQUEST['months'] : 0;
            $date = strtotime('+'.$month.' months', $patient->getLastDate() / 1000);

            $arr = explode('-', date('Y-m-d',$date));
            $first_date = strtotime($arr[0] . '-' . $arr[1] . '-01 00:00:00');
            $last_date = strtotime('+1 months', $first_date );
            $first_date = strtotime('+1 days', $first_date );
            $data1 = $patient->getLogByActivity(1, $first_date, $last_date);
            $data2 = $patient->getLogByActivity(7, $first_date, $last_date);
            return Json::encode(['data1'=>$data1, 'data2'=>$data2, 'title'=>date('F Y', $date), 'month'=>$month]);
        }

        return null;
    }

    public function actionSearchPatients()
    {
        $doctor = \Yii::$app->user->identity->getDoctor();

        $list = null;
        if (isset($_REQUEST['query']) && !empty($_REQUEST['query'])) {
            $query = Patient::find();
            $query->andFilterWhere(['or',
                ['like','username',$_REQUEST['query']],
                ['like','email',$_REQUEST['query']],
                ['like','name',$_REQUEST['query']]
            ]);
            $list = $query->all();
        }
        return $this->render('search', [
            'doctor'=>$doctor,
            'list'=>$list,
        ]);
    }

    public function actionAssignPatient($id)
    {
        /** @var Doctor $doctor */
        $doctor = \Yii::$app->user->identity->getDoctor();
        $patient = Patient::findOne($id);

        if (!is_null($patient) && (empty($patient->id_doctor) || $doctor->admin)) {
            $patient->id_doctor = $doctor->id;
            $patient->save();
        }

    }

    public function actionUnassignPatient($id)
    {
        /** @var Doctor $doctor */
        $doctor = \Yii::$app->user->identity->getDoctor();
        $patient = Patient::findOne($id);

        if (!is_null($patient) && ($patient->id_doctor==$doctor->id)) {
            $patient->id_doctor = 0;
            $patient->save();
        }

    }

    /**
     * @param $patient Patient
     * @param null $date
     * @return array
     */
    private function getPatientLogFor($patient, $date = null)
    {
        $result = [];

        // get week for this date
        if (!isset($date) || empty($date)) $date = time();

        // check if there is records for this date
        $fd = $patient->getFirstDate();
        $ld = $patient->getLastDate();
        if (!is_null($fd) && $fd>0 && !is_null($ld) && $ld>0) {
            // adjust selected date
            if ($date<$fd/1000) $date = $fd/1000;
            else if ($date>$ld/1000) $date = $ld/1000;

            $result['ini'] = strtotime('last sunday', $date);
            $result['end'] = strtotime('next saturday', $result['ini']);
            $result['data'] = $patient->getSleepLogsForDays($result['ini'], $result['end']);
            $result['prev'] = $patient->hasSleepLogsBefore($result['ini']) ? $date - 7*60*60*24 : 0;
            $result['next'] = $patient->hasSleepLogsAfter($result['end']) ? $date + 7*60*60*24 : 0;
        }
        return $result;
    }

    public function actionError()
    {
        if (($exception = Yii::$app->errorHandler->exception) === null) {
            return '';
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = Yii::t('yii', 'An internal server error occurred.');
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            return $this->render('error', [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }

}
