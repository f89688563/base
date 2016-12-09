<?php

/**
* author： panfeng
* email ：89688563@qq.com
* date ：2016年7月12日
* charset ： UTF-8
*/
namespace Admin\Controller;

use Think\Controller;

class BaseController extends Controller {
	var $sysTitle = '微信后台系统';
	var $pageSize = 10;
	var $metaTitle;
	var $model;
	var $menu;
	var $auth;
	public function __construct() {
		parent::__construct ();
		$this->__init ();
	}
	public function __init() {
		$this->isLogin ();
		$this->initAuth ();
		$this->checkAuth ();
		$this->initConfig();
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
			S($cacheName, $config, array('expires'=>7200));
		}
		
		if ($config){
			foreach ($config as $v){
				C($v['name'], $v['value']);
			}
		}
	}
	
	/**
	 * 初始化权限
	 * 
	 * @author : panfeng <89688563@qq.com>
	 *         time : 2016-9-9上午10:51:21
	 */
	public function initAuth() {
		$cacheName = 'admin_auth_'.UID;
// 		S($cacheName, null);
		$data = S($cacheName);
		if (! $data) {
			$user = M ( 'member' )->field ( 'group' )->find ( UID );
			// 用户权限
			$rules = M ( 'auth_group' )->where ( array ( 'id' => $user ['group'] ) )->getField ( 'rules' );
			
			$w ['status'] = 1;
			$w ['id'] = array (
				'in',
				$rules 
			);
			$menu = M ( 'auth_rule' )->where ( $w )->order ( '`order` desc' )->select ();
			$auth = '';
			foreach ( $menu as $v ) {
				$auth .= ',' . $v ['url'];
			}
			
			$data['auth'] = $auth;
			$data['menu'] = getTree ( $menu, 0, 1 );
			S($cacheName, $data, array('expire'=>7200));
		}
		$this->auth = $data['auth'];
		$this->menu = $data['menu'];
		$this->assign ( 'menu', $this->menu );
	}
	
	/**
	 * 验证权限，防止url直接输入地址进入
	 * 
	 * @author : panfeng <89688563@qq.com>
	 *         time : 2016-9-9上午11:05:37
	 */
	public function checkAuth() {
		$url = CONTROLLER_NAME . '/' . ACTION_NAME;
		$actions = array (
			'del',
			'status',
			'uploadPicture',
			'index',
			'qrcode'
		);
		if (strpos ( $this->auth, $url ) === false && ! in_array ( ACTION_NAME, $actions )) {
			$this->error ( '非法进入' );
		}
	}
	
	/**
	 * 编辑
	 * @author : panfeng <89688563@qq.com>
	 * time : 2016-8-24下午4:23:24
	 */
	public function edit() {
		$model = D ( $this->model );
		if (IS_POST) {
			$key = $model->getPk ();
			if ($model->create () !== false) {
				
				$id = I ( $key, '', 'int' );
				if ($id) {
					$w [$key] = $id;
					$model->where ( $w )->save ();
					$res = 1;
				} else {
					$res = $model->add ();
				}
				if ($res) {
					$arr = array (
						'status' => 1,
						'url'=>U('lists')
					);
				} else {
					$arr = array (
						'status' => 0,
						'msg' => $model->getError () 
					);
				}
			} else {
				$arr = array (
					'status' => 0,
					'msg' => $model->getError () 
				);
			}
			echo json_encode ( $arr );
			die ();
		} else {
			$id = I ( 'get.id' );
			$info = $model->find ( $id );
			$this->assign ( 'info', $info );
			$this->adminDisplay ();
		}
	}
	
	/**
	 * 删除
	 * 
	 * @author : panfeng <89688563@qq.com>
	 *         time : 2016-8-24下午5:21:54
	 */
	public function del() {
		$id = $_REQUEST ['id'];
		$model = D ( $this->model );
		
		if ($id) {
			$key = $model->getPk ();
			if (strpos ( $id, ',' )) {
				$w [$key] = array (
						'in',
						$id 
				);
			} else {
				$w [$key] = $id;
			}
			$res = $model->where ( $w )->delete ();
			
			if ($res) {
				$arr ['status'] = 1;
				$arr ['msg'] = '删除成功';
			} else {
				$arr ['status'] = 0;
				$arr ['msg'] = '删除失败';
			}
		} else {
			$arr ['status'] = 0;
			$arr ['msg'] = '无效参数';
		}
		
		echo json_encode ( $arr );
		die ();
	}
	public function status() {
		$s = P ( 's', 1 );
		$this->setField ( 'status', $s );
	}
	
	/**
	 * 设置字段值
	 * 
	 * @param unknown $field        	
	 * @param unknown $value        	
	 * @author : panfeng <89688563@qq.com>
	 *         time : 2016-9-7下午5:06:31
	 */
	public function setField($field, $value) {
		$id = $_REQUEST ['id'];
		$model = D ( $this->model );
		
		if ($id) {
			$key = $model->getPk ();
			if (strpos ( $id, ',' )) {
				$w [$key] = array (
						'in',
						$id 
				);
			} else {
				$w [$key] = $id;
			}
			$res = $model->where ( $w )->setField ( $field, $value );
			
			if ($res) {
				$arr ['status'] = 1;
				$arr ['msg'] = '操作成功';
			} else {
				$arr ['status'] = 0;
				$arr ['msg'] = '操作失败';
			}
		} else {
			$arr ['status'] = 0;
			$arr ['msg'] = '无效参数';
		}
		
		echo json_encode ( $arr );
		die ();
	}
	
	/**
	 * 统计信息，sql执行
	 * 
	 * @param unknown $model        	
	 * @param number $p        	
	 * @param unknown $extend
	 *        	author panfeng
	 *        	email 89688563@qq.com
	 *        	date 2016年7月20日下午3:16:40
	 */
	public function listsBySql($model, $p = 0, $extend = array()) {
		$model || $this->error ( '模型名标识必须！' );
		$page = intval ( $p );
		$page = $page ? $page : 1; // 默认显示第一页数据
		$page --;
		
		if (is_string ( $model )) {
			$model = D ( $model );
		} elseif (is_object ( $model )) {
			$model = $model;
		}
		
		// 查询的字段
		$fields = $extend ['fields'] ? $extend ['fields'] : '*';
		
		$tablename = $model->getTableName ();
		
		$sql = "SELECT $fields FROM $tablename ";
		
		// 条件
		$w = $extend ['where'];
		$w and $sql .= ' WHERE ' . $w;
		
		// 分组
		$group = $extend ['group'];
		$group and $sql .= ' GROUP BY ' . $group;
		
		// 统计总数
		$count = $model->execute ( $sql );
		
		// 分页
		$row = $this->pageSize;
		$start = $page * $this->pageSize;
		$end = $start + $this->pageSize;
		$sql .= " LIMIT $start , $end";
		
		$data = $model->query ( $sql );
		
		// 分页
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( '_page', $page->show () );
		}
		
		$this->assign ( 'list', $data );
		$this->adminDisplay ();
	}
	public function lists($model = null, $p = 0, $extend = array(), $return=0) {
		$model || $this->error ( '模型名标识必须！' );
		$page = intval ( $p );
		$page = $page ? $page : 1; // 默认显示第一页数据
		
		if (is_string ( $model )) {
			$model = D ( $model );
		} elseif (is_object ( $model )) {
			$model = $model;
		}
		
		// 查询的字段
		$fields = $extend ['fields'];
		
		// 关键字搜索
		$map = empty ( $extend ['where'] ) ? array () : $extend ['where'];
		
		// 模糊搜索
		if ($extend['key']) {
			foreach ( $extend ['key'] as $val ) {
				if (isset ( $_REQUEST [$val] )) {
					$map [$val] = array (
							'like',
							'%' . $_GET [$val] . '%' 
					);
					unset ( $_REQUEST [$val] );
				}
			}
		}
		
		// 精确搜索
		if ($extend ['eq']) {
			foreach ( $extend ['eq'] as $v ) {
				if (isset ( $_REQUEST [$v] )) {
					$map [$v] = P ( $v );
					unset ( $_REQUEST [$v] );
				}
			}
		}
		
		$row = $this->pageSize;
		
		// 查询 单表 or 联表
		$data = $model
        /* 查询指定字段，不指定则查询所有字段 */
        ->field ( empty ( $fields ) ? true : $fields )
        // 查询条件
        ->where( $map )
        /* 默认通过id逆序排列 */
        ->order ( empty ( $extend ['order'] ) ? 'id desc' : $extend ['order'] )
        /* 数据分页 */
        ->page ( $page, $row )
        /* 执行查询 */
        ->select ();
		
		/* 查询记录总数 */
		$count = $model->where ( $map )->count ();
		
		// 分页
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( '_page', $page->show () );
		}
		
		if ($return) {
			return $data;
		} else {
			$this->assign ( 'list', $data );
			$this->adminDisplay ();
		}
	}
	
	/**
	 * 判断是否登录
	 * 
	 * @author : panfeng <89688563@qq.com>
	 *         time : 2016-8-24上午10:59:23
	 */
	public function isLogin() {
		// 获取当前用户ID
		if (defined ( 'UID' ))
			return;
		define ( 'UID', is_login () );
		$auth = session ( 'user_auth' );
		define ( 'USERNAME', $auth ['username'] );
		if (! UID) { // 还没登录 跳转到登录页面
			$this->redirect ( 'Public/login' );
		}
	}
	
	/**
	 * 输出模板
	 * 
	 * @param string $templateFile        	
	 * @param string $charset        	
	 * @param string $contentType        	
	 * @param string $content        	
	 * @param string $prefix        	
	 * @author : panfeng <89688563@qq.com>
	 *         time : 2016-8-24上午10:59:41
	 */
	public function adminDisplay($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
		$this->assign ( 'sys_title', $this->sysTitle );
		$this->assign ( 'meta_title', $this->metaTitle );
		$this->assign ( 'url', CONTROLLER_NAME . '/' . ACTION_NAME );
		$this->assign ( 'controller_name', CONTROLLER_NAME );
		$this->assign ( 'action_name', ACTION_NAME );
		$arrParam = array_merge ( $_REQUEST, $_GET, $_POST );
		foreach ( $arrParam as $k => $v ) {
			if ($v) {
				$this->assign ( $k, $v );
			}
		}
		$this->display ( $templateFile, $charset, $contentType, $content, $prefix );
	}
}