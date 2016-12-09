<?php
/**
* author： panfeng
* email ：89688563@qq.com
* date ：2016年5月29日
* charset ： UTF-8
*/

namespace Api\Controller;
use Think\Controller\RestController;
use Common\Logic\WxLogic;
use Common\Logic\QyLogic;
use Common\Logic\AppLogic;

class BaseApiController extends RestController{
	
	var $wxLogic;
	var $qyLogic;
	var $appLogic;
	
	//构造函数，获取Access Token
	public function __construct()
	{
		$this->initConfig();
		$this->initObject();
	}
	
	private function initObject(){
		
		$this->wxLogic = new WxLogic( C('WX_APPID'), C('WX_SECRET') );
// 		$this->qyLogic = new QyLogic( C('WX_QY_APPID'), C('WX_QY_SECRET') );
// 		$this->appLogic = new AppLogic();
	}
	
	/**
	 * 读取并设置配置
	 * @author : panfeng <89688563@qq.com>
	 * time : 2016-12-6上午10:28:07
	 */
	private function initConfig(){
		$cacheName = 'sys_config';
		
		$config = S($cacheName);
		if (! $config) {
			$config = M('config')->field('name,value')->select();
			S($cacheName, $config, array('expire'=>7200));
		}
		
		if ($config){
			foreach ($config as $v){
				C($v['name'], $v['value']);
			}
		}
	}
	
	public function successResponse($data=[], $type='json', $code=200)
	{
		$data['res'] = dv($data, 'res', 1);
		$data['msg'] = dv($data, 'msg', '操作成功');
		parent::response($data, $type, $code);
	}
	
	public function errorResponse($data=[], $type='json', $code=200)
	{
		$data['res'] = dv($data, 'res', 0);
		$data['msg'] = dv($data, 'msg', '操作失败');
		parent::response($data, $type, $code);
	}
	
}