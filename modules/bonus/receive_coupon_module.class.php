<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 领取优惠券
 * @author will.chen
 *
 */
class receive_coupon_module extends api_front implements api_interface {
	
	 public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {	
		$this->authSession();	
		
// 		$bonus_id = isset($_POST['bonus_type_id']) ? $_POST['bonus_type_id'] : 0 ;
		$bonus_id = $this->requestData('bonus_type_id', 0);
 		if ($bonus_id <= 0 ) {
 			EM_Api::outPut(101);
 		}
 		if (!empty($_SESSION['user_id'])) {
 			$user_id = $_SESSION['user_id'];
 		}
 		$time = RC_Time::gmtime();
 		$where = array(
 				'send_type'			=> SEND_COUPON,
 				'ub.user_id'		=> $user_id,
 				'ub.bonus_type_id'	=> $bonus_id,
 				'send_start_date' 	=> array('elt' => $time),
 				'send_end_date'		=> array('egt' => $time),
 		);
 		$user_bonus_count = RC_Model::model('bonus/user_bonus_type_viewmodel')->join(array('bonus_type'))->where($where)->count();
 		
 		if ($user_bonus_count > 0) {
 			return new ecjia_error('received', '此优惠卷每人只限领一次'); 
 		}
 		$options = array('type' => SEND_COUPON, 'bonus_type_id' => $bonus_id, 'where' => $where);
 		$result = RC_Api::api('bonus', 'send_bonus', $options);
 		if (is_ecjia_error($result)) {
 			return $result;	
 		}
 		return array();
	}
}

// end