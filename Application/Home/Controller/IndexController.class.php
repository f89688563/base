<?php
namespace Home\Controller;
use Common\Logic\WxJSApiLogic;

class IndexController extends BaseController {
	
	public function index(){
	    $jsLoigc = new WxJSApiLogic( C('WX_APPID'), C('WX_SECRET') );
	    $res = $jsLoigc->getSignPackage();
	    
	    $this->assign('sign', $res);
		$this->display();
	}
	
}