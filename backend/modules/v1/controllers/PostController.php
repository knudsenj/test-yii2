<?php 
namespace backend\modules\v1\controllers;

use Yii;
use yii\web\ForbiddenHttpException;

class PostController extends ApiController
{
	public $modelClass = 'backend\modules\v1\models\Post';

	public function actions(){
		$actions = parent::actions();

		$actions['create'] = [
			'class' => 'backend\modules\v1\actions\CreateWithOwnerAction',
			'ownerAttribute' => 'user_id',
			'scenario' => 'create',
			'modelClass' => $this->modelClass,
		];

		$actions['createLike'] = [
			'class' => 'backend\modules\v1\actions\CreateLinkToUserAction',
			'linkName' => 'users',
			'modelClass' => $this->modelClass,
		];
		$actions['deleteLike'] = [
			'class' => 'backend\modules\v1\actions\DeleteLinkToUserAction',
			'linkName' => 'users',
			'modelClass' => $this->modelClass,
		];
		$actions['indexLike'] = [
			'class' => 'backend\modules\v1\actions\IndexLike',
		];

		return $actions;
	}

	public function checkAccess($action, $model = null, $params = [])
	{
	    // check if the user can access $action and $model
	    // throw ForbiddenHttpException if access should be denied
	    if(($action == 'update' || $action == 'delete') 
	    		&& Yii::$app->user->identity->id != $model->user_id){
	    	throw new ForbiddenHttpException("You cannot modify someone elses post");
	    }
	}	
}