<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo ($sys_title); ?></title>
<meta name="author" content="DeathGhost" />
<link rel="stylesheet" type="text/css" href="/base/Public/res/Admin/css/style.css">
<!--[if lt IE 9]>
<script src="/base/Public/res/staticjs/html5.js"></script>
<![endif]-->
<script src="/base/Public/res/static/js/jquery.js"></script>
<script src="/base/Public/res/static/js/layer/layer.js"></script>
<script src="/base/Public/res/Admin/js/base.js"></script>
</head>

<script>

$(function(){
	$('.btn_auth').on('click', function(){
		var id = $('#product_id').val(),
			url = '<?php echo U("qrcode");?>'
		$.post(url, {id:id}, function(json){
			if (json.status == 1) {
				
				$('.step').each(function(){
					$(this).toggleClass('hidden')
				})
				
				$('#id').val(json.device_id)
				$('#proid').val(id)
				$('#qrcode').val(json.qrcode)
				$('#img_code').attr('src', '/base/Upload/Qr/'+json.qrcode)
				
			} else {
				tips(json.msg, json.status, false)
			}
		}, 'json')
	})
})
</script>


<body>
	<!--header-->
	<header>
		<h1>
			<img src="/base/Public/res/Admin/img/admin_logo.png" />
		</h1>
		<ul class="rt_nav">
			<li><a href="http://www.deathghost.cn" target="_blank" class="website_icon">站点首页</a></li>
			<li><a href="javascript:;" class="clear_icon ajax-confirm" data-url="<?php echo U('Config/clear');?>" data-title="确定清除？">清除缓存</a></li>
			<li><a href="<?php echo U('Member/update');?>" class="set_icon">账号设置</a></li>
			<li><a href="<?php echo U('Public/logout');?>" class="quit_icon">安全退出</a></li>
		</ul>
	</header>
	<!--aside nav-->
	<aside class="lt_aside_nav content mCustomScrollbar">
		<h2>
			<a href="<?php echo U('Index/index');?>">首页</a>
		</h2>
		<ul class="menu">
			<?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
					<dl>
						<dt><?php echo ($v['title']); ?></dt>
						<?php if(is_array($v['sub'])): $i = 0; $__LIST__ = $v['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><dd>
								<a href="<?php echo U($sub['url']);?>" <?php if($url == $sub['name']): ?>class='active'<?php endif; ?>><?php echo ($sub['title']); ?></a>
							</dd><?php endforeach; endif; else: echo "" ;endif; ?>
					</dl>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</aside>

	<section class="rt_wrap content mCustomScrollbar">
		
<div class="rt_content">
	<div class="page_title">
		<h2 class="fl"><?php echo ($meta_title); ?></h2>
	</div>
	<section class="step-body">
		<div class="step step1">
			<ul class="ulColumn2">
	<li>
		<span class="item_name">产品编号：</span>
		<input type="text" class="textbox textbox_225" id="product_id" value="26432" />
	</li>
	<li>
		<span class="item_name"></span>
		<input type="button" class="link_btn btn_auth" value="授权" />
	</li>
</ul>
<script>
	
</script>
		</div>
		<div class="step step2 hidden">
			<form action="<?php echo U('auth');?>" method="post" class="form" id="editForm">
	<input type="hidden" name="edit" value="<?php echo ($info['id']); ?>" />
	<input type="hidden" name="product_id" id="proid" readonly value="<?php echo ($info['product_id']); ?>" />
	<ul class="ulColumn2">
		<li>
			<span class="item_name">设备id：</span>
			<input type="text" class="textbox textbox_365" id="id" name="id" value="<?php echo ($info['id']); ?>" readonly />
		</li>
		<li>
			<span class="item_name">二维码：</span>
			<input type="hidden" name="qrcode" id="qrcode" value="<?php echo ($info['qrcode']); ?>" readonly />
			<img class="img-middle" id="img_code" src="/base/Upload/Qr/<?php echo ($info['qrcode']); ?>" />
		</li>
		<li>
			<span class="item_name">mac：</span>
			<input type="text" class="textbox textbox_365" name="mac" value="<?php echo ($info['mac']); ?>" />
			<span class="errorTips">格式采用16进制串的方式 如：1234567890AB</span>
		</li>
		<li>
			<span class="item_name">连接协议：</span>
			<label class="single_selection">
				<input type="checkbox" class="textbox" name="connect_protocol[]" value="1" <?php if(strpos(','.$info['connect_protocol'], '1') > 0): ?>checked<?php endif; ?> /> android classic bluetooth
			</label>
			<label class="single_selection">
				<input type="checkbox" class="textbox" name="connect_protocol[]" value="2" <?php if(strpos(','.$info['connect_protocol'], '2') > 0): ?>checked<?php endif; ?> /> ios classic bluetooth
			</label>
			<label class="single_selection">
				<input type="checkbox" class="textbox" name="connect_protocol[]" value="3" <?php if(strpos(','.$info['connect_protocol'], '3') > 0): ?>checked<?php endif; ?> /> ble
			</label>
			<label class="single_selection">
				<input type="checkbox" class="textbox" name="connect_protocol[]" value="4" <?php if(strpos(','.$info['connect_protocol'], '4') > 0): ?>checked<?php endif; ?> /> wifi
			</label>
		</li>
		<li>
			<span class="item_name">auth及通信的加密key：</span>
			<input type="text" class="textbox textbox_365" name="auth_key" value="<?php echo ($info['auth_key']); ?>" />
			<span class="errorTips">auth及通信的加密key，第三方需要将key烧制在设备上（128bit）</span>
		</li>
		<li>
			<span class="item_name">断开策略：</span>
			<label class="single_selection">
				<input type="radio" class="textbox" name="close_strategy" value="1" <?php if(($info['close_strategy']) == "1"): ?>checked<?php endif; ?> /> 退出公众号断开
			</label>
			<label class="single_selection">
				<input type="radio" class="textbox" name="close_strategy" value="2" <?php if(($info['close_strategy']) == "2"): ?>checked<?php endif; ?> /> 退出公众号不断开
			</label>
		</li>
		<li>
			<span class="item_name">连接策略：</span>
			<label class="single_selection">
				<input type="radio" class="textbox" name="conn_strategy" value="1" <?php if(($info['conn_strategy']) == "1"): ?>checked<?php endif; ?> /> 公众号页面连接
			</label>
			<label class="single_selection">
				<input type="radio" class="textbox" name="conn_strategy" value="4" <?php if(($info['conn_strategy']) == "4"): ?>checked<?php endif; ?> /> 非公众号页面连接
			</label>
		</li>
		<li>
			<span class="item_name">auth加密方法：</span>
			<label class="single_selection">
				<input type="radio" class="textbox" name="crypt_method" value="0" <?php if(($info['crypt_method']) == "0"): ?>checked<?php endif; ?> /> 不加密
			</label>
			<label class="single_selection">
				<input type="radio" class="textbox" name="crypt_method" value="1" <?php if(($info['crypt_method']) == "1"): ?>checked<?php endif; ?> /> AES加密
			</label>
		</li>
		<li>
			<span class="item_name">auth version：</span>
			<label class="single_selection">
				<input type="radio" class="textbox" name="auth_ver" value="0" <?php if(($info['auth_ver']) == "0"): ?>checked<?php endif; ?> /> 不加密的version
			</label>
			<label class="single_selection">
				<input type="radio" class="textbox" name="auth_ver" value="1" <?php if(($info['auth_ver']) == "1"): ?>checked<?php endif; ?> /> version 1
			</label>
		</li>
		<li>
			<span class="item_name">mac地址在厂商广播的偏移：</span>
			<label class="single_selection">
				<input type="radio" class="textbox" name="manu_mac_pos" value="-1" <?php if(($info['manu_mac_pos']) == "-1"): ?>checked<?php endif; ?> /> 在尾部
			</label>
			<label class="single_selection">
				<input type="radio" class="textbox" name="manu_mac_pos" value="-2" <?php if(($info['manu_mac_pos']) == "-2"): ?>checked<?php endif; ?> /> 不包含mac地址
			</label>
		</li>
		<li>
			<span class="item_name">mac地址在厂商的偏移：</span>
			<label class="single_selection">
				<input type="radio" class="textbox" name="ser_mac_pos" value="-1" <?php if(($info['ser_mac_pos']) == "-1"): ?>checked<?php endif; ?> /> 在尾部
			</label>
			<label class="single_selection">
				<input type="radio" class="textbox" name="ser_mac_pos" value="-2" <?php if(($info['ser_mac_pos']) == "-2"): ?>checked<?php endif; ?> /> 不包含mac地址
			</label>
		</li>
		<!-- <li>
			<span class="item_name">精简协议类型：</span>
			<input type="text" class="textbox textbox_365" name="ble_simple_protocol" />
		</li> -->
		<li>
			<span class="item_name"></span>
			<input type="submit" class="link_btn" />
		</li>
	</ul>
</form>
		</div>
	</section>
</div>

	</section>
	<script type="text/javascript">
		$(function() {
			// 设置左边选项卡样式
			// 1.先隐藏所有子选项卡
			$('.menu dd').each(function() {
				$(this).addClass('hidden')
			})
			// 2.父选项卡设置点击事件
			$('.menu dt').on('click', function() {
				// 隐藏其他子选项卡
				$(this).parents('li').siblings().find('dd').addClass('hidden')
				// 显示自己的子选项卡
				$(this).siblings().toggleClass('hidden')
			})
			// 3.显示激活的选项卡的兄弟
			$('.menu .active').parent().removeClass('hidden').siblings().removeClass('hidden')
			
			// 搜索
	       	$("#btn_search").on('click',function(){
	       		var url = '<?php echo ($action_name); ?>';
	       		$(".search").each(function(){
	       			if ($(this).val()){
	       				url += '/' + $(this).attr('name') + '/' + $(this).val();
	       			}
	       		});
	       		location.href = '<?php echo U("'+url+'");?>';
	       	});
		})
	</script>
</body>
</html>