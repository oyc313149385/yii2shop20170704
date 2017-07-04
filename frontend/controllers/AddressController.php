<?php

namespace frontend\controllers;

use frontend\models\Address;
use yii\web\Request;

class AddressController extends \yii\web\Controller
{
    public $layout = 'address';
    
    //收货
    public function actionIndex()
    {
        //$model =  Address::findAll(['name'=>]);
        $address =  Address::find()->all();

        return $this->render('index',['address'=>$address]);
    }

    public function actionAdd()
    {
              $model = new Address();
//        var_dump(\Yii::$app->request->post());exit;

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
           $user = \Yii::$app->user->identity;
            $model->member_id = $user->id;
            $model->save();


            //找到该用户的所有地址记录
            $addressAll = Address::findAll(['member_id'=>$model->member_id]);
//            var_dump($addressAll);exit;
            //遍历所有地址记录  把状态全部设为0
            foreach($addressAll as $address){
                $address->status = 0;
                $address->save();
//            var_dump($address->status);exit;
            }
            //得到当前要设置为默认地址的记录  把状态设置为1
            $area = Address::findOne(['id'=>$model->id]);
            $area->status = 1;
            $area->save();

            \Yii::$app->session->setFlash('success','添加收货地址成功');
            return $this->redirect(['address/add']);

        }
        return $this->render('add',['model'=>$model]);
    }

   
     public function actionEdit($member_id,$id)
    {
         $model =  Address::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $addressAll = Address::findAll(['member_id'=>$member_id]);
            //遍历所有地址记录  把状态全部设为0
            foreach($addressAll as $address){
                $address->status = 0;
                $address->save();
//            var_dump($address->status);exit;
            }
            //得到当前要设置为默认地址的记录  把状态设置为1
            $area = Address::findOne(['id'=>$id]);
            $area->status = 1;
            $model->save();
            \Yii::$app->session->setFlash('success','修改地址成功');
            return $this->redirect(['address/add']);

        }
        return $this->render('add',['model'=>$model]);

    }

    //删除收货地址
    public function actionDel($id){
        Address::findOne(['id'=>$id])->delete();
         \Yii::$app->session->setFlash('success','删除地址成功');
        return $this->redirect(['address/add']);

    }

    //设为默认地址

    public function actionDefault($member_id,$id){
        //找到该用户的所有地址记录
        $addressAll = Address::findAll(['member_id'=>$member_id]);
        //var_dump($addressAll);exit;
        //遍历所有地址记录  把状态全部设为0
        foreach($addressAll as $address){
            $address->status = 0;
            // var_dump($address);exit;
            $address->save();
           // var_dump($address->save());exit;
          //var_dump($address->status);exit;
        }
        //得到当前要设置为默认地址的记录  把状态设置为1
        $area = Address::findOne(['id'=>$id]);
        //var_dump($area);
        $area->status = 1;
        //var_dump($area);exit;
        //数据库里的tel数据类型为int，所以无法验证数据类型
        $area->save();
        //var_dump($area->getErrors());exit;
        //var_dump($area->save());exit;  
        \Yii::$app->session->setFlash('success','设置默认地址成功');
        return $this->redirect(['address/add']);
    }


}
