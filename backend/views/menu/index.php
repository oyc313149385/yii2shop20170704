
<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>地址/路由</th>
        <th>上级菜单</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->label?></td>
            <td><?=$model->url?></td>
            <td><?=$model->parent_id==0?'一级菜单':$model->parent->label?></td>
            <td><?=$model->sort?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
               <?=\yii\bootstrap\Html::a('删除',['menu/delete','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
        </tr>

    <?php endforeach;?>
    <?=\yii\bootstrap\Html::a('增加',['menu/add'],['class'=>'btn btn-warning btn-xs'])?></td>

</table>