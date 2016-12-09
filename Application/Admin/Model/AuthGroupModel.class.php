<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-9-8
 * charset : UTF-8
 */
namespace Admin\Model;
use Think\Model;

class AuthGroupModel extends Model{
	
	public function __construct(){
		$_POST['rules'] and $_POST['rules'] = implode(',', $_POST['rules']);
		parent::__construct();
	}
	
}