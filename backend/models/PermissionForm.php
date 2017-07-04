<?php
namespace backend\models;
use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{
    public $name;//权限名称
    public $description;//权限描述

    public function rules()
    {
        return [
            [['name','description'],'required'],
            //权限名称不能重复
            //['name','validateName'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
        ];
    }
    /*public function validateName()
    {
        $authManager = \Yii::$app->authManager;
        //获取权限
        if($authManager->getPermission($this->name)){
            //权限存在
            $this->addError('name','权限已存在');
        }
    }*/

    public function addPermission()
    {
        $authManager = \Yii::$app->authManager;
        //创建权限
        //创建权限前判断权限是否已存在
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            $permission = $authManager->createPermission($this->name);
            $permission->description = $this->description;
            //保存数据表
            return $authManager->add($permission);
        }
        return false;
    }

    //从权限中加载数据
    public function loadData(Permission $permission){
        $this->name = $permission->name;
        $this->description = $permission->description;
    }

    //更新权限
    public function updatePermission($name)
    {
        $authManager = \Yii::$app->authManager;
        //获取要修改的权限对象
        $permission = $authManager->getPermission($name);
        //判断修改后的权限名称是否存在
        if($name != $this->name && $authManager->getPermission($this->name)){
                $this->addError('name','权限已存在');
        }else{
            //赋值
            $permission->name = $this->name;
            $permission->description = $this->description;
            //更新权限
            return $authManager->update($name,$permission);
        }
        return false;
    }

}