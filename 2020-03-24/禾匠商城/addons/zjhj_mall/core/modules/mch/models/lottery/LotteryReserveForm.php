<?php
namespace app\modules\mch\models\lottery;
use app\models\BargainOrder;
use app\models\Goods;
use app\models\LotteryGoods;
use app\models\LotteryReserve;
use app\modules\mch\models\MchModel;
use app\models\User;
use yii\data\Pagination;

class LotteryReserveForm extends MchModel
{
    public $store_id;
    public $model;

    public $lottery_id;
    public $user_id_list;

    public function rules()
    {
        return [
            [['store_id', 'lottery_id'], 'integer'],
            [['lottery_id'], 'required'],
            [['user_id_list'], 'trim'],
        ];
    }

    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'store_id' => 'Store ID',
            'user_id' => '用户',
            'lottery_id' => '奖品',
        ]; 
    }

    public function search()
    {

        $query = LotteryGoods::find()
            ->where([
                'store_id' => $this->store_id,
                'is_delete' => 0
            ])->with(['goods' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0
                ]);
            }]);


        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy(['create_time' => SORT_ASC, 'id' => SORT_DESC])->asArray()->all();



        foreach($list as $k => $v){
            $attrs = json_decode($v['attr'],true);
            $name = '';
            foreach($attrs as $v1){
                $name .= $v1['attr_group_name'].':'.$v1['attr_name'].';';
            }
            $list[$k]['attrs'] = $name; 
        }

        return [
            'list'=>$list,
            'pagination'=>$pagination
        ];

    }



    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $user_list = User::find()->select('id')->where(['id' => $this->user_id_list, 'store_id' => $this->store_id])->all();

        $count = 0;
        foreach ($user_list as $u) {
            $res = LotteryReserve::userAdd($u->id, $this->lottery_id);
            if ($res) {
                $count++;
            } 
        }
        return [
            'code' => 0,
            'msg' => "操作完成，共操作{$count}人次。",
        ];
    }
}