<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends \yii\web\Controller
{   
    //显示商品分类
    public function actionIndex()
    {   
        $models = GoodsCategory::find()->all();

        return $this->render('index',['models'=>$models]);
    }


    //添加商品分类
    public function actionAdd()
    {
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是添加一级分类（parent_id是否为0）
            if($model->parent_id){
                //添加非一级分类
                //获取上一级分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                //添加到上一级分类下面
                $model->prependTo($parent);
            }else{
                //添加一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods-category/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());


        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }

    //修改
    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是添加一级分类（parent_id是否为0）
            if($model->parent_id){
                //添加非一级分类
                //获取上一级分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                //添加到上一级分类下面
                $model->prependTo($parent);
            }else{
                //添加一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods-category/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());


        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
 

    //测试
    public function actionTest()
    {
        //创建一级菜单
        /*$jydq = new GoodsCategory();
        $jydq->name = '家用电器';
        $jydq->parent_id = 0;
        $jydq->makeRoot();//将当前分类设置为一级分类
        var_dump($jydq);*/

        /*
        //创建二级分类
        $parent = GoodsCategory::findOne(['id'=>1]);
        $xjd = new GoodsCategory();
        $xjd->name = '小家电';
        $xjd->parent_id = $parent->id;
        $xjd->prependTo($parent);
        echo '操作成功';*/
        //获取所有一级分类
       /* $roots = GoodsCategory::find()->roots()->all();
        var_dump($roots);*/
       //获取该分类下面的所有子孙分类
        /*$parent = GoodsCategory::findOne(['id'=>1]);
        $children = $parent->leaves()->all();
        var_dump($children);*/
    }

    public function actionZtree()
    {
        $categories = GoodsCategory::find()->asArray()->all();    
         
        //不加载布局文件 
        return $this->renderPartial('ztree',['categories'=>$categories]);
    }

}

