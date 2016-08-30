<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 红包信息
 * @author will.chen
 *
 */
class bonus_bonus_info_api extends Component_Event_Api {
    
    public function call(&$options) {
        if (!is_array($options) || !isset($options['bonus_id'])) {
            return new ecjia_error('invalid_parameter', RC_Lang::get('bonus::bonus.invalid_parameter'));
        }
        $options['bonus_sn'] = isset($options['bonus_sn']) ? $options['bonus_sn'] : '';
        return $this->bonus_info($options['bonus_id'], $options['bonus_sn']);
    }
    
    /**
	* 取得红包信息
	* @param   int	 $bonus_id   红包id
	* @param   string  $bonus_sn   红包序列号
	* @param   array   红包信息
	*/
	private function bonus_info($bonus_id, $bonus_sn = '') 
	{
		$dbview	= RC_Loader::load_app_model('user_bonus_type_viewmodel', 'bonus');
		$dbview->view = array(
			'bonus_type' => array(
				'type'	=> Component_Model_View::TYPE_LEFT_JOIN,
				'alias'	=> 'bt',
				'field'	=> 'bt.type_id, bt.type_name, bt.type_money, bt.send_type, bt.usebonus_type, bt.min_amount, bt.max_amount, bt.send_start_date, bt.send_end_date, bt.use_start_date, bt.use_end_date, bt.min_goods_amount, bt.seller_id as seller_id, ub.*',
				'on'	=> 'bt.type_id = ub.bonus_type_id'
			)
		);
	
		if ($bonus_id > 0) {
			return $dbview->find(array('ub.bonus_id' => $bonus_id));
		} else {
			return $dbview->find(array('ub.bonus_sn' => $bonus_sn));
		}
	}
}

// end