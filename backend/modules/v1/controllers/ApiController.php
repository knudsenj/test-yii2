<?php 
namespace backend\modules\v1\controllers;

use common\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class ApiController extends ActiveController
{
	public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => HttpBasicAuth::className(),
	        'auth' => [$this, 'auth'],
	    ];
	    return $behaviors;
	}

	public function auth($username, $password){
		$user = User::findByUsername($username);
		if (isset($user) && $user->validatePassword($password)){
			return $user;
		}
	}
}