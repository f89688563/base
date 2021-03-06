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
	<section class="mtb">
		<input type="text" name="openid" value="<?php echo ($openid); ?>" class="textbox textbox_295 search" placeholder="请输入openid" />
		<input type="button" id="btn_search" value="查询" class="group_btn" />
	</section>
	<table class="table">
        <tr>
            <th>openid</th>
            <th>昵称</th>
            <th>性别</th>
            <th>头像</th>
            <th>关注时间</th>
            <th>备注</th>
        </tr>
       	<?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
	              	<td><?php echo ($v['openid']); ?></td>
	              	<td><?php echo ($v['nickname']); ?></td>
	              	<td>
	              		<?php if(1 == $v['sex']): ?>男
		              	<?php elseif(2 == $v['sex']): ?>
		              		女
		              	<?php else: ?>
		              		-<?php endif; ?>
          			</td>
	            		<td><img class="img-small" src="<?php echo ($v['headimgurl']); ?>" /></td>
              		<td><?php echo date('Y-m-d H:i', $v['subscribe_time']);?></td>
              		<td><?php echo ((isset($v['remark']) && ($v['remark'] !== ""))?($v['remark']):'-'); ?></td>
	          	</tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </table>
    <aside class="paging">
 	  <?php echo ((isset($_page) && ($_page !== ""))?($_page):''); ?>
  	</aside>
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