<?php
return array(
	/* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
		'__STATIC__' => __ROOT__ . '/Public/res/static',
		'__IMG__'    => __ROOT__ . '/Public/res/' . MODULE_NAME . '/img',
		'__CSS__'    => __ROOT__ . '/Public/res/' . MODULE_NAME . '/css',
		'__JS__'     => __ROOT__ . '/Public/res/' . MODULE_NAME . '/js',
		'__IMGUPLOAD__'     => __ROOT__ . '/Upload/Img',
		'__QRUPLOAD__'     => __ROOT__ . '/Upload/Qr',
	),
	'USER_ADMINISTRATOR'=>1,
	'MEMBER_STATUS'=>array(
		1=>'已启用',
		0=>'已禁用',
	),
	'CONFIG_TYPE'=>array(
		1=>'字符串',
		2=>'文本',
		3=>'枚举',
	),
);