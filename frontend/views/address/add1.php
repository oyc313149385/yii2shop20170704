<!-- 右侧内容区域 start -->
<div class="content fl ml10">

    <div class="address_bd mt10">

        <?php
         $form=\yii\widgets\ActiveForm::begin(
             ['fieldConfig'=>[
                 'options'=>[
                     'tag'=>'li'
                 ],
                'errorOptions'=>[
                'tag'=>'p'
                ]
             ]]
         );
         echo '<ul>';
         echo $form->field($model,'username')->textInput(['class'=>'txt']);//收货人
         //echo $form->field($model,'detail')->dropDownList();用插件完成三级联动

         echo $form->field($model,'address')->textInput(['class'=>'txt address']);
         echo $form->field($model,'tel' )->textInput(['class'=>'txt']);
         echo $form->field($model,'status')->checkbox();
         echo '<li>
                  <label for="">&nbsp;</label>
                  <input type="submit" class="btn" value="保存">
              </li>';
        echo '</ul>';
        ?>
       <?php \yii\widgets\ActiveForm::end();?>
     </div>
</div>
<!-- 右侧内容区域 end -->