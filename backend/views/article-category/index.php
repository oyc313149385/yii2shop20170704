<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名字</th>
        <th>介绍</th>
        <th>分类</th>
        <th>状态</th>
        <th>类型</th>
        <th>操作</th>
    </tr>
    <?php foreach($article_categorys as $article_category): ?>
       <tr> 
        <td><?=$article_category->id?></td>
         <td><?=$article_category->name?></td>
            <td><?=$article_category->intro?></td>
            <td><?=$article_category->sort?></td>
            <td><?=$article_category->status?></td>
            <td><?=$article_category->is_help?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$article_category->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['article-category/del','id'=>$article_category->id],['class'=>'btn btn-danger btn-xs'])?>
            </td>
       </tr>     
    <?php endforeach; ?>
</table>

