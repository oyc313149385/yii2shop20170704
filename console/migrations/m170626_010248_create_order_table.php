<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170626_010248_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer(),
            'name'=>$this->string(50),
            'province'=>$this->string(20),
            'city'=>$this->string(20),
            'area'=>$this->string(20),
            'address'=>$this->string(255),
            'tel'=>$this->char(11),
            'delivery_id'=>$this->integer(20),
            'delivery_price'=>$this->float(),
            'delivery_name'=>$this->string(255),
            'payment_id'=>$this->integer(),
            'payment_name'=>$this->string(),
            'total'=>$this->decimal(),
            'status'=>$this->integer(),
            'trane_no'=>$this->string(),
            'create_time'=>$this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
