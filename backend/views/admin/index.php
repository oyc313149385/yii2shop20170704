
<table class="table">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>email</th>
        <th>ip</th>
        <th>时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->username?></td>
            <td><?=$model->email?></td>
            <td><?=$model->last_login_ip?></td>
            <td><?=date('Y-m-d H:i:s',$model->created_at)?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['admin/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['admin/delete','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>          
        </tr>

    <?php endforeach;?>
    <?=\yii\bootstrap\Html::a('增加',['admin/add'],['class'=>'btn btn-warning btn-xs'])?></td>

</table>