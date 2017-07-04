<?php
namespace backend\controllers;
use backend\models\RoleForm;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use yii\web\Controller;
use backend\models\PermissionForm;
use yii\web\NotFoundHttpException;

class RbacController extends Controller
{
    //权限列表
    public function  actionPermissionionIndex()
    {
        $models = \Yii::$app->request->authManager->getPermissions();
        return $this->render('permission-index',['models'=>$models]);
    }


    //权限的添加
    public function ationAddPermisson()
    {
        $model = new PermissionForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
           if($model->addPermission()){
               \Yii::$app->session->setFlash('success','添加权限成功');
               //
               return $this->redirect(['permission-index']);
           }
        }
               return $this->render('add-permission',['model'=>$model]);
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
    public function actionDelPermission($name)
    {
          $permission = \Yii::$app->authManager->getPermission($name);
          if($permission==null){
              throw new NotFoundHttpException('权限不存在');
          }
          \Yii::$app->authManager->remove($permission);
          \Yii::$app->session->setFlash();
          return $this->redirect(['permission-index']);
    }

    //角色增删改查
    //角色列表
    public function actionRoleIndex()
    {
      $models = \Yii::$app->authManager->getRole();
      return $this->render('role-index',['models'=>$models]);
    }

    public function actionAddRole()
    {
        $model = new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
           if($model->addRole()){
               \Yii::$app->session->setFlash('success','');
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
     public function actionDelRole($name)
    {
          $role = \Yii::$app->authManager->getrole($name);
          if($role==null){
              throw new NotFoundHttpException('角色不存在');
          }
          \Yii::$app->authManager->remove($role);
          \Yii::$app->session->setFlash();
          return $this->redirect(['role-index']);
    }

}