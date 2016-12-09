<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-12-7
 * charset : UTF-8
 */
namespace Admin\Controller;
use Common\Logic\WxLogic;

class DeviceController extends BaseController {
	
	var $wxLogic;
	
	public function __construct() {
		parent::__construct();
		$this->model = CONTROLLER_NAME;
		$this->wxLogic = new WxLogic( C('WX_APPID'), C('WX_SECRET') );
	}
	
	public function lists() {
		$this->metaTitle = '设备列表';
		$extend = array(
			'key' => array('id')
		);
		
		$status = array(
			1 => '未授权',
			1 => '已授权',
			2 => '已绑定',
			3 => '属性未设置',
		);
		$this->assign('bind_status', $status);
		parent::lists($this->model, P('page'), $extend);
	}
	
	public function edit() {
		$this->metaTitle = '设备设置';
		parent::edit();
	}
	
	/**
	 * 设备授权
	 * @author : panfeng <89688563@qq.com>
	 * time : 2016-12-8下午3:12:52
	 */
	public function auth() {
		
		if (IS_POST) {
			
			if ( ! $_POST['id'] || ! $_POST['mac'] ) {
				$this->error('设备id和mac不能为空');
			}
			
			$_POST['connect_protocol'] = implode('|', $_POST['connect_protocol']);
			
			$data = array(
				'device_num' => 1,
				'device_list' => array( $_POST ),
				'op_type' => 1
			);
			
			$res = $this->wxLogic->deviceAuthorize($data);
			
			$return = array('status'=>1);
			if ( $res['errcode'] != 0 ) {
				$return = array(
					'status' => 0,
					'msg' => $this->wxLogic->errorCode($res['errcode'])
				);
			} else {
				
				$err = $res['resp'][0]['errcode'];
				
				if ($err != 0) {
					$return = array(
						'status' => 0,
						'msg' => $this->wxLogic->errorCode($err)
					);
				} else {
					$model = D($this->model);
					if ($model->create() !== false) {
						if ($_POST['edit']) {
							$res = $model->save();
						} else {
							$res = $model->add();
						}
						if (!$res) {
							$return = array(
								'status' => 0,
								'msg' => $model->getError()
							);
						} else {
							$return['url'] = U('lists');
						}
					}
				}
				
			}
			
			echo json_encode($return);die;
			
		} else {
			$this->metaTitle = '设备授权';
			$this->adminDisplay();
		}
		
	}
	
	public function qrcode() {
		$id = P('id');
		$res = $this->wxLogic->deviceQRCode($id);
		
		$return = [];
		if ($res['base_resp']['errcode'] == 0) {
			$deviceId = $res['deviceid'];
			$ticket = $res['qrticket'];
			
			$filename = $id . '/' . $deviceId . '.png';
			generate_qr($ticket, $filename);
			
			$return = array(
				'status' => 1,
				'device_id' => $deviceId,
				'qrcode' => $filename
			);
		} else {
			$return = array(
				'status' => 0,
				'msg' => $this->wxLogic->errorCode($res['base_resp']['errcode'])
			);
		}
		echo json_encode($return);die;
	}
	
}