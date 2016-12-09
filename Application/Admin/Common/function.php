<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-8-22
 * charset : UTF-8
 */

/**
 * 管理员密码加密
 * @param string $password
 * @return string
 * @author : panfeng
 * 2016-8-22上午10:12:33
 */
function psd_sign($password, $username=''){
	return md5(md5($username.$password).'_hud');
}

