<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台权限API
 * @author royalwang
 *
 */
class bonus_admin_purview_api extends Component_Event_Api {
    
    public function call(&$options) {
        $purviews = array(
            array('action_name' => __('红包类型管理'), 'action_code' => 'bonus_type_manage', 'relevance'   => ''),
        	array('action_name' => __('红包类型添加'), 'action_code' => 'bonus_type_add', 'relevance'   => ''),
        	array('action_name' => __('红包类型更新'), 'action_code' => 'bonus_type_update', 'relevance'   => ''),
        	array('action_name' => __('红包类型删除'), 'action_code' => 'bonus_type_delete', 'relevance'   => ''),
        		
        	array('action_name' => __('红包发送管理'), 'action_code' => 'bonus_send_manage', 'relevance'   => ''),
        	
        	array('action_name' => __('红包管理'), 'action_code' => 'bonus_manage', 'relevance'   => ''),
        	array('action_name' => __('红包删除'), 'action_code' => 'bonus_delete', 'relevance'   => '')
        );
        
        return $purviews;
    }
}

// end