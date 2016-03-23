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
				)
		);
		parent::__construct();
	}
}

// end