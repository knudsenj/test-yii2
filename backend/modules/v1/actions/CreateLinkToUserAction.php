<?php

namespace backend\modules\v1\actions;

use Yii;
use yii\base\Model;
use yii\db\IntegrityException;
use yii\helpers\Url;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

class CreateLinkToUserAction extends Action
{
    public $linkName;

    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $modelClass = $this->modelClass;
        $model = $modelClass::findOne($id);

        try{
            $model->link($this->linkName, Yii::$app->user->identity);

            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        } catch (IntegrityException $e){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(208);
        }

        return $model;
    }
}
