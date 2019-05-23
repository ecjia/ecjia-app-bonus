<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2018/12/12
 * Time: 14:04
 */

namespace Ecjia\App\Bonus\StoreDuplicateHandlers;

use Ecjia\App\Store\StoreDuplicate\StoreDuplicateAbstract;
use ecjia_admin;
use ecjia_error;
use RC_Api;
use RC_DB;
use Royalcms\Component\Database\QueryException;

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

    public function __construct($store_id, $source_store_id, $sort = 31)
    {
        $this->name = __('店铺红包', 'bonus');
        parent::__construct($store_id, $source_store_id, $sort);
    }

    /**
     * 获取源店铺数据操作对象
     */
    public function getSourceStoreDataHandler()
    {
        return RC_DB::table('bonus_type')
            ->rightJoin('user_bonus', 'bonus_type.type_id', '=', 'user_bonus.bonus_type_id')
            ->where('bonus_type.store_id', $this->source_store_id);
    }

    /**
     * 数据描述及输出显示内容
     */
    public function handlePrintData()
    {
        $text = sprintf(__('店铺红包总共<span class="ecjiafc-red ecjiaf-fs3">%s</span>个', 'bonus'), $this->handleCount());
        return <<<HTML
<span class="controls-info w300">{$text}</span>
HTML;
    }

    /**
     * 统计数据条数并获取
     *
     * @return mixed
     */
    public function handleCount()
    {
        //如果已经统计过，直接返回统计过的条数
        if ($this->count) {
            return $this->count;
        }

        // 统计数据条数
        try {
            $this->count = $this->getSourceStoreDataHandler()->count();
        } catch (QueryException $e) {
            ecjia_log_warning($e->getMessage());
        }
        return $this->count;
    }

    /**
     * 执行复制操作
     *
     * @return mixed
     */
    public function handleDuplicate()
    {
        //检测当前对象是否已复制完成
        if ($this->isCheckFinished()) {
            return true;
        }

        //如果当前对象复制前仍存在依赖，则需要先复制依赖对象才能继续复制
        if (!empty($this->dependents)) { //如果设有依赖对象
            //检测依赖
            $items = $this->dependentCheck();
            if (!empty($items)) {
                return new ecjia_error('handle_duplicate_error', __('复制依赖检测失败！', 'store'), $items);
            }
        }

        //执行具体任务
        $result = $this->startDuplicateProcedure();
        if (is_ecjia_error($result)) {
            return $result;
        }

        //标记处理完成
        $this->markDuplicateFinished();

        //记录日志
        $this->handleAdminLog();

        return true;
    }

    /**
     * 店铺复制操作的具体过程
     */
    protected function startDuplicateProcedure()
    {
        $replacement_bonus_type = [];
        try {
            RC_DB::table('bonus_type')->where('store_id', $this->source_store_id)->chunk(50, function ($items) use (& $replacement_bonus_type) {
                //构造可用于复制的数据
                foreach ($items as &$item) {
                    $type_id = $item['type_id'];
                    unset($item['type_id']);

                    //将源店铺ID设为新店铺的ID
                    $item['store_id'] = $this->store_id;

                    //插入数据到新店铺
                    $new_type_id = RC_DB::table('bonus_type')->insertGetId($item);

                    $replacement_bonus_type[$type_id] = $new_type_id;
                }
            });
            $this->setReplacementData($this->getCode(), $replacement_bonus_type);
            return true;
        } catch (QueryException $e) {
            ecjia_log_warning($e->getMessage());
            return new ecjia_error('duplicate_data_error', $e->getMessage());
        }
    }

    /**
     * 返回操作日志编写
     *
     * @return mixed
     */
    public function handleAdminLog()
    {
        \Ecjia\App\Store\Helper::assign_adminlog_content();

        static $store_merchant_name, $source_store_merchant_name;

        if (empty($store_merchant_name)) {
            $store_info = RC_Api::api('store', 'store_info', ['store_id' => $this->store_id]);
            $store_merchant_name = array_get(empty($store_info) ? [] : $store_info, 'merchants_name');
        }

        if (empty($source_store_merchant_name)) {
            $source_store_info = RC_Api::api('store', 'store_info', ['store_id' => $this->source_store_id]);
            $source_store_merchant_name = array_get(empty($source_store_info) ? [] : $source_store_info, 'merchants_name');
        }

        $content = sprintf(__('录入：将【%s】店铺所有%s复制到【%s】店铺中', 'goods'), $source_store_merchant_name, $this->name, $store_merchant_name);
        ecjia_admin::admin_log($content, 'duplicate', 'store_goods');
    }
}