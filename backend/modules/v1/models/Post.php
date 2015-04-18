<?php
namespace backend\modules\v1\models;

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
class Post extends \common\models\Post
{
    public $userClassName = 'backend\modules\v1\models\User';

    public function scenarios(){
        return [
            'create' => ['title', 'body'],
            'update' => ['title', 'body'],
        ];
    }

    public function getLikes(){
        return [
            'count' => (integer) $this->getUsers()->count(),
            'users' => $this->users,
        ];
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

    public function afterSave($insert, $changedAttributes){
        $this->refresh();
        parent::afterSave($insert, $changedAttributes);
    }
}
