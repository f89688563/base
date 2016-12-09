<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-12-7
 * charset : UTF-8
 */

function generate_qr($ticket, $filename) {
	vendor('phpqrcode.phpqrcode');
	$obj = new \QRcode();
	
	
	$basepath = C('QR');
	$path = $basepath . '/' . $filename;
	
	mkdir( pathinfo($path, PATHINFO_DIRNAME), 0757, true );
	
	$obj->png($ticket, $path, QR_ECLEVEL_Q, 6);
	
}