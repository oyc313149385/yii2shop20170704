<?php

namespace backend\controllers;
use backend\components\RbacFilter;
use yii\web\Request;
use yii\web\Controller;
use backend\models\Brand;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;

class BrandController extends Controller
{
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'only'=>['add','index'],
            ]
        ];
    }


    //显示主页

    public function actionIndex()
    {   
        //$brands = Brand::find()->all();
        $model =  new Brand();
        $brands = $model->find()->all();
        //根据条件查找所需要的数据
        //$brands = Brand::findAll(['status'=>1]);
        return $this->render('index',['brands'=>$brands]);
    }
    
    //显示添加界面
    public function actionAdd()
    {
        $model = new Brand();
        $request = new Request();
        if($request->isPost){
              $model->load($request->post());
              //$model->imgFile = UploadedFile::getInstance($model,'imgFile');
              if($model->validate()){
                   //$filename = '/images/'.uniqid().'.'.$model->imgFile->extension;
                   //$model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                   //$model->logo = $filename;
                   $model->save();
                  return $this->redirect(['brand/index']);
              }

        }
                return  $this->render('add',['model'=>$model]);
    }

    
    //修改
    public function actionEdit($id){
           $model = Brand::findOne(['id'=>$id]);
           $requset = new Request();
           if($requset->isPost){
               $model->load($requset->post());
              // $model->imgFile = UploadedFile::getInstance($model,'imgFile');
               if($model->validate()){
                   // $filename = '/images/'.uniqid().$model->imgFile->extension;
                   // $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                   // $model->logo = $filename;
                   $model->save();
                   return $this->redirect(['brand/index']);
               }
           }
              return $this->render('add',['model'=>$model]);
    }
    
    //删除 
    public function actionDel($id){
        Brand::findOne(['id'=>$id])->delete();
        return $this->redirect(['brand/index']);
    }
    
    //引入上传文件工具
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }


}
