<?php
use yii\web\JsExpression;
use xj\uploadify\Uploadify;
//use \kucha\ueditor\UEditor;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goodses,'name');
echo $form->field($goodses,'sort');
echo $form->field($goodses,'market_price');
echo $form->field($goodses,'shop_price');
echo $form->field($goodses,'stock');
echo $form->field($goodses,'is_on_sale',['inline'=>true])->radioList(['0'=>'下架','1'=>'上架']);
echo $form->field($goodses,'status',['inline'=>true])->radioList(['1'=>'隐藏','2'=>'显示']);
//echo $form->field($model,'imgFile')->fileInput();
echo $form->field($goodses,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\Brand::find()->all(),'id','name'));
echo $form->field($intro, 'content')->widget(crazyfd\ueditor\Ueditor::className(),[]);
echo $form->field($goodses,'logo')->hiddenInput();
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
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($goodses->logo){
    echo \yii\helpers\Html::img('@web'.$goodses->logo,['id'=>'img_logo','width'=>'50']);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','width'=>'50']);
}

//echo \kucha\ueditor\UEditor::widget([]);
/*echo UEditor::widget([
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'zh-cn', //中文为 zh-cn
        //定制菜单
        'toolbars' => [
            [
                'fullscreen', 'source', 'undo', 'redo', '|',
                'fontsize',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                'forecolor', 'backcolor', '|',
                'lineheight', '|',
                'indent', '|'
            ],
        ]
    ]]);*/
echo $form->field($goodses,'goods_category_id')->hiddenInput();
//使用ztree加载2个静态资源
echo '  <ul id="treeDemo" class="ztree"></ul>';
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($categories);
$js = new \yii\web\JsExpression(
    <<<JS
var zTreeObj;
    // zTree的setting配置详解
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
            onClick: function(event, treeId, treeNode) {
                //console.log(treeNode.id);
                //将选中节点的id赋值给表单中的隐藏域input
                $("#goods-goods_category_id").val(treeNode.id);
            }
        }
    };
    // zTree 的数据属性zTreeNode 节点数据详解
    var zNodes = {$zNodes};
    
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);//展开所有节点
    //获取当前节点的父节点（根据id查找）
    var node = zTreeObj.getNodeByParam("id", $("#goodscategory-parent_id").val(), null);
    zTreeObj.selectNode(node);//选中当前节点的父节点
JS

);
$this->registerJs($js);
    

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

?>