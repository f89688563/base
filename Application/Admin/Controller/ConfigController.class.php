<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-12-2
 * charset : UTF-8
 */
namespace Admin\Controller;
use Think\Log;

class ConfigController extends BaseController {
	
	var $configType;
	
	public function __construct(){
		parent::__construct();
		
		$this->model = CONTROLLER_NAME;
		
		$this->configType = C('CONFIG_TYPE');
		$this->assign('config_type', $this->configType);
	}
	
	public function lists() {
		$this->metaTitle = '配置管理';
		$page = P('p', 1);
		$extends = array(
			'key'=>array('title'),
		);
		parent::lists($this->model, $page, $extends);
	}
	
	public function clear(){
		$res = del_dir(RUNTIME_PATH);
		
		$arr['status'] = intval( 1!==$res );
		
		// 清缓存
		S(null);
		echo json_encode($arr);
	}
	
	public function setting() {
		$model = M($this->model);
		if (IS_POST){
			
			$post = $_REQUEST['name'];
			foreach ($post as $k=>$v){
				$map['name'] = $k;
				$sql .= $model->where($map)->fetchSql(true)->setField('value', $v) . ';';
			}
			
			$model->execute($sql);
			
			$return['status'] = 1;
			echo json_encode($return);die;
			
		} else {
			$this->metaTitle = '网站设置';
			$order = 'sort desc';
			$list = $model->order($order)->select();
			$this->assign('list', $list);
			$this->adminDisplay();
		}
	}
}