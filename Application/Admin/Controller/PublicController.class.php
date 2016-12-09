<?php

namespace Admin\Controller;

class PublicController extends \Think\Controller {
	var $title = '微信后台系统';
	public function login($username = null, $password = null, $verify = null) {
		if (IS_POST) {
			
			$model = D ( 'Member' );
			$uid = $model->login ( $username, $password );
			
			if (0 < $uid) {
				$this->success ( '登录成功！', U ( 'Index/index' ) );
			} else { // 登录失败
				$error = $model->getError ();
				$this->error ( $error );
			}
		} else {
			if (is_login ()) {
				$this->redirect ( 'Index/index' );
			} else {
				$this->assign ( 'sys_title', $this->title );
				$this->display ();
			}
		}
	}
	
	/* 退出登录 */
	public function logout() {
		if (is_login ()) {
			D ( 'Member' )->logout ();
			session ( '[destroy]' );
			$this->success ( '退出成功！', U ( 'login' ) );
		} else {
			$this->redirect ( 'login' );
		}
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
					'url' => U ( 'logout' ),
					'msg' => $model->getError () 
			);
			echo json_encode ( $arr );
			die ();
		} else {
			$this->assign ( 'sys_title', $this->title );
			$this->display ();
		}
	}
	public function verify() {
		$verify = new \Think\Verify ();
		$verify->entry ( 1 );
	}
}
