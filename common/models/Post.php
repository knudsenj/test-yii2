<?php

namespace common\models;

use Intervention\Image\ImageManagerStatic as Image;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Url;

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

    public $photo;

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
            [['title', 'body', 'photo_original', 'photo_160', 'photo_320', 'photo_640'], 'string'],
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
            'photo_original' => 'Original Photo',
            'photo_160' => '160px Wide Photo',
            'photo_320' => '320px Wide Photo',
            'photo_640' => '640px Wide Photo',
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

    private function getPhotoUrl($relativePath){
        if ($relativePath != null) {
            if (strpos($relativePath, 'http') === 0) {
                return $relativePath;
            }
            return Url::base(true).$relativePath;
        }
    }

    public function getPhotoOriginal(){
        return $this->getPhotoUrl($this->photo_original);
    }

    public function getPhoto160(){
        return $this->getPhotoUrl($this->photo_160);
    }

    public function getPhoto320(){
        return $this->getPhotoUrl($this->photo_320);
    }

    public function getPhoto640(){
        return $this->getPhotoUrl($this->photo_640);
    }

    private function savePhotos(){
        if (isset($this->photo)){
            $name = uniqid();
            $directory = "/media/photos/";
            $base = Yii::$app->basePath."/web";
            $relative = "{$directory}original/{$name}.".$this->photo->extension;
            $absolute = "{$base}{$relative}";

            $this->photo->saveAs($absolute);
            $this->photo_original = $relative;

            $image = Image::make($absolute);
            for($i = 160; $i <= 640; $i*=2){
                $attribute = "photo_$i";
                if($image->width() > $i) {
                    $image->backup();
                    $relative = "{$directory}$i/${name}.jpg";
                    $absolute = "{$base}{$relative}";
                    $image->widen($i)
                        ->save($absolute);
                    $this->$attribute = $relative;
                    $image->reset(); 
                } else {
                    $this->$attribute = $relative;
                }
            }
        }
    }

    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            $this->savePhotos();
        }
        return true;
    }
}
