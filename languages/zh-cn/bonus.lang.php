<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
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