<?php 
namespace backend\modules\v1\controllers;

use common\models\UserLikesPost;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class PostController extends ApiController
{
	public $modelClass = 'backend\modules\v1\models\Post';

	const PAGE_SIZE = 20;

	const PAGE_MAX = 50;

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
			'class' => 'yii\rest\IndexAction',
			'modelClass' => $this->modelClass,
			'prepareDataProvider' => [$this, 'prepareLikesDataProvider'],
		];

		$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

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

	public function prepareDataProvider() {
		$modelClass = $this->modelClass;
		$query = $modelClass::find();

		$count = Yii::$app->request->get('count', static::PAGE_SIZE);
		$count = $count > static::PAGE_MAX ? static::PAGE_MAX : $count;
		$count = $count < 0 ? 0 : $count;

		$since_id = Yii::$app->request->get('since_id');
		$max_id = Yii::$app->request->get('max_id'); 

		if ($since_id != '') {
            $query->andWhere("id > :since_id", [':since_id' => $since_id]);
        }
        if ($max_id != '') {
            $query->andWhere("id < :max_id", [':max_id' => $max_id]);
        }
        $query->limit($count)
			->orderBy(['id'=>SORT_DESC]);

		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => false,
		]);
	}

	public function prepareLikesDataProvider(){
		$modelClass = $this->modelClass;
		$id = Yii::$app->request->get('id');
		$model = $modelClass::findOne($id);

		if(!isset($model)){
			throw new NotFoundHttpException;
		}

		return new ActiveDataProvider([
			'query' => $model->getUsers()
				->joinWith("userLikesPosts")
				->orderBy([UserLikesPost::tableName().'.created_at'=>SORT_DESC]),
		]);
	}
}