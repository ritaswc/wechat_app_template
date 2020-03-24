<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 15:25
 */

namespace app\modules\mch\models;

/**
 * @property \app\models\Card $card;
 */
class CardForm extends MchModel
{
    public $store_id;
    public $card;

    public $name;
    public $pic_url;
    public $content;

    public function rules()
    {
        return [
            [['name','pic_url','content'],'trim'],
            [['name','content'],'string','max'=>999999],
            [['name','pic_url','content'],'required'],
            [['pic_url'],'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'卡券名称',
            'pic_url'=>'卡券图片',
            'content'=>'卡券描述'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        if ($this->card->isNewRecord) {
            $this->card->is_delete = 0;
            $this->card->store_id = $this->store_id;
            $this->card->addtime = time();
        }

        $this->card->name = $this->name;
        $this->card->pic_url = $this->pic_url;
        $this->card->content = $this->content;

        if ($this->card->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return $this->getErrorResponse($this->card);
        }
    }
}
