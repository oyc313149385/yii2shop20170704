<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Request;

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
               if (key_exists($goods->id, $cart)) {
                   $cart[$goods_id] += $amount;
               } else {
                   $cart[$goods_id] = $amount;
               }
               //cookie中数据是字符串，要序列化
               $cookie = new Cookie([
                   'name' => 'cart', 'value' => serialize($cart)
               ]);
               $cookies->add($cookie);
               //var_dump($cookies->add($cookie));exit;
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
                $this->layout = 'cart';
               if(Yii::$app->user->isGuest){
                   $cookies = Yii::$app->request->cookies;
                   $cookie = $cookies->get('cart');
                   if($cookie==null){
                       $cart = [];
                   }else{
                       $cart = unserialize($cookie->value);
                   }
                   //将cookie中的数据保存在数组中
                   $models = [];
                   foreach ($cart as $goods_id=>$amount){
                       //将goods对象变为数组
                       //$goods = Goods::findOne(['id'=>$goods_id])->attributes;
                       //var_dump($goods_id);exit;
                       $goods= Goods::findOne(['id'=>$goods_id])->attributes;
                       //var_dump($goods);exit;
                       $goods['amount'] = $amount;
                       $models[] = $goods;
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
                          $cookies=\Yii::$app->response->cookies;
                          $cookie=$cookies->get('cart');
                          $cookies->remove('cart');

                          $models=[];
                          //遍历对象取获取用户的所有goods
                          $mo=Cart::find()->where(['member_id'=>$member])->all();
                          foreach($mo as $goods_id ){
                              $goods= Goods::findOne(['id'=>$goods_id['goods_id']])->attributes;
                              $goods['amount']=$goods_id['amount'];
                              $models[]=$goods;
                           }



               }
               return $this->render('cart',['models'=>$models]);
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

        //订单界面显示
          public function actionOrder()
          {
            $this->layout = 'cart';

            $member_id = \Yii::$app->request->post('member_id');
            $address = Address::findAll(['member_id'=>$member_id]);
                          $models=[];
                          //遍历对象取获取用户的所有goods
                          $model=Cart::find()->where(['member_id'=>$member_id])->all();
                          //var_dump($model);
                          foreach($model as $goods_id ){
                              $goods= Goods::findOne(['id'=>$goods_id['goods_id']])->attributes;
                              $goods['amount']=$goods_id['amount'];
                              $models[]=$goods;
                           }
                          //var_dump($models);
                          /*$model=Cart::find()->where(['member_id'=>$member_id])->all();
                          //var_dump($mode);exit;
                          $amount = [];
                          foreach($model as $goods_id ){
                            //var_dump($goods_id);exit;
                              $goods= Goods::findOne(['id'=>$goods_id->goods_id]);
                             $amount[]=$goods_id['amount'];
                           }
                           var_dump($amount);exit;
                          //var_dump($goods);exit;*/
            return $this->render('order',['address'=>$address,'models'=>$models]);
          }


          //添加订单
          public function actionOrderAdd()
          {
              $this->layout = 'cart';
              $request = new Request();
              if($request->isPost) {
                //处理POST传输的值
                  $model = new Order();
                  $address_id = Yii::$app->request->post('address_id');
                  $total = doubleval(Yii::$app->request->post('total'));
                  //注意post提交过来的都是string类型
                  //$total = Yii::$app->request->post('total');
                  $address = Address::findOne(['id' => $address_id]);
                  //var_dump($address);exit;
                  //var_dump($total);exit;
                  $model->status = 1;
                  $model->trane_no = 1;
                  $model->create_time = time();
                  $model->name = $address->username;
                  $model->member_id = $address->member_id;
                  $model->province = $address->province;
                  $model->city = $address->city;
                  $model->area = $address->county;
                  $model->address = $address->detail;
                  $model->tel = $address->tel;
                  $model->total = $total;
                  $delivery = Yii::$app->request->post('delivery');
                  if ($delivery == 'pt') {
                      $model->delivery_id = 1;
                      $model->delivery_name = '普通快递';
                      $model->delivery_price = 10;
                  }
                  if ($delivery == 'tk') {
                      $model->delivery_id = 2;
                      $model->delivery_name = '特快专递';
                      $model->delivery_price = 20;
                  }
                  if ($delivery == 'jj') {
                      $model->delivery_id = 3;
                      $model->delivery_name = '加急快递送货上门';
                      $model->delivery_price = 30;
                  }
                  if ($delivery == 'py') {
                      $model->delivery_id = 4;
                      $model->delivery_name = '平邮';
                      $model->delivery_price = 5;
                  }
                  $pay = Yii::$app->request->post('pay');
                  if ($pay == 'hd') {
                      $model->payment_id = 1;
                      $model->payment_name = '货到付款';
                  }

                  if ($pay == 'zx') {
                      $model->payment_id = 2;
                      $model->payment_name = '在线支付';
                  }

                  if ($pay == 'sm') {
                      $model->payment_id = 3;
                      $model->payment_name = '上门自提';
                  }

                  if ($pay == 'yj') {
                      $model->payment_id = 4;
                      $model->payment_name = '邮局汇款';
                  }
                  //事务，表需要时innnodb
                  //开启事务
                  $transaction = Yii::$app->db->beginTransaction();
                  try{

                  $model->save(false);
                  //return $this->render('order-add');
                  //根据购物车数据，把商品的详情查询出来逐条保存
                  $carts = Cart::findAll(['member_id'=>Yii::$app->user->id]);
                    foreach($carts as $cart){
                        //将商品信息的查询出来
                        $goods = Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);
                        if($goods==null){
                            //商品不存在
                            throw new Exception('商品已售完');
                        }
                        if($goods->stock < $cart->amount){
                            //商品库存不足
                            throw new Exception('商品库存不足');
                        }
                        //添加商品详情,yii数据添加后会自动添加id
                        $order_goods = new OrderGoods();
                        $order_goods->order_id = $model->id;
                        $order_goods->goods_id = $goods->id;
                        $order_goods->goods_name = $goods->name;
                        $order_goods->logo = $goods->logo;
                        $order_goods->price = $goods->shop_price;
                        $order_goods->amount = $goods->amount;
                        $order_goods->total = $order_goods->price*$order_goods->amount;
                        $order_goods->save(false);
                        //减少该商品库存
                        $goods->stock -= $cart->amount;
                        $goods->save(false);
                        //return $this->render('order-add');

                    }
                //提交
                $transaction->commit();
                  }catch (Exception $e){
                      //数据异常回滚
                      $transaction->rollBack();
                  }
              }


              }
            
                //清理超时未支付订单
                public function actionClean()
                {   
                    //不限制脚本执行时间
                    set_time_limit(0);
                    while (1){
                        //超时未支付订单,待支付状态1超过1个小时则变为已取消0
                        $models = Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-3600])->all();
                        foreach($models as $model){
                            $model->status = 0;
                            $model->save();
                            //返还库存
                            foreach($model->goods as $goods){
                                Goods::updateAllCounters(['stock'=>$goods->amount],'id='.$goods->goods_id);
                            }
                            echo 'ID为'.$model->id.'的订单被取消了';

                        }
                        //1秒钟执行一次
                        sleep(1);
                    }


                }


               
}