<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 13:54
 */

namespace app\modules\mch\models;

/**
 * @property \app\models\User $user
 */
class UserForm extends MchModel
{
    public $store_id;
    public $user;
    public $level;
    public $contact_way;
    public $comments;
    public $parent_id;
    public $price;
    public $blacklist;

    public function rules()
    {
        return [
            [['level','parent_id', 'blacklist'],'integer'],
            [['contact_way','comments'], 'string', 'max' => 255],
            [['price'],'number', 'min'=>0, 'max'=>99999999.99],
            [['price'],'default','value'=>0]
        ];
    }

    public function attributeLabels()
    {
        return [
            'level'=>'会员等级',
            'contact_way'=>'联系方式',
            'comments'=>'备注',
            'parent_id'=>'上级',
            'price'=>'可提现佣金',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $this->user->level = $this->level;
        $this->user->contact_way = trim($this->contact_way);
        $this->user->comments = trim($this->comments);
        $this->user->parent_id = $this->parent_id;
        $resetPrice = $this->user->price - $this->price;
        $this->user->total_price = $this->user->total_price - $resetPrice;
        $this->user->price =  $this->price;
        $this->user->blacklist =  $this->blacklist;

        if ($this->user->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return $this->getErrorResponse($this->user);
        }
    }
}
