<?php 
namespace backend\modules\v1\controllers;

use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class UserController extends ActiveController
{
	public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => HttpBasicAuth::className(),
	        'except' => 'create',
	    ];
	    return $behaviors;
	}

	public function actions(){
		$actions = parent::actions();

		unset($actions['delete']);

		return $actions;
	}

	public function checkAccess($action, $model = null, $params = [])
	{
	    // check if the user can access $action and $model
	    // throw ForbiddenHttpException if access should be denied
	    if($action == 'update' && Yii::$app->user->identity != $model){
	    	throw new ForbiddenHttpException;
	    }
	}

	public function fields(){
		return [
			'username',
			'since' => 'created_at'
		];
	}	
}