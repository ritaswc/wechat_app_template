<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 11:41
 */

namespace app\modules\mch\models;

use app\models\Level;
use app\models\Store;
use app\models\User;

/**
 * @property \app\models\Level $model;
 */
class LevelForm extends MchModel
{
    public $store_id;
    public $model;

    public $level;
    public $name;
    public $money;
    public $status;
    public $discount;
    public $content;
    public $price;
    public $image;
    public $buy_prompt;
    public $detail;
    public $synopsis;

    public function rules()
    {
        return [
            [['name','money', 'synopsis'],'trim'],
            [['name','image'],'string'],
            [['level','name','money','status','discount'],'required','on'=>'edit'],
            [['status'],'in','range'=>[0,1]],
            [['discount'],'number','min'=>0.1,'max'=>10],
            [['money','price'],'number','min'=> 0,'max' => 99999999],
            [['level'],'integer','min'=>0,'max'=>100],
            [['content'],'required','on'=>'content'],
            [['name', 'detail', 'buy_prompt'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'level'=>'会员等级',
            'name'=>'等级名称',
            'money'=>'升级条件',
            'status'=>'状态',
            'discount'=>'折扣',
            'content'=>'会员等级说明',
            'price'=>'升级所需价格',
            'image'=>'会员图片',
            'buy_prompt'=>'购买前显示',
            'detail'=>'会员说明',
            'synopsis' => '小标题标题'
        ];
    }
    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        if ($this->model->isNewRecord) {
            $this->model->is_delete = 0;
            $this->model->addtime = time();
        }
        if($this->money <= 0) {
            return [
                'code' => 1,
                'msg' => '升级条件必须大于0'
            ];
        }
        if ($this->level != $this->model->level) {
            $exit = Level::find()->where(['level'=>$this->level,'store_id'=>$this->store_id,'is_delete'=>0])->exists();
            if ($exit) {
                return [
                    'code'=>1,
                    'msg'=>'会员等级已存在'
                ];
            }
        }
        if ($this->name != $this->model->name) {
            $exit_0 = Level::find()->where(['name'=>$this->name,'store_id'=>$this->store_id,'is_delete'=>0])->exists();
            if ($exit_0) {
                return [
                    'code'=>1,
                    'msg'=>'等级名称重复'
                ];
            }
        }
        if($this->model->id && $this->model->level != $this->level) {
            $count = User::find()->where(['store_id' => $this->store->id, 'level' => $this->model->level])->count();
            if($count > 0) {
                return [
                    'code'=>1,
                    'msg'=>'当前会员等级下有会员，禁止修改会员等级'
                ];
            }
        }
        /*
        $exit_2 = Level::find()->where(['store_id'=>$this->store_id,'is_delete'=>0])
            ->andWhere(['<','level',$this->level])->andWhere(['>=','money',$this->money])->exists();
        if($exit_2){
            return [
                'code'=>1,
                'msg'=>'升级条件不能小于等于低等级会员'
            ];
        }
        $exit_1 = Level::find()->where(['store_id'=>$this->store_id,'is_delete'=>0])
            ->andWhere(['<','level',$this->level])->andWhere(['<','discount',$this->discount])->exists();
        if($exit_1){
            return [
                'code'=>1,
                'msg'=>'折扣不能小于低等级会员'
            ];
        }
        $exit_3 = Level::find()->where(['store_id'=>$this->store_id,'is_delete'=>0])
            ->andWhere(['>','level',$this->level])->andWhere(['<=','money',$this->money])->exists();
        if($exit_3){
            return [
                'code'=>1,
                'msg'=>'升级条件不能大于等于高等级会员'
            ];
        }
        $exit_4 = Level::find()->where(['store_id'=>$this->store_id,'is_delete'=>0])
            ->andWhere(['>','level',$this->level])->andWhere(['>','discount',$this->discount])->exists();
        if($exit_4){
            return [
                'code'=>1,
                'msg'=>'折扣不能大于高等级会员'
            ];
        }
        */

        $this->model->store_id = $this->store_id;
        $this->model->level = $this->level;
        $this->model->name  = $this->name;
        $this->model->synopsis = $this->synopsis ? \Yii::$app->serializer->encode($this->synopsis):'';
        $this->model->money = $this->money;
        $this->model->status = $this->status;
        $this->model->discount = $this->discount;
        $this->model->image = $this->image;
        $this->model->price = $this->price;
        $this->model->buy_prompt = $this->buy_prompt;
        $this->model->detail = $this->detail;

        if ($this->model->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }

    public function saveContent()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $store = Store::findOne(['id'=>$this->store_id]);
        $store->member_content = $this->content;

        if ($store->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return $this->getErrorResponse($store);
        }
    }
}
