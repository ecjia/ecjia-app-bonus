<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 获取红包类型列表 --bonus.func
 * @access  public
 * @return void
 */
function get_type_list()
{
	RC_Lang::load('bonus');
	$db_user_bonus = RC_Loader::load_app_model('user_bonus_model', 'bonus');
	$db_bonus_type = RC_Loader::load_app_model('bonus_type_model', 'bonus');
	$merchants_db_bonus_type = RC_Loader::load_app_model('merchants_user_bonus_type_viewmodel','bonus');
	
	/* 获得所有红包类型的发放数量 */
	$data = $db_user_bonus->field("bonus_type_id, COUNT(*) AS sent_count,SUM(IF(used_time>0,1,0)) as used_count")->group('bonus_type_id')->select();
	$sent_arr = array();
	$used_arr = array();
	if(!empty($data)) {
		foreach ($data as $row) {
			$sent_arr[$row['bonus_type_id']] = $row['sent_count'];
			$used_arr[$row['bonus_type_id']] = $row['used_count'];
		}
	}
	$bonustype_id=$_GET['bonustype_id'];
	$filter['send_type']='';
	$where=array();
	if(!empty($_GET['bonustype_id']) || (isset($_GET['bonustype_id']) && trim($_GET['bonustype_id'])==='0' )){
		$where['send_type']=$_GET['bonustype_id'];
		$filter['send_type']  =  $bonustype_id;
	}
	/* 查询条件 */
	$filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'type_id' : trim($_REQUEST['sort_by']);
	$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

	$count = $db_bonus_type->where($where)->count();
	$page = new ecjia_page($count, 15, 6);
	$res = $merchants_db_bonus_type->where($where)->order($filter[sort_by].' '.$filter[sort_order])->limit($page->limit())->select();
	
	$arr = array();
	if(!empty($res)) {
		foreach ($res as $row){
			$row['send_by']    = RC_Lang::lang('send_by/'. $row['send_type']);
			$row['send_count'] = isset($sent_arr[$row['type_id']]) ? $sent_arr[$row['type_id']] : 0;
			$row['use_count']  = isset($used_arr[$row['type_id']]) ? $used_arr[$row['type_id']] : 0;
			
			if(empty($row['user_id'])){
				if(empty($row['usebonus_type'])){
					$row['user_bonus_type'] = 1; //自主使用
				}else{
					$row['user_bonus_type'] = 2; //全场通用
				}
			}else{
				$row['user_bonus_type'] = $row['shoprz_brandName'].$row['shopNameSuffix']; //商家名称
			}
			$arr[] = $row;
		}
	}

	$arr = array('item' => $arr, 'filter' => $filter, 'page' => $page->show(5), 'desc' => $page->page_desc());
	return $arr;
}

/**
 * 查询红包类型的商品列表 --bonus.func
 *
 * @access public
 * @param integer $type_id        	
 * @return array
 */
function get_bonus_goods($type_id) 
{
	$db_goods = RC_Loader::load_app_model('goods_model', 'goods');
	$row = $db_goods->field('goods_id, goods_name')->where("bonus_type_id = '$type_id'")->select ();
	return $row;
}

/**
 * 获取用户红包列表 --bonus.func
 * 
 * @access public
 * @param
 *        	$page_param
 * @return void
 */
function get_bonus_list() 
{
	RC_Lang::load('bonus');
	$db_user_bonus = RC_Loader::load_app_model( 'user_bonus_model', 'bonus');
	$dbview = RC_Loader::load_app_model('user_bonus_type_viewmodel', 'bonus');
	/* 查询条件 */
	$filter ['sort_by']    = empty( $_REQUEST ['sort_by'] ) ? 'bonus_id' : trim( $_REQUEST ['sort_by'] );
	$filter ['sort_order'] = empty( $_REQUEST ['sort_order'] ) ? 'DESC' : trim( $_REQUEST ['sort_order'] );
	$filter ['bonus_type'] = empty( $_REQUEST ['bonus_type'] ) ? 0 : intval( $_REQUEST ['bonus_type'] );
	$where = empty( $filter ['bonus_type'] ) ? '' : "bonus_type_id='$filter[bonus_type]'";
	$count = $db_user_bonus->where ( $where )->count ();
	$page = new ecjia_page ( $count, 15, 6 );
	$dbview->view = array (
			'bonus_type' => array (
					'type' => Component_Model_View::TYPE_LEFT_JOIN,
					'alias' => 'bt',
					'field' => 'ub.*, u.user_name, u.email, o.order_sn, bt.type_name',
					'on' => 'bt.type_id = ub.bonus_type_id' 
			),
			'users' => array (
					'type' => Component_Model_View::TYPE_LEFT_JOIN,
					'alias' => 'u',
					'on' => 'u.user_id = ub.user_id' 
			),
			'order_info' => array (
					'type' => Component_Model_View::TYPE_LEFT_JOIN,
					'alias' => 'o',
					'on' => 'o.order_id = ub.order_id' 
			) 
	);
	$row = $dbview->where( $where )->order( $filter ['sort_by'] . " " . $filter ['sort_order'] )->limit ( $page->limit () )->select ();
	if (! empty( $row )) {
		foreach( $row as $key => $val ) {
			$row[$key]['used_time'] = $val ['used_time'] == 0 ? RC_Lang::lang ( 'no_use' ) : RC_Time::local_date ( ecjia::config ( 'date_format' ), $val ['used_time'] );
			$row[$key]['emailed']   = RC_Lang::lang ( 'mail_status/' . $row [$key] ['emailed'] );
		}
	}
	$arr = array (
			'item' => $row,
			'filter' => $filter,
			'page' => $page->show ( 15 ),
			'desc' => $page->page_desc () 
	);
	return $arr;
}

/**
 * 取得红包类型信息 --bonus.func
 * 
 * @param int $bonus_type_id
 *        	红包类型id
 * @return array
 */
function bonus_type_info($bonus_type_id) 
{
	$db_bonus_type = RC_Loader::load_app_model ('bonus_type_model', 'bonus');
	return $db_bonus_type->find( "type_id = '$bonus_type_id'" );
}

/**
 * 插入邮件发送队列 --bonus.func
 * @param unknown $username
 * @param unknown $email
 * @param unknown $subject
 * @param unknown $content
 * @param unknown $is_html
 * @return boolean
 */
function add_to_maillist($username, $email, $subject, $content, $is_html) 
{
	$db_mail_templates = RC_Loader::load_app_model ( 'mail_templates_model', 'mail');
	$db_email_sendlist = RC_Loader::load_app_model ( 'email_sendlist_model', 'mail');
	$time = time ();
	$content = addslashes ( $content );
	$template_id = $db_mail_templates->field ( 'template_id' )->find ( "template_code = 'send_bonus'" );
	$template_id = $template_id ['template_id'];
	$data = array (
			'email' => $email,
			'template_id' => $template_id,
			'email_content' => $content,
			'pri' => 1,
			'last_send' => $time 
	);
	$db_email_sendlist->insert ( $data );
	return true;
}

/********从order.func移出的有关红包的方法---start************/
/**
 * 取得用户当前可用红包
 * @param   int	 $user_id		用户id
 * @param   float   $goods_amount   订单商品金额
 * @return  array   红包数组
 */
function user_bonus($user_id, $goods_amount = 0, $cart_id = array()) 
{
	//will.chen start
	$db_cart_view = RC_Loader::load_app_model('cart_goods_viewmodel', 'cart');
// 	$sql = "SELECT g.user_id FROM " .$GLOBALS['ecs']->table('cart') ." as c,". $GLOBALS['ecs']->table('goods') ." as g". " WHERE  c.goods_id = g.goods_id AND c.rec_id in($cart_value)";
// 	$goods_list = $GLOBALS['db']->getAll($sql);
    $where = array();
    if(!empty($cart_id)){
        $where = array('c.rec_id' => $cart_id);
    }
    $where['c.user_id'] = $_SESSION['user_id'];
    $where['rec_type'] = CART_GENERAL_GOODS;
	$goods_list = $db_cart_view->join(array('goods'))->field('g.user_id')->where($where)->group('g.user_id')->select();
	
	$where = "";
	$goods_user = array();
	if($goods_list){
		foreach($goods_list as $key=>$row){
			$goods_user[] = $row['user_id'];
		}
	}
	
// 	if(!empty($goods_user)){
// 		$goods_user = substr($goods_user, 0, -1);
// 		$goods_user = explode(',', $goods_user);
// 		$goods_user = array_unique($goods_user);
// 		$goods_user = implode(',', $goods_user);
// 		$where = "IF(bt.usebonus_type > 0, bt.usebonus_type = 1, bt.user_id in($goods_user)) ";
// 	}
	//will.chen end 
	
	$dbview	= RC_Loader::load_app_model('user_bonus_type_viewmodel', 'bonus');
// 	$day	= getdate();
// 	$today	= RC_Time::local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);
	$today = RC_Time::gmtime();
	$dbview->view = array(
			'bonus_type' 	=> array(
					'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
					'alias'	=> 'bt',
					'field'	=> 'bt.type_id, bt.type_name, bt.type_money, ub.bonus_id, bt.user_id, bt.usebonus_type',
					'on'   	=> 'ub.bonus_type_id = bt.type_id'
			)
	);
	$bt_where = array('bt.use_start_date' => array('elt' => $today),
			'bt.use_end_date'		=> array('egt' => $today),
			'bt.min_goods_amount'	=> array('elt' => $goods_amount),
			'ub.user_id'			=> array('neq' => 0),
			'ub.user_id'			=> $user_id,
			'ub.order_id'			=> 0	
	);
	
// 	$bt_where = empty($where) ? $bt_where : array_merge($bt_where, $where);
	$row = $dbview->where($bt_where)->select();
	
	foreach ($row as $key => $val) {
		if ($val['usebonus_type'] == 0) {
			if (!in_array($val['user_id'], $goods_user)) {
				unset($row[$key]);
			}
		}
	}
	
	$row = array_merge($row);
	
	return $row;
	//	$sql = "SELECT bt.type_id, bt.type_name, bt.type_money, b.bonus_id " .
	//			"FROM " . $GLOBALS['ecs']->table('bonus_type') . " AS bt," .
	//			$GLOBALS['ecs']->table('user_bonus') . " AS ub " .
	//			"WHERE bt.type_id = ub.bonus_type_id " .
	//			"AND bt.use_start_date <= '$today' " .
	//			"AND bt.use_end_date >= '$today' " .
	//			"AND bt.min_goods_amount <= '$goods_amount' " .
	//			"AND ub.user_id<>0 " .
	//			"AND ub.user_id = '$user_id' " .
	//			"AND ub.order_id = 0";
	//	return $GLOBALS['db']->getAll($sql);
	//$dbview	= RC_Loader::load_app_model('bonus_type_viewmodel','user');
	//return $dbview->join('user_bonus')->where('bt.use_start_date <= '.$today.' AND bt.use_end_date >= '.$today.' AND bt.min_goods_amount <= '.$goods_amount.' AND ub.user_id <> 0 AND ub.user_id = '.$user_id.' AND ub.order_id = 0')->select();
}

/**
* 取得红包信息
* @param   int	 $bonus_id   红包id
* @param   string  $bonus_sn   红包序列号
* @param   array   红包信息
*/
function bonus_info($bonus_id, $bonus_sn = '') 
{
	$dbview	= RC_Loader::load_app_model('user_bonus_type_viewmodel', 'bonus');
	$dbview->view = array(
		'bonus_type' => array(
			'type'	=> Component_Model_View::TYPE_LEFT_JOIN,
			'alias'	=> 'bt',
			'field'	=> 'bt.*, ub.*',
			'on'	=> 'bt.type_id = ub.bonus_type_id'
		)
	);

	if ($bonus_id > 0) {
		return $dbview->find(array('ub.bonus_id' => $bonus_id));
	} else {
		return $dbview->find(array('ub.bonus_sn' => $bonus_sn));
	}

	
	// 	$dbview->view = array(
	// 			'user_bonus' => array(
	// 					'type'	=> Component_Model_View::TYPE_LEFT_JOIN,
	// 					'alias'	=> 'ub',
	// 					'field'	=> 'bt.*, ub.*',
	// 					'on'	=> 'bt.type_id = ub.bonus_type_id'
	// 			)
	// 	);
	//	 $sql = "SELECT bt.*, ub.* " ."FROM " . $GLOBALS['ecs']->table('bonus_type') . " AS bt," .
	//				 $GLOBALS['ecs']->table('user_bonus') . " AS ub " .
	//			 "WHERE bt.type_id = ub.bonus_type_id ";
	//	$sql .= "AND b.bonus_id = '$bonus_id'";
	//	$sql .= "AND b.bonus_sn = '$bonus_sn'";
	//	return $GLOBALS['db']->getRow($sql);
	
	//$dbview = RC_Loader::load_app_model('bonus_type_viewmodel','user');
}

/**
* 检查红包是否已使用
* @param   int $bonus_id   红包id
* @return  bool
*/
function bonus_used($bonus_id) 
{
	$db = RC_Loader::load_app_model('user_bonus_model', 'bonus');
	$order_id = $db->where(array('bonus_id' => $bonus_id))->get_field('order_id');
	return $order_id > 0;
	
	//	 $sql = "SELECT order_id FROM " . $GLOBALS['ecs']->table('user_bonus') ." WHERE bonus_id = '$bonus_id'";
	//	 return  $GLOBALS['db']->getOne($sql) > 0;
}

/**
* 设置红包为已使用
* @param   int	 $bonus_id   红包id
* @param   int	 $order_id   订单id
* @return  bool
*/
function use_bonus($bonus_id, $order_id) 
{
	$db = RC_Loader::load_app_model('user_bonus_model', 'bonus');
	$data = array(
		'order_id'	=> $order_id,
		'used_time' => RC_Time::gmtime()
	);
	return $db->where(array('bonus_id' => $bonus_id))->update($data);
		
		//	 $sql = "UPDATE " . $GLOBALS['ecs']->table('user_bonus') .
		//			 " SET order_id = '$order_id', used_time = '" . gmtime() . "' " .
		//			 "WHERE bonus_id = '$bonus_id' LIMIT 1";
		//	 return  $GLOBALS['db']->query($sql);
}

/**
* 设置红包为未使用
* @param   int	 $bonus_id   红包id
* @param   int	 $order_id   订单id
* @return  bool
*/
function unuse_bonus($bonus_id) 
{
	$db = RC_Loader::load_app_model('user_bonus_model', 'bonus');
	$data = array(
			'order_id'	=> 0,
			'used_time'	=> 0
					);
	return $db->where(array('bonus_id' => $bonus_id))->update($data);
	
	//	 $sql = "UPDATE " . $GLOBALS['ecs']->table('user_bonus') .
	//			 " SET order_id = 0, used_time = 0 " .
	//			 "WHERE bonus_id = '$bonus_id' LIMIT 1";
	//	 return  $GLOBALS['db']->query($sql);
}

/**
 * 取得当前用户应该得到的红包总额
 */
function get_total_bonus() 
{
	$db_cart	= RC_Loader::load_app_model('cart_model', 'cart');
	$dbview		= RC_Loader::load_app_model('cart_exchange_viewmodel', 'cart');
	$db_bonus	= RC_Loader::load_app_model('bonus_type_model', 'bonus');
	$day		= RC_Time::local_getdate();
	$today		= RC_Time::local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

	/* 按商品发的红包 */
	$dbview->view = array(
			'goods' => array(
					'type'	=> Component_Model_View::TYPE_LEFT_JOIN,
					'alias'	=> 'g',
					'on'	=> 'c.goods_id = g.goods_id'
			),
			'bonus_type' => array(
					'type'	=> Component_Model_View::TYPE_LEFT_JOIN,
					'alias'	=> 't',
					'on'	=> 'g.bonus_type_id = t.type_id'
			),
	);
	if ($_SESSION['user_id']) {
		$goods_total = floatval($dbview->where(array('c.user_id' => $_SESSION['user_id'] , 'c.is_gift' => 0 , 't.send_type' => SEND_BY_GOODS , 't.send_start_date' => array('elt' => $today) , 't.send_end_date' => array('egt' => $today) , 'c.rec_type' => CART_GENERAL_GOODS))->sum('c.goods_number * t.type_money')); 
	    /* 取得购物车中非赠品总金额 */
	    $amount = floatval($db_cart->where(array('user_id' => $_SESSION['user_id'] , 'is_gift' => 0 , 'rec_type' => CART_GENERAL_GOODS))->sum('goods_price * goods_number'));
	} else {
		$goods_total = floatval($dbview->where(array('c.session_id' => SESS_ID , 'c.is_gift' => 0 , 't.send_type' => SEND_BY_GOODS , 't.send_start_date' => array('elt' => $today) , 't.send_end_date' => array('egt' => $today) , 'c.rec_type' => CART_GENERAL_GOODS))->sum('c.goods_number * t.type_money')); 
	    /* 取得购物车中非赠品总金额 */
	    $amount = floatval($db_cart->where(array('session_id' => SESS_ID , 'is_gift' => 0 , 'rec_type' => CART_GENERAL_GOODS))->sum('goods_price * goods_number'));
	}
	/* 按订单发的红包 */
	$order_total = floatval($db_bonus->field('FLOOR('.$amount.' / min_amount) * type_money')->find('send_type = "'. SEND_BY_ORDER . '" AND send_start_date <= '.$today.'  AND send_end_date >= '.$today.' AND min_amount > 0'));
	return $goods_total + $order_total;

	//	$sql = "SELECT SUM(c.goods_number * t.type_money)" .
	//			"FROM " . $GLOBALS['ecs']->table('cart') . " AS c, "
	//					. $GLOBALS['ecs']->table('bonus_type') . " AS t, "
	//							. $GLOBALS['ecs']->table('goods') . " AS g " .
	//							"WHERE c.session_id = '" . SESS_ID . "' " .
	//							"AND c.is_gift = 0 " .
	//							"AND c.goods_id = g.goods_id " .
	//							"AND g.bonus_type_id = t.type_id " .
	//							"AND t.send_type = '" . SEND_BY_GOODS . "' " .
	//							"AND t.send_start_date <= '$today' " .
	//							"AND t.send_end_date >= '$today' " .
	//							"AND c.rec_type = '" . CART_GENERAL_GOODS . "'";
	//	$goods_total = floatval($GLOBALS['db']->getOne($sql));

	//	$sql = "SELECT SUM(goods_price * goods_number) " .
	//			"FROM " . $GLOBALS['ecs']->table('cart') .
	//			" WHERE session_id = '" . SESS_ID . "' " .
	//			" AND is_gift = 0 " .
	//			" AND rec_type = '" . CART_GENERAL_GOODS . "'";
	//	$amount = floatval($GLOBALS['db']->getOne($sql));

	//	$sql = "SELECT FLOOR('$amount' / min_amount) * type_money " .
	//	"FROM " . $GLOBALS['ecs']->table('bonus_type') .
	//	" WHERE send_type = '" . SEND_BY_ORDER . "' " .
	//	" AND send_start_date <= '$today' " .
	//	"AND send_end_date >= '$today' " .
	//	"AND min_amount > 0 ";
	//	$order_total = floatval($GLOBALS['db']->getOne($sql));
	}

/**
* 处理红包（下订单时设为使用，取消（无效，退货）订单时设为未使用
* @param   int	 $bonus_id   红包编号
* @param   int	 $order_id   订单号
* @param   int	 $is_used	是否使用了
*/
function change_user_bonus($bonus_id, $order_id, $is_used = true) 
{
	$db = RC_Loader::load_app_model('user_bonus_model', 'bonus');
	if ($is_used) {
		$data = array(
				'used_time'	=> RC_Time::gmtime(),
				'order_id'	=> $order_id
		);
		$db->where(array('bonus_id' => $bonus_id))->update($data);
	} else {
		$data = array(
				'used_time'	=> 0,
				'order_id'	=> 0
		);
		$db->where(array('bonus_id' => $bonus_id))->update($data);
	}
	
	//	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_bonus') . ' SET ' .
	//			'used_time = ' . gmtime() . ', ' ."order_id = '$order_id' " ."WHERE bonus_id = '$bonus_id'";
	//	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_bonus') . ' SET ' .
	//			'used_time = 0, ' .'order_id = 0 ' ."WHERE bonus_id = '$bonus_id'";
	//	$GLOBALS['db']->query($sql);
}

/********从order.func移出的有关红包的方法---end************/

/********从system.func移出的有关红包的方法---start************/
/**
 * 取得红包类型数组（用于生成下拉列表）
 *
 * @return  array       分类数组 bonus_typeid => bonus_type_name
 */
function get_bonus_type()
{
	$db = RC_Loader::load_app_model('bonus_type_model', 'bonus');
	$bonus = array();

	$data = $db->field('type_id, type_name, type_money')->where('send_type = 3')->select();
	if (!empty($data)) {
		foreach ($data as $row) {
			$bonus[$row['type_id']] = $row['type_name'].' [' .sprintf(ecjia::config('currency_format'), $row['type_money']).']';
		}
	}
	return $bonus;
}

/**
 * 
 * 取得用户等级数组,按用户级别排序
 * @param   bool      $is_special      是否只显示特殊会员组
 * @return  array     rank_id=>rank_name
 */
function get_rank_list($is_special = false)
{
	//这个它调的model还是user的，做不了彻底的隔离，可以在每个模块提供公用的api,把这些方法放进去 
	$db = RC_Loader::load_app_model('user_rank_model', 'user');

	$rank_list = array();
	if ($is_special) {
		$data = $db->field('rank_id, rank_name, min_points')->where('special_rank = 1')->order('min_points asc')->select();
	} else {
		$data = $db->field('rank_id, rank_name, min_points')->order('min_points asc')->select();
	}
	if (!empty($data)) {
		foreach ($data as $row) {
			$rank_list[$row['rank_id']] = $row['rank_name'];
		}
	}
	return $rank_list;
}

/********从system.func移出的有关红包的方法---start************/

// end