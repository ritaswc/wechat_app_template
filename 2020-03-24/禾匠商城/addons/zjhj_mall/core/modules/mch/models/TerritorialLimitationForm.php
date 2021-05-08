<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/4/25
 * Time: 11:39
 */

namespace app\modules\mch\models;

use app\models\TerritorialLimitation;

class TerritorialLimitationForm extends MchModel
{

    public $store_id;
    public $detail;
    public $is_enable;
    public $is_delete;
    public $territorial_limitation;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'addtime', 'is_enable', 'is_delete'], 'integer'],
            [['detail'], 'string'],
        ];
    }
    public function save()
    {
        $this->territorial_limitation->store_id = $this->store_id;
        $this->territorial_limitation->addtime = time();
        $this->territorial_limitation->is_enable = $this->is_enable;
        $this->territorial_limitation->is_delete = 0;
        $this->territorial_limitation->detail = \Yii::$app->serializer->encode($this->detail);
        if ($this->territorial_limitation->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        }
    }
}
