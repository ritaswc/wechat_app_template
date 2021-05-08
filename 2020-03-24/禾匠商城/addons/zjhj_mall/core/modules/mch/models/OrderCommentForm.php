<?php

namespace app\modules\mch\models;

use app\models\OrderComment;

/**
 * @property Topic $model
 */
class OrderCommentForm extends MchModel
{
    public $model;

    public $store_id;
    public $goods_id;
    public $score;
    public $content;
    public $pic_list;
    public $is_hide;
    public $is_virtual;
    public $virtual_user;
    public $virtual_avatar;
    public $addtime;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'score','virtual_user','content','is_hide'], 'required'],
            [['goods_id', 'is_hide', 'is_virtual'], 'integer'],
            [['content','virtual_avatar'], 'string', 'max' => 1000],
            [['virtual_user'], 'string', 'max' => 255],
            [['addtime'], 'integer', 'max'=>2000000000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'order_id' => '订单号',
            'order_detail_id' => 'Order Detail ID',
            'goods_id' => '商品',
            'user_id' => '用户名',
            'score' => '评分',
            'content' => '评价',
            'pic_list' => '图片',
            'is_hide' => '是否隐藏：0=不隐藏，1=隐藏',
            'is_delete' => 'Is Delete',
            'addtime' => '评价时间',
            'reply_content' => '回复内容',
            'is_virtual' => 'Is Virtual',
            'virtual_user' => '虚拟用户名',
            'virtual_avatar' => '虚拟头像',
        ];
    }

    public function save()
    {
       
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->model->attributes = $this->attributes;
        $this->model->store_id = $this->store_id;
        $this->model->user_id = 0;
        $this->model->order_id = 0;
        $this->model->order_detail_id = 0;
        $this->model->user_id = 0;
        $this->model->is_delete = 0;
        $this->model->addtime = $this->addtime;
        $this->model->is_virtual = 1;
        $this->model->pic_list = $this->pic_list;

        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
