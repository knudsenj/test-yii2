<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "general.post".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property string $created_at
 * @property string $updated_at
 * @property integer $user_id
 *
 * @property User $user
 * @property UserLikesPost[] $userLikesPosts
 * @property User[] $users
 */
class Post extends \yii\db\ActiveRecord
{
    public $userClassName = 'common\models\User';
    public $userLikesPostClassName = 'common\models\UserLikesPost';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'general.post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body', 'user_id'], 'required'],
            [['title', 'body'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'body' => 'Body',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne($this->userClassName, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLikesPosts()
    {
        return $this->hasMany($this->userLikesPostClassName, ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        $userLikesPostClassName = $this->userLikesPostClassName;
        return $this->hasMany($this->userClassName, ['id' => 'user_id'])
            ->viaTable($userLikesPostClassName::tableName(), ['post_id' => 'id']);
    }
}
