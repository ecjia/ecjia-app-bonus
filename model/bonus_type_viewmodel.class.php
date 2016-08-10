<?php
defined('IN_ROYALCMS') or exit('No permission resources.');

class bonus_type_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'bonus_type';
		$this->table_alias_name	= 'bt';
		
		$this->view = array(
				'user_bonus' 	=> array(
				'type' 			=> Component_Model_View::TYPE_LEFT_JOIN,
				'alias' 		=> 'ub',
				'field' 		=> 'bt.type_id, bt.type_name, bt.type_money, ub.bonus_id',
				'on'   			=> 'bt.type_id = ub.bonus_type_id'
				),
				/*商家优惠红包*/
				'seller_shopinfo' 	=> array(
						'type' 			=> Component_Model_View::TYPE_LEFT_JOIN,
						'alias' 		=> 'ssi',
						'on'   			=> 'bt.seller_id = ssi.id'
				),
		);
		parent::__construct();
	}
	
	/*获取商家优惠红包列表*/
	public function seller_coupon_list($options) {
		$res = $this->join(array('seller_shopinfo', 'user_bonus'))->where($options['where'])->field('ssi.shop_name, bt.*,ub.user_id')->group('bt.type_id')->limit($options['limit']->limit())->select();
		return $res;
	}
}

// end