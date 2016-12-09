<?php
require 'config.php';
return array(
	/*配置公共函数库*/
	"LOAD_EXT_FILE"=>"extend",
	
	'IMG'=>'Upload/Img',
	'QR'=>'Upload/Qr',
		
	/* 数据库配置 */
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => DB_HOST, // 服务器地址
	'DB_NAME'   => DB_NAME, // 数据库名
	'DB_USER'   => DB_USER, // 用户名
	'DB_PWD'    => DB_PWD,  // 密码
	'DB_PORT'   => DB_PORT, // 端口
	'DB_PREFIX' => DB_PREFIX, // 数据库表前缀
);