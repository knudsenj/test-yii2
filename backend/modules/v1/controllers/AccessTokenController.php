<?php 
namespace backend\modules\v1\controllers;

use Yii;
use yii\web\UnauthorizedHttpException;

class AccessTokenController extends ApiController
{
	public $modelClass = 'backend\modules\v1\models\User';

	public function actions(){
		return [];
	}

	public function actionCreate(){
		$username = Yii::$app->request->post("username");
		$password = Yii::$app->request->post("password");

		$modelClass = $this->modelClass;
		$model = $modelClass::findByUsername($username);
		if ($model && $model->validatePassword($password)) {
			$model->generateAccessToken();
			if($model->save(false)){
				return array(
					"id" => $model->id,
					"access_token" => $model->access_token,
				);
			}
		}
		throw new UnauthorizedHttpException(401);
	}

	public function actionUpdate(){
        $model = Yii::$app->user->identity;
        $model->generateAccessToken();
        if($model->save()){
            return [
                'id' => $model->id,
                'token' => $model->access_token,
            ];
        }
        throw new ServerErrorHttpException("Failed to generate access token.");
    }

    public function actionDelete(){
        $model = Yii::$app->user->identity;
        $model->access_token = null;
        if(!$model->save()){
            throw new ServerErrorHttpException("Failed to generate access token.");
        }
        Yii::$app->response->statusCode = 204;
    }

	public function behaviors()
    {
    	$behaviors = parent::behaviors();
        // Disable authentication for creating a token
        $behaviors['authenticator']['except'] = ['create'];

        return $behaviors;
    }
}