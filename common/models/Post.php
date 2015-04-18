<?php

namespace common\models;

use Yii;

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
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLikesPosts()
    {
        return $this->hasMany(UserLikesPost::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_likes_post', ['post_id' => 'id']);
    }
}
