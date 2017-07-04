<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名字</th>
        <th>介绍</th>
        <th>分类</th>
        <th>状态</th>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach($brands as $brand): ?>
       <tr>
        <td><?=$brand->id?></td>
         <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?=$brand->sort?></td>
            <td><?=$brand->status?></td>
            <td><?=$brand->logo?\yii\bootstrap\Html::img($brand->logo,['width'=>100]):''?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$brand->id],['class'=>'btn btn-danger btn-xs'])?>
            </td>
       </tr>     
    <?php endforeach; ?>
</table>

