<?php
/**
 * author : panfeng
 * email : 89688563@qq.com
 * date : 2016-12-5
 * charset : UTF-8
 */
namespace Admin\Model;
use Think\Model;

class ConfigModel extends Model{
	
	protected $_validate = array(
		array('name', 'require', '标示不能为空！'), // 在新增的时候验证name字段是否唯一
		array('name', '', '标示已存在！', 0, 'unique', 1), // 在新增的时候验证name字段是否唯一
		array('title', 'require', '标题不能为空！'), // 在新增的时候验证name字段是否唯一
	);
	
	protected $_auto = array(
		array('create_time', 'time', self::MODEL_INSERT, 'function'),
		array('update_time', 'time', self::MODEL_BOTH, 'function'),
	);
	
}