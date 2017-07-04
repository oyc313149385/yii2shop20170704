<?php
namespace backend\models;


use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    public function getCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }

    public function getDetail()
    {
        return $this->hasOne(ArticleDetail::className(),['article_id'=>'id']);
    }
     
     public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','intro','sort','status','is_help','article_category_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'is_help' => '类型',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '时间',
            'article_category_id' => '分类'
        ];
    }

}