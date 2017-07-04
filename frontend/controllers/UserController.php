<?php
namespace frontend\controllers;
use common\models\LoginForm;
use frontend\models\Login;
use frontend\models\LoginFrom;
use frontend\models\Member;
use yii\bootstrap\Html;
use yii\web\Controller;

class UserController extends Controller{
    public $layout = 'login';
    //public $layout = 'index';


    //用户登录
    public function actionLogin(){
        $model=new LoginFrom();


        if ($model->load(\Yii::$app->request->post())&& $model->validateUsername() ) {
            \Yii::$app->session->setFlash('seccuss', '登陆成功');
            return $this->redirect(['address/add']);
        }
        return $this->render('login',['model'=>$model]);
    }


 public function actionRegister()
 {
     $model = new Member();
     if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
         $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);

         $model->save(false);
         \Yii::$app->session->setFlash('seccuss', '注册成功');

         return $this->redirect(['login']);

     }
     return $this->render('register', ['model' => $model]);
 }

    //用户注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
}