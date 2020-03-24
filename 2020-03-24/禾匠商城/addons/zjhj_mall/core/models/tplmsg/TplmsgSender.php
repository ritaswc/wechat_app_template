<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/4 10:31
 */


namespace app\models\tplmsg;

use app\models\Option;
use app\models\User;
use luweiss\wechat\Wechat;
use yii\helpers\VarDumper;

class TplmsgSender
{
    private $store_id;
    private $wechat;
    private $config;
    private $sender;

    private $error_message;

    public function __construct($store_id)
    {
        $this->store_id = $store_id;
    }

    /**
     * 向卖家（管理员、入驻商、操作员）发送新订单提醒消息
     * @param integer|string $user 发送对象，user_id或openid
     * @param array $data [<br>
     *     'time' => '提交时间',<br>
     *     'type' => '订单类型',<br>
     *     'user' => '客户信息',<br>
     *     'goods' => '商品信息',<br>
     * ]
     * @return bool
     */
    public function sendNewOrder($user, $data = [])
    {
        try {
            $sender = $this->getSender();
            if (is_integer($user)) {
                $user = $this->getUser($user);
                $touser = $user->wechat_platform_open_id;
            } else {
                $touser = $user;
            }
            $config = $this->getConfig();
            if (empty($config['tpl_id_list']['new_order'])) {
                throw new TplmsgException('模板消息发送失败，模板ID尚未配置。');
            }
            if (empty($touser)) {
                throw new TplmsgException('模板消息发送失败，发送对象未设置。');
            }
            $res = $sender->send([
                'touser' => $touser,
                'template_id' => $config['tpl_id_list']['new_order'],
                'data' => [
                    'first' => [
                        'value' => '您有一笔新订单，请及时处理。',
                        'color' => '#666666',
                    ],
                    'tradeDateTime' => [
                        'value' => $data['time'],
                        'color' => '#000000',
                    ],
                    'orderType' => [
                        'value' => $data['type'] ? $data['type'] : '',
                        'color' => '#000000',
                    ],
                    'customerInfo' => [
                        'value' => $data['user'],
                        'color' => '#000000',
                    ],
                    'orderItemName' => [
                        'value' => '商品信息',
                        'color' => '#000000',
                    ],
                    'orderItemData' => [
                        'value' => $data['goods'],
                        'color' => '#000000',
                    ],
                    'remark' => [
                        'value' => '',
                        'color' => '#000000',
                    ],
                ],
            ]);
            return $res;
        } catch (\Exception $e) {
            $this->error_message = $e->getMessage();
            \Yii::warning($this->error_message);
            return false;
        }
    }

    /**
     * 发送分销商入驻申请通知
     * @param integer|string $user 发送对象，user_id或openid
     * @param array $data [<br>
     *     'time' => '时间',<br>
     *     'content' => '详细信息',<br>
     * ]
     * @return bool
     */
    public function sendFxsApply($user, $data = [])
    {
        try {
            $sender = $this->getSender();
            if (is_integer($user)) {
                $user = $this->getUser($user);
                $touser = $user->wechat_platform_open_id;
            } else {
                $touser = $user;
            }
            $config = $this->getConfig();
            if (empty($config['tpl_id_list']['fxs_apply'])) {
                throw new TplmsgException('模板消息发送失败，模板ID尚未配置。');
            }
            if (empty($touser)) {
                throw new TplmsgException('模板消息发送失败，发送对象未设置。');
            }
            $res = $sender->send([
                'touser' => $touser,
                'template_id' => $config['tpl_id_list']['fxs_apply'],
                'data' => [
                    'first' => [
                        'value' => '有新的用户申请成为分销商，请及时处理。',
                        'color' => '#666666',
                    ],
                    'keyword1' => [
                        'value' => $data['time'],
                        'color' => '#000000',
                    ],
                    'keyword2' => [
                        'value' => $data['content'],
                        'color' => '#000000',
                    ],
                    'remark' => [
                        'value' => '',
                        'color' => '#666666',
                    ],
                ],
            ]);
            return $res;
        } catch (\Exception $e) {
            $this->error_message = $e->getMessage();
            \Yii::warning($this->error_message);
            return false;
        }
    }

    /**
     * 发送分销商提现通知
     * @param integer|string $user 发送对象，user_id或openid
     * @param array $data [<br>
     *     'time' => '时间',<br>
     *     'money' => '金额',<br>
     *     'user' => '用户',<br>
     * ]
     * @return bool
     */
    public function sendFxsCash($user, $data = [])
    {
        try {
            $sender = $this->getSender();
            if (is_integer($user)) {
                $user = $this->getUser($user);
                $touser = $user->wechat_platform_open_id;
            } else {
                $touser = $user;
            }
            $config = $this->getConfig();
            if (empty($config['tpl_id_list']['fxs_cash'])) {
                throw new TplmsgException('模板消息发送失败，模板ID尚未配置。');
            }
            if (empty($touser)) {
                throw new TplmsgException('模板消息发送失败，发送对象未设置。');
            }
            $res = $sender->send([
                'touser' => $touser,
                'template_id' => $config['tpl_id_list']['fxs_cash'],
                'data' => [
                    'first' => [
                        'value' => '有分销商申请提现，请及时处理。',
                        'color' => '#666666',
                    ],
                    'keyword1' => [
                        'value' => $data['time'],
                        'color' => '#000000',
                    ],
                    'keyword2' => [
                        'value' => $data['money'],
                        'color' => '#000000',
                    ],
                    'remark' => [
                        'value' => "申请用户：{$data['user']}",
                        'color' => '#000000',
                    ],
                ],
            ]);
            return $res;
        } catch (\Exception $e) {
            $this->error_message = $e->getMessage();
            \Yii::warning($this->error_message);
            return false;
        }
    }

    /**
     * 发送多商户入驻申请通知
     * @param integer|string $user 发送对象，user_id或openid
     * @param array $data [<br>
     *     'time' => '时间',<br>
     *     'content' => '详细信息',<br>
     * ]
     * @return bool
     */
    public function sendMchApply($user, $data = [])
    {
        try {
            $sender = $this->getSender();
            if (is_integer($user)) {
                $user = $this->getUser($user);
                $touser = $user->wechat_platform_open_id;
            } else {
                $touser = $user;
            }
            $config = $this->getConfig();
            if (empty($config['tpl_id_list']['mch_apply'])) {
                throw new TplmsgException('模板消息发送失败，模板ID尚未配置。');
            }
            if (empty($touser)) {
                throw new TplmsgException('模板消息发送失败，发送对象未设置。');
            }
            $res = $sender->send([
                'touser' => $touser,
                'template_id' => $config['tpl_id_list']['mch_apply'],
                'data' => [
                    'first' => [
                        'value' => '有新的用户申请成为入驻商，请及时处理。',
                        'color' => '#666666',
                    ],
                    'keyword1' => [
                        'value' => $data['time'],
                        'color' => '#000000',
                    ],
                    'keyword2' => [
                        'value' => $data['content'],
                        'color' => '#000000',
                    ],
                    'remark' => [
                        'value' => '',
                        'color' => '#666666',
                    ],
                ],
            ]);
            return $res;
        } catch (\Exception $e) {
            $this->error_message = $e->getMessage();
            \Yii::warning($this->error_message);
            return false;
        }
    }

    /**
     * 发送入驻商商品上架申请通知
     * @param integer|string $user 发送对象，user_id或openid
     * @param array $data [<br>
     *     'goods' => '商品',<br>
     * ]
     * @return bool
     */
    public function sendMchUploadGoods($user, $data = [])
    {
        try {
            $sender = $this->getSender();
            if (is_integer($user)) {
                $user = $this->getUser($user);
                $touser = $user->wechat_platform_open_id;
            } else {
                $touser = $user;
            }
            $config = $this->getConfig();
            if (empty($config['tpl_id_list']['mch_upload_goods'])) {
                throw new TplmsgException('模板消息发送失败，模板ID尚未配置。');
            }
            if (empty($touser)) {
                throw new TplmsgException('模板消息发送失败，发送对象未设置。');
            }
            $res = $sender->send([
                'touser' => $touser,
                'template_id' => $config['tpl_id_list']['mch_upload_goods'],
                'data' => [
                    'first' => [
                        'value' => '入驻商有新的商品申请上架，请及时处理。',
                        'color' => '#666666',
                    ],
                    'keyword1' => [
                        //申请业务
                        'value' => '商品上架',
                        'color' => '#000000',
                    ],
                    'keyword2' => [
                        //申请时间
                        'value' => date('Y-m-d H:i'),
                        'color' => '#000000',
                    ],
                    'remark' => [
                        'value' => "商品信息：{$data['goods']}",
                        'color' => '#000000',
                    ],
                ],
            ]);
            return $res;
        } catch (\Exception $e) {
            $this->error_message = $e->getMessage();
            \Yii::warning($this->error_message);
            return false;
        }
    }

    /**
     * 发送入驻商商品上架申请结果通知
     * @param integer|string $user 发送对象，user_id或openid
     * @param array $data [<br>
     *     'goods' => '商品',<br>
     *     'result' => '审核结果',<br>
     * ]
     * @return bool
     */
    public function sendMchUploadGoodsResult($user, $data = [])
    {
        try {
            $sender = $this->getSender();
            if (is_integer($user)) {
                $user = $this->getUser($user);
                $touser = $user->wechat_platform_open_id;
            } else {
                $touser = $user;
            }
            $config = $this->getConfig();
            if (empty($config['tpl_id_list']['mch_upload_goods_result'])) {
                throw new TplmsgException('模板消息发送失败，模板ID尚未配置。');
            }
            if (empty($touser)) {
                throw new TplmsgException('模板消息发送失败，发送对象未设置。');
            }
            $res = $sender->send([
                'touser' => $touser,
                'template_id' => $config['tpl_id_list']['mch_upload_goods_result'],
                'data' => [
                    'first' => [
                        'value' => "您申请的商品上架{$data['result']}",
                        'color' => '#000000',
                    ],
                    'keyword1' => [
                        //申请业务
                        'value' => '商品上架',
                        'color' => '#000000',
                    ],
                    'keyword2' => [
                        //申请时间
                        'value' => '-',
                        'color' => '#000000',
                    ],
                    'remark' => [
                        'value' => "商品信息：{$data['goods']}",
                        'color' => '#000000',
                    ],
                ],
            ]);
            return $res;
        } catch (\Exception $e) {
            $this->error_message = $e->getMessage();
            \Yii::warning($this->error_message);
            return false;
        }
    }

    public function send($data = [])
    {
        try {
            $sender = $this->getSender();
            $res = $sender->send($data);
            return $res;
        } catch (\Exception $e) {
            $this->error_message = $e->getMessage();
            \Yii::warning($this->error_message);
            return false;
        }
    }

    private function getWechat()
    {
        if ($this->wechat) {
            return $this->wechat;
        }
        $config = $this->getConfig();
        $this->wechat = new Wechat([
            'appId' => $config['app_id'],
            'appSecret' => $config['app_secret'],
        ]);
        return $this->wechat;
    }

    private function getConfig()
    {
        if ($this->config) {
            return $this->config;
        }
        $this->config = Option::get(BindWechatPlatform::KEY, $this->store_id);
        if (!$this->config) {
            throw new TplmsgException('系统尚未配置公众号信息。');
        }
        return $this->config;
    }

    /**
     * @param $user_id
     * @return User
     * @throws TplmsgException
     */
    private function getUser($user_id)
    {
        $user = User::findOne(['id' => $user_id]);
        if (!$user) {
            throw new TplmsgException("用户不存在，id={$user_id}");
        }
        if (!$user->wechat_platform_open_id) {
            throw new TplmsgException("用户尚未关联公众号，id={$user_id}");
        }
        return $user;
    }

    /**
     * @return WechatTemplateMessageSender
     */
    private function getSender()
    {
        if ($this->sender) {
            return $this->sender;
        }
        $this->sender = new WechatTemplateMessageSender($this->getWechat());
        return $this->sender;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }
}
