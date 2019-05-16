<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2018/12/12
 * Time: 14:04
 */

namespace Ecjia\App\Bonus\StoreDuplicateHandlers;

use Ecjia\App\Store\StoreDuplicate\StoreDuplicateAbstract;
use RC_DB;
use RC_Api;
use ecjia_admin;

/**
 * 复制店铺中的红包
 *
 * Class StoreBonusDuplicate
 * @package Ecjia\App\Bonus\StoreDuplicateHandlers
 */
class StoreBonusDuplicate extends StoreDuplicateAbstract
{

    /**
     * 代号标识
     * @var string
     */
    protected $code = 'store_bonus_duplicate';

    /**
     * 排序
     * @var int
     */
    protected $sort = 13;

    public function __construct($store_id, $source_store_id)
    {
        $this->name = __('店铺红包', 'bonus');

        parent::__construct($store_id, $source_store_id);
    }

    /**
     * 数据描述及输出显示内容
     */
    public function handlePrintData()
    {
        $count = $this->handleCount();
        $text = sprintf(__('店铺红包总共<span class="ecjiafc-red ecjiaf-fs3">%s</span>个', 'bonus'), $count);

        return <<<HTML
<span class="controls-info w300">{$text}</span>
HTML;
    }

    /**
     * 获取数据统计条数
     *
     * @return mixed
     */
    public function handleCountOld()
    {
        $bonus_type_list = RC_DB::table('bonus_type')->where('store_id', $this->source_store_id)->lists('type_id');
        return RC_DB::table('user_bonus')->whereIn('bonus_type_id', $bonus_type_list)->count();

    }

    /**
     * 获取数据统计条数
     *
     * @return int
     */
    public function handleCount()
    {
        $count = RC_DB::table('bonus_type')
            ->leftJoin('user_bonus', 'bonus_type.type_id', '=', 'user_bonus.bonus_type_id')
            ->where('bonus_type.store_id', $this->source_store_id)
            ->count();

        return $count;

    }


    /**
     * 执行复制操作
     *
     * @return mixed
     */
    public function handleDuplicate()
    {
        $item = $this->dependentCheck();
        //判断提示错误
        if (empty($item)){
            //标记处理完成
            $this->markDuplicateFinished();
            return TRUE;
        }

        return FALSE;

        /*$count = $this->handleCount();
        if (empty($count)) {
            return true;
        }

        $bonus_type_list = RC_DB::table('bonus_type')->where('store_id', $this->store_id)->lists('type_id');

        $res = RC_DB::table('user_bonus')->whereIn('bonus_type_id', $bonus_type_list)->delete();
        $result = RC_DB::table('bonus_type')->where('store_id', $this->store_id)->delete();

        if ($result || $res) {
            $this->handleAdminLog();
        }

        return $result;*/
    }

    /**
     * 返回操作日志编写
     *
     * @return mixed
     */
    public function handleAdminLog()
    {
        \Ecjia\App\Store\Helper::assign_adminlog_content();

        $store_info = RC_Api::api('store', 'store_info', array('store_id' => $this->store_id));

        $merchants_name = !empty($store_info) ? sprintf(__('店铺名是%s', 'bonus'), $store_info['merchants_name']) : sprintf(__('店铺ID是%s', 'bonus'), $this->store_id);

        ecjia_admin::admin_log($merchants_name, 'clean', 'store_bonus');
    }


}