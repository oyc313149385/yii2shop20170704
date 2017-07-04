<?php
namespace frontend\controllers;



use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use yii\web\Controller;
use backend\models\Goods;
use yii\web\Request;
use yii\web\Response;

class ApiController extends Controller
{
    //关闭csrf验证
    public $enableCsrfValidation = false;

    //所有实例化类（只要继承Object或其子类），一般都会执行init这个方法
    public function init(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();

    }

    //获得品牌下面的所有商品，get
    public function actionGetGoodsByBrand(){
        if($brand_id = \Yii::$app->request->get('brand_id')){
            $goods = Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>$goods];
        }
            return ['status'=>-1,'msg'=>'brand_id不正确'];
    }

    //获取某分类下面的所有商品,get
    public function actionGetGoodsByCategoryId(){
        $category_id = \Yii::$app->request->get('goods_categor_id');
        if($category_id){
            $goods = Goods::find()->where(['goods_categroy_id'=>$category_id]);
            return ['status'=>1,'msg'=>'','data'=>$goods];
        }
        return ['status'=>-1,'msg'=>'goods_category_id不正确'];
    }

    //用户注册，post
    public function actionGetUserRegister(){
        $request = new Request();
        if($request->isPost){
            $member = new Member();
            $member->username = $request->post('username');
            $password = $request->post('password');
            $repassword = $request->post('repassword');
            //将得到的明文密码转为加盐加密
            $member->password_hash=\Yii::$app->security->generatePasswordHash($password);
            $member->email = $request->post('email');
            $member->tel = $request->post('tel');
            $member->code = $request->post('code');

            if($member->validate()){
                $member->save();
                return ['status'=>1,'msg'=>'','data'=>$member->toArray()];
            }
            //验证数据失败
            return ['status'=>-1,'msg'=>$member->getErrors()];
        }
        return ['status'=>-1,'msg'=>'提交方式不是POST'];
    }

    //用户登录，POST
    public function actionLogin(){
       $request = new Request;
       if($request->isPost){
           $member = Member::findOne(['username'=>$request->post('username')]);
           if($member && \Yii::$app->security->validatePassword($request->post('password'),$member->password_hash)){
               \Yii::$app->user->login($member);
               return ['status'=>1,'msg'=>'登录成功'];
           }
           return ['status'=>-1,'msg'=>'账号或密码错误'];
       }
          return ['status'=>-1,'msg'=>'提交方式不是POST'];
    }

    //获取当前登录的用户信息
    public function actionGetCurrentUser(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>-1,'msg'=>'请先登录'];
        }
        return ['status'=>1,'msg'=>'','data'=>\Yii::$app->user->identity->toArray()];
    }

    //添加收货地址,post
    public function actionAddAdress(){
        $request = new Request();
        if($request->isPost && !\Yii::$app->user->isGuest){
            $address = new Address();
            $address->username = $request->post('username');
            $address->province = $request->post('province');
            $address->city = $request->post('city');
            $address->county = $request->post('county');
            $address->tel = $request->post('tel');
            $address->detail = $request->post('detail');
            $address->member_id = \Yii::$app->user->id;
            $address->status = 0;
            if($address->validate()){
                $address->save();
                return ['status'=>1,'msg'=>'','data'=>$address->toArray()];
            }
        }
        return ['status'=>-1,'msg'=>'提交方式不对'];
    }

        public function actionEditAddress(){
            $request = new Request();
            if($request->isPost && !\Yii::$app->user->isGuest){
                $id = $request->post('id');
                $address = Address::findOne(['id'=>$id]);
                $address->username = $request->post('username');
                $address->province = $request->post('province');
                $address->city = $request->post('city');
                $address->county = $request->post('county');
                $address->tel = $request->post('tel');
                $address->detail = $request->post('detail');
                $address->member_id = \Yii::$app->user->id;
                $address->status = 0;
                if($address->validate()){
                    $address->save();
                    return ['status'=>1,'msg'=>'','data'=>$address->toArray()];
                }
            }
            return ['status'=>-1,'msg'=>'提交方式不对'];
        }

        public function actionDeleteAddress(){
            if($id=\Yii::$app->request->get('id')){
                Address::findOne(['id'=>$id])->delete();
                return ['status'=>1,'msg'=>'删除成功'];
            }
            return ['status'=>-1,'msg'=>'提交方式不对'];
        }

        public function actionIndexAddress(){
            $address = Address::findAll()->asArray();
            return ['status'=>1,'msg'=>'','data'=>$address];
        }

        public function actionGetGoodsCategory(){
            $category = GoodsCategory::findAll()->asArray();
            return ['status'=>1,'msg'=>'','data'=>$category];
        }

        public function actionGetGoodsCategoryChild(){
            if($id=\Yii::$app->request->get('id')){
                $category = GoodsCategory::findAll(['parent_id'=>$id])->asArray();
                return ['status'=>1,'msg'=>'','data'=>$category];
            }
            return ['status'=>-1,'msg'=>'提交方式不对'];
        }

        public function actionGetGoodsCategoryParent(){
            if($parent_id=\Yii::$app->request->get('parent_id')){
                $category = GoodsCategory::findAll(['id'=>$parent_id])->asArray();
                return ['status'=>1,'msg'=>'','data'=>$category];
            }
            return ['status'=>-1,'msg'=>'提交方式不对'];
        }

        public function actionGetAllArticleCategory(){
             $category = ArticleCategory::findAll()->asArray();
             return ['status'=>1,'msg'=>'','data'=>$category];
        }

       public function actionGetArticleByAticleCategory(){
        if($article_category_id = \Yii::$app->request->get('article_category_id')){
            $article = Article::find()->where(['article_category_id'=>$article_category_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>$article];
        }
        return ['status'=>-1,'msg'=>'文章分类id不正确'];
       }

       public function actionGetActicleCategoryByArticle(){
           if($id = \Yii::$app->request->get('id')){
               $article = Article::findOne(['id'=>$id]);
               $articlecategoryid = $article->article_category_id;
               $articlecategory = ArticleCategory::findOne(['id'=>$articlecategoryid])->asArray();
               return ['status'=>1,'msg'=>'','data'=>$articlecategory];
           }
           return ['status'=>-1,'msg'=>'文章id不正确'];
       }

                //添加商品到购物车
                public function actionCartAddGoods(){
                //未登录
                $goods_id=\Yii::$app->request->post('goods_id');
                $amount=\Yii::$app->request->post('amount');
                $goods = Goods::findOne(['id'=>$goods_id]);
                if($goods == null){
                    return ['status'=>-1,'msg'=>'没有此商品'];
                }
                if(\Yii::$app->user->isGuest){
                    //缓存
                    //获取response里面的cookie
                    $cookies=\Yii::$app->request->cookies;
                    $cookie=$cookies->get('cart');
                    if($cookie == null){
                        $cart=[];
                    }else{
                        $cart = unserialize($cookie->value);;
                    }
                    $cookiess=\Yii::$app->response->cookies;
                    //如果不存在这个建名就创建
                    if(key_exists($goods->id,$cart)){
                        $cart[$goods_id] += $amount;
                    }else{
                        $cart[$goods_id] = $amount;
                    }
                    $cookie=new Cookie([
                        'name'=>'cart',
                        'value'=>serialize($cart)
                    ]);
                   $cookiess->add($cookie);
                    return ['status'=>1,'msg'=>'存入cookie成功','data'=>$cart];
                }else{
                    //登录
                    $model=new Cart();
                    $member_id=\Yii::$app->user->id;
                    $cart = Cart::find()->where(['member_id' => $member_id])->andWhere(['goods_id'=>$goods_id])->one();
                    if(\Yii::$app->request->isPost){
                        if($cart){
                            $cart->amount  +=$amount;
                            $cart->save();
                            return ['status'=>1,'msg'=>'累加成功','data'=>$cart];
                        }else{
                            $model->goods_id=$goods_id;
                            $model->amount=$amount;
                            $model->member_id=$member_id;
                            $model->save(false);
                            return ['status'=>1,'msg'=>'添加成功','data'=>$model];
                        }

                    }
                    return ['status'=>-1,'msg'=>'提交方式不正确'];
                }
            }
            //修改购物车某商品数量
            public function actionCartEditGoods(){
                //接收goods_id和amount
                $goods_id = \Yii::$app->request->post('goods_id');
                $amount = \Yii::$app->request->post('amount');
                $goods=Goods::findOne(['id'=>$goods_id]);
                if($goods == null){
                    return ['status'=>-1,'msg'=>'没有该商品'];
                }
                    //未登录
                if(\Yii::$app->user->isGuest){
                    //获取response里面的cookie
                    $cookies = \Yii::$app->request->cookies;
                    $cookie = $cookies->get('cart');
                    if ($cookie == null) {
                        return ['status'=>-1,'msg'=>'没有该商品'];
                    } else {
                        $cart = unserialize($cookie->value);;
                    }
                    $cookiess= \Yii::$app->response->cookies;
                    $cart[$goods->id] = $amount;
                        $cookie1=new Cookie([
                            'name'=>'cart',
                            'value'=>serialize($cart)
                        ]);
                        $cookiess->add($cookie1);
                    return ['status'=>1,'msg'=>'修改成功','data'=>$cart];

                }else{
                    //登录  操作数据表
                    $member_id=\Yii::$app->user->id;
                    $cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
                    $cart->amount=$amount;
                    $cart->save();
                    return ['status'=>1,'msg'=>'修改成功','data'=>$cart];
                }
            }
            //删除购物车某商品
            public function actionCartDelGoods(){
                //未登录
                if(\Yii::$app->request->isGet){
                    $goods_id=\Yii::$app->request->get('goods_id');
                    if(\Yii::$app->user->isGuest){
                        //获取response里面的cookie
                        $cookies=\Yii::$app->request->cookies;
                        $cookie=$cookies->get('cart');
                        $cart = unserialize($cookie->value);
                        unset($cart[$goods_id]);
                        return ['status'=>1,'msg'=>'删除成功'];
                    }else{
                        //登录  操作数据表
                        $member_id=\Yii::$app->user->id;
                        $cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
                        $cart->delete();
                        return ['status'=>1,'msg'=>'删除成功'];
                    }
                }
                return ['status'=>-1,'msg'=>'提交方式错误'];

            }
            //清空购物车
            public function actionGetCartCleanGoods(){
                //未登录
                if(\Yii::$app->user->isGuest){
                    //清空缓存
                    $cookiess=\Yii::$app->response->cookies;
                    $cookiess->remove('cart');
                    return ['status'=>1,'msg'=>'清除成功'];
                }else{
                    $carts=Cart::findAll(['member_id'=>\Yii::$app->user->id]);
                    foreach ($carts as $cart){
                        $cart->delete();
                    }
                    return ['status'=>1,'msg'=>'清除成功'];
                }
            }
            //获取购物车所有商品
            public function actionGetCartAllGoods(){
                //未登录
                if(\Yii::$app->user->isGuest){
                    $cookies=\Yii::$app->request->cookies;
                    $cookie=$cookies->get('cart');
                    if($cookie == null){
                        return ['status'=>-1,'msg'=>'购物车里面无商品'];
                    }else{
                        $cart = unserialize($cookie->value);;
                    }
                    return ['status'=>1,'msg'=>'获取成功','data'=>$cart];
                }else{
                    $cart=Cart::findAll(['member_id'=>\Yii::$app->user->id]);
                    return ['status'=>1,'msg'=>'获取成功','data'=>$cart];
                }
            }
            //获取支付方式
            public function actionOrderPayType(){
                $payments=Order::$payments;
                return ['status'=>1,'msg'=>'获取成功','data'=>$payments];
            }
            //获取送货方式
            public function actionOrderDeliveryType(){
                $deliveries=Order::$deliveries;
                return ['status'=>1,'msg'=>'获取送货方式成功','date'=>$deliveries];
            }

            //获取当前用户订单列表
            public function actionGetOrderList(){
                if(\Yii::$app->user->isGuest){
                    return ['status'=>-1,'msg'=>'未登录请登录'];
                }
                $member_id=\Yii::$app->user->id;
                $order=Order::findAll(['member_id'=>$member_id]);
                return ['status'=>1,'msg'=>'获取列表成功','data'=>$order];
            }
            //取消订单
            public function actionCancelOrder(){
                if(\Yii::$app->request->isGet){
                    $id=\Yii::$app->request->get('id');
                    $order=Order::findOne(['id'=>$id]);
                    $order->status=0;
                    $order->save();
                    return ['status'=>1,'msg'=>'取消订单成功'];
                }
                return ['status'=>-1,'msg'=>'提交方式错误'];
            }


       //验证码
        public function actions()
        {
            return [
                'captcha' => [
                    'class' => 'yii\captcha\CaptchaAction',
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                    'minLength'=>3,
                    'maxLength'=>3,
                ],
            ];
        }
        //http://www.yii2shop.com/api/captcha.html 显示验证码
        //http://www.yii2shop.com/api/captcha.html?refresh=1 获取新验证码图片地址
        //http://www.yii2shop.com/api/captcha.html?v=59573cbe28c58 新验证码图片地址

        //文件上传功能
        public function actionUpload()
            {
                $img = UploadedFile::getInstanceByName('img');
                if($img){
                    $fileName = '/upload/'.uniqid().'.'.$img->extension;
                    $result = $img->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                    if($result){
                        return ['status'=>'1','msg'=>'','data'=>$fileName];
                    }
                    return ['status'=>'-1','msg'=>$img->error];
                }
                return ['status'=>'-1','msg'=>'没有文件上传'];
            }

}