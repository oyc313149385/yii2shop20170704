<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {   $brands = Brand::find()->all();
        return $this->render('index',['brands'=>$brands]);
    }
    //添加品牌
    public function actionAdd()
    {
        $model = new Brand();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            //$model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证
            if($model->validate()){
                //保存图片
//                $fileName = '/images/'.uniqid().'.'.$model->imgFile->extension;
//                $model->imgFile->saveAs(\yii::getAlias('@webroot').$fileName,false);
//                $model->logo = $fileName;
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','品牌添加成功');
                return $this->redirect(['brand/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id)
    {
        $model = Brand::findone(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证
            if($model->validate()){
                //保存图片
                $fileName = '/images/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\yii::getAlias('@webroot').$fileName,false);
                $model->logo = $fileName;
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','品牌添加成功');
                return $this->redirect(['brand/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }
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
