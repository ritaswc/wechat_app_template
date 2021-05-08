<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/12 19:24
 */


namespace app\modules\admin\models;

use app\hejiang\cloud\Cloud;
use app\models\Admin;
use app\models\AdminRegister;
use app\models\Option;
use Hejiang\Sms\Exceptions\SmsException;
use Hejiang\Sms\Messages\TemplateMessage;
use Hejiang\Sms\Senders\AliyunSender;

class UserRegisterForm extends AdminModel
{
    public $status;
    public $id;
    private $sms_error;

    public function rules()
    {
        return [
            [['id', 'status'], 'required'],
            ['status', function ($attribute, $params) {
                if ($this->status != 1) {
                    return;
                }
                $hostInfo = Cloud::getHostInfo();
                $account_count = (Admin::find()->where(['is_delete' => 0])->count()) - 1;
                $account_max = $hostInfo['data']['host']['account_num'];
                $account_over_max = $account_max != -1 && $account_count >= $account_max;
                if ($account_over_max) {
                    $this->addError($attribute, "操作失败，子账户创建数量上限。");
                    return;
                }
            }],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $admin_register = AdminRegister::findOne([
            'id' => $this->id,
            'status' => 0,
            'is_delete' => 0,
        ]);
        if (!$admin_register) {
            return [
                'code' => 1,
                'msg' => '申请的内容不存在，请刷新页面查看。',
            ];
        }
        if ($this->status == 1) {
            $admin = new Admin();
            $admin->username = $admin_register->username;
            $admin->password = $admin_register->password;
            $admin->auth_key = \Yii::$app->security->generateRandomString();
            $admin->access_token = \Yii::$app->security->generateRandomString();
            $admin->addtime = time();
            $admin->is_delete = 0;
            $admin->app_max_count = 1;
            $admin->permission = '[]';
            $admin->remark = $admin_register->desc;
            $admin->expire_time = 0;
            $admin->mobile = $admin_register->mobile;
            if ($admin->save()) {
                $admin_register->status = 1;
                $admin_register->save();
                $this->sendResultSms($admin_register);
                return [
                    'code' => 0,
                    'msg' => '操作成功。',
                    'data' => [
                        'sms_error' => $this->sms_error,
                    ],
                ];
            } else {
                return $this->getErrorResponse($admin);
            }
        } else {
            $admin_register->status = 2;
            $admin_register->save();
            $this->sendResultSms($admin_register);
            return [
                'code' => 0,
                'msg' => '操作成功。',
                'data' => [
                    'sms_error' => $this->sms_error,
                ],
            ];
        }
    }

    /**
     * @param AdminRegister $admin_register
     * @return bool
     */
    private function sendResultSms($admin_register)
    {
        $ind_sms = Option::get('ind_sms', 0, 'admin');
        if (!$ind_sms) {
            $this->sms_error = '发送失败，短信尚未配置。';
            return false;
        }
        if (!$ind_sms['aliyun'] || !$ind_sms['aliyun']['access_key_id'] || !$ind_sms['aliyun']['access_key_secret'] || !$ind_sms['aliyun']['sign'] || !$ind_sms['aliyun']['register_result_tpl_id']) {
            $this->sms_error = '发送失败，短信尚未配置。';
            return false;
        }
        $sender = new AliyunSender($ind_sms['aliyun']['access_key_id'], $ind_sms['aliyun']['access_key_secret']);
        $message = new TemplateMessage([
            'sender' => $sender,
            'sign' => $ind_sms['aliyun']['sign'],
            'tplId' => $ind_sms['aliyun']['register_result_tpl_id'],
            'tplParams' => [
                'name' => $admin_register->username,
                'result' => $admin_register->status == 1 ? '已通过' : '未通过',
            ],
            'phoneNumber' => $admin_register->mobile,
        ]);
        try {
            $message->send();
            return true;
        } catch (SmsException $e) {
            $this->sms_error = '发送失败,' . $e->getMessage();
            return false;
        }
    }
}
