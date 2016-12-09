$(function(){
	
	/**
	 * 弹出layer页面
	 */
	$(".layer-html").on('click',function(){
		var title = $(this).data('title') || '',
			width = $(this).data('width') || '600px',
			height = $(this).data('height') || '400px',
			content = $(this).data('content') || '';
		layer.open({
			type: 2,
            title: title,
//            maxmin: true,
            shadeClose: true, //点击遮罩关闭层
            area: [width, height],
            content: content
		});
	});
	
	// 回车触发表单提交
	$(".search").on('keyup', function(e){
		var keyCode = e.keyCode;
		if (keyCode == 13)
		{
			$('#btn_search').trigger('click');
		}
	});
	
	/**
	 * 表单异步提交
	 */
	$('#editForm').submit(function() {
		var btnTitle = $("#submit").html();
		jQuery.ajax({
			url:$(this).attr('action'),
			data:$(this).serialize(),
			type:"POST",
			beforeSend:function()
			{
				$("#submit").html(btnTitle+'...');
				$("#submit").attr('disabled',true);
			},
			success:function(json)
			{
				if (typeof json == "string"){
					json = eval("(" + json + ")");
				}
				if (json.status == 1){
					layer.msg(json.msg || '操作成功' , {icon: 6});
					setTimeout(function(){
						if (json.url){						
							top.location.href = json.url;
						} else {
							top.location.reload();
						}
					}, 3000);
				} else {
					layer.msg(json.info || json.msg || '操作失败', {icon: 5});					
				}
				$("#submit").html(btnTitle);
				$("#submit").attr('disabled',false);
			}
		});
		return false;
	});
	
	// 全部选中
	$(".check_all").on('click', function(){
		$(".checkboxs").prop('checked', $(this).prop('checked'));
	});
	
	// table行点击选中复选框
	$("tr").on('click', function(){
		$(this).find(".checkboxs").trigger('click');
	});
	
	/**
	 * 批量操作
	 */
	$(".btn_multi").on('click', function(){
		var url = $(this).data('url');
		var title = $(this).data('title') || '确定执行操作？';
		var ids = '';

		$(".checkboxs:checked").each(function(){
			if (ids) {
				ids += ','+$(this).val();
			} else {
				ids += $(this).val();
			}
		});
		
		if (!ids){
			tips('未选中任何项', 0, false);
			return;
		}
		
		layer.confirm(title, {icon: 3, title:'提示'}, function(index){
			// 执行操作
			$.post(url,{id:ids},function(json){
				tips(json.msg, json.status);
			},'json');
		});
	});
	
	/**
	 * 确认操作
	 */
	$(".ajax-confirm").on('click',function(){
		var url = $(this).data('url');
		var title = $(this).data('title') || '确定执行操作？';
		layer.confirm(title, {icon: 3, title:'提示'}, function(index){
		  // 执行操作
			$.post(url,{},function(json){
				tips(json.msg, json.status);
			},'json');
		});
	});
	
	$(".ajax-do").on('click',function(){
		
		var url = $(this).data('url'),
			title = $(this).data('title');
		$.post(url,{},function(json){
			tips(title, 1, false);
			if (json.status){
				tips('');
			}
		},'json');
		
	});
	
});

/**
 * 提示信息
 * @param msg
 * @param status
 */
function tips(msg, status, url){
	msg = msg || '操作成功';
	
	if (status == 1) {		
		layer.msg(msg, {icon: 6});
	} else {		
		layer.msg(msg, {icon: 5});
	}
	if (url !== false){
		setTimeout(function(){			
			if (url) {			
				location.href=url;
			} else {
				location.reload();
			}
		}, 3000);
	}
}
// 初始化左侧菜单
function init_bar(url){
	$(".menu-second").find("li > a[data-url='"+url+"']").closest("li").addClass("menu-second-selected").parents("div").addClass("in");
//	$(".menu-second").find("li > a[href='"+url+"']").closest("li").addClass("menu-second-selected").parents("div").addClass("in");
}
