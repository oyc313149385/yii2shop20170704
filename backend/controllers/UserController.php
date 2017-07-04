<?php
namespace backend\controllers;

use backend\models\User;
use backend\models\LoginForm;
use backend\models\Login1Form;
use yii\web\Controller;
use yii\web\Request;


class UserController extends Controller
{    

    public $defaultAction = 'login';
    //添加user
    public function actionAdd()
    {
         
        $model = new User();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
              
        
                $model->save();
               
               
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改user
    /*public function actionEdit($id)
    {
        $model = User::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
             //在数据验证之前，实例化文件上传对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                //保存图片
                $fileName = '/images/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                //图片地址赋值
                $model->img = $fileName;
                $model->save();
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }*/
    //分类列表
    public function actionIndex()
    {  
        $users = User::find()->findAll();

       
        return $this->render('index',['users'=>$users]);
    }
   
    //删除book
    public function actionDel($id)
    {
        User::findOne(['id'=>$id])->delete();
        return $this->redirect(['user/index']);
    }


    //判断当前用户是否已登录
    public function actionUser()
    {
        //实例化user组件
        $user = \Yii::$app->user;
        //获取当前登录用户实例(如果当前用户已登录)
        //var_dump($user->identity);
        //获取当前登录用户的id
        //var_dump($user->id);
        //判断当前用户是否是游客（未登录）
        var_dump($user->isGuest);
    }

    //登录
    //先认证（对比账号密码），再登录
    public function actionLogin()
    {
        /*$user = \Yii::$app->user;
        //假设admin已经认证通过
        $admin = Account::findOne(['id'=>1]);
        //登录（将登录标识保存到session）
        $user->login($admin);
        echo 'admin登录成功';*/
        $model = new LoginForm();
        $user = new User();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $user->load($request->post());
            if($model->validate()){
                $last_time = time();
                $ip = \Yii::$app->request->userIP;
                $user->last_ip = $ip;
                $user->save(); 
                //跳转到登录检测页
                return $this->redirect(['user/index']);

            }
        }


        return $this->render('login',['model'=>$model]);

    }
     
     //修改密码
     public function actionLogin1()
    {
        $model = new Login1Form();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //var_dump($model);exit;
                $account = \Yii::$app->user->identity;
                $account->password = $model->newpassword;
                //var_dump($model);
                //var_dump($account);exit;
                $account->save(false);
                //跳转到登录检测页
                return $this->redirect(['user/index']);

            }
        }


        return $this->render('login1',['model'=>$model]);

    }

    //退出 注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        echo '注销成功';
    }

}