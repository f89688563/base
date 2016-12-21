<?php
/**
* author： panfeng
* email ：89688563@qq.com
* date ：2016年5月29日
* charset ： UTF-8
*/

namespace Api\Controller;
use Common\Logic\WechatCallback;
use Common\Logic\WxLogic;

class WxController extends BaseApiController{
    
	public function __construct()
	{
		parent::__construct();
	}
	
	public function test() {
	    $wxLogic = new WxLogic();
	    
	    $filename = 'ak.jpeg';
	    $type = 'image';
	    $res = $wxLogic->uploadMedia($filename, $type);
	    dump($res);die;
	}
	
    public function index(){
        header('Content-type:text');
        $config = '';
        $wechatObj = new WechatCallback($config);
        if (!isset($_GET['echostr'])) {
            $wechatObj->responseMsg();
        }else{
            $token = 'gzhtoken';
            $wechatObj->valid($token);
        }
    }
    
}