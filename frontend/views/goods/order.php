<!-- 主体部分 start -->
<form action="<?=\yii\helpers\Url::to(['goods/order-add']) ?>" method="post" >
	<div class="fillin w990 bc mt15">
		<div class="fillin_hd">
			<h2>填写并核对订单信息</h2>
		</div>

		<div class="fillin_bd">
			<!-- 收货人信息  start-->
			<div class="address">
				<h3>收货人信息</h3>
				<div class="address_info">
				<?php foreach ($address as $address): ?>
                <input type="radio" value="<?=$address->id?>" name="address_id" <?=$address->status==1?'checked':'';?>/>
                <?=$address->username.' '.$address->tel.' '.$address->province.' '.$address->city.'  '.$address->county ?>
			    <?php endforeach;?>

				</div>


			</div>
			<!-- 收货人信息  end-->

			<!-- 配送方式 start -->
			<div class="delivery">
				<h3>送货方式 </h3>


				<div class="delivery_select">
					<table>
						<thead>
							<tr>
								<th class="col1">送货方式</th>
								<th class="col2">运费</th>
								<th class="col3">运费标准</th>
							</tr>
						</thead>
						<tbody>
							<tr class="cur">	
								<td>
									<input type="radio" name="delivery" checked="checked" value="pt"/>普通快递送货上门

								</td>
								<td>￥10.00</td>
								<td>每张订单不满499.00元,运费15.00元, 订单4...</td>
							</tr>
							<tr>
								
								<td><input type="radio" name="delivery" value="tk"/>特快专递</td>
								<td>￥40.00</td>
								<td>每张订单不满499.00元,运费40.00元, 订单4...</td>
							</tr>
							<tr>
								
								<td><input type="radio" name="delivery" value="jj"/>加急快递送货上门</td>
								<td>￥40.00</td>
								<td>每张订单不满499.00元,运费40.00元, 订单4...</td>
							</tr>
							<tr>

								<td><input type="radio" name="delivery" value="py"/>平邮</td>
								<td>￥10.00</td>
								<td>每张订单不满499.00元,运费15.00元, 订单4...</td>
							</tr>
						</tbody>
					</table>

				</div>
			</div> 
			<!-- 配送方式 end --> 

			<!-- 支付方式  start-->
			<div class="pay">
				<h3>支付方式 </h3>


				<div class="pay_select">
					<table> 
						<tr class="cur">
							<td class="col1"><input type="radio" name="pay" value="hd" checked/>货到付款</td>
							<td class="col2">送货上门后再收款，支持现金、POS机刷卡、支票支付</td>
						</tr>
						<tr>
							<td class="col1"><input type="radio" name="pay" value="zx"/>在线支付</td>
							<td class="col2">即时到帐，支持绝大数银行借记卡及部分银行信用卡</td>
						</tr>
						<tr>
							<td class="col1"><input type="radio" name="pay" value="sm"/>上门自提</td>
							<td class="col2">自提时付款，支持现金、POS刷卡、支票支付</td>
						</tr>
						<tr>
							<td class="col1"><input type="radio" name="pay" value="yj"/>邮局汇款</td>
							<td class="col2">通过快钱平台收款 汇款后1-3个工作日到账</td>
						</tr>
					</table>

				</div>
			</div>
			<!-- 支付方式  end-->

			<!-- 发票信息 start-->
			<div class="receipt none">
				<h3>发票信息 </h3>


				<div class="receipt_select ">
					<form action="">
						<ul>
							<li>
								<label for="">发票抬头：</label>
								<input type="radio" name="type" checked="checked" class="personal" />个人
								<input type="radio" name="type" class="company"/>单位
								<input type="text" class="txt company_input" disabled="disabled" />
							</li>
							<li>
								<label for="">发票内容：</label>
								<input type="radio" name="content" checked="checked" />明细
								<input type="radio" name="content" />办公用品
								<input type="radio" name="content" />体育休闲
								<input type="radio" name="content" />耗材
							</li>
						</ul>						
					</form>

				</div>
			</div>
			<!-- 发票信息 end-->

			<!-- 商品清单 start -->
			<div class="goods">
				<h3>商品清单</h3>
				<table>
					<thead>
						<tr>
							<th class="col1">商品</th>
							<th class="col3">价格</th>
							<th class="col4">数量</th>
							<th class="col5">小计</th>
						</tr>	
					</thead>
					<tbody>
						<?php $sum=0; ?>
						<?php foreach($models as $model): 
                        $sum+=($model['shop_price']*$model['amount']);
                        //var_dump($sum);exit;
						?>           
						<tr>
							<td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com/'.$model['logo'])?></a>  <strong><a href=""><?=$model['name']?></a></strong></td>
							<td class="col3"><span><?=($model['shop_price']*$model['amount'])?></span></td>
							<td class="col4"><?=$model['amount']?></td>
							<td class="col5"><span class="money"><?=($model['shop_price']*$model['amount'])?></span></td>
				 		</tr>
					    <?php endforeach; ?>
					    <?php //var_dump($sum)?>
                       
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<ul>
									<li>
										<!--<span>4 件商品，总商品金额：</span>-->
										<em>￥<?=$sum?>.00</em>
									</li>
									<li>
										<span>返现：</span>
										<em>-￥0.00</em>
									</li>
									<li>
										<span>运费：</span>
										<em>￥0.00</em>
									</li>
									<li>
										<span>应付总额：</span>
										<em id="sum">￥<?=$sum?>.00</em>
										
									</li>
								</ul>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<!-- 商品清单 end -->
		
		</div>

		<div class="cart_btn w990 bc mt10">
			<a href=""><input type="submit"  class="checkout" value="提交订单" /></a>
			<p>应付总额：<strong>￥<?=$sum?>.00元</strong></p>
			<input type="hidden" value="<?=$sum?>" name="total">
			<input name="_csrf-frontend" type="hidden" id="_csrf-frontend" value="<?= Yii::$app->request->csrfToken ?>">
		</div>
	</div>
	<!-- 主体部分 end -->
</form>
<?php	
$this->registerJs(new \yii\web\JsExpression(
 <<<JS
     //var sum=0;
     //sum+=$(".money").val();
     //$("#sum").val()=sum;   
     //sum+=document.getElementsByClassName("money").innerText;
     //document.getElementById("sum").innnerText=sum;

JS

));