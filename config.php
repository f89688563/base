<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-8-19
 * charset : UTF-8
 */

$host = $_SERVER['HTTP_HOST'];

if ($host == 'localhost') {		// 本地测试环境
	
	$dbHost = '127.0.0.1';
	$dbUsername = 'root';
	$dbPassword = 'root';
	$dbName = 'mp';
	$dbPrefix = 'sys_';
	$dbPort = '3306';
	$site = 'http://localhost/mp';
	
} else {	// 线上部署环境
	
	$dbHost = '127.0.0.1';
	$dbUsername = 'test';
	$dbPassword = 'feng_test';
	$dbName = 'mp';
	$dbPrefix = 'sys_';
	$dbPort = '3306';
	$site = 'https://portshock.cn/mp';
	
}

define('DB_HOST', $dbHost);
define('DB_NAME', $dbName);
define('DB_USER', $dbUsername);
define('DB_PWD', $dbPassword);
define('DB_PREFIX', $dbPrefix);
define('DB_PORT', $dbPort);
define('SITE_URL', $site);

