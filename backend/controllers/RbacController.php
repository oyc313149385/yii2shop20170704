<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{

    //权限的增删改查
    //添加权限
    public function actionAddPermission()
    {
        $model = new PermissionForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addPermission()){
                \Yii::$app->session->setFlash('success','权限添加成功');
                return $this->redirect(['permission-index']);
            }
        }

        return $this->render('add-permission',['model'=>$model]);
    }
    //权限列表
    public function actionPermissionIndex()
    {

        $models = \Yii::$app->authManager->getPermissions();

        return $this->render('permission-index',['models'=>$models]);
    }
    //修改权限
    public function actionEditPermission($name)
    {
        $permission = \Yii::$app->authManager->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }

        $model = new PermissionForm();
        //将要修改的权限的值赋值给表单模型
        $model->loadData($permission);

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updatePermission($name)){
                \Yii::$app->session->setFlash('success','权限修改成功');
                return $this->redirect(['permission-index']);
            }
        }


        return $this->render('add-permission',['model'=>$model]);
    }
    //删除权限
    public function actionDelPermission($name){
        $permission = \Yii::$app->authManager->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect(['permission-index']);
    }

    //角色增删改查
    //创建角色
    public function actionAddRole()
    {
        $model = new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addRole()){
                \Yii::$app->session->setFlash('success','角色添加成功');
                return $this->redirect(['role-index']);
            }
        }

        return $this->render('add-role',['model'=>$model]);
    }
    //修改角色
    public function actionEditRole($name){
        $role = \Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }

        $model = new RoleForm();
        $model->loadData($role);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updateRole($name)){
                \Yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['role-index']);
            }
        }

        return $this->render('add-role',['model'=>$model]);
    }
    //删除角色
    //角色列表
    public function actionRoleIndex()
    {
        $models = \Yii::$app->authManager->getRoles();

        return $this->render('role-index',['models'=>$models]);
    }

  
}
