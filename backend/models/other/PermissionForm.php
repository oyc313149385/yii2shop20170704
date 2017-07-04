<?php
namespace backend\models;

use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model
{
    public $name;//权限名称
    public $description;//权限描述

    public function rules()
    {
        return[
          [['name','description'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
          'name' => '名称',
          'description' => '描述'
        ];
    }

    public function addPermission()
    {
        $authManager = \Yii::$app->authManager;
        //创建权限
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已经存在');
        }else{
            $permission = $authManager->createPermission($this->name);
            $permission->description = $this->description;
            //保存数据表
            return $authManager->add($permission);
        }
            return false;
    }

    //从权限中加加载数据
    public function loadData(Permission $permission)
    {
        $this->name = $permission->name;
        $this->description = $permission->description;
    }

    //更新权限
    public function updatePermission($name)
    {
        $authManager = \Yii::$app->authManager;
        //
        $permission = $authManager->getPermission($name);
        //
        if($name != $this->name && $authManager->getPermission($this->name)){
            $this->addError('name','');
        }else{
            //
            $permission->name = $this->name;
            $permission->description = $this->description;
            //
            return $authManager->update($name,$permission);
        }
        return false;
    }


}