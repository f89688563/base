<?php
/**
* author： panfeng
* email ：89688563@qq.com
* date ：2016年5月29日
* charset ： UTF-8
*/

namespace Api\Controller;
use Common\Logic\WechatCallback;

class WxController extends BaseApiController{
    
	public function __construct()
	{
		parent::__construct();
	}
	
    public function index(){
        header('Content-type:text');
        $wechatObj = new WechatCallback();
        if (!isset($_GET['echostr'])) {
            $wechatObj->responseMsg();
        }else{
            $wechatObj->valid();
        }
    }
    
}