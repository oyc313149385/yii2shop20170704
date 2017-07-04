<?php
namespace backend\models;


use yii\db\ActiveRecord;

class User extends ActiveRecord
{      
   
     
    public function rules()
    {   
        //所有数据必须验证，不然数据无法提交
        return[
             [['username','password'],'required'],
           
        ];
    }
    public function attributeLabels()
    {
        return[
            'username'=>'用户名',
            'password'=>'密码',          
            'last_time'=>'时间',
            'last_ip'=>'时间',
            
        ];
    }
}