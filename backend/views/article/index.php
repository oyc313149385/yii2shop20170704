<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名字</th>
        <th>介绍</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($articles as $article): ?>
       <tr> 
        <td><?=$article->id?></td>
         <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->category->name?></td>
            <td><?=$article->sort?></td>
            <td><?=$article->status?></td>
            <td><?=date('Y:m:d H:i:s',$article->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('查看',['article/view','id'=>$article->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$article->id],['class'=>'btn btn-danger btn-xs'])?>
            </td>
       </tr>     
    <?php endforeach; ?>
</table>