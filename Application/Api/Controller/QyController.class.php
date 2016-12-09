<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-11-7
 * charset : UTF-8
 */
namespace Api\Controller;
use Common\Logic\MessageLogic;
vendor( 'Wxqy.WXBizMsgCrypt' );
class QyController extends BaseApiController
{
	
	public function index()
	{
		$sVerifyMsgSig = P('msg_signature', 'urldecode');
		$sVerifyTimeStamp = time();
		$sVerifyNonce = P('nonce', 'urldecode');
		$sVerifyEchoStr = P('echostr', 'urldecode');
		// 需要返回的明文
		$sEchoStr = "";
		
		$wxcpt = new \WXBizMsgCrypt( C('WX_QY_TOKEN'), C('WX_QY_AESKEY'), C('WX_QY_APPID') );
		$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
		if ($errCode == 0) {
			echo $sEchoStr;
		} else {
			print("ERR: " . $errCode . "\n\n");
		}
		
	}
	
	public function msg()
	{
		$wxcpt = new \WXBizMsgCrypt( C('WX_QY_TOKEN'), C('WX_QY_AESKEY'), C('WX_QY_APPID') );

		$sReqMsgSig = P('msg_signature','urldecode');
		$sReqTimeStamp = P('timestamp','urldecode');
		$sReqNonce = P('nonce','urldecode');
		$sReqData = file_get_contents('php://input');
		$sMsg = "";  // 解析之后的明文
		$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
		
		if ($errCode == 0) {
			// 处理消息
			$msgLogic = new MessageLogic();
			$msgLogic->msg_2_cus($sMsg);
		} else {
			print("ERR: " . $errCode . "\n\n");
		}
	}
	
}