<?php
/**
 * 添加管理员记录日志操作对象
 *
 */
function assign_adminlog_content() {
	ecjia_admin_log::instance()->add_object('bonus', '红包');
	ecjia_admin_log::instance()->add_object('send_manage', '线下红包');
	ecjia_admin_log::instance()->add_object('goods_bonus', '商品红包');
	ecjia_admin_log::instance()->add_object('user_bonus', '会员红包');
}

//end