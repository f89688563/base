<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-12-6
 * charset : UTF-8
 */
namespace Admin\Controller;
use Common\Logic\WxLogic;

class WxController extends BaseController {
	
	var $wxLogic;
	
	public function __construct() {
		parent::__construct();
		
		$this->wxLogic = new WxLogic( C('WX_APPID'), C('WX_SECRET') );
	}
	
	/**
	 * 菜单管理
	 * @author : panfeng <89688563@qq.com>
	 * time : 2016-12-6下午3:14:01
	 */
	public function menu() {
		
		$this->metaTitle = '公众号菜单管理';
		if (IS_POST) {
			$data = $this->serializeData($_POST['button']);
			$res = $this->wxLogic->create_menu($data);
			$return = array(
				'status'=>$res['errmsg'] == 'ok' ? 1 : 0
			);
			echo json_encode($return);die;
		} else {
			$this->assign('btn_type', $this->btnType());
			$data = $this->wxLogic->getMenu();
			$this->assign('list', $data['menu']['button']);
			$this->adminDisplay();
		}
	}
	
	/**
	 * 关注用户列表
	 * @author : panfeng <89688563@qq.com>
	 * time : 2016-12-6下午3:06:45
	 */
	public function user() {
		$this->metaTitle = '关注用户';
		
		$model = D('subscribe');
		$page = P('page');
		$extend = array(
			'key'=> array('openid'),
			'where'=>array('status'=>1)
		);
		$data = parent::lists($model, $page, $extend, 1);
		
		if ($data){
			foreach ($data as $v){
				$arr[] = $this->wxLogic->get_user_info($v['openid']);
			}
		}
		$this->assign('list', $arr);
		$this->adminDisplay();
	}
	
	/**
	 * 菜单数组重组，防止button size error
	 * @param array $data
	 * @return Ambigous <unknown, multitype:unknown multitype:unknown  >
	 * @author : panfeng <89688563@qq.com>
	 * time : 2016-12-6下午6:08:59
	 */
	private function serializeData($data) {
		$return = [];
		foreach ($data as $v){
			if ($v['sub_button']) {
				$sub = [];
				foreach ($v['sub_button'] as $val) {
					$sub[] = $val;
				}
				$return['button'][] = array(
					'name'=>$v['name'],
					'sub_button'=>$sub
				);
			} else {
				$return['button'][] = $v;
			}
		}
		return $return;
	}
	
	private function btnType() {
		$btnType = array(
			'主菜单',
			'click'=>'按钮',
			'view'=>'跳转链接',
			'scancode_push'=>'扫码推事件',
			'scancode_waitmsg'=>'扫码推事件且弹出',
			'pic_sysphoto'=>'拍照发图',
			'pic_photo_or_album'=>'选图',
			'pic_weixin'=>'微信相册',
			'location_select'=>'地理位置',
			'media_id'=>'下发消息',
			'view_limited'=>'图文url',
		);
		return $btnType;
	}
}