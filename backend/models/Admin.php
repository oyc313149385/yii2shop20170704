<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\rbac\Role;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;//保存密码的明文
    //public $defaultUrl = 'login';
    public $role=[];//用户角色

    /*
     * 定义场景
     */
    const SCENARIO_ADD = 'add';
    
    
      public static function getRoleOption(){
        $authManager=\Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(),'name','description');//获取所有角色
    }
     
     public function addRole($id){
          $authManager=\Yii::$app->authManager;
          foreach($this->role as $role){
            $role=$authManager->getRole($role);
            //var_dump($role);exit;
            if($role) {$authManager->assign($role,$id);}
        }
        return true;
 }
   
    //修改回显
    public  function loadData($roles){
        foreach ($roles as $role){
            $this->role[]=$role->name;
        }
    }

    public function updateRole($id){
        $authManager=\Yii::$app->authManager;
        if(Admin::findOne(['id'=>$id])){
            $authManager->revokeAll($id);
            $roles=$this->role;
            if($roles){
                foreach($roles as $role){
                    $authManager->assign($authManager->getRole($role),$id);
                }
            }
            return true;
        }else{
            throw new NotFoundHttpException('错误');
        }
      }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            ['password','required','on'=>self::SCENARIO_ADD],
            //限制密码长度8-32位
            ['password','string','length'=>[8,32],'tooShort'=>'密码太短了'],
            [['status', 'created_at', 'updated_at', 'last_login_time'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['last_login_ip'], 'string', 'max' => 15],
            [['username'], 'unique'],//on指定验证规则生效场景
            [['email'], 'unique'],
            [['email'], 'email'],
            [['role'], 'safe'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'roles' => '角色',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
        ];
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at = time();
            $this->status = 1;
            //生成随机字符串
            $this->auth_key = Yii::$app->security->generateRandomString();
        }
        if($this->password){
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }
        //给用户关联权限
        /*if($this->roles){
            $authManager = Yii::$app->authManager;
            $authManager->revokeAll($this->id);
            foreach ($this->roles as $roleName){
                $role = $authManager->getRole($roleName);
                if($role) $authManager->assign($role,$this->id);
            }
        }*/
        return parent::beforeSave($insert);
    }


    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {

        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;

    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() == $authKey;
    }

    
}
