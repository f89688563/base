<?php
namespace Admin\Model;
use Think\Model;

class MemberModel extends Model {

    protected $_validate = array(
        array('username', '1,16', '昵称长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),
        array('username', '', '昵称被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用
    );

    public function lists($status = 1, $order = 'uid DESC', $field = true){
        $map = array('status' => $status);
        return $this->field($field)->where($map)->order($order)->select();
    }

    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($username, $password){
        /* 检测是否在当前应用注册 */
    	$w['username'] = $username;
    	$user = $this->where($w)->find();
    	if (empty($user)) {
    		$this->error = '用户不存在';
    		$res = -3;
    	} else {
    		if ($user['status'] == 1) {
    			$group = M('auth_group')->find($user['group']);
    			if ($group['status'] == 1) {
	    			if ($user['password'] == psd_sign($password, $username)) {
	    				$res = $user['uid'];
	    				//记录行为
	    				action_log('user_login', 'member', $res, $res);
	    				/* 登录用户 */
	    				$this->autoLogin($user);
	    			} else {
	    				$this->error = '密码错误';
	    				$res = -2;
	    			}
    			} else {
    				$this->error = '用户所属组已被禁用，请联系管理员';
    				$res = -4;
    			}
    		} else {
    			$this->error = '用户已被禁用，请联系管理员';
    			$res = -1;
    		}
    	}
    	return $res;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'uid'             => $user['uid'],
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(),
        );
        $this->save($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['uid'],
            'username'        => $user['username'],
            'last_login_time' => $user['last_login_time'],
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));

    }
    
    /**
     * 修改密码
     * @param unknown $uid
     * @param unknown $password
     * @param unknown $new
     * @return number
     * @author : panfeng
     * 2016-8-22上午10:41:57
     */
    public function updatePsd($uid, $password, $new){
    	$user = $this->find($uid);
    	
    	if (empty($user)) {
    		$res = -3;
    		$this->error = '用户不存在';
    	} else {
    		if ($user['status'] == 1) {
    			if ($user['password'] == psd_sign($password, $user['username'])) {
    				$data = array(
    					'uid'=>$uid,
    					'password'=>psd_sign($new, $user['username'])
    				);
    				$res = $this->updateInfo($data);
    				$user['password'] == $data['password'] and $res = 1;	// 新密码与旧密码一致
    			} else {
    				$res = -1;
    				$this->error = '密码错误';
    			}
    		} else {
    			$res = -2;
    			$this->error = '用户已被禁用，请联系管理员';
    		}
    	}
    	return $res;
    }

    public function getusername($uid){
        return $this->where(array('uid'=>(int)$uid))->getField('username');
    }

    public function updateInfo($data){
        $this->create($data);
        $return = $this->save($data);
        return $return;
    }

}
