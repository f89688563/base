<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-11-18
 * charset : UTF-8
 */
namespace Common\Logic;

class AppLogic extends BaseLogic
{
	
	var $appid = "wx554a396c5dde5f3d";
	var $appsecret = "e537ceed49e064a991657d624a7b44c9";
	var $access_token;
	var $token_name = 'app_token';
	
	//构造函数，获取Access Token
	public function __construct($appid = NULL, $appsecret = NULL)
	{
		if($appid){
			$this->appid = $appid;
		}
		if($appsecret){
			$this->appsecret = $appsecret;
		}
	
		$this->access_token = $this->getAccessToken();
	}
	
	public function sendModelMsg()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$this->access_token;
		$data = [
			
		];
	}
	
	public function getAccessToken( $force = false )
	{
		$tokenname = $this->token_name;
		if ( ! $force ){
			$data = S( $tokenname );
		}
		if ( empty( $data ) ){
			$data = $this->saveAccessToken();
		} else {
			if ( $data['expires'] < time() + 7200 ){     // token自动刷新十分钟前重新获取新的token
				$this->saveAccessToken();
			}
		}
	
		return $data['access_token'];
	}
	
	public function saveAccessToken(){
		$tokenname = $this->token_name;
	
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
		$res = $this->https_request($url);
		$result = json_decode($res, true);
	
		if ($result['access_token']){
	
			$data = array(
					'access_token' => $result['access_token'],
					'expires' => time() + $result['expires_in'],
			);
			S( $tokenname, $data, array( 'expire' => $result['expires_in'] ) );
		} else {
			die('获取异常');
		}
		return $data;
	}
	
	//https请求（支持GET和POST）
	protected function https_request($url, $data = null)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
}