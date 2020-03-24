<?php

namespace app\models\alipay;

use app\models\TemplateMsg;
use app\modules\mch\models\MchModel;

class TplMsgForm extends MchModel
{
    public $store_id;

    public $mch_tpl_2;
    public $mch_tpl_1;
    public $pt_fail_notice;
    public $pt_success_notice;
    public $apply_tpl;
    public $cash_fail_tpl;
    public $cash_success_tpl;
    public $refund_tpl;
    public $yy_refund_notice;
    public $send_tpl;
    public $pay_tpl;
    public $yy_success_notice;
    public $revoke_tpl;
    public $tpl_msg_id;
    public $activity_success_tpl;
    public $activity_refund_tpl;

    public function getTplNames()
    {
        $class = new \ReflectionClass($this);
        $props = array_filter($class->getProperties(), function (\ReflectionProperty $p) {
            return $p->class == __CLASS__;
        });
        $props = array_map(function (\ReflectionProperty $v) {
            return $v->getName();
        }, $props);
        $props = array_diff($props, ['store_id']);
        return $props;
    }

    public function rules()
    {
        return [
            [$this->getTplNames(), 'trim'],
        ];
    }

    /**
     * 获取此实例
     *
     * @param int|string $store_id
     * @return static
     */
    public static function get($store_id)
    {
        $instance = new static();
        $instance->store_id = $store_id;

        $tpls = TemplateMsg::findAll(
            ['store_id' => $store_id]
        );

        foreach ($tpls as $k => $tpl) {
            $key = $tpl->tpl_name;
            $value = $tpl->tpl_id;
            if (property_exists($instance, $key)) {
                $instance->$key = $value;
            }
        }
        return $instance;
    }

    public function save()
    {
        $trans = \Yii::$app->db->beginTransaction();
        try {
            foreach ($this->attributes as $key => $value) {
                $tpl = TemplateMsg::findOne(
                    ['store_id' => $this->store_id, 'tpl_name' => $key]
                );
                if ($tpl == null) {
                    $tpl = new TemplateMsg();
                }

                $tpl->tpl_id = $value;
                $tpl->tpl_name = $key;
                $tpl->store_id = $this->store_id;

                $tpl->save();
            }
            $trans->commit();
            return [
                'code' => 0,
                'msg' => '保存成功！',
            ];
        } catch (\yii\db\Exception $ex) {
            $trans->rollBack();
            throw $ex;
        }
    }
}
