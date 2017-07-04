<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use backend\models\ArticleCategory;
use yii\web\Controller;
use yii\web\Request;


class ArticleController extends Controller{

    public function actionIndex()
    {
          $articles = Article::find()->all();
          return $this->render('index',['articles'=>$articles]);   


    }


   public function actionAdd()
   {
       $articlecategory = ArticleCategory::find()->all();
       $article_detail = new ArticleDetail();
       $model = new Article();
       $request = new Request();
       if($request->isPost){
           $article_detail->load($request->post());
           $model->load($request->post());
           if($model->validate()&&$article_detail->validate()){
               $model->create_time = time();
               $model->save();
               $article_detail->article_id = $model->id;
               $article_detail->save();
               return $this->redirect(['article/index']);
           }
       }

        return $this->render('add',['model'=>$model,'articlecategory'=>$articlecategory,'article_detail'=>$article_detail]);
   }

    public function actionEdit($id)
   {   
       $articlecategory = ArticleCategory::find()->all();
       $model = Article::findOne(['id'=>$id]);
       //$article_detail = ArticleDetail::findOne(['article_id'=>$id]);
       $article_detail = ArticleDetail::findOne(['article_id'=>$id]);
       //var_dump($model->detail);exit;
       $request = new Request();
       if($request->isPost){
           $article_detail->load($request->post());
           $model->load($request->post());
           if($model->validate()){
               $model->save();
               $article_detail->save();
               return $this->redirect(['article/index']);
           }
       }

        return $this->render('add',['model'=>$model,'articlecategory'=>$articlecategory,'article_detail'=>$article_detail]);
   }

      public function actionDel($id)
    {
        Article::findOne(['id'=>$id])->delete();
        return $this->redirect(['article/index']);
    }

    //查看文章详情页面
    public function actionView($id)
    {
        $model = Article::findOne($id);

        return $this->render('view',['model'=>$model]);
    }


}