<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/6
 * Time: 11:34
 */

namespace app\modules\mch\models;

/**
 * @property \app\models\Sender $sender;
 */
class SenderForm extends MchModel
{
    public $sender;
    public $store_id;
    public $delivery_id;

    public $company;
    public $name;
    public $tel;
    public $mobile;
    public $post_code;
    public $province;
    public $city;
    public $exp_area;
    public $address;

    public function rules()
    {
        return [
            [['company', 'name', 'tel', 'mobile', 'post_code', 'province', 'city', 'exp_area', 'address'], 'string', 'max' => 255],
            [['name', 'province', 'city', 'exp_area', 'address'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'company'=>'发件公司',
            'name'=>'发件人名称',
            'tel'=>'电话',
            'mobile'=>'手机',
            'province'=>'省份',
            'city'=>'城市',
            'exp_area'=>'地区',
            'address'=>'详细地址',
        ];
    }
    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $sender = $this->sender;
        if ($sender->isNewRecord) {
            $sender->is_delete = 0;
            $sender->addtime = time();
            $sender->store_id = $this->store_id;
            $sender->delivery_id = 0;
        }
        if (!$this->mobile && !$this->tel) {
            return [
                'code'=>1,
                'msg'=>'电话与手机，必填一个'
            ];
        }
        $sender->attributes = $this->attributes;
        if ($sender->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return [
                'code'=>0,
                'msg'=>'网络异常'
            ];
        }
    }
}
