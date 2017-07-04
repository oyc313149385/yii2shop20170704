<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $username
 * @property string $area
 * @property string $address
 * @property integer $tel
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        /*return [
            [['username','address', 'tel','province', 'city', 'county'], 'required'],
            [['tel', 'status'], 'integer'],
            [['province', 'city', 'county'], 'string', 'max' => 100],
            [['username'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 255],
        ];*/

         return [
            [['username', 'province', 'city', 'county', 'detail', 'tel'], 'required'],
            [['status'],'safe'],
            [['username', 'province', 'city', 'county'], 'string', 'max' => 100],
            [['detail'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '收货人',
            'area' => '所在地区',
            'detail' => '详细地址',
            'province' => '省',
            'city' => '市',
            'county' => '县',
            'tel' => '手机号码',
            'status' => '设置为默认地址',
        ];
    }
}
