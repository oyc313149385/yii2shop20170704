<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use Yii;

class GoodsController extends Controller{

    public $layout='list';

    //商品列表
    public function actionList($id){

        $models = Goods::findAll(['goods_category_id'=>$id]);
        //var_dump($models);exit;
        return $this->render('list',['models'=>$models]);
    }

    //商品
     public function actionIndex($id){

        $model = Goods::findOne(['id'=>$id]);
        //var_dump($models->name);exit;
        //var_dump($models);exit;
        return $this->render('index',['model'=>$model]);
    }

       //添加购物车
       public function actionAdd()
       {
           //点击加入购物车form表单以post传值
           $goods_id = Yii::$app->request->post('goods_id');
           //var_dump($goods_id);exit;
           $amount = Yii::$app->request->post('amount');
           //判断商品是否存在
           $goods = Goods::findOne(['id' => $goods_id]);
           if ($goods == null) {
               throw new NotFoundHttpException('没有商品');
           }
           if (Yii::$app->user->isGuest) {
               //未登录状态，获取cookie中购物车数据
               $cookies = Yii::$app->request->cookies;
               $cookie = $cookies->get('cart');
               //var_dump($cookie);exit;
               if ($cookie == null) {
                   //cookie中没数据，设置空数组防止报错
                   $cart = [];
               } else {
                   $cart = unserialize($cookie->value);
               }
               $cookies = Yii::$app->response->cookies;
               //
               if (key_exists($goods_id, $cart)) {
                   $cart['$goods_id'] += $amount;
               } else {
                   $cart['$goods_id'] = $amount;
               }
               //cookie中数据是字符串，要序列化
               $cookie = new Cookie([
                   'name' => 'cart', 'value' => serialize($cart)
               ]);
               $cookies->add($cookie);
              }else{
               //登录状态，将数据插入数据库
                   $member=\Yii::$app->user->id;
                   $mode=new Cart();
               if($model=Cart::findOne(['goods_id'=>$goods_id])){
                   $model->amount+=$amount;
                   $model->save(false);
               }
               else{
                    $mode->amount=$amount;
                    $mode->goods_id=$goods_id;
                    $mode->member_id=$member;
                    $mode->save(false);
               }

              }
              return $this->redirect(['goods/cart']);
        }
           //购物车显示
           public function actionCart()
           {
               if(Yii::$app->user->isGuest){
                   $cookies = Yii::$app->request->cookies;
                   $cookie = $cookies->get('cart');
                  // var_dump($cookie);exit;
                   if($cookie==null){
                       $cart = [];
                   }else{
                       $cart = unserialize($cookie->value);
                       //var_dump($cart);exit;
                   }
                   //将cookie中的数据保存在数组中
                   $models = [];
                   foreach ($cart as $goods_id=>$amount){
                      // var_dump($cart);exit;
                       var_dump($amount);exit;
                       //将goods对象变为数组
                       //$goods = Goods::findOne(['id'=>$goods_id])->attributes;
                       //var_dump($goods_id);exit;
                       $goods= Goods::findOne(['id'=>$goods_id])->attributes;
                       //var_dump($goods);exit;
                       $goods['amount'] = $amount;
                       $models = $goods;
                       //var_dump($models);exit;
                   }
               }else{
                   //登录状态，显示购物车界面
                    $cookies=\Yii::$app->request->cookies;
                    $member=\Yii::$app->user->id;
                    $cookie=$cookies->get('cart');
                    if($cookie==null){
                        $cart=[];
                    }else{
                        $cart=unserialize($cookie->value);
                    }
                    $mode=new Cart();

                    foreach($cart as $goods_id=>$amount) {
                        if ($model = Cart::findOne(['goods_id' => $goods_id])) {
                            $model->amount += $amount;
                            $model->save();
                        } else {
                            $mode->amount = $amount;
                            $mode->goods_id = $goods_id;
                            $mode->member_id = $member;
                            $mode->save();
                        }
                    }
                          $cooki=\Yii::$app->response->cookies;
                          $cookie=$cookies->get('cart');
                          $cooki->remove('cart');

                          $models=[];
                          //遍历对象取用户id值
                          $mo=Cart::find()->where(['member_id'=>$member])->all();
                    foreach($mo as $goods_id ){
                        $goods= Goods::findOne(['id'=>$goods_id['goods_id']])->attributes;
                        $goods['amount']=$goods_id['amount'];
                        $models[]=$goods;
                     }



               }
               return $this->render('cart',['models'=>'models']);
           }

           //更新购物车的数据
           public function actionUpdate()
           {
            //前端点击+-号时发送的ajax请求传输的数据
            $goods_id = \Yii::$app->request->post('goods_id');
            $amount = \Yii::$app->request->post('amount');
            $goods = Goods::findOne(['id'=>$goods_id]);
            if($goods==null){
                throw new NotFoundHttpException('商品不存在');
            }
            if(\Yii::$app->user->isGuest){
            //未登录
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookie中没有购物车数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
                //$cart = [2=>10];
            }
            //将商品id和数量存到cookie   id=2 amount=10  id=1 amount=3
            $cookies = \Yii::$app->response->cookies;
            /*$cart=[
                ['id'=>2,'amount'=>10],['id'=>1,'amount'=>3]
            ];*/
            //检查购物车中是否有该商品,有，数量累加
            /*if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }*/
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)) unset($cart[$goods_id]);
            }
            //$cart = [$goods_id=>$amount];
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
            }else{
            //已登录  修改数据库里面的购物车数据
                $member=\Yii::$app->user->id;
                $goods_id = \Yii::$app->request->post('goods_id');
                $amount = \Yii::$app->request->post('amount');
                $goods = Goods::findOne(['id'=>$goods_id]);
            if($goods==null){
                throw new NotFoundHttpException('商品不存在');
            }
            $model=Cart::findOne(['goods_id'=>$goods_id]);
            if($amount==0){
                Cart::findOne(['goods_id'=>$goods_id])->delete();
               }
                $model->amount=$amount;
                $model->save();
            }

          }
               
}