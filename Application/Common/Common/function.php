<?php

/**
 * 删除目录
 * @param unknown $dir
 * @return number
 * @author : panfeng <89688563@qq.com>
 * time : 2016-12-5下午6:25:15
 */
function del_dir($dir){
	$handle = opendir($dir);
	while(($item = readdir($handle)) !== false){
		if($item != '.' and $item != '..' and $item != 'Logs'){
			if(is_dir($dir.'/'.$item)){
				del_dir($dir.'/'.$item);
			}else{
				if(!unlink($dir.'/'.$item)){
					return 1;
				}
			}
		}
	}
}

/**
 * 获取菜单树
 * @param array $data 菜单数组
 * @param int $pid 父节点
 * @param int $type 获取类型 1-获取数组，2-输出html
 * @return Ambigous <string, unknown>
 * @author : panfeng <89688563@qq.com>
 * time : 2016-9-9上午9:45:35
 */
function getTree($data, $pid, $type=1){
	$return = '';
	foreach($data as $k => $v){
		if($v['pid'] == $pid){         //父亲找到儿子
			switch ($type){
				case 1:
					$sub = getTree($data, $v['id'], $type);
					$sub and $v['sub'] = $sub;
					$return[] = $v;
					break;
				case 2:
					
					if ($pid == 0){
						$return .= '';
					}
					
					$return .= getTree($data, $v['id'], $type);
					$return = $return."</a>";
					break;
			}
		}
	}
	#mark - 多级菜单需设计
	switch ($type){
		case 2:
			$return .= '<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"
                        aria-expanded="false" aria-controls="collapseTwo"><i class="'.$v['icon'].' icon-large"></i> '.$v['title'].'</a>';
			$return = $return ? '<div class="panel panel-default menu-first">'.$return.'</div>' : $return ;
			break;
	}
	return $return;
}

/**
 * 权限选项
 * @param string $type
 * @return string
 * @author : panfeng <89688563@qq.com>
 * time : 2016-9-9上午10:04:46
 */
function get_menu_list($type='option'){
	$html = '';
	get_sub_menu($html, 0, $type, ' ');
	return $html;
}

/**
 * 下拉框
 * @param unknown $html
 * @param unknown $pid
 * @param string $type
 * @param string $pre
 * @return string
 * @author : panfeng <89688563@qq.com>
 * time : 2016-9-8下午2:27:28
 */
function get_sub_menu(&$html, $pid, $type='option', $pre='|--', $extend=array()){
	$model = M('auth_rule');
	$menu = $model->where(array('pid'=>$pid))->order('`order` desc')->select();
	
	if ($menu){
		foreach ($menu as $v){
			switch ($type){
				case 'option':
					$html .= "<option value='{$v['id']}'>".$pre.$v['title']."</option>";
					break;
				case 'checkbox':
					$html .= "<label class='single_selection lbl' data-id='{$v['id']}' data-pid='{$v['pid']}'><input class='ckb' type='checkbox' name='rules[]' value='{$v['id']}'>".$v['title']."</label>";
					break;
			}
			get_sub_menu($html, $v['id'], $type, $pre.'|--');
		}
	} else {
		return '';
	}
}

/**
 * 日志记录
 * @param unknown $content
 * @author : panfeng <89688563@qq.com>
 * time : 2016-9-6下午4:08:01
 */
function log2file($content){
	$path = C('LOG');
	if ( ! is_dir($path) ){
		mkdir($path);
	}
	$filename = 'log_'.date('Y-m-d').'.txt';
	$content = date('Y-m-d H:i:s').'--'.$content."\n";
	file_put_contents($path.$filename, $content, FILE_APPEND);
}

/**
 * 获取请求参数
 * @param unknown $param
 * @param string $default
 * @param string $filter
 * @return Ambigous <unknown, number>
 * @author : panfeng <89688563@qq.com>
 * time : 2016-8-29下午2:46:43
 */
function P($param, $default='', $filter=''){
	
	$_REQUEST = array_merge($_POST, $_GET, $_REQUEST);
	
	if (isset($_REQUEST[$param])) {
		$value = $_REQUEST[$param];
	} else {
		$value = $default;
	}

	switch ($filter){
		case 'int':
			$value = intval($value);
			break;
		case 'string':
			$value = (string)$value;
			break;
		case 'float':
			$value = floatval($value);
	}
	return $value;
}

/**
 * 增加前缀
 * @param string|array $src
 * @param string $field 如果是数组则指定字段
 * @param string $type 文件类型
 * @param string $pre 手动指定前缀
 */
function filter($src,$field='img',$type='img',$pre = ''){
	switch ($type){
		case 'img':
			$pre = $pre ? $pre : trim(SITE_URL,'/').'/'.trim(C('IMG_UPLOAD'),'/').'/';
			break;
		case 'file':
			$pre = $pre ? $pre : trim(SITE_URL,'/').'/'.trim(C('FILE_URL'),'/').'/';
			break;
	}

	if (empty($src)){
		return '';
	} else {
		if (is_string($src)){
			if (strpos($src, ',') === false){
				return $pre.$src;
			} else {
				$arr = explode(',',$src);
				foreach ($arr as &$v){
					$v = $pre.$v;
				}
				return implode(',', $arr);
			}
		} elseif (is_array($src)) {
			$arrF = explode(',', $field);
			$mult = array_multi($src);
			if ($mult == 1){
				foreach ($arrF as $f){
					$src[$f] = filter($src[$f],$field,$type,$pre);
				}
			} elseif ($mult == 2){
				foreach ($src as &$v){
					foreach ($arrF as $f){
						$v[$f] = filter($v[$f],$field,$type,$pre);
					}
				}
			}
			return $src;
		}
	}
}

/**
 * 判断数组是一维还是二维
 * @param array $arr
 * @return number
 */
function array_multi($arr){
	if (is_array($arr) && !empty($arr)){
		foreach ($arr as $v){
			if (is_array($v)){
				return 2;
			} else {
				return 1;
			}
			break;
		}
	} else {
		return 0;
	}
}

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author panfeng <89688563@qq.com>
 */
function is_login(){
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}

/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 * @author panfeng <89688563@qq.com>
 */
function is_administrator($uid = null){
    $uid = is_null($uid) ? is_login() : $uid;
    return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}

/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @author panfeng <89688563@qq.com>
 */
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

/**
* 对查询结果集进行排序
* @access public
* @param array $list 查询结果
* @param string $field 排序的字段名
* @param array $sortby 排序类型 asc正向排序 desc逆向排序 nat自然排序
* @return array
*/
function list_sort_by($list, $field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=>$val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author panfeng <89688563@qq.com>
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 设置跳转页面URL
 * 使用函数再次封装，方便以后选择不同的存储方式（目前使用cookie存储）
 * @author panfeng <89688563@qq.com>
 */
function set_redirect_url($url){
    cookie('redirect_url', $url);
}

/**
 * 获取跳转页面URL
 * @return string 跳转页URL
 * @author panfeng <89688563@qq.com>
 */
function get_redirect_url(){
    $url = cookie('redirect_url');
    return empty($url) ? __APP__ : $url;
}

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook,$params=array()){
    \Think\Hook::listen($hook,$params);
}

/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param int $record_id 触发行为的记录id
 * @param int $user_id 执行行为的用户id
 * @return boolean
 * @author : panfeng <89688563@qq.com>
 * time : 2016-8-22上午10:58:48
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null){

    //参数检查
    if(empty($action) || empty($model) || empty($record_id)){
        return '参数不能为空';
    }
    if(empty($user_id)){
        $user_id = is_login();
    }

    //查询行为,判断是否执行
    $action_info = M('Action')->getByName($action);
    if($action_info['status'] != 1){
        return '该行为被禁用或删除';
    }

    //插入行为日志
    $data['action_id']      =   $action_info['id'];
    $data['user_id']        =   $user_id;
    $data['action_ip']      =   ip2long(get_client_ip());
    $data['model']          =   $model;
    $data['record_id']      =   $record_id;
    $data['create_time']    =   NOW_TIME;

    //解析日志规则,生成日志备注
    if(!empty($action_info['log'])){
        if(preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)){
            $log['user']    =   $user_id;
            $log['record']  =   $record_id;
            $log['model']   =   $model;
            $log['time']    =   NOW_TIME;
            $log['data']    =   array('user'=>$user_id,'model'=>$model,'record'=>$record_id,'time'=>NOW_TIME);
            foreach ($match[1] as $value){
                $param = explode('|', $value);
                if(isset($param[1])){
                    $replace[] = call_user_func($param[1],$log[$param[0]]);
                }else{
                    $replace[] = $log[$param[0]];
                }
            }
            $data['remark'] =   str_replace($match[0], $replace, $action_info['log']);
        }else{
            $data['remark'] =   $action_info['log'];
        }
    }else{
        //未定义日志规则，记录操作url
        $data['remark']     =   '操作url：'.$_SERVER['REQUEST_URI'];
    }

    M('ActionLog')->add($data);

    if(!empty($action_info['rule'])){
        //解析行为
        $rules = parse_action($action, $user_id);

        //执行行为
        $res = execute_action($rules, $action_info['id'], $user_id);
    }
}

/**
 * 解析行为规则
 * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 *              field->要操作的字段；
 *              condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 *              rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 *              cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 *              max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 * 单个行为后可加 ； 连接其他规则
 * @param string $action 行为id或者name
 * @param int $self 替换规则里的变量为执行用户的id
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 * @author panfeng <89688563@qq.com>
 */
function parse_action($action = null, $self){
    if(empty($action)){
        return false;
    }

    //参数支持id或者name
    if(is_numeric($action)){
        $map = array('id'=>$action);
    }else{
        $map = array('name'=>$action);
    }

    //查询行为信息
    $info = M('Action')->where($map)->find();
    if(!$info || $info['status'] != 1){
        return false;
    }

    //解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
    $rules = $info['rule'];
    $rules = str_replace('{$self}', $self, $rules);
    $rules = explode(';', $rules);
    $return = array();
    foreach ($rules as $key=>&$rule){
        $rule = explode('|', $rule);
        foreach ($rule as $k=>$fields){
            $field = empty($fields) ? array() : explode(':', $fields);
            if(!empty($field)){
                $return[$key][$field[0]] = $field[1];
            }
        }
        //cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
        if(!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])){
            unset($return[$key]['cycle'],$return[$key]['max']);
        }
    }

    return $return;
}

/**
 * 执行行为
 * @param array $rules 解析后的规则数组
 * @param int $action_id 行为id
 * @param array $user_id 执行的用户id
 * @return boolean false 失败 ， true 成功
 * @author panfeng <89688563@qq.com>
 */
function execute_action($rules = false, $action_id = null, $user_id = null){
    if(!$rules || empty($action_id) || empty($user_id)){
        return false;
    }

    $return = true;
    foreach ($rules as $rule){

        //检查执行周期
        $map = array('action_id'=>$action_id, 'user_id'=>$user_id);
        $map['create_time'] = array('gt', NOW_TIME - intval($rule['cycle']) * 3600);
        $exec_count = M('ActionLog')->where($map)->count();
        if($exec_count > $rule['max']){
            continue;
        }

        //执行数据库操作
        $Model = M(ucfirst($rule['table']));
        $field = $rule['field'];
        $res = $Model->where($rule['condition'])->setField($field, array('exp', $rule['rule']));

        if(!$res){
            $return = false;
        }
    }
    return $return;
}
