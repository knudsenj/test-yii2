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
        $userLikesPostClassName = $this->userLikesPostClassName;
        return [
            'count' => (integer) $this->getUsers()->count(),
            'users' => $this->getUsers()
                ->joinWith("userLikesPosts")
                ->orderBy([$userLikesPostClassName::tableName().'.created_at'=>SORT_DESC])
                ->limit(3)->all(),
        ];
    }

    public function fields(){
        return [
            'id',
            'title',
            'body',
            'date' => 'created_at',
            'author' => 'user',
            'likes' => 'likes',
            'photo_original_url' => 'photoOriginal',
            'photo_160_url' => 'photo160',
            'photo_320_url' => 'photo320',
            'photo_640_url' => 'photo640',
        ];
    }

    public function afterSave($insert, $changedAttributes){
        $this->refresh();
        parent::afterSave($insert, $changedAttributes);
    }
}
