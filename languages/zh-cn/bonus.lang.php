<?php
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * ECJia 红包类型/红包管理程序
 */
/* 红包类型字段信息 */
$LANG['bonus_type'] = '红包类型';
$LANG['bonus_list'] = '红包列表';
$LANG['type_name'] = '类型名称';
$LANG['type_money'] = '红包金额';
$LANG['min_goods_amount'] = '最小订单金额';
$LANG['notice_min_goods_amount'] = '只有商品总金额达到这个数的订单才能使用这种红包';
$LANG['min_amount'] = '订单下限';
$LANG['max_amount'] = '订单上限';
$LANG['send_startdate'] = '发放起始日期';
$LANG['send_enddate'] = '发放结束日期';

$LANG['use_startdate'] = '使用起始日期';
$LANG['use_enddate'] = '使用结束日期';
$LANG['send_count'] = '发放数量';
$LANG['use_count'] = '使用数量';
$LANG['send_method'] = '如何发放此类型红包';
$LANG['send_type'] = '发放类型';
$LANG['param'] = '参数';
$LANG['no_use'] = '未使用';
$LANG['yuan'] = '元';
$LANG['user_list'] = '会员列表';
$LANG['type_name_empty'] = '红包类型名称不能为空！';
$LANG['type_money_empty'] = '红包金额不能为空！';
$LANG['min_amount_empty'] = '红包类型的订单下限不能为空！';
$LANG['max_amount_empty'] = '红包类型的订单上限不能为空！';
$LANG['send_count_empty'] = '红包类型的发放数量不能为空！';

$LANG['send_by'][SEND_BY_USER] = '按用户发放';
$LANG['send_by'][SEND_BY_GOODS] = '按商品发放';
$LANG['send_by'][SEND_BY_ORDER] = '按订单金额发放';
$LANG['send_by'][SEND_BY_PRINT] = '线下发放的红包';
$LANG['send_by'][SEND_BY_REGISTER] = '注册送红包';
$LANG['send_by'][SEND_COUPON] = '优惠券';
$LANG['report_form'] = '报表';
$LANG['send'] = '发放';
$LANG['bonus_excel_file'] = '线下红包信息列表';

$LANG['goods_cat'] = '选择商品分类';
$LANG['goods_brand'] = '商品品牌';
$LANG['goods_key'] = '商品关键字';
$LANG['all_goods'] = '可选商品';
$LANG['send_bouns_goods'] = '发放此类型红包的商品';
$LANG['remove_bouns'] = '移除红包';
$LANG['all_remove_bouns'] = '全部移除';
$LANG['goods_already_bouns'] = '该商品已经发放过其它类型的红包了!';
$LANG['send_user_empty'] = '您没有选择需要发放红包的会员，请返回!';
$LANG['batch_drop_success'] = '成功删除了 %d 个红包';
$LANG['sendbonus_count'] = '共发送了 %d 个红包。';
$LANG['send_bouns_error'] = '发送会员红包出错, 请返回重试！';
$LANG['no_select_bonus'] = '您没有选择需要删除的用户红包';
$LANG['bonustype_edit'] = '编辑红包类型';
$LANG['bonustype_view'] = '查看详情';
$LANG['drop_bonus'] = '删除红包';
$LANG['send_bonus'] = '发放红包';
$LANG['continus_add'] = '继续添加红包类型';
$LANG['back_list'] = '返回红包类型列表';
$LANG['bonustype_list'] = '红包类型列表';
$LANG['continue_add'] = '继续添加红包';
$LANG['back_bonus_list'] = '返回红包列表';
$LANG['validated_email'] = '只给通过邮件验证的用户发放红包';

/* 提示信息 */
$LANG['attradd_succed'] = '操作成功!';
$LANG['del_bonustype_succed'] = '删除红包类型成功!';
$LANG['js_languages']['type_name_empty'] = '请输入红包类型名称!';
$LANG['js_languages']['type_money_empty'] = '请输入红包类型价格!';
$LANG['js_languages']['order_money_empty'] = '请输入订单金额!';
$LANG['js_languages']['type_money_isnumber'] = '类型金额必须为数字格式!';
$LANG['js_languages']['order_money_isnumber'] = '订单金额必须为数字格式!';
$LANG['js_languages']['bonus_sn_empty'] = '请输入红包的序列号!';
$LANG['js_languages']['bonus_sn_number'] = '红包的序列号必须是数字!';
$LANG['send_count_error'] = '红包的发放数量必须是一个整数!';
$LANG['js_languages']['bonus_sum_empty'] = '请输入您要发放的红包数量!';
$LANG['js_languages']['bonus_sum_number'] = '红包的发放数量必须是一个整数!';
$LANG['js_languages']['bonus_type_empty'] = '请选择红包的类型金额!';
$LANG['js_languages']['user_rank_empty'] = '您没有指定会员等级!';
$LANG['js_languages']['user_name_empty'] = '您至少需要选择一个会员!';
$LANG['js_languages']['invalid_min_amount'] = '请输入订单下限（大于0的数字）';
$LANG['js_languages']['send_start_lt_end'] = '红包发放开始日期不能大于结束日期';
$LANG['js_languages']['use_start_lt_end'] = '红包使用开始日期不能大于结束日期';

$LANG['order_money_notic'] = '只要订单金额达到该数值，就会发放红包给用户';
$LANG['type_money_notic'] = '此类型的红包可以抵销的金额';
$LANG['send_startdate_notic'] = '只有当前时间介于起始日期和截止日期之间时，此类型的红包才可以发放';
$LANG['use_startdate_notic'] = '只有当前时间介于起始日期和截止日期之间时，此类型的红包才可以使用';
$LANG['type_name_exist'] = '此类型的名称已经存在!';
$LANG['type_money_error'] = '金额必须是数字并且不能小于 0 !';
$LANG['bonus_sn_notic'] = '提示:红包序列号由六位序列号种子加上四位随机数字组成';
$LANG['creat_bonus'] = '生成了 ';
$LANG['creat_bonus_num'] = ' 个红包序列号';
$LANG['bonus_sn_error'] = '红包序列号必须是数字!';
$LANG['send_user_notice'] = '给指定的用户发放红包时,请在此输入用户名, 多个用户之间请用逗号(,)分隔开<br />如:liry, wjz, zwj';

/* 红包信息字段 */
$LANG['bonus_id'] = '编号';
$LANG['bonus_type_id'] = '类型金额';
$LANG['send_bonus_count'] = '红包数量';
$LANG['start_bonus_sn'] = '起始序列号';
$LANG['bonus_sn'] = '红包序列号';
$LANG['user_id'] = '使用会员';
$LANG['used_time'] = '使用时间';
$LANG['order_id'] = '订单号';
$LANG['send_mail'] = '发邮件';
$LANG['emailed'] = '邮件通知';
// $LANG['mail_status'][BONUS_NOT_MAIL] = '未发';
// $LANG['mail_status'][BONUS_MAIL_FAIL] = '已发失败';
// $LANG['mail_status'][BONUS_MAIL_SUCCEED] = '已发成功';
$LANG['mail_status'][BONUS_NOT_MAIL] = '未发';
$LANG['mail_status'][BONUS_INSERT_MAILLIST_FAIL] = '插入邮件发送队列失败';
$LANG['mail_status'][BONUS_INSERT_MAILLIST_SUCCEED] = '插入邮件发送队列成功';
$LANG['mail_status'][BONUS_MAIL_FAIL] = '发送邮件通知失败';
$LANG['mail_status'][BONUS_MAIL_SUCCEED] = '发送邮件通知成功';

$LANG['sendtouser'] = '给指定用户发放红包';
$LANG['senduserrank'] = '按用户等级发放红包';
$LANG['userrank'] = '用户等级';
$LANG['select_rank'] = '选择会员等级...';
$LANG['keywords'] = '关键字：';
$LANG['userlist'] = '会员列表：';
$LANG['send_to_user'] = '给下列用户发放红包';
$LANG['search_users'] = '搜索会员';
$LANG['confirm_send_bonus'] = '确定发送红包';
$LANG['bonus_not_exist'] = '该红包不存在';
$LANG['success_send_mail'] = '已成功加入邮件列表';
$LANG['send_continue'] = '继续发放红包';

//end