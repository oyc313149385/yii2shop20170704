<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m170626_084123_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->comment(''),
            'goods_id' => $this->integer(11)->comment(''),
            'goods_name' => $this->string(255)->comment(''),
            'logo' => $this->string(255)->comment(''),
            'price' => $this->decimal(10,2)->comment(''),
            'amount' => $this->integer(11)->comment(''),
            'total' => $this->decimal(10,2)->comment('')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
