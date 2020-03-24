<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\diy;

use app\models\DiyPage;

;

use app\modules\mch\models\MchModel;

class DiyPageEditForm extends MchModel
{
    public $title;
    public $template_id;
    public $id;
    public $status;

    public function rules()
    {
        return [
            [['title', 'template_id'], 'required'],
            [['template_id', 'id', 'status'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => '页面标题',
            'template_id' => '模板',
        ];
    }


    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }


        try {

            if ($this->id) {
                $detail = DiyPage::findOne($this->id);
                $detail->title = $this->title;
                $detail->template_id = $this->template_id;
            } else {
                $detail = new DiyPage();
                $detail->store_id = $this->getCurrentStoreId();
                $detail->title = $this->title;
                $detail->template_id = $this->template_id;
                $detail->addtime = time();
            }
            $detail->status = $this->status;

            if ($detail->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功'
                ];
            }

        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }

}
}