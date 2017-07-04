<?php
use yii\web\JsExpression;
use xj\uploadify\Uploadify;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>true])->radioList(['1'=>'隐藏','2'=>'显示']);
//echo $form->field($model,'imgFile')->fileInput();
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);
echo Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        $("#img_logo").attr("src",data.fileUrl).show();
        $("#brand-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($model->logo){
    echo \yii\helpers\Html::img('@web'.$model->logo,['id'=>'img_logo','width'=>'50']);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','width'=>'50']);
}
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>

