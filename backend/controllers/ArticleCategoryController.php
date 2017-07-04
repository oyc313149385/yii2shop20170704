<?php

namespace backend\controllers;

use yii\web\Request;
use yii\web\Controller;
use backend\models\ArticleCategory;


class ArticleCategoryController extends Controller
{
    public function actionIndex()
    {
        $article_categorys = ArticleCategory::find()->all();

        return $this->render('index',['article_categorys'=>$article_categorys]);
    }

    public function actionAdd()
    {             
        $model = new ArticleCategory();
        $request = new Request();
        if($request->isPost){
              $model->load($request->post());
              if($model->validate()){
                   $model->save();
                  return $this->redirect(['articlecategory/index']);
              }

        }
                return  $this->render('add',['model'=>$model]);
    }

    

    public function actionEdit($id){
           $model = ArticleCategory::findOne(['id'=>$id]);
           $requset = new Request();
           if($requset->isPost){
               if($model->validate()){
                   $model->save();
                   return $this->redirect(['articlecategory/index']);
               }
           }
              return $this->render('add',['model'=>$model]);
    }

    public function actionDel($id){
        ArticleCategory::findOne(['id'=>$id])->delete();
        return $this->redirect(['articlecategory/index']);
    }

}
