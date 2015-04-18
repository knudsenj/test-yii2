<?php 
namespace backend\modules\v1\controllers;

use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\web\ForbiddenHttpException;

class UserController extends ApiController
{
	public $modelClass = 'backend\modules\v1\models\User';

	public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator']['except'] = ['create'];
	    return $behaviors;
	}

	public function actions(){
		$actions = parent::actions();

		unset($actions['delete']);

		$actions['options']['resourceOptions'] = ['GET', 'PUT', 'PATCH', 'HEAD', 'OPTIONS'];

		return $actions;
	}

	public function checkAccess($action, $model = null, $params = [])
	{
	    // check if the user can access $action and $model
	    // throw ForbiddenHttpException if access should be denied
	    if($action == 'update' && Yii::$app->user->identity->id != $model->id){
	    	throw new ForbiddenHttpException("You cannot modify someone elses account");
	    }
	}
}