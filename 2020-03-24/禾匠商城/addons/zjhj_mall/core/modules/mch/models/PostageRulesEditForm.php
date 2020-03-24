<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/3
 * Time: 14:37
 */

namespace app\modules\mch\models;

use app\models\PostageRules;

/**
 * @property PostageRules $postage_rules
 */
class PostageRulesEditForm extends MchModel
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
            [['name','type',], 'required'],
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
        try{
            $detail = $this->detailFormat($this->detail);
        }catch(\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }
        $this->detail = \Yii::$app->serializer->encode($detail);
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
        //\Yii::$app->response->redirect()->send();
    }

    private function detailFormat($detail)
    {
        if (!is_array($detail)) {
            return [];
        }
        $new_detail = [];
        foreach ($detail as $item) {
            if(isset($item['frist'])) {
                if(is_numeric($item['frist'])) {
                    $frist = $item['frist'];
                } else {
                    throw new \Exception('首件/首重必须是数字且不小于0');
                }
            } else {
                $frist = 0;
            }
            if(isset($item['frist_price'])) {
                if(is_numeric($item['frist_price']) && $item['frist_price'] >= 0) {
                    $frist_price = $item['frist_price'];
                } else {
                    throw new \Exception('运费必须是数字且不小于0');
                }
            } else {
                $frist_price = 0;
            }
            if(isset($item['second'])) {
                if(is_numeric($item['second'])) {
                    $second = $item['second'];
                } else {
                    throw new \Exception('续件/续重必须是数字且不小于0');
                }
            } else {
                $second = 0;
            }
            if(isset($item['second_price'])) {
                if(is_numeric($item['second_price']) && $item['second_price'] >= 0) {
                    $second_price = $item['second_price'];
                } else {
                    throw new \Exception('运费必须是数字且不小于0');
                }
            } else {
                $second_price = 0;
            }
            $new_item = [
                'frist' => $frist,
                'frist_price' => $frist_price,
                'second' => $second,
                'second_price' => $second_price,
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
