<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/1
 * Time: 10:45
 */

namespace app\modules\mch\models\mch;

use Alipay\AlipayRequestFactory;
use app\models\alipay\MpConfig;
use app\models\alipay\TplMsgForm;
use app\models\common\CommonFormId;
use app\models\FormId;
use app\models\Mch;
use app\models\Option;
use app\models\User;
use app\modules\mch\models\MchModel;
use app\models\common\admin\log\CommonActionLog;

class MchEditForm extends MchModel
{
    /** @var Mch $model */
    public $model;
    public $realname;
    public $tel;
    public $name;
    public $province_id;
    public $city_id;
    public $district_id;
    public $address;
    public $mch_common_cat_id;
    public $service_tel;
    public $logo;
    public $header_bg;
    public $transfer_rate;
    public $sort;
    public $wechat_name;

    public $review_status;
    public $review_result;
    public $user_id;

    public function rules()
    {
        $rules = [
            [['realname', 'tel', 'wechat_name', 'name', 'address', 'service_tel', 'logo', 'header_bg', 'review_result'], 'trim'],
            [['realname', 'tel', 'name', 'province_id', 'city_id', 'district_id', 'address', 'mch_common_cat_id', 'service_tel'], 'required'],
            [['realname', 'tel', 'name', 'province_id', 'city_id', 'district_id', 'address', 'mch_common_cat_id', 'service_tel'], 'string','max'=>250],
            [['user_id'], 'integer'],
            [['review_status'], 'in', 'range' => [1, 2]],
            [['review_result'], 'string'],
            [['transfer_rate'], 'integer', 'min' => 0, 'max' => 1000],
            [['sort'], 'integer', 'min' => 0, 'max' => 10000000],
        ];

        if ($this->model->review_status == 0) {
            $rules[] = [['review_status'], 'required'];
        }
        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'realname' => '联系人',
            'tel' => '联系电话',
            'name' => '店铺名称',
            'province_id' => '所在地区',
            'city_id' => '所在地区',
            'district_id' => '所在地区',
            'address' => '详细地址',
            'mch_common_cat_id' => '所售类目',
            'service_tel' => '客服电话',
            'transfer_rate' => '手续费',
            'review_status' => '审核状态',
            'sort' => '排序',
            'user_id' => '小程序用户',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->model->realname = $this->realname;
        $this->model->tel = $this->tel;
        $this->model->wechat_name = $this->wechat_name;
        $this->model->name = $this->name;
        $this->model->province_id = $this->province_id;
        $this->model->city_id = $this->city_id;
        $this->model->district_id = $this->district_id;
        $this->model->address = $this->address;
        $this->model->mch_common_cat_id = $this->mch_common_cat_id;
        $this->model->service_tel = $this->service_tel;
        $this->model->logo = $this->logo;
        $this->model->header_bg = $this->header_bg;
        $this->model->transfer_rate = $this->transfer_rate;
        $this->model->sort = $this->sort;

        if($this->user_id){
            $result = '商户列表id'.$this->model->id.'、小程序用户id'.$this->model->user_id.'、修改为、小程序用户id'.$this->user_id;
            CommonActionLog::storeMchLog('商户小程序用户id', false, 0, $result,1);
            $this->model->user_id = $this->user_id;   
        }
        if ($this->model->review_status == 0) {
            $this->model->review_status = $this->review_status;
            $this->model->review_result = $this->review_result;
            $this->model->review_time = time();
            if ($this->review_status == 1) {
                $this->model->is_open = 1;
            }
        }
        if ($this->model->save()) {
            $this->sendTplMsg();
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }

    private function sendTplMsg()
    {
        if (!$this->review_status) {
            return;
        }
        $user = User::findOne($this->model->user_id);
        if (!$user) {
            \Yii::warning('模板消息发送失败：未找到对应用户');
            return;
        }

        // 用户来源
        $is_alipay = $user->platform == 1;

        if ($is_alipay) {
            $tpl = [
                'apply' => TplMsgForm::get($this->model->store_id)->mch_tpl_1
            ];
        } else {
            $tpl = Option::get('mch_tpl_msg', $this->model->store_id);
        }
        if (!$tpl || !$tpl['apply']) {
            return;
        }

        $user_form_id = CommonFormId::getFormId($user->wechat_open_id);


        if (empty($user_form_id)) {
            \Yii::warning('模板消息发送失败：未找到FormID');
            return;
        }
        $wechat = $this->getWechat();
        $access_token = $wechat->getAccessToken();
        $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
        $data = [
            'touser' => $user->wechat_open_id,
            'template_id' => $tpl['apply'],
            'page' => 'mch/apply/apply',
            'form_id' => $user_form_id->form_id,
            'data' => [
                'keyword1' => [
                    'color' => '',
                    'value' => '商户入驻申请',
                ],
                'keyword2' => [
                    'color' => $this->model->review_status == 1 ? '#3fc24c' : '#ff4544',
                    'value' => '您的申请' . ($this->model->review_status == 1 ? '已通过' : '未通过') . ($this->model->review_result ? ':' . $this->model->review_result : ''),
                ],
            ],
        ];


        if ($is_alipay) {
            $config = MpConfig::get($this->model->store_id);
            $aop = $config->getClient();
            $request = AlipayRequestFactory::create('alipay.open.app.mini.templatemessage.send', [
                'biz_content' => [
                    'to_user_id' => $data['touser'],
                    'form_id' => $data['form_id'],
                    'user_template_id' => $data['template_id'],
                    'page' => $data['page'],
                    'data' => $data['data'],
                ],
            ]);
            $response = $aop->execute($request);

            if ($response->isSuccess() === false) {
                \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($response->getError(), JSON_UNESCAPED_UNICODE));
            }
        } else {
            $wechat->curl->post($api, \Yii::$app->serializer->encode($data));
            if (!$wechat->curl->response) {
                \Yii::warning('模板消息发送失败：' . $wechat->curl->error_message);
                return;
            }

            if (!empty($user_form_id)) {
                $user_form_id->send_count = $user_form_id->send_count + 1;
                $user_form_id->save();
            }

            $res = json_decode($wechat->curl->response, true);
            if ($res && $res['errcode'] == 0) {
                return;
            }
            if ($res && ($res['errcode'] == 41028 || $res['errcode'] == 41029)) {
                \Yii::warning('模板消息发送失败：' . $res['errmsg']);
                return;
            }
            if ($res && $res['errcode']) {
                \Yii::warning('模板消息发送失败：' . $res['errmsg']);
            }
        }
    }
}
