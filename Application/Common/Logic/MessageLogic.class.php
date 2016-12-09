<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-11-15
 * charset : UTF-8
 */
namespace Common\Logic;

use Think\Log;
class MessageLogic
{
	
	public function __construct()
	{
		
	}
	
	/**
	 * 发送消息到企业号客服
	 * @param unknown $object
	 * @author : panfeng <89688563@qq.com>
	 * time : 2016-11-15下午2:44:32
	 */
	public function msg_2_qy($object)
	{
		$msg = json_decode(json_encode($object), true);
		$qyLogic = new QyLogic();
		$qyLogic->msg_2_kf('', $msg);
		
		$this->saveMsg($msg, 'pf');
	}
	
	public function msg_2_cus($xml)
	{
		$obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		$res = json_decode(json_encode($obj), true);
		
		$agentType = $res['AgentType'];
		
		if (!$res['Item'][0])
		{
			$res['Item'] = array($res['Item']);
		}
		
		$wxLogic = new WxLogic();
		foreach ($res['Item'] as $v)
		{
			$kf = $v['FromUserName'];
			$msgType = $v['MsgType'];
			$content = $v['Content'];
			$toUser = $v['Receiver']['Id'];
			
			switch ($agentType)
			{
				case 'kf_external':
					$r = $wxLogic->send_custom_message($v);
					Log::write($r);
					file_put_contents('log.txt', $r."\r\n", FILE_APPEND);
					break;
				case 'kf_internal':
					break;
			}
			
			// 保存记录
			$this->saveMsg($v, $toUser);
			
			// 开启服务
			if ( ! $this->isInservice($toUser) )
			{
				$this->saveService($toUser, $kf);
			}
			// 关闭服务
			if ($content == '关闭')
			{
				$this->saveService($toUser, null);
			}
		}
		
		echo $res['PackageId'];
	}
	
	/**
	 * 保存消息数据
	 * @param unknown $msg
	 * @author : panfeng <89688563@qq.com>
	 * time : 2016-11-15下午3:36:19
	 */
	public function saveMsg($msg, $toUser)
	{
		$data['to'] = $toUser;
		$data['from'] = $msg['FromUserName'];
		$data['create_time'] = $msg['CreateTime'];
		$data['type'] = $msg['MsgType'];
		$data['msg_id'] = $msg['MsgId'];
		switch ($msg['MsgType'])
		{
			case 'text':
				$data['content'] = $msg['Content'];
				break;
			case 'image':
				$data['media_id'] = $msg['MediaId'];
				$data['img'] = $msg['PicUrl'];
				break;
		}
		
		$model = M('msg');
		$model->add($data);
	}
	
	public function saveService($user, $kf)
	{
		S($user.'_cache', $kf, C('SERVICE_CACHE'));
	}
	
	/**
	 * 判断是否在服务中
	 * @param unknown $openid
	 * @author : panfeng <89688563@qq.com>
	 * time : 2016-11-15下午2:43:09
	 */
	public function isInservice($openid)
	{
		return !empty(S($openid.'_cache'));
	}
	
}