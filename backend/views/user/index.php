<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>密码</th>
        <th>IP</th>
        <th>操作</th>
    </tr>
    <?php foreach($users as $user):?>
        <tr>
            <td><?=$user->id?></td>
            <td><?=$user->username?></td>
            <td>保密</td>        
            <td><?= date('Y:m:d H:i:s',$user->last_time) ?></td>
            <td><?= $user->last_ip ?></td>            
            <td><?=\yii\bootstrap\Html::a('修改',['user/login1'],['class'=>'btn btn-warning btn-xs'])?> <?=\yii\bootstrap\Html::a('删除',['user/del','id'=>$user
                ->id],['class'=>'btn btn-danger btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
</table>
