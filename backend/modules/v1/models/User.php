<?php
namespace backend\modules\v1\models;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password 
 */
class User extends \common\models\User
{
    public function scenarios(){
        return [
            'create' => ['username', 'email', 'password'],
            'update' => ['email', 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [['username', 'email', 'password'], 'required', 'on'=>['create']];
        $rules[] = ['email', 'email'];
        $rules[] = ['username', 'match', 'pattern' => '/^\w*$/i'];
        $rules[] = ['password', 'string', 'min' => 5];
        $rules[] = ['email', 'unique', 'targetAttribute'=>'lower(email)'];
        $rules[] = ['username', 'unique', 'targetAttribute'=>'lower(username)'];

        return $rules;
    }

    public function getPassword(){
        return $this->password_hash;
    }

    public function fields(){
        return [
            'id',
            'username',
            'since' => 'created_at'
        ];
    }   
}
