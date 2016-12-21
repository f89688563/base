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
		$('.menu_type').on('change', function(){
			var $li = $(this).closest('li').next()
			var val = $(this).val()
			var type = '<?php echo ($btn_type); ?>';
			console.log(type)
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
	<section>
		<form action="/base/admin.php/Wx/menu.html" method="post" class="form" id="editForm">
			<ul class="ulColumn2">
				<?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><li>
						<span class="item_name">菜单<?php echo ($k); ?>：</span>
						<input type="text" class="textbox textbox_225" name="button[<?php echo ($k); ?>][name]" value="<?php echo ($v['name']); ?>" />
					</li>
					<li>
						<span class="item_name">菜单类型：</span>
						<select class="select menu_type" name="button[<?php echo ($k); ?>][type]">
							<?php if(is_array($btn_type)): $i = 0; $__LIST__ = $btn_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($key) == $v['type']): ?>selected<?php endif; ?> ><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</li>
					<?php if($v['type']): ?><li>
							<span class="item_name">值：</span>
							<?php if(view == $v['type']): ?><input type="text" class="textbox textbox_435" name="button[<?php echo ($k); ?>][url]" value="<?php echo ($v['url']); ?>" />
							<?php elseif(media_id == $v['type']): ?>
								<input type="text" class="textbox textbox_225" name="button[<?php echo ($k); ?>][media_id]" value="<?php echo ($v['media_id']); ?>" />
							<?php else: ?>
								<input type="text" class="textbox textbox_435" name="button[<?php echo ($k); ?>][key]" value="<?php echo ($v['key']); ?>" /><?php endif; ?>
						</li><?php endif; ?>
						<ul class="ulColumn2 left-60">
							<?php if(is_array($v['sub_button'])): $k2 = 0; $__LIST__ = $v['sub_button'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($k2 % 2 );++$k2;?><li>
									<span class="item_name">子菜单<?php echo ($key); ?>：</span>
									<input type="text" class="textbox textbox_225" name="button[<?php echo ($k); ?>][sub_button][<?php echo ($k2); ?>][name]" value="<?php echo ($sub['name']); ?>" />
								</li>
								<li>
									<span class="item_name">菜单类型：</span>
									<select class="select menu_type" name="button[<?php echo ($k); ?>][sub_button][<?php echo ($k2); ?>][type]">
										<?php if(is_array($btn_type)): $i = 0; $__LIST__ = $btn_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($key) == $sub['type']): ?>selected<?php endif; ?> ><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
									</select>
								</li>
								<li>
									<span class="item_name">值：</span>
									<?php if(view == $v['type']): ?><input type="text" class="textbox textbox_225" name="button[<?php echo ($k); ?>][sub_button][<?php echo ($k2); ?>][url]" value="<?php echo ($sub['url']); ?>" />
									<?php elseif(media_id == $v['type']): ?>
										<input type="text" class="textbox textbox_225" name="button[<?php echo ($k); ?>][sub_button][<?php echo ($k2); ?>][media_id]" value="<?php echo ($sub['media_id']); ?>" />
									<?php else: ?>
										<input type="text" class="textbox textbox_225" name="button[<?php echo ($k); ?>][sub_button][<?php echo ($k2); ?>][key]" value="<?php echo ($sub['key']); ?>" /><?php endif; ?>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul><?php endforeach; endif; else: echo "" ;endif; ?>
				<li>
					<span class="item_name"></span>
					<input type="submit" class="link_btn" />
				</li>
			</ul>
		</form>
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