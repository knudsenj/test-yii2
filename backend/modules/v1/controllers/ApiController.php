<?php 
namespace backend\modules\v1\controllers;

use common\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class ApiController extends ActiveController
{
	public $createScenario = 'create';

	public $updateScenario = 'update';

	public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => HttpBasicAuth::className(),
	    ];
	    return $behaviors;
	}
}