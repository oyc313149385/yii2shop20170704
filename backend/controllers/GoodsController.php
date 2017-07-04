<?php
namespace backend\controllers;
use backend\models\GoodsDayCount;
use yii\web\Request;
use backend\models\Goods;
use backend\models\GoodsIntro;
use xj\uploadify\UploadAction;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {   
       $key=isset($_GET['key'])?$_GET['key']:'';
       $model=Goods::find()->where(['like','name',$key]);
       $total=$model->count();
       $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>3,

        ]);
        $cate=$model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$cate,'page'=>$page]);
    }

    //显示添加界面
    public function actionAdd()
    {
        $goodses = new Goods();
        $category = new GoodsCategory();
        $intro = new GoodsIntro();
        $daycount = new GoodsDayCount();
        $request = new Request();
       
        if($request->isPost){
            $goodses->load($request->post());
            $intro->load($request->post());
            //var_dump($goodses);exit;
            if($goodses->validate()&&$intro->validate()){
                $day = date('Ymd');
                if(!empty( GoodsDayCount::findOne(['day'=>$day]))){
                    $daycount = GoodsDayCount::findOne(['day'=>$day]);
                    $daycount->count+=1;
                    $daycount->save();
                }else{
                    $daycount->day=$day;
                    //var_dump($daycount->day);exit;
                    $daycount->count=0;
                    $daycount->save();
                }
                //注意数据库里sn类型不能是int,int位数只有11位，
                $goodses->sn=$day.substr('000'.($daycount->count+1),-4,4);
                //var_dump($goodses->sn);


                $goodses->save();
                $intro->goods_id = $goodses->id;
                $intro->save();


                return $this->redirect(['goods/index']);
            }

        }

        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return  $this->render('add',['goodses'=>$goodses,'categories'=>$categories,'intro'=>$intro,'daycount'=>$daycount,'category'=>$category]);
    }
     
     //显示修改界面
    public function actionEdit($id)
    {
        $goodses = Goods::findOne(['id'=>$id]);
        $category = GoodsCategory::findOne(['id'=>$id]);
        $intro = GoodsIntro::findOne(['goods_id'=>$id]);
        
        $request = new Request();
       
        if($request->isPost){
            $goodses->load($request->post());
            $intro->load($request->post());
            //var_dump($goodses);exit;
            if($goodses->validate()&&$intro->validate()){

                $goodses->save();
                $intro->goods_id = $goodses->id;
                $intro->save();


                return $this->redirect(['goods/index']);
            }

        }

        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return  $this->render('add',['goodses'=>$goodses,'categories'=>$categories,'intro'=>$intro,'category'=>$category]);
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


             'ueditor' => [
                'class' => 'crazyfd\ueditor\Upload',
                'config'=>[
                    'uploadDir'=>date('Y/m/d')
                ]

            ],
        ];
    }

      /*
     * 商品相册
     */
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }


        return $this->render('gallery',['goods'=>$goods]);

    }

    /*
     * AJAX删除图片
     */
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }

   

}
