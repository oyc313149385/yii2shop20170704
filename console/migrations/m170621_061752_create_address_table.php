<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_061752_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull()->comment('收货人'),
            'area' => $this->string(255)->notNull()->comment('所在地区'),
            'address' => $this->string(255)->notNull()->comment('详细地址'),
            'tel' => $this->integer(11)->notNull()->comment('手机号码'),
            'status' => $this->integer(3)->comment('设置为默认地址')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
