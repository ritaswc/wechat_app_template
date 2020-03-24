<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11
 * Time: 10:45
 */

namespace app\modules\mch\models;

use app\models\Cash;
use app\models\User;
use yii\data\Pagination;

class CashForm extends MchModel
{
    public $store_id;
    public $user_id;
    public $flag;
    public $fields;
    public $date_start;
    public $date_end;

    public $page;
    public $limit;
    public $status;
    public $keyword;
    public $id;
    public $platform;//所属平台

    public function rules()
    {
        return [
            [['keyword',], 'trim'],
            [['page', 'limit', 'status', 'id'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['page'], 'default', 'value' => 1],
            [['flag', 'date_start', 'date_end'], 'safe']
        ];
    }


    public function getList()
    {
        $query = Cash::find()->alias('c')
            ->where(['c.is_delete' => 0, 'c.store_id' => $this->store_id])
            ->leftJoin('{{%user}} u', 'u.id=c.user_id')
            ->leftJoin('{{%share}} s', 's.user_id=c.user_id')
            ->andWhere(['s.is_delete' => 0,'u.is_distributor' => 1]);
        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['like', 'u.nickname', $this->keyword],
                ['like', 's.name', $this->keyword]
            ]);
        }
        if ($this->date_start) {
            $query->andWhere(['>=', 'c.addtime', strtotime($this->date_start)]);
        }
        if ($this->date_end) {
            $query->andWhere(['<=', 'c.addtime', strtotime($this->date_end)]);
        }

        if ($this->status == 0 and $this->status != '') {//待审核
            $query->andWhere(['c.status' => 0]);
        }
        if ($this->status == 1) {//待打款
            $query->andWhere(['c.status' => 1]);
        }
        if ($this->status == 2) {//已打款
            $query->andWhere(['in', 'c.status', [2, 5]]);
        }

        if ($this->status == 3) {//无效
            $query->andWhere(['c.status' => 3]);
        }
        if ($this->id) {
            $query->andWhere(['s.id' => $this->id]);
        }
        if (isset($this->platform)) {
            $query->andWhere(['u.platform' => $this->platform]);
        }
        if ($this->flag == 'EXPORT') {
            $export = new ExportList();
            $data = $query->orderBy('c.status ASC,c.addtime DESC')->select(['c.*', 'u.nickname', 'u.avatar_url','u.platform', 'u.id user_id'])->asArray()->all();
            $export->shareExportData($data, $this->fields);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('c.status ASC,c.addtime DESC')
            ->select([
                'c.*', 'u.nickname','u.platform', 'u.avatar_url', 'u.id user_id'
            ])->asArray()->all();
        foreach($list as &$value){
            $value['service_money'] = $value['service_charge'] * $value['price'] / 100;
            $value['money'] = Cash::getServiceMoney($value);
        }
        unset($value);

        return [
            'list' => $list,
            'pagination' => $pagination,
            'exportList' => $this->getCustomField()
        ];
    }

    public function getCount()
    {
        $list = Cash::find()->alias('c')->select([
            'sum(case when c.status = 0 then 1 else 0 end) count_1',
            'sum(case when c.status = 1 then 1 else 0 end) count_2',
            'sum(case when c.status = 2 or status = 5 then 1 else 0 end) count_3',
            'sum(case when c.status = 3 then 1 else 0 end) count_4',
            'count(1) total'
        ])->leftJoin(['u' => User::tableName()],'u.id = c.user_id')->where(['c.is_delete' => 0, 'c.store_id' => $this->store_id])
            ->andWhere(['u.is_distributor' => 1])->asArray()->one();
        return $list;
    }

    public function getCustomField()
    {
        return [
            [
                'key' => 'order_no',
                'value' => '订单号'
            ],
            [
                'key' => 'nickname',
                'value' => '姓名',
            ],
            [
                'key' => 'price',
                'value' => '提现金额',
            ],
            [
                'key' => 'addtime',
                'value' => '申请日期',
            ],
            [
                'key' => 'bank_name',
                'value' => '银行名称',
            ],
            [
                'key' => 'bank_card',
                'value' => '打款账号',
            ],
            [
                'key' => 'name',
                'value' => '真实姓名',
            ],
            [
                'key' => 'type',
                'value' => '类型',
            ],
            [
                'key' => 'pay_type',
                'value' => '打款方式',
            ],
            [
                'key' => 'pay_time',
                'value' => '付款时间',
            ],
        ];
    }
}
