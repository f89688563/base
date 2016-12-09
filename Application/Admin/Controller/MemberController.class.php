<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-8-25
 * charset : UTF-8
 */
namespace Admin\Controller;

class MemberController extends BaseController{
	
	var $status;
	
	public function __construct(){
		parent::__construct();
		$this->model = CONTROLLER_NAME;
		$this->status = C('MEMBER_STATUS');
		$this->assign('status', $this->status);
		$this->assign('group', M('auth_group')->getField('id,title'));
	}
	
	public function edit(){
		if (strlen($_POST['password']) <= 16) {		// 若是未加密的则进行加密
			$_POST['password'] = psd_sign($_POST['password'], $_POST['username']);
		}
		parent::edit();
	}
	
	public function status(){
		$s = P('s');
		$this->setField('status', $s);
	}
	
	public function lists(){
		$this->metaTitle = '用户管理';
		$model = 'Member';
		$p = I('get.p');
		$extend = array(
			'order'=>'uid desc',
			'key'=>array(
				'username'
			),
			'where'=>array(
				'uid'=>array('neq',C('USER_ADMINISTRATOR'))
			),
		);
		parent::lists($model, $p, $extend);
	}
	
	public function update() {
		if (IS_POST) {
			$user = session ( 'user_auth' );
			$uid = $user ['uid'];
			! $uid && $this - redirect ( 'login' );
			// 获取参数
			$password = I ( 'post.old' );
			empty ( $password ) && $this->error ( '请输入原密码', '', true );
			$new = I ( 'post.password' );
			empty ( $new ) && $this->error ( '请输入新密码' );
			$repassword = I ( 'post.repassword' );
			empty ( $repassword ) && $this->error ( '请输入确认密码' );
				
			if ($new !== $repassword) {
				$this->error ( '您输入的新密码与确认密码不一致' );
			}
				
			$model = D ( 'Member' );
			$res = $model->updatePsd ( $uid, $password, $new );
				
			$arr = array (
				'status' => $res,
				'url' => U ( 'Public/logout' ),
				'msg' => $model->getError ()
			);
			echo json_encode ( $arr );
			die ();
		} else {
			$this->assign ( 'sys_title', $this->title );
			$this->display ();
		}
	}
}