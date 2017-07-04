<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;//用户名
    public $password;//密码

    public function rules()
    {
        return [
            [['username','password'],'required'],
            //添加自定义验证方法
            ['username','validateUsername'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码'
        ];
    }

    //自定义验证方法
    public function validateUsername(){
        $account = Account::findOne(['username'=>$this->username]);
        if($account){
            //用户存在 验证密码
            if($this->password != $account->password){
                $this->addError('password','密码不正确');
            }else{
                //账号秘密正确，登录
                \Yii::$app->user->login($account);
            }
        }else{
            //账号不存在  添加错误
            $this->addError('username','账号不正确');

        }
    }
}