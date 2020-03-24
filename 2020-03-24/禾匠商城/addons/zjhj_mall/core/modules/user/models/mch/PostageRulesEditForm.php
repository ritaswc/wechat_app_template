<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/3
 * Time: 14:37
 */

namespace app\modules\user\models\mch;

use app\models\PostageRules;
use app\modules\user\models\UserModel;

/**
 * @property PostageRules $postage_rules
 */
class PostageRulesEditForm extends UserModel
{
    public $postage_rules;

    public $name;
    public $express_id;
    public $express;
    public $detail;
    public $type;

    public function rules()
    {
        return [
            [['name', 'type',], 'required'],
            [['type'], 'integer'],
            [['express'], 'trim'],
            [['express_id',], 'default', 'value' => 0],
            [['detail',], 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->detail = json_encode($this->detailFormat($this->detail), JSON_UNESCAPED_UNICODE);
        $this->postage_rules->attributes = $this->attributes;
        if ($this->postage_rules->isNewRecord) {
            $this->postage_rules->is_delete = 0;
            $this->postage_rules->addtime = time();
        }
        if ($this->postage_rules->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($this->postage_rules);
        }
    }

    private function detailFormat($detail)
    {
        if (!is_array($detail)) {
            return [];
        }
        $new_detail = [];
        foreach ($detail as $item) {
            $new_item = [
                'frist' => isset($item['frist']) ? intval($item['frist']) : 0,
                'frist_price' => isset($item['frist_price']) ? intval($item['frist_price']) : 0,
                'second' => isset($item['second']) ? intval($item['second']) : 0,
                'second_price' => isset($item['second_price']) ? intval($item['second_price']) : 0,
                'province_list' => [],
            ];
            foreach ($item['province_list'] as $province) {
                $new_item['province_list'][] = [
                    'id' => isset($province['id']) ? $province['id'] : 0,
                    'name' => isset($province['name']) ? $province['name'] : '',
                ];
            }
            $new_detail[] = $new_item;
        }
        return $new_detail;
    }
}
