<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>tree</th>
        <th>lft</th>
        <th>rgt</th>
        <th>depth</th>
        <th>name</th>
        <th>parent_id</th>
        <th>介绍</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->tree?></td>
        <td><?=$model->lft?></td>
        <td><?=$model->rgt?></td>
        <td><?=$model->depth?></td>
        <td><?=$model->name?></td>
        <td><?=$model->parent_id?></td>
        <td><?=$model->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-xs btn-info'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-xs btn-danger'])?></td>
    </tr>
    <?php endforeach;?>
</table>
