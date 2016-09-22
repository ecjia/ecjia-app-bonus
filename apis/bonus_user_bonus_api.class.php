<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 获取可使用红包
 * @author will.chen
 *
 */
class bonus_user_bonus_api extends Component_Event_Api {
    
    public function call(&$options) {
        
        
        return $this->user_bonus($options['user_id'], $options['goods_amount'], $options['cart_id']);
    }
    
    /**
	 * 取得用户当前可用红包
	 * @param   int	 $user_id		用户id
	 * @param   float   $goods_amount   订单商品金额
	 * @return  array   红包数组
	 */
	private function user_bonus($user_id, $goods_amount = 0, $cart_id = array()) {
		//$db_cart_view = RC_Model::model('cart/cart_goods_viewmodel');
		//$where = array();
		
		$db_cart_view = RC_DB::table('cart as c')->leftJoin('goods as g', RC_DB::raw('c.goods_id'), '=', RC_DB::raw('g.goods_id'));
	    if(!empty($cart_id)){
	    	//$where = array('c.rec_id' => $cart_id);
	    	$db_cart_view->where(RC_DB::raw('c.rec_id'), '=', $cart_id);
	    }
	    
	    //$where['c.user_id']    = $_SESSION['user_id'];
	    //$where['rec_type']    = CART_GENERAL_GOODS;
	    $db_cart_view->where(RC_DB::raw('c.user_id'), '=', $_SESSION['user_id']);
	    $db_cart_view->where(RC_DB::raw('c.rec_type'), '=', CART_GENERAL_GOODS);
	   
	    //$goods_list = $db_cart_view->join(array('goods'))->field('g.user_id')->where($where)->group('g.user_id')->select();
	    //$goods_list = $db_cart_view->join(array('goods'))->field('g.seller_id')->where($where)->group('g.seller_id')->select();
	     
// 	    $goods_list = $db_cart_view->select(RC_DB::raw('g.seller_id'))->groupBy(RC_DB::raw('g.seller_id'))->get();
		
// 		$goods_user = array();
// 		if($goods_list){
// 			foreach($goods_list as $key=>$row){
// 				$goods_user[] = $row['user_id'];
// 			}
// 		}
		
		//$dbview	= RC_Model::model('bonus/user_bonus_type_viewmodel');
		$dbview = RC_DB::table('user_bonus as ub')->leftJoin('bonus_type as bt', RC_DB::raw('ub.bonus_type_id'), '=', RC_DB::raw('bt.type_id'));
		$today = RC_Time::gmtime();
		//$dbview->view = array(
		//		'bonus_type' 	=> array(
		//				'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
		//				'alias'	=> 'bt',
		//				'field'	=> 'bt.type_id, bt.type_name, bt.type_money, ub.bonus_id, bt.seller_id, bt.usebonus_type',
		//				'on'   	=> 'ub.bonus_type_id = bt.type_id'
		//		)
		//);
		//$bt_where = array('bt.use_start_date' => array('elt' => $today),
		//		'bt.use_end_date'		=> array('egt' => $today),
		//		'bt.min_goods_amount'	=> array('elt' => $goods_amount),
		//		'ub.user_id'			=> array('neq' => 0),
		//		'ub.user_id'			=> $user_id,
		//		'ub.order_id'			=> 0	
		//);

		//$row = $dbview->where($bt_where)->select();
		$row = $dbview
			->where(RC_DB::raw('bt.use_end_date'), '>=', $today)
			->where(RC_DB::raw('bt.min_goods_amount'), '<=', $goods_amount)
			->where(RC_DB::raw('ub.user_id'), '<>', 0)
			->where(RC_DB::raw('ub.user_id'), '=', $user_id)
			->where(RC_DB::raw('ub.order_id'), '=', 0)
			->select(RC_DB::raw('bt.type_id'),RC_DB::raw('bt.type_name'), RC_DB::raw('bt.type_money'), RC_DB::raw('ub.bonus_id'), RC_DB::raw('bt.usebonus_type'))->get();
		
// 		foreach ($row as $key => $val) {
// 			if ($val['usebonus_type'] == 0) {
// 				if (!in_array($val['user_id'], $goods_user)) {
// 					unset($row[$key]);
// 				}
// 			}
// 		}
		
		$row = array_merge($row);
		
		return $row;
	}
}

// end