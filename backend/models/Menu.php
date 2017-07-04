<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Menu extends ActiveRecord{

    public function rules(){
        return[
            [['label'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '名称',
            'url' => '地址/路由',
            'parent_id' => '上级菜单',
            'sort' => '排序',
        ];
    }
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
       public function getParent(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }


}