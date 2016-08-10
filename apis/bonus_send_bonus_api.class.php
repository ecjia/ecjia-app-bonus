<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 发放红包功能
 * @author will.chen
 *
 */
class bonus_send_bonus_api extends Component_Event_Api {
	
    /**
     * @param  array $options	条件参数
     * @return array
     */
	public function call(&$options) {
		if (!is_array($options) || !isset($options['type'])) {
			return new ecjia_error('invalid_parameter', '无效的参数');
		}
		
		return $this->send_coupon($options);
	}
	
	/* 发放优惠券*/
	private function send_coupon($options) {
		$db_user_bonus = RC_Loader::load_app_model('user_bonus_model', 'bonus');
		$result = $db_user_bonus->where(array('bonus_type_id' => $options['bonus_type_id'], 'user_id' => $_SESSION['user_id']))->find();
		if (empty($result)) {
			
			$data = array(
				'bonus_type_id' => $options['bonus_type_id'],
				'user_id'	   	=> $_SESSION['user_id'],
			);
			$db_user_bonus->insert($data);
			return true;
		} 
	}
}

// end