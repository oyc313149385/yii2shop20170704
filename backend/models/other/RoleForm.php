<?php
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions=[];//保存角色权限

    public function rules()
    {
        return [
          [['name','description'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
          'name'=>'名称',
          'description'=>'描述',
        ];
    }

     //获取所有权限选项
    public static function getPermissionOptions()
    {
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');//获取所有权限
    }



    //添加角色
    public function addRole()
    {
        $authManager = \Yii::$app->authManager;
        //判断角色是否存在
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已经存在');
        }else{
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
            if($authManager->add($role)){//保存到数据表
                //关联该角色的权限,多选框传值，遍历数据
                foreach ($this->permissions as $permissName){
                    $permission = $authManager->getPermission($permissName);
                    if($permission){
                        $authManager->addChild($role,$permission);
                    }
                }
                return true;
            }
        }
                return false;
    }

    public function updateRole($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        //给角色赋值
        $role->name = $this->name;
        $role->description = $this->description;
        //如果角色名修改,检查修改后名称是否存在
        if($name != $this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色名已存在');
        }else{
            if($authManager->update($name,$role)){
                $authManager->removeChildren($role);
                foreach ($this->permission as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    if($permission){
                        $authManager->addChild($role,$permission);
                    }
                }
                return true;
            }
        }
        return false;
    }

    public function loadData(Role $role)
    {
        $this->name = $role->name;
        $this->description = $role->description;
        //权限属性赋值
        //获取该角色对应的权限
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);
        foreach($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }

}