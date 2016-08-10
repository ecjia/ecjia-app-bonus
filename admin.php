<?php
/**
 * 红包类型的处理
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin extends ecjia_admin {
	private $db_goods;
	private $db_user_bonus;
	private $db_bonus_type;
	private $db_user;
	private $db_user_rank;
	public function __construct() {
		parent::__construct();
		
 		RC_Lang::load('bonus');
		RC_Loader::load_app_func('common', 'goods');
		RC_Loader::load_app_func('category', 'goods');
		RC_Loader::load_app_func('bonus');
		RC_Loader::load_app_func('global');
		assign_adminlog_content();
		
		$this->db_user_bonus 	= RC_Loader::load_app_model('user_bonus_model');
		$this->db_bonus_type 	= RC_Loader::load_app_model('bonus_type_model' );
		$this->db_goods 		= RC_Loader::load_app_model('goods_model', 'goods');
		$this->db_user 		 	= RC_Loader::load_app_model('users_model', 'user');
		$this->db_user_rank		= RC_Loader::load_app_model('user_rank_model', 'user');

		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');

		/* 红包、红包类型列表页面 js/css */
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js') , array(), false, false);
		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'), array(), false, false);
		
		/* 红包类型编辑页面 js/css */
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Style::enqueue_style('datepicker', RC_Uri::admin_url('statics/lib/datepicker/datepicker.css'));
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Script::enqueue_script('bonus_type', RC_App::apps_url('statics/js/bonus_type.js', __FILE__), array(), false, true);
		RC_Script::enqueue_script('bonus', RC_App::apps_url('statics/js/bonus.js', __FILE__), array(), false, true);
		RC_Script::enqueue_script('bootstrap-datepicker', RC_Uri::admin_url('statics/lib/datepicker/bootstrap-datepicker.min.js'));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('红包类型'), RC_Uri::url('bonus/admin/init')));
	}
	
	/**
	 * 红包类型列表页面
	 */
	public function init() {
		$this->admin_priv('bonus_type_manage', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here', RC_Lang::lang('bonustype_list'));
		$this->assign('action_link', array('text' => RC_Lang::lang('bonustype_add'), 'href' => RC_Uri::url('bonus/admin/add')));
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('红包类型')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台红包类型列表页面，系统中所有的红包都会显示在此列表中。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:红包类型" target="_blank">关于红包类型帮助文档</a>') . '</p>'
		);
		
		$list = get_type_list();
		
		$this->assign('type_list', $list);
		$this->assign('bonustype', $list['filter']);
		$this->assign('search_action', RC_Uri::url('bonus/admin/init'));
		$this->assign_lang();
		
		$this->display('bonus_type.dwt');
	}

	/**
	 * 红包类型添加页面
	 */
	public function add() {
		$this->admin_priv('bonus_type_add', ecjia::MSGTYPE_JSON);
	
		$this->assign('ur_here', RC_Lang::lang('bonustype_add'));
		$this->assign('action_link', array('href' => RC_Uri::url('bonus/admin/init'), 'text' => '红包类型列表'));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('添加红包类型')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台添加红包页面，可以在此页面添加红包类型信息。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:红包类型#.E6.B7.BB.E5.8A.A0.E7.BA.A2.E5.8C.85.E7.B1.BB.E5.9E.8B" target="_blank">关于添加红包类型帮助文档</a>') . '</p>'
		);
	
		$next_month = RC_Time::local_strtotime('+1 months');
		$bonus_arr['send_start_date'] = RC_Time::local_date('Y-m-d');
		$bonus_arr['use_start_date']  = RC_Time::local_date('Y-m-d');
		$bonus_arr['send_end_date']   = RC_Time::local_date('Y-m-d', $next_month);
		$bonus_arr['use_end_date']    = RC_Time::local_date('Y-m-d', $next_month);
		
		$this->assign('bonus_arr', $bonus_arr);
		$this->assign('form_action', RC_Uri::url('bonus/admin/insert'));
		
		$this->assign_lang();
		$this->display('bonus_type_info.dwt');
	}
	
	/**
	 * 红包类型添加的处理
	 */
	public function insert() {
		$this->admin_priv('bonus_type_add', ecjia::MSGTYPE_JSON);

		$type_name   = !empty($_POST['type_name']) ? trim($_POST['type_name']) : '';
		$type_id     = !empty($_POST['type_id'])    ? intval($_POST['type_id'])    : 0;
		$min_amount  = !empty($_POST['min_amount']) ? floatval($_POST['min_amount']) : 0;
		$bonus_type  = intval($_POST['bonus_type']) == 1 ? 1 : 0;
		
		if ($this->db_bonus_type->where(array('type_name' => $type_name))->count() > 0) {
			$this->showmessage(RC_Lang::lang('type_name_exist'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$send_startdate = !empty($_POST['send_start_date']) ? RC_Time::local_strtotime($_POST['send_start_date']) : '';
		$send_enddate   = !empty($_POST['send_end_date']) ? RC_Time::local_strtotime($_POST['send_end_date']) : '';
		$use_startdate  = !empty($_POST['use_start_date']) ? RC_Time::local_strtotime($_POST['use_start_date']) : '';
		$use_enddate    = !empty($_POST['use_end_date']) ? RC_Time::local_strtotime($_POST['use_end_date']) : '';
		
		if ($send_startdate >= $send_enddate) {
			$this->showmessage('发送起始日期不能超于发送结束日期', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		
		if ($use_startdate >= $use_enddate) {
			$this->showmessage('使用起始日期不能超于使用结束日期', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		
		$data = array(
			'type_name'        	=> $type_name,
			'type_money'       	=> floatval($_POST['type_money']),
			'send_start_date'  	=> $send_startdate,
			'send_end_date'    	=> $send_enddate,
			'use_start_date'   	=> $use_startdate,
			'use_end_date'     	=> $use_enddate,
			'send_type'        	=> intval($_POST['send_type']),
			'min_amount'       	=> $min_amount,
			'min_goods_amount' 	=> floatval($_POST['min_goods_amount']),
			'usebonus_type'		=> $bonus_type,
			//'user_id'			=> 0,
			'seller_id'			=> 0,
		);
		$id=$this->db_bonus_type->insert($data);
		 
		ecjia_admin::admin_log($type_name, 'add', 'bonustype');
	
		$links[] = array('text' => RC_Lang::lang('back_list'), 'href' => RC_Uri::url('bonus/admin/init'));
		$links[] = array('text' => RC_Lang::lang('continus_add'), 'href' => RC_Uri::url('bonus/admin/add'));
		$this->showmessage(RC_Lang::lang('add') . "&nbsp;" . $type_name . "&nbsp;" . RC_Lang::lang('attradd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links, 'pjaxurl' => RC_Uri::url('bonus/admin/edit', array('type_id' => $id))));
	}
	
	/**
	 * 红包类型编辑页面
	 */
	public function edit() {
		$this->admin_priv('bonus_type_update', ecjia::MSGTYPE_JSON);
	
		$type_id   = !empty($_GET['type_id']) ? intval($_GET['type_id']) : 0;
		$bonus_arr = $this->db_bonus_type->where(array('type_id' => $type_id))->find();
		$bonus_arr['send_start_date'] = RC_Time::local_date('Y-m-d', $bonus_arr['send_start_date']);
		$bonus_arr['send_end_date']   = RC_Time::local_date('Y-m-d', $bonus_arr['send_end_date']);
		$bonus_arr['use_start_date']  = RC_Time::local_date('Y-m-d', $bonus_arr['use_start_date']);
		$bonus_arr['use_end_date']    = RC_Time::local_date('Y-m-d', $bonus_arr['use_end_date']);

		$this->assign('ur_here',     RC_Lang::lang('bonustype_edit'));
		$this->assign('action_link', array('href' => RC_Uri::url('bonus/admin/init'), 'text' => '红包类型列表'));
		$this->assign('bonus_arr',   $bonus_arr);
		$this->assign('form_action', RC_Uri::url('bonus/admin/update'));
	
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('编辑红包类型')));
		
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台编辑红包类型页面，可以在此对相应的红包类型进行编辑。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:红包类型#.E7.BC.96.E8.BE.91.E7.BA.A2.E5.8C.85.E7.B1.BB.E5.9E.8B" target="_blank">关于编辑红包类型帮助文档</a>') . '</p>'
		);
		
		$this->assign_lang();
		$this->display('bonus_type_info.dwt');
	}
	
	/**
	 * 红包类型编辑的处理
	 */
	public function update() {
		$this->admin_priv('bonus_type_update', ecjia::MSGTYPE_JSON);
		

		$type_name = !empty($_POST['type_name']) ? trim($_POST['type_name']) : '';
		$old_typename = !empty($_POST['old_typename']) ? trim($_POST['old_typename']) : '';
		if ($type_name != $old_typename ) {
			if ($this->db_bonus_type->where(array('type_name' => $type_name))->count() > 0) {
			 	$this->showmessage(RC_Lang::lang('type_name_exist'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}
	
		$use_startdate  = !empty($_POST['use_start_date']) ? RC_Time::local_strtotime($_POST['use_start_date']) : '';
		$use_enddate    = !empty($_POST['use_end_date']) ? RC_Time::local_strtotime($_POST['use_end_date']) : '';

		if ($use_startdate >= $use_enddate) {
			$this->showmessage('使用起始日期不能超于使用结束日期', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	
		$type_id     = !empty($_POST['type_id'])    ? intval($_POST['type_id'])    : 0;
		$min_amount  = !empty($_POST['min_amount']) ? intval($_POST['min_amount']) : 0;
		$data = array(
			'type_name'        => $type_name,
			'type_money'       => floatval($_POST['type_money']),
			'use_start_date'   => $use_startdate,
			'use_end_date'     => $use_enddate,
			'send_type'        => intval($_POST['send_type']),
			'min_amount'       => $min_amount,
			'min_goods_amount' => floatval($_POST['min_goods_amount']),
			'usebonus_type'	   => intval($_POST['bonus_type']),
		);
		
		if ($_POST['send_start_date'] >= $_POST['send_end_date']) {
			$this->showmessage('发送起始日期不能超于发送结束日期', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		
		if ( isset($_POST['send_start_date']) && !empty($_POST['send_start_date'])) {
			$send_startdate = RC_Time::local_strtotime($_POST['send_start_date']);
			$data['send_start_date'] = $send_startdate;
		}
		if ( isset($_POST['send_end_date']) && !empty($_POST['send_end_date'])) {
			$send_enddate   = RC_Time::local_strtotime($_POST['send_end_date']);
			$data['send_end_date'] = $send_enddate;
		}
	
		$this->db_bonus_type->where(array('type_id' => $type_id))->update($data);
	
		ecjia_admin::admin_log($type_name, 'edit', 'bonustype');
		$links[] = array('text' => RC_Lang::lang('back_list'), 'href' => RC_Uri::url('bonus/admin/init'));
		$links[] = array('text' => RC_Lang::lang('continus_add'), 'href' => RC_Uri::url('bonus/admin/add'));
		$this->showmessage(RC_Lang::lang('edit') .' '. $type_name.' '. RC_Lang::lang('attradd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links, 'pjaxurl' => RC_Uri::url('bonus/admin/edit', array('type_id' => $type_id))));
	}
	
	/**
	 * 编辑红包类型名称
	 */
	public function edit_type_name() {
		$this->admin_priv('bonus_type_update', ecjia::MSGTYPE_JSON);
		
// 		if (!empty($_SESSION['ru_id'])) {
// 			$this->showmessage(__('入驻商家没有操作权限，请登陆商家后台操作！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
// 		}
		
		$typename = trim($_POST['value']);
		$id		  = intval($_POST['pk']);
		/* 检查红包类型名称是否重复 */
		$old_name = $this->db_bonus_type->where(array('type_id' => $id))->get_field('type_name');
		if (!empty($typename)) {
			if ($typename != $old_name) {
				if ($this->db_bonus_type->where(array('type_name' => $typename))->count() == 0) {
					$this->db_bonus_type->where(array('type_id' => $id))->update(array('type_name' => $typename));
					$this->showmessage(RC_Lang::lang('attradd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
				} else {
					$this->showmessage(RC_Lang::lang('type_name_exist'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
				}
			}
		} else {
			$this->showmessage('请输红包类型名称！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 编辑红包金额
	 */
	public function edit_type_money() {
		$this->admin_priv('bonus_type_update', ecjia::MSGTYPE_JSON);
		
		if (!empty($_SESSION['ru_id'])) {
			$this->showmessage(__('入驻商家没有操作权限，请登陆商家后台操作！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$id		= intval($_POST['pk']);
		$val 	= floatval($_POST['value']);
		if ($val <= 0) {
			$this->showmessage(RC_Lang::lang('type_money_error'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
			$this->db_bonus_type->where(array('type_id' => $id))->update(array('type_money' => $val));
			$this->showmessage(RC_Lang::lang('attradd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('bonus/admin/init')));
		}
	}
	
	
	/**
	 * 编辑订单下限
	 */
	public function edit_min_amount() {
		$this->admin_priv('bonus_type_update', ecjia::MSGTYPE_JSON);
		
		
		$id  = intval($_POST['pk']);
		$val = floatval($_POST['value']);
		/* 可为0 */
		if ($val <= 0 && !($_POST['value'] === '0')) {
			$this->showmessage(RC_Lang::lang('min_amount_empty'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		else {
			$this->db_bonus_type->where(array('type_id' => $id))->update(array('min_amount' => $val));
			$this->showmessage(RC_Lang::lang('attradd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('bonus/admin/init')));
		}
	}
	
	/**
	 * 删除红包类型
	 */
	public function remove() {
		$this->admin_priv('bonus_type_delete', ecjia::MSGTYPE_JSON);	
		
		$id = intval($_GET['id']);
		if (empty($_SESSION['seller_id'])) {
			$this->db_bonus_type->where(array('type_id' => $id))->delete();
			$this->db_user_bonus->where(array('bonus_type_id' => $id))->delete();
			$data = array('bonus_type_id' => 0);
			$this->admin_priv('bonus_type_update', ecjia::MSGTYPE_JSON);
			$this->db_goods->where(array('bonus_type_id' => $id))->update($data);
		}
		/*记录管理员日志*/
		ecjia_admin::admin_log($id, 'remove', 'bonustype');
		$this->showmessage(RC_Lang::lang('del_bonustype_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}

	/**
	 * 红包发送页面
	 */
	public function send() {
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);
		
		/* 取得参数 */
		$id = !empty($_GET['id'])  ? intval($_GET['id'])  : 0;
		$send_by = intval($_GET['send_by']);
		
		$this->assign('ur_here',      RC_Lang::lang('send_bonus'));
		$this->assign('action_link',  array('href' => RC_Uri::url('bonus/admin/init', array('bonus_type' => $id)), 'text' => '红包类型列表'));

		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('发放红包')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台发放红包页面，可以在此页面发放红包。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:红包类型#.E5.8F.91.E6.94.BE.E7.BA.A2.E5.8C.85" target="_blank">关于发放红包帮助文档</a>') . '</p>'
		);
		
		$this->assign_lang();
		if ($send_by == SEND_BY_USER) {
			//用户发放
			$bonus_type = $this->db_bonus_type->field('type_id, type_name')->find(array('type_id' => $id));
			$this->assign('id',               $id);
			$this->assign('ranklist',         get_rank_list());
			$this->assign('bonus_type',       $bonus_type);
			$this->assign('form_action',      RC_Uri::url('bonus/admin/send_by_user_rank'));
			$this->assign('form_user_action', RC_Uri::url('bonus/admin/send_by_user'));
			$this->display('bonus_by_user.dwt');
			
		} elseif ($send_by == SEND_BY_GOODS) {
			//商品发放
			RC_Loader::load_app_func('category', 'goods');
			$bonus_type = $this->db_bonus_type->field('type_id, type_name')->find(array('type_id' => $id));
			$goods_list = get_bonus_goods($id);
			$where_sql = array('bonus_type_id' => array('gt' => 0), 'bonus_type_id' => array('neq' => $id));
			$other_goods_list = $this->db_goods->field('goods_id')->where($where_sql)->get_field('goods_id', true);
			if (!empty($other_goods_list)) {
				$this->assign('other_goods', join(',', $other_goods_list));
			}
		
			/* 模板赋值 */
			$this->assign('cat_list', cat_list());
			$this->assign('bonus_type_id', $id);
			$this->assign('brand_list', get_brand_list());
			$this->assign('bonus_type', $bonus_type);
			$this->assign('goods_list', $goods_list);
			$this->assign('form_search', RC_Uri::url('bonus/admin/get_goods_list'));
			$this->assign('form_action', RC_Uri::url('bonus/admin/send_by_goods'));
			$this->display('bonus_by_goods.dwt');
			
		} elseif ($send_by == SEND_BY_PRINT) {
			//线下发放
			$this->assign('type_list', get_bonus_type());
			$this->assign('form_action', RC_Uri::url('bonus/admin/send_by_print'));
			
			$this->display('bonus_by_print.dwt');
		} elseif ($send_by == SEND_COUPON) {//优惠券
			ecjia_screen::get_current_screen()->add_help_tab(array(
				'id'		=> 'overview',
				'title'		=> __('概述'),
				'content'	=>
				'<p>' . __('欢迎访问ECJia智能后台按照商品发放优惠券，在此页面可以对商品进行发放优惠券操作。') . '</p>'
			));
			
			ecjia_screen::get_current_screen()->set_help_sidebar(
				'<p><strong>' . __('更多信息:') . '</strong></p>' .
				'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:红包类型#.E6.8C.89.E7.85.A7.EF.BC.88.E5.95.86.E5.93.81.EF.BC.89.E5.8F.91.E6.94.BE.E7.BA.A2.E5.8C.85" target="_blank">关于按照商品发放红包帮助文档</a>') . '</p>'
			);
			
			//发放优惠券
			RC_Loader::load_app_class('goods_category', 'goods', false);
			/* 模板赋值 */
			$this->assign('cat_list', goods_category::cat_list());
			$this->assign('bonus_type_id', $id);
			$this->assign('brand_list', get_brand_list());
				
			$bonus_relation = RC_Loader::load_model('term_meta_model');
			$where = array(
					'object_type'	=> 'ecjia.goods',
					'object_group'	=> 'goods_bonus_coupon',
					'meta_key'		=> 'bonus_type_id',
					'meta_value'	=> $id,
			);
			$goods_group = $bonus_relation->where($where)->get_field('object_id', true);
			if (!empty($goods_group)) {
				$goods_list = $this->db_goods->field(array('goods_id', 'goods_name'))->in($goods_group)->select();
			} else {
				$goods_list = array();
			}
			$this->assign('goods_list', $goods_list);
			$this->assign('form_search', RC_Uri::url('bonus/admin/get_goods_list'));
			$this->assign('form_action', RC_Uri::url('bonus/admin/send_by_coupon'));
			
			$this->display('bonus_by_goods.dwt');
		}
	}
	
	/**
	 * 处理红包的发送页面 
	 */
	public function send_by_user_rank() {
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);
		
		if (!empty($_SESSION['ru_id'])) {
			$this->showmessage(__('入驻商家没有操作权限，请登陆商家后台操作！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}

		$user_list = array();
		$send_count = 0;
		/* 按等级发放红包时-只给通过邮件验证的用户发放红包 */
		$validated_email = empty($_POST['validated_email']) ? 0 : intval($_POST['validated_email']);
		/* 按会员等级来发放红包 */
		$rank_id = !empty($_POST['rank_id']) ? intval($_POST['rank_id']) : 0;
		
		if ($rank_id > 0) {
			$row = $this->db_user_rank->field('min_points, max_points, special_rank')->find(array('rank_id' => $rank_id));
			if ($row['special_rank']) {
				/* 特殊会员组处理 */
				if ($validated_email) {
					$user_list = $this->db_user->field('user_id, email, user_name')->where(array('user_rank' => $rank_id, 'is_validated' => 1))->select();
				} else {
					$user_list = $this->db_user->field('user_id, email, user_name')->where(array('user_rank' => $rank_id))->select();
				}
			} else {
				$where_sql = ' rank_points >= ' . intval($row['min_points']) . ' AND rank_points < ' . intval($row['max_points']);
				if ($validated_email) {
					$where_sql .= ' AND is_validated = 1';
				}
				$user_list = $this->db_user->field('user_id, email, user_name')->where($where_sql)->select();
			}
		}
		
		/* 发送红包 */
		$loop       	= 0;
		$loop_faild 	= 0;
		$bonus_type_id 	= intval($_POST['id']);
		$bonus_type 	= bonus_type_info($bonus_type_id);
		$tpl_name 		= 'send_bonus';
		$tpl   			= RC_Api::api('mail', 'mail_template', $tpl_name);
		
		$today = RC_Time::local_date(ecjia::config('date_format'));
		if (!empty($user_list)) {
			foreach ($user_list AS $key => $val) {
				/* 读取邮件配置项 */
				$db_config = RC_Loader::load_model('shop_config_model');
				$arr 	   = $db_config->get_email_setting();
				$email_cfg = array_merge($val, $arr);
				$email_cfg['reply_email'] = $arr['smtp_user'];
				$this->assign('user_name', $val['user_name']);
				$this->assign('shop_name', ecjia::config('shop_name'));
				$this->assign('send_date', $today);
				$this->assign('count', 1);
				$this->assign('money', price_format($bonus_type['type_money']));
				$content = $this->fetch_string($tpl['template_content']);
				if (add_to_maillist($val['user_name'], $email_cfg['email'], $tpl['template_subject'], $content, $tpl['is_html'])) {
					/* 向会员红包表录入数据 */
					$data = array(
						'bonus_type_id' => $bonus_type_id,
						'bonus_sn'  	=> 0,
						'user_id'   	=> $val['user_id'],
						'used_time' 	=> 0,
						'order_id'  	=> 0,
						'emailed'   	=> BONUS_INSERT_MAILLIST_SUCCEED,
					);
					$this->db_user_bonus->insert($data);
			
					$loop++;
				} else {
					/* 邮件发送失败，更新数据库 */
					$data = array(
						'bonus_type_id' => $bonus_type_id,
						'bonus_sn'  	=> 0,
						'user_id'   	=> $val['user_id'],
						'used_time' 	=> 0,
						'order_id'  	=> 0,
						'emailed'   	=> BONUS_INSERT_MAILLIST_FAIL,
					);
					$this->db_user_bonus->insert($data);
					$loop_faild++;
				}
			}
		} 
       
		/*记录管理员日志*/
		ecjia_admin::admin_log($loop."个", 'add', 'user_bonus');
		$this->showmessage(sprintf(RC_Lang::lang('sendbonus_count'), $loop), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('max_id' => $bonus_type_id));
	}

	/**
	 * 处理红包的发送页面 post
	 */
	public function send_by_user() {
		
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);
		
		$user_list = array();
		$user_ids = !empty($_POST['linked_array']) ? $_POST['linked_array'] : '';

		if (empty($user_ids)) {
			$this->showmessage(RC_Lang::lang('send_user_empty'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$user_array = (is_array($user_ids)) ? $user_ids : explode(',', $user_ids);
		
		$new_ids = array();
		if (!empty($user_array)) {
			foreach ($user_array as $value) {
				$new_ids[] = $value['user_id'];
			}
		}
		
		/* 根据会员ID取得用户名和邮件地址 */
		$user_list = $this->db_user->field('user_id, email, user_name')->in(array('user_id' => $new_ids))->select();
		$count = count($user_list);

		/* 发送红包 */
		$bonus_type_id = intval($_POST['bonus_type_id']);
		$bonus_type = bonus_type_info($bonus_type_id);
		$tpl_name = 'send_bonus';
		$tpl   = RC_Api::api('mail', 'mail_template', $tpl_name);
		
		$today = RC_Time::local_date(ecjia::config('date_format'));
			
		if (!empty($user_list)) {
			foreach ($user_list as $key => $val) {
				/* 读取邮件配置项 */
				$db_config = RC_Loader::load_model('shop_config_model');
				$arr       = $db_config->get_email_setting();
				$email_cfg = array_merge($val, $arr);
				$email_cfg['reply_email'] = $arr['smtp_user'];
				$this->assign('user_name', $val['user_name']);
				$this->assign('shop_name', ecjia::config('shop_name'));
				$this->assign('send_date', $today);
				$this->assign('count',     1);
				$this->assign('money',     price_format($bonus_type['type_money']));
				$content = $this->fetch_string($tpl['template_content']);
				if (add_to_maillist($val['user_name'], $email_cfg['email'], $tpl['template_subject'], $content, $tpl['is_html'])) {
					$data = array(
						'bonus_type_id' => $bonus_type_id,
						'bonus_sn'      => 0,
						'user_id' 	    => $val['user_id'],
						'used_time' 	=> 0,
						'order_id' 		=> 0,
						'emailed' 		=> BONUS_INSERT_MAILLIST_SUCCEED,
					);
					$result = $this->db_user_bonus->insert($data);
				} else {
					$data = array(
						'bonus_type_id' => $bonus_type_id,
						'bonus_sn' 	    => 0,
						'user_id' 		=> $val['user_id'],
						'used_time' 	=> 0,
						'order_id' 		=> 0,
						'emailed' 		=> BONUS_INSERT_MAILLIST_FAIL,
					);
					$result = $this->db_user_bonus->insert($data);
				}
			}
			if ($result) {
				$this->showmessage(sprintf(RC_Lang::lang('sendbonus_count'), $count), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
			}
		}
	}
	
	
	/**
	 * 添加发放红包的商品
	 */
	public function send_by_goods() {
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);

		$goods_id = !empty($_GET['linked_array']) ? $_GET['linked_array'] : '';
		$type_id = intval($_GET['bonus_type_id']);
		$data = array('bonus_type_id' => 0);
		
		$this->db_goods->where(array('bonus_type_id' => $type_id))->update($data);
		
		if (!empty($goods_id)) {
			$goods_array = (is_array($goods_id)) ? $goods_id : explode(',', $goods_id);
			$new_ids = array();
			if (!empty($goods_array)) {
				foreach ($goods_array as $value) {
					if (!empty($value['goods_id'])) {
						$new_ids[] = $value['goods_id'];
					}
				}
			}
			$data = array( 'bonus_type_id' => $type_id );
			$this->db_goods->in(array('goods_id' => $new_ids))->update($data);
			$ids = implode(',', $new_ids);
			ecjia_admin::admin_log($ids, 'add', 'goods_bonus');
		}
		
		$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}
	
	/**
	 * 添加发放红包的商品
	 */
	public function send_by_coupon() {
		$this->admin_priv('bonus_manage', ecjia::MSGTYPE_JSON);
		
		$goods_id = !empty($_POST['linked_array']) ? $_POST['linked_array'] : '';
		$type_id = intval($_POST['bonus_type_id']);
		$info = $this->db_bonus_type->where(array('type_id' => $type_id))->find();
	
		$bonus_relation = RC_Loader::load_model('term_meta_model');
		$where = array(
				'object_type'	=> 'ecjia.goods',
				'object_group'	=> 'goods_bonus_coupon',
				'meta_key'		=> 'bonus_type_id',
				'meta_value'	=> $type_id,
		);
		$goods_group = $bonus_relation->where(array($where))->get_field('object_id', true);
		$coupon_goods = array();
		/* 商品若不再优惠范围内，则新增*/
		if (is_array($goods_id) && !empty($goods_id)) {
			foreach ($goods_id as $val) {
				if (empty($goods_group) || !in_array($val['goods_id'], $goods_group)) {
					$data = array(
							'object_type'	=> 'ecjia.goods',
							'object_group'	=> 'goods_bonus_coupon',
							'object_id'		=> $val['goods_id'],
							'meta_key'		=> 'bonus_type_id',
							'meta_value'	=> $type_id,
					);
					$bonus_relation->insert($data);
				}
				$coupon_goods[] = $val['goods_id'];
			}
		}
	
		/* 更新取消的商品*/
		if (!empty($coupon_goods)) {
			$bonus_relation->in(array('object_id' => $coupon_goods), true)->delete($where);
		} else {
			$bonus_relation->delete($where);
		}
	
		if ($info['send_type'] == 0) {
			$send_type = RC_Lang::get('bonus::bonus.send_by.'.SEND_BY_USER);
		} elseif ($info['send_type'] == 1) {
			$send_type = RC_Lang::get('bonus::bonus.send_by.'.SEND_BY_GOODS);
		} elseif ($info['send_type'] == 2) {
			$send_type = RC_Lang::get('bonus::bonus.send_by.'.SEND_BY_ORDER);
		} elseif ($info['send_type'] == 3) {
			$send_type = RC_Lang::get('bonus::bonus.send_by.'.SEND_BY_PRINT);
		} elseif ($info['send_type'] == 4) {
			$send_type = RC_Lang::get('bonus::bonus.send_by.'.SEND_BY_REGISTER);
		} elseif ($info['send_type'] == 5) {
			$send_type = RC_Lang::get('bonus::bonus.send_by.'.SEND_COUPON);
		}
	
		ecjia_admin::admin_log(RC_Lang::get('bonus::bonus.send_type_is').$send_type.'，'.RC_Lang::get('bonus::bonus.bonustype_name_is').$info['type_name'], 'add', 'userbonus');
		$this->showmessage(RC_Lang::get('bonus::bonus.attradd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}
	
	
	/**
	 * 删除发放红包的商品
	 */
// 	public function drop_bonus_goods() {
// 		$this->admin_priv('bonus_manage', ecjia::MSGTYPE_JSON);
// 		$drop_goods     = str_replace('\\', '', $_GET['drop_ids']);
// 		$arguments      = str_replace('\\', '', $_GET['JSON']);
// 		$type_id        = $arguments[0];
// 		$data = array(
// 				'bonus_type_id' => 0
// 		);
// 		$this->db_goods->where(array('bonus_type_id' => $type_id))->in(array('goods_id' => $drop_goods))->update($data);
// 		/* 重新载入 */
// 		$arr = get_bonus_goods($type_id);
// 		$opt = array();
	
// 		foreach ($arr AS $key => $val) {
// 			$opt[] = array(
// 					'value' => $val['goods_id'],
// 					'text'  => $val['goods_name'],
// 					'data'  => '');
// 		}
// 		$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $opt));
// 	}
	
	
	/**
	 * 按线下发放红包
	 */
	public function send_by_print()	{
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);
		
// 		if (!empty($_SESSION['ru_id'])) {
// 			$this->showmessage(__('入驻商家没有操作权限，请登陆商家后台操作！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
// 		}

		@set_time_limit(0);
		/* 线下红包的类型ID和生成的数量的处理 */
		$bonus_typeid = !empty($_POST['bonus_type_id']) ? intval($_POST['bonus_type_id']) : 0;
		$bonus_sum    = !empty($_POST['bonus_sum'])     ? intval($_POST['bonus_sum'])    : 1;
	
		/* 生成红包序列号 */
		$num = $this->db_user_bonus->max('bonus_sn');
		$num = $num ? floor($num / 10000) : 100000;
	
		for ($i = 0, $j = 0; $i < $bonus_sum; $i++) {
			$bonus_sn = ($num + $i) . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
			$data = array(
				'bonus_type_id' => $bonus_typeid,
				'bonus_sn' 		=> $bonus_sn
			);
			$this->db_user_bonus->insert($data);
			ecjia_admin::admin_log($bonus_sn, 'add', 'send_manage');
			$j++;
		}
		$this->showmessage(RC_Lang::lang('creat_bonus') . $j . RC_Lang::lang('creat_bonus_num'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('max_id' => $bonus_typeid));
	}
	
	/**
	 * 发送邮件
	 */
	public function send_mail() {
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);

		$bonus_id = intval($_GET['bonus_id']);
		if ($bonus_id <= 0) {
			$this->showmessage('invalid params', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$bonus = bonus_info($bonus_id);
		if (empty($bonus)) {
			$this->showmessage(RC_Lang::lang('bonus_not_exist', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR));
		}
		$count = $this->send_bonus_mail($bonus['bonus_type_id'], array($bonus_id));
		$this->showmessage(sprintf(RC_Lang::lang('success_send_mail'), $count), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}
	
	/**
	 * 发送红包邮件
	 * @param   int     $bonus_type_id  红包类型id
	 * @param   array   $bonus_id_list  红包id数组
	 * @return  int     成功发送数量
	 */
	function send_bonus_mail($bonus_type_id, $bonus_id_list) {
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);
		$dbview = RC_Loader::load_app_model('user_bonus_type_viewmodel');

// 		if (!empty($_SESSION['ru_id'])) {
// 			$this->showmessage(__('入驻商家没有操作权限，请登陆商家后台操作！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
// 		}

		$bonus_type = bonus_type_info($bonus_type_id);
		if ($bonus_type['send_type'] != SEND_BY_USER) {
			return 0;
		}
		$dbview->view = array(
			'users' => array(
				'type' =>Component_Model_View::TYPE_LEFT_JOIN,
				'alias'=> 'u',
				'field'=> 'ub.bonus_id, u.user_name, u.email',
				'on'   => 'ub.user_id = u.user_id '
			)
		);
	
		$bonus_list = $dbview->where(array('ub.order_id' => 0, 'u.email' => array('neq' => '')))->in(array('ub.bonus_id' => $bonus_id_list))->select();
		if (empty($bonus_list)) {
			return 0;
		}
		$send_count = 0;
		/* 发送邮件 */
		//$tpl   = get_mail_template('send_bonus');
		$tpl_name = 'send_bonus';
		$tpl = RC_Api::api('mail', 'mail_template', $tpl_name);
		
		$today = RC_Time::local_date(ecjia::config('date_format'));
		if (!empty($bonus_list)) {
			foreach ($bonus_list AS $bonus) {
				$this->assign('user_name', $bonus['user_name']);
				$this->assign('shop_name', ecjia::config('shop_name'));
				$this->assign('send_date', $today);
				$this->assign('count',     1);
				$this->assign('money',     price_format($bonus_type['type_money']));
				$content = $this->fetch_string($tpl['template_content']);
				if (add_to_maillist($bonus['user_name'], $bonus['email'], $tpl['template_subject'], $content, $tpl['is_html'], false)) {
					$data =array( 'emailed' => BONUS_INSERT_MAILLIST_SUCCEED);
					$this->db_user_bonus->where(array('bonus_id' => $bonus['bonus_id']))->update($data);
					$send_count++;
				}
				else {
					$data = array( 'emailed' => BONUS_INSERT_MAILLIST_FAIL);
					$this->db_user_bonus->where(array('bonus_id' => $bonus['bonus_id']))->update($data);
				}
			}
		}
		return $send_count;
	}
	
	
	/**
	 * 导出线下发放的信息 excel
	 */
	public function gen_excel() {
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);
		
// 		if (!empty($_SESSION['ru_id'])) {
// 			$this->showmessage(__('入驻商家没有操作权限，请登陆商家后台操作！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
// 		}

		@set_time_limit(0);
		$tid  = !empty($_GET['tid']) ? intval($_GET['tid']) : 0;
		$type_name = $this->db_bonus_type->where(array('type_id' => $tid))->get_field('type_name');
		$bonus_filename = $type_name .'_bonus_list';
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$bonus_filename.xls");
		
		echo mb_convert_encoding(RC_Lang::lang('bonus_excel_file')."\t\n", "GBK", "UTF-8");
		echo mb_convert_encoding(RC_Lang::lang('bonus_sn')."\t" ,"GBK", "UTF-8");
		echo mb_convert_encoding(RC_Lang::lang('type_money')."\t","GBK", "UTF-8") ;
		echo mb_convert_encoding(RC_Lang::lang('type_name')."\t" ,"GBK", "UTF-8");
		echo mb_convert_encoding(RC_Lang::lang('use_enddate')."\t\n","GBK", "UTF-8") ;
		$val = array();
		$dbview = RC_Loader::load_app_model('user_bonus_type_viewmodel');

		$dbview->view = array(
			'bonus_type' => array(
				'type' =>Component_Model_View::TYPE_LEFT_JOIN,
				'alias'=> 'bt',
				'field'=> 'ub.bonus_id, ub.bonus_type_id, ub.bonus_sn, bt.type_name, bt.type_money, bt.use_end_date',
				'on'   => 'bt.type_id = ub.bonus_type_id'
			)
		);
		$data = $dbview->where(array('ub.bonus_type_id' => $tid))->order(array('ub.bonus_id' => 'DESC'))->select();
		$code_table = array();

		if (!empty($data)) {
			foreach ($data as $val) {
				echo mb_convert_encoding($val['bonus_sn']. "\t" ,"GBK", "UTF-8");
				echo mb_convert_encoding($val['type_money']. "\t","GBK", "UTF-8");
				if (!isset($code_table[$val['type_name']])) {
					$code_table[$val['type_name']] = $val['type_name'];
				}
				echo mb_convert_encoding($code_table[$val['type_name']]. "\t" ,"GBK", "UTF-8");
				echo mb_convert_encoding(RC_Time::local_date('Y-m-d', $val['use_end_date']),"GBK", "UTF-8");
				echo "\t\n";
			}
		}
	}
	
	/**
	 * 搜索商品
	 */
	public function get_goods_list() {
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);
		
		$keyword  = !empty($_GET['keywords']) ? trim($_GET['keywords']) : '';
		$cat_id   = intval($_GET['cat_id']);
		$brand_id = intval($_GET['brand_id']);
		$db_view  = RC_Loader::load_app_model('goods_auto_viewmodel','goods');
		$where = ' 1 ';
		if (!empty($cat_id)) {
			$where  .= ' and '.get_children($cat_id) ;
		}
		if (!empty($brand_id)) {
			$where  .= ' and brand_id = ' .$brand_id;
		}
  		if (!empty($keyword)) {
  			$where .=" and goods_name LIKE '%" . mysql_like_quote($keyword) . "%'
        		 OR goods_sn LIKE '%" . mysql_like_quote($keyword) . "%'
        		 OR goods_id LIKE '%" . mysql_like_quote($keyword) . "%'";
  		} 
		$arr=$db_view->join(null)->field('goods_id, goods_name, shop_price')->where($where)->limit(50)->select();

		$opt = array();
		if (!empty($arr)) {
			foreach ($arr AS $key => $val) {
				$opt[] = array(
					'value' => $val['goods_id'],
					'text'  => $val['goods_name'],
					'data'  => $val['shop_price']
				);
			}
		}
		$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $opt));
	}
	
	/**
	 * 搜索用户
	 */
	public function search_users() {
		$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);

// 		if (!empty($_SESSION['ru_id'])) {
// 			$this->showmessage(__('入驻商家没有操作权限，请登陆商家后台操作！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
// 		}
		
		$json = $_POST['JSON'];
		$keywords = !empty($json) && isset($json['keyword']) ? trim($json['keyword']) : '';
		$row = '';
		if (!empty($keywords)) {
			$row = $this->db_user->field("user_id, user_name")->where("user_name LIKE '%" . mysql_like_quote($keywords) . "%' OR user_id LIKE '%" . mysql_like_quote($keywords) . "%'")->select();
		}
		$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $row));
	}
	
	/**
	 * 红包列表
	 */
	public function bonus_list() {
		$this->admin_priv('bonus_manage', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here', RC_Lang::lang('bonus_list'));
		$this->assign('action_link', array('href' => RC_Uri::url('bonus/admin/init'), 'text' => '红包类型列表'));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('红包列表')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台红包类表页面，可以在此页面查看指定类型的红包列表。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:红包类型#.E6.9F.A5.E7.9C.8B.E7.BA.A2.E5.8C.85" target="_blank">关于红包列表帮助文档</a>') . '</p>'
		);
		
		$list = get_bonus_list();
		$bonus_type_id = intval($_GET['bonus_type']);
		$bonus_type = bonus_type_info($bonus_type_id);
		
		if ($bonus_type['send_type'] == SEND_BY_PRINT) {
			$this->assign('show_bonus_sn', 1);
		} elseif ($bonus_type['send_type'] == SEND_BY_USER) {
			$this->assign('show_mail', 1);
		}
			
		$this->assign('bonus_type_id', $bonus_type_id);
		$this->assign('bonus_list',    $list);
		$this->assign('form_action',   RC_Uri::url('bonus/admin/batch'));
		
		$this->assign_lang();
		$this->display('bonus_list.dwt');
	}
	
	/**
	 * 删除红包
	 */
	public function remove_bonus() {
		$this->admin_priv('bonus_delete', ecjia::MSGTYPE_JSON);
		
// 		if (!empty($_SESSION['ru_id'])) {
// 			$this->showmessage(__('入驻商家没有操作权限，请登陆商家后台操作！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
// 		}
		$id = intval($_GET['id']);
		$this->db_user_bonus->where(array('bonus_id'=> $id ))->delete();

		/* 记录日志 */
		ecjia_admin::admin_log($id, 'remove', 'bonus');
		$this->showmessage(RC_Lang::lang('attradd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);	
	}
	
	/**
	 * 批量操作
	 */
	public function batch() {
// 		if (!empty($_SESSION['ru_id'])) {
// 			$this->showmessage(__('入驻商家没有操作权限，请登陆商家后台操作！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
// 		}

		$bonus_type_id = intval($_GET['bonus_type_id']);
		$sel_action = trim($_GET['sel_action']);
		$action = !empty($sel_action) ? $sel_action : 'send';
		$ids = $_POST['checkboxes'];
		
		if ($action == 'remove') {
			$this->admin_priv('bonus_delete', ecjia::MSGTYPE_JSON);
		} else {
			$this->admin_priv('bonus_send_manage', ecjia::MSGTYPE_JSON);
		}
		if (!empty($ids)) {
			switch ($action) {
				case 'remove':
					if (!is_array($ids)) {
						$idsArray = explode(',', $ids);
						$count = count($idsArray);
					} else {
						$count = count($ids);
					}
					$this->db_user_bonus->in(array('bonus_id' => $ids))->delete();
					/* 记录日志 */
					ecjia_admin::admin_log($ids, 'batch_remove', 'bonus');

					$this->showmessage(sprintf(RC_Lang::lang('batch_drop_success'), $count), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('bonus/admin/bonus_list', array('bonus_type' => $bonus_type_id))));
					break;
		
				case 'send' :
					$this->send_bonus_mail($bonus_type_id, $ids);
					$this->showmessage(RC_Lang::lang('success_send_mail'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('bonus/admin/bonus_list', array('bonus_type' => $bonus_type_id))));
					break;
					
				default :
					break;
			}
		}
    }
}
//end