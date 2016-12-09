<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-12-8
 * charset : UTF-8
 */
namespace Admin\Model;
use Think\Model;

class DeviceModel extends Model {
	
	protected $_auto = array(
		array('create_time', 'time', self::MODEL_INSERT, 'function'),
		array('update_time', 'time', self::MODEL_BOTH, 'function'),
		array('sub_time', 'time', self::MODEL_UPDATE, 'function'),
	);
	
}