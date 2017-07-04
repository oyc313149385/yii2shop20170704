<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170609_031322_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(50)->notNull()->comment('名称'),
            'intro'=> $this->text()->comment('介绍'),
            'sort' => $this->integer(50)->comment('排序'),
            'status' => $this->integer(50)->comment('状态'),
            'is_help' => $this->integer(5)->comment('类型')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
