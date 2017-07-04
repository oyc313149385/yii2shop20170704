<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($articlecategory,'id','name'));
echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>true])->radioList(['1'=>'隐藏','2'=>'显示']);
echo $form->field($model,'is_help',['inline'=>true])->radioList(['1'=>'帮助','2'=>'正常']);
echo $form->field($article_detail,'content')->textarea();

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();