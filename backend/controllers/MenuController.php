<?php
namespace backend\controllers;

use backend\models\Menu;
use yii\web\Controller;

class MenuController extends Controller{
    //展示增加页面
    public function actionAdd(){
        $model=new Menu();

        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->parent_id==null){
                $model->parent_id=0;
            }
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    
    //修改界面 
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->parent_id==null){
                $model->parent_id=0;
            }
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    //删除
    public function actionDelete($id){
        Menu::findOne(['id'=>$id])->delete();
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['menu/index']);
    }



    //显示主页面
    public function actionIndex(){

        $model=Menu::find()->all();
          return $this->render('index',['model'=>$model]);
    }


}