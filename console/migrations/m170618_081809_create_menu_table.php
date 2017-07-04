<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170618_081809_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'lable' => $this->string(255)->notNull()->comment('权限名称'),
            'url' => $this->string(255)->comment('地址/路由'),
            'parent_id' => $this->integer()->comment('上级菜单'),
            'sort' => $this->integer()->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
