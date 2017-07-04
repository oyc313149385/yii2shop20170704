<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170612_063437_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('名称'),
            'sn' => $this->integer(50)->notNull()->comment('货号'),
            'logo' => $this->string(50)->notNull()->comment('LOGO图片'),
            'goods_category_id' => $this->integer(50)->notNull()->comment('商品分类id'),
            'brand_id' => $this->integer(50)->notNull()->comment('品牌分类'),
            'market_price' => $this->decimal(10,2)->notNull()->comment('市场价格'),
            'shop_price' => $this->decimal(10,2)->notNull()->comment('商品价格'),
            'stock' => $this->integer(50)->notNull()->comment('库存'),
            'is_on_sale' => $this->integer(1)->notNull()->comment('是否上架'),
            'status' => $this->integer(1)->notNull()->comment('状态'),
            'sort' => $this->integer(50)->notNull()->comment('排序'),
            'create_time' => $this->integer(50)->notNull()->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
