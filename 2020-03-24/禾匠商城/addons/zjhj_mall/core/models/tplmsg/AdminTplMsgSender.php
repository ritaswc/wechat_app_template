<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/16 0:35
 */


namespace app\models\tplmsg;

class AdminTplMsgSender
{
    protected static $sender;
    protected static $admin_open_id;

    protected static function getSender($store_id)
    {
        if (self::$sender) {
            return self::$sender;
        }
        self::$sender = new TplmsgSender($store_id);
        return self::$sender;
    }

    protected static function getAdminOpenId($store_id)
    {
        if (self::$admin_open_id) {
            return self::$admin_open_id;
        }
        self::$admin_open_id =
        $form = new BindWechatPlatform();
        $form->store_id = $store_id;
        $data = $form->search();
        if ($data['admin_open_id']) {
            self::$admin_open_id = $data['admin_open_id'];
        } else {
            self::$admin_open_id = 'empty_admin_open_id';
        }
        return self::$admin_open_id;
    }

    /**
     * 向卖家（管理员、入驻商、操作员）发送新订单提醒消息
     * @param $store_id
     * @param array $data [<br>
     *     'time' => '提交时间',<br>
     *     'type' => '订单类型',<br>
     *     'user' => '客户信息',<br>
     *     'goods' => '商品信息',<br>
     * ]
     * @return bool
     */
    public static function sendNewOrder($store_id, $data)
    {
        $sender = self::getSender($store_id);
        $admin_open_id = self::getAdminOpenId($store_id);
        return $sender->sendNewOrder($admin_open_id, $data);
    }

    /**
     * 发送分销商入驻申请通知
     * @param $store_id
     * @param array $data [<br>
     *     'time' => '时间',<br>
     *     'content' => '详细信息',<br>
     * ]
     * @return bool
     */
    public static function sendFxsApply($store_id, $data)
    {
        $sender = self::getSender($store_id);
        $admin_open_id = self::getAdminOpenId($store_id);
        return $sender->sendFxsApply($admin_open_id, $data);
    }

    /**
     * 发送分销商提现通知
     * @param $store_id
     * @param array $data [<br>
     *     'time' => '时间',<br>
     *     'money' => '金额',<br>
     *     'user' => '用户',<br>
     * ]
     * @return bool
     */
    public static function sendFxsCash($store_id, $data)
    {
        $sender = self::getSender($store_id);
        $admin_open_id = self::getAdminOpenId($store_id);
        return $sender->sendFxsCash($admin_open_id, $data);
    }

    /**
     * 发送多商户入驻申请通知
     * @param $store_id
     * @param array $data [<br>
     *     'time' => '时间',<br>
     *     'content' => '详细信息',<br>
     * ]
     * @return bool
     */
    public static function sendMchApply($store_id, $data)
    {
        $sender = self::getSender($store_id);
        $admin_open_id = self::getAdminOpenId($store_id);
        return $sender->sendMchApply($admin_open_id, $data);
    }

    /**
     * 发送入驻商商品上架申请通知
     * @param $store_id
     * @param array $data [<br>
     *     'goods' => '商品',<br>
     * ]
     * @return bool
     */
    public static function sendMchUploadGoods($store_id, $data)
    {
        $sender = self::getSender($store_id);
        $admin_open_id = self::getAdminOpenId($store_id);
        return $sender->sendMchUploadGoods($admin_open_id, $data);
    }
}
