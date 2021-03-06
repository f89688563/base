<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-9-7
 * charset : UTF-8
 */
namespace Admin\Controller;

class AuthRuleController extends BaseController{
	
	public function __construct(){
		parent::__construct();
		
		$this->model = CONTROLLER_NAME;
		$this->metaTitle = '菜单管理';
	}
	
	public function lists(){
		$page = P('p', 1);
		$extends = array(
			'key'=>array('title')
		);
		parent::lists($this->model, $page, $extends);
	}
}