<?php 
namespace backend\modules\v1\controllers;

class PostController extends ApiController
{
	public $modelClass = 'backend\modules\v1\models\Post';

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