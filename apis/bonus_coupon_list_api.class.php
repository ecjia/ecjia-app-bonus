<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 获取优惠红包
 * @author will.chen
 *
 */
class bonus_coupon_list_api extends Component_Event_Api {
    
    public function call(&$options) {
    	if (!is_array($options) || empty($options['location'])) {
    		return new ecjia_error('invalid_parameter', '参数无效');
    	}
        return $this->coupon_list($options);
    }
    
    /**
	 * 取取优惠红包
	 * @param   array	 $location		经纬度
	 * @return  array   优惠红包数组
	 */
	private function coupon_list($options) 
	{
		$where = array();
		$where['bt.send_type'] = '5';
		$where['bt.seller_id'] = array('gt' => '0');
		/*根据经纬度查询附近店铺*/
		if (is_array($options['location']) && !empty($options['location']['latitude']) && !empty($options['location']['longitude'])) {
			$geohash = RC_Loader::load_app_class('geohash', 'shipping');
			$geohash_code = $geohash->encode($options['location']['latitude'] , $options['location']['longitude']);
			$geohash_code = substr($geohash_code, 0, 5);
			$where['geohash'] = array('like' => "%$geohash_code%");
		}
	   
	    if (!empty($_SESSION['user_id'])) {
	    	$where['ub.user_id'] =  $_SESSION['user_id'];
	    }
		$dbview = RC_Model::Model('bonus/bonus_type_viewmodel');
		$record_count = $dbview->join(array('seller_shopinfo', 'user_bonus'))->where($where)->group('bt.type_id')->count();
		//实例化分页
		$page_row = new ecjia_page($record_count, $options['size'], 6, '', $options['page']);
		$par = array(
				'where' => $where,
				'limit' => $page_row,
		);
		$res = RC_Model::Model('bonus/bonus_type_viewmodel')->seller_coupon_list($par);
		$list = array();
		if (!empty($res)) {
			foreach ($res as $row) {
				$list['shop_name']  				= $row['shop_name'];
				$list['bonus_id']   				= $row['type_id'];
				$list['bonus_name']					= $row['type_name'];
				$list['bonus_amount']				= intval($row['bonus_amount']);
				$list['formatted_bonus_amount']		= price_format($row['bonus_amount']);
				$list['request_amount']				= $row['min_goods_amount'];
				$list['formatted_request_amount']	= price_format($row['request_amount']);
				$list['formatted_start_date']		= RC_Time::local_date(ecjia::config('date_format'), $row['use_start_date']);
				$list['formatted_end_date']			= RC_Time::local_date(ecjia::config('date_format'), $row['use_end_date']);
				$list['received_coupon']			= (isset($row['user_id']) && $row['user_id'] > 0) ? 1 : 0 ;
				$lists[] = $list;
			}
		}
		return array('coupon_list' => $lists, 'page' => $page_row);		
	}
}

// end