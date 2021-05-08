<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/7 14:12
 */


namespace app\modules\mch\models\wechatplatform;

use app\models\tplmsg\BindWechatPlatform;
use app\modules\mch\models\MchModel;

class AddTplForm extends MchModel
{
    public $store_id;
    public $tpl;

    public function rules()
    {
        return [
            ['tpl', 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $this->tpl = \Yii::$app->serializer->decode($this->tpl);
        $form = new BindWechatPlatform();
        $form->store_id = $this->store_id;
        $res = $form->addTpl($this->tpl);
        if ($res) {
            return [
                'code' => 0,
                'msg' => '添加模板成功。',
                'data' => [
                    'tpl' => $res,
                ],
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '添加模板失败。',
            ];
        }
    }
}
