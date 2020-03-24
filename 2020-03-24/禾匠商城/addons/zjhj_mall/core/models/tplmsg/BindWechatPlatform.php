<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/6/29 15:35
 */


namespace app\models\tplmsg;

use app\models\Model;
use app\models\Option;

class BindWechatPlatform extends Model
{
    public $store_id;
    const KEY = 'BIND_WECHAT_PLATFORM';

    public $app_id;
    public $app_secret;
    public $app_name;
    public $tpl_id_list;
    public $admin_open_id;

    public function rules()
    {
        return [
            [['app_id', 'app_secret', 'admin_open_id', 'app_name'], 'trim'],
            [['app_id', 'app_secret'], 'required'],
            [['tpl_id_list',], 'each', 'rule' => ['trim'],],
            [['tpl_id_list',], 'each', 'rule' => ['string', 'max' => 128],],
        ];
    }

    public function attributeLabels()
    {
        return [
            'tpl_id_list' => '模板消息ID',
        ];
    }

    public function search()
    {
        $default = [
            'app_id' => '',
            'app_secret' => '',
            'app_name' => '',
            'app_qrcode' => '',
            'tpl_id_list' => [],
            'admin_open_id' => '',
            'app_logo' => '',
        ];
        $data = Option::get(self::KEY, $this->store_id, '', $default);
        $data['bind_url'] = $this->getAdminUrl('bind');

        return $data;
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $data = [
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'app_name' => $this->app_name,
            'tpl_id_list' => $this->tpl_id_list,
            'admin_open_id' => $this->admin_open_id,
        ];
        Option::set(self::KEY, $data, $this->store_id);
        return [
            'code' => 0,
            'msg' => '保存成功。',
            'data' => $this->attributes,
        ];
    }

    public function addTpl($tpl)
    {
        $data = $this->search();
        if (!$data['tpl_list'] || !is_array($data['tpl_list'])) {
            $data['tpl_list'] = [];
            $tpl['id'] = 1;
        } else {
            foreach ($data['tpl_list'] as $tpl_item) {
                $tpl['id'] = $tpl_item['id'] + 1;
            }
        }
        $data['tpl_list'][] = $tpl;
        Option::set(self::KEY, $data, $this->store_id);
        return $tpl;
    }

    public function deleteTpl($id)
    {
        $data = $this->search();
        if (!$data['tpl_list'] || !is_array($data['tpl_list'])) {
            return false;
        }
        foreach ($data['tpl_list'] as $i => $tpl_item) {
            if ($id == $tpl_item['id']) {
                unset($data['tpl_list'][$i]);
                Option::set(self::KEY, $data, $this->store_id);
                return true;
            }
        }
        return false;
    }

    public function getTplList()
    {
        $data = $this->search();
        if (!$data['tpl_list'] || !is_array($data['tpl_list'])) {
            return [];
        }
        return $data['tpl_list'];
    }
}
