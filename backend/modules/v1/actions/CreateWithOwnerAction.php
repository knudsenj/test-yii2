<?php

namespace backend\modules\v1\actions;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

class CreateWithOwnerAction extends Action
{
    public $scenario = Model::SCENARIO_DEFAULT;

    public $viewAction = 'view';

    public $ownerAttribute;

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        $model->{$this->ownerAttribute} = Yii::$app->user->identity->id;

        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }
}
