<?php 
namespace backend\modules\v1\controllers;

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