<?php

$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label');
echo $form->field($model,'url');
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\Menu::find()->all(),'id','label'),['prompt'=>'请选择']);
echo $form->field($model,'sort');
echo yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();
