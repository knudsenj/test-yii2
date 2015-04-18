<?php 
namespace backend\modules\v1\controllers;

use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class PostController extends ActiveController
{
	public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => HttpBasicAuth::className(),
	    ];
	    return $behaviors;
	}

	public function actions(){
		$actions = parent::actions();

		$actions['createLike'] = [
			'class' => 'backend\modules\v1\actions\CreateLike',
		];
		$actions['deleteLike'] = [
			'class' => 'backend\modules\v1\actions\DeleteLike',
		];
		$actions['indexLike'] = [
			'class' => 'backend\modules\v1\actions\IndexLike',
		];

		return $actions;
	}


	public function fields(){
		return [
			'title',
			'body',
			'date' => 'created_at',
			'author' => 'user',
			'likes' => 'likes',
		];
	}	
}