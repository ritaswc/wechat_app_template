<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/7
 * Time: 18:49
 */

namespace app\modules\mch\models\group;

use app\models\Option;
use app\modules\mch\models\MchModel;

class AdForm extends MchModel
{
    public $store_id;
    public $pic_list;

    public function rules()
    {
        return [
            [['pic_list'], 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        Option::set('pt_ad', $this->pic_list, $this->store_id);
        return [
            'code' => 0,
            'msg' => '保存成功',
        ];
    }

    public function getPicList()
    {
        $data = Option::get('pt_ad', $this->store_id);
        return $data;
    }
}
