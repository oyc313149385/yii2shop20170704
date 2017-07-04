<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Order extends  ActiveRecord{
	 //订单和订单商品关系
    public function getGoods()
    {
        return $this->hasMany(OrderGoods::classname(),['order_id'=>'id']);
    }

}