<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 获取优惠红包列表
 * @author zrl
 *
 */
class coupon_module implements ecjia_interface {
	
	public function run(ecjia_api & $api) {
		EM_Api::authSession();
		$location	 = _POST('location', array());
		/*经纬度为空判断*/
		if (!is_array($location) || empty($location['longitude']) || empty($location['latitude'])) {
			$coupon_list = array();
			$page = array(
					'total'	=> '0',
					'count'	=> '0',
					'more'	=> '0',
			);
			EM_Api::outPut($coupon_list, $page);
		}
		
		$page_parm = EM_Api::$pagination;
		$page = $page_parm['page'];
		$size = $page_parm['count'];
		
		$options = array('location' => $location, 'page' => $page, 'size' => $size);
		
		$result = RC_Api::api('bonus', 'coupon_list', $options);
		if (is_ecjia_error($result)) {
			return $result;
		}
		
		$pager = array(
				'total' => $result['page']->total_records,
				'count' => $result['page']->total_records,
				'more'	=> $result['page']->total_pages <= $page ? 0 : 1,
		);
		
		EM_Api::outPut($result['coupon_list'], $pager);

	 }	
}
// end