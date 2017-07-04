<?php
namespace frontend\models;
use yii\base\Model;

class LoginFrom extends Model{

    public $password;
    public $code;
    public $username;
    public $remenber;


    public function rules()
    {
        return [
            [['username','password'], 'required','message'=>'用户名名或密码不能为空'],
            ['username','validateUsername'],
            ['remenber','boolean']

        ];
    }

    /**
     * @inheritdoc
     */


    public function attributeLabels()
    {
        return [

            'username' =>'用户名',

            'password'=>'密码',

            'code'=>'验证码',
            'remenber'=>'记住我'

        ];
    }
    public function validateUsername(){
        $asd=Member::findOne(['username'=>$this->username]);
        if($asd){

            if(!(\Yii::$app->security->validatePassword($this->password,$asd->password_hash))){
                $this->addError('password','密码不正确');
            }else{

                $asd->last_login_ip=\Yii::$app->request->userIP;
                $asd->auth_key=\Yii::$app->security->generateRandomString();
                $asd->last_login_time=time();
//                var_dump(\Yii::$app->user->isGuest);
//                exit;
                $asd->save(false);
                $duration=$this->remenber?7*24*3600:0;

                \Yii::$app->user->login($asd,$duration);
                return true;
            }
        }else{
            $this->addError('username','账号不正确');
        }
    }


}