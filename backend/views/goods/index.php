<?php
echo \yii\bootstrap\Html::beginForm(['goods/index'],'get');
       echo \yii\bootstrap\Html::textInput('key') ;
  echo \yii\bootstrap\Html::submitButton('查找', ['class' => 'btn btn-sm btn-primary']) ;

echo yii\bootstrap\Html::endForm() ;?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>货号</th>
        <th>Logo图片</th>
        <th>商品分类id</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->sn?></td>
            <td><img src="<?=$model->logo?>" style="width: 50px"></td>

            <td><?=$model->goodsCategory->name?></td>
            <td><?=$model->brand->name?></td>
            <td><?=$model->market_price?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=$model->is_on_sale?></td>
            <td><?=$model->status?></td>
            <td><?=$model->sort?></td>
            <td><?=date('Y-m-d',$model->create_time)?></td>




            <td><?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>

                <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('相册',['gallery','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>

        </tr>

    <?php endforeach;?>
    <?=\yii\bootstrap\Html::a('增加',['goods/add'],['class'=>'btn btn-warning btn-xs'])?></td>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',

]);