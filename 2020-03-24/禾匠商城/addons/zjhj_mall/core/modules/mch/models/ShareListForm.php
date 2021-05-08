<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 16:13
 */

namespace app\modules\mch\models;

use app\models\MsOrder;
use app\models\Order;
use app\models\PtOrder;
use app\models\Share;
use app\models\User;
use app\models\YyOrder;
use app\modules\mch\extensions\Export;
use yii\data\Pagination;
use yii\helpers\VarDumper;

class ShareListForm extends MchModel
{
    public $store_id;

    public $page;
    public $limit;
    public $status;
    public $keyword;
    public $seller_comments;
    public $platform;
    public $fields;
    public $flag;

    public function rules()
    {
        return [
            [['keyword', 'seller_comments', 'flag'], 'trim'],
            [['page', 'limit', 'status'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['page'], 'default', 'value' => 1],
            [['seller_comments'], 'string'],
            [['fields', 'platform'], 'safe']
        ];
    }


    public function getList()
    {
        if ($this->validate()) {

            $query = Share::find()->alias('s')
                ->where(['s.is_delete' => 0, 's.store_id' => $this->store_id])
                ->leftJoin('{{%user}} u', 'u.id=s.user_id')
                ->andWhere(['u.is_delete' => 0])
                ->andWhere(['in', 's.status', [0, 1]]);
            if ($this->keyword) {
                $query->andWhere([
                    'or',
                    ['like', 's.name', $this->keyword],
                    ['like', 'u.nickname', $this->keyword],
                ]);
            }
            if ($this->status == 0 && $this->status != '') {
                $query->andWhere(['s.status' => 0]);
            }
            if ($this->status == 1) {
                $query->andWhere(['s.status' => 1]);
            }
            if (isset($this->platform)) {
                $query->andWhere(['u.platform' => $this->platform]);
            }

            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
            {
                $orderCount = Order::find()->where([
                    'store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0, 'is_recycle' => 0
                ])->andWhere('user_id = u.id')->select('count(1)');
                $msOrderCount = MsOrder::find()->where([
                    'store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0
                ])->andWhere('user_id = u.id')->select('count(1)');
                $ptOrderCount = PtOrder::find()->where([
                    'store_id' => $this->store_id, 'is_delete' => 0
                ])->andWhere('user_id = u.id')->select('count(1)');
                $yyOrderCount = YyOrder::find()->where([
                    'store_id' => $this->store_id, 'is_delete' => 0
                ])->andWhere('user_id = u.id')->select('count(1)');
            }

            if ($this->flag === Export::EXPORT) {

                $list = $query->orderBy('s.status ASC,s.addtime DESC')
                    ->select([
                        's.*', 'u.nickname', 'u.avatar_url', 'u.platform', 'u.time', 'u.price', 'u.total_price', 'u.id user_id', 'u.parent_id',
                        'order_count' => $orderCount, 'ms_order_count' => $msOrderCount, 'pt_order_count' => $ptOrderCount, 'yy_order_count' => $yyOrderCount,
                        'parent_nickname' => User::find()->alias('parent')->where('parent.id = u.parent_id')->select('nickname')
                    ])
                    ->orderBy(['status' => SORT_ASC, 'total_price' => SORT_DESC])->asArray()->all();


                foreach ($list as $index => &$value) {
                    $first = $this->getTeam1($value['user_id'], 1);
                    $value['first'] = count($first['data']);
                    $second = $this->getTeam1($value['user_id'], 2);
                    $value['second'] = count($second['data']);
                    $third = $this->getTeam1($value['user_id'], 3);
                    $value['third'] = count($third['data']);
                }
                $export = new ExportList();
                $export->fields = $this->fields;
                $export->ShareInfoExportData($list);
            }


            $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('s.status ASC,s.addtime DESC')
                ->select([
                    's.*', 'u.nickname', 'u.avatar_url', 'u.platform', 'u.time', 'u.price', 'u.total_price', 'u.id user_id', 'u.parent_id',
                    'order_count' => $orderCount, 'ms_order_count' => $msOrderCount, 'pt_order_count' => $ptOrderCount, 'yy_order_count' => $yyOrderCount,
                    'parent_nickname' => User::find()->alias('parent')->where('parent.id = u.parent_id')->select('nickname')
                ])
                ->orderBy(['status' => SORT_ASC, 'total_price' => SORT_DESC])->asArray()->all();

            foreach ($list as $index => &$value) {
                $first = $this->getTeam1($value['user_id'], 1);
                $value['first'] = count($first['data']);
                $second = $this->getTeam1($value['user_id'], 2);
                $value['second'] = count($second['data']);
                $third = $this->getTeam1($value['user_id'], 3);
                $value['third'] = count($third['data']);
            }

            return [
                'list' => $list,
                'team_list' => [],
                'pagination' => $pagination
            ];
        } else {
            return $this->errorResponse;
        }
    }

    //无效
    public function getList1()
    {
        $query = Share::find()->alias('s')
            ->where(['s.is_delete' => 0, 's.store_id' => $this->store_id, 'status' => 1])
            ->leftJoin(User::tableName() . ' u', 'u.id=s.user_id')
            ->joinWith('firstChildren')->groupBy('s.id');
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('s.addtime DESC')
            ->select(['s.*', 'u.nickname', 'u.avatar_url', 'u.time', 'u.price', 'u.total_price',])->asArray()->all();
        $new_list = $list;
        foreach ($list as $index => $value) {
            $list[$index]['first'] = count($value['firstChildren']);
            foreach ($value['firstChildren'] as $i => $v) {
                $list[$index]['firstChildren'][$i]['time'] = date('Y-m-d', $v['addtime']);
                foreach ($new_list as $j => $item) {
                    if ($v['id'] == $item['user_id']) {
                        $list[$index]['second'] = $new_list[$j]['firstChildren'];
                    }
                }
            }
        }
        $new_list = $list;
        foreach ($list as $index => $value) {
            if (isset($value['secondChildren']) && is_array($value['secondChildren'])) {
                foreach ($value['secondChildren'] as $i => $v) {
                    $list[$index]['secondChildren'][$i]['time'] = date('Y-m-d', $v['addtime']);
                    foreach ($new_list as $j => $item) {
                        if ($v['id'] == $item['user_id']) {
                            $list[$index]['thirdChildren'] = $new_list[$j]['firstChildren'];
                        }
                    }
                }
            }
        }
        return $list;
    }

    public function getTeam()
    {
        //获取有一级下线的分销商
        $query = Share::find()->alias('s')
            ->where(['s.is_delete' => 0, 's.store_id' => $this->store_id])
//            ->leftJoin(User::tableName() . ' u', 'u.id=s.user_id')
            ->joinWith('firstChildren')->groupBy('s.id');
        $userQuery = User::find()->where('s.user_id=id')->select('nickname');
        $list = $query->select([
            's.*', 'nickname' => $userQuery
        ])->asArray()->all();
        $new_list = $list;
        //获取二级下线
        foreach ($list as $index => $value) {
            $res = [];
            foreach ($value['firstChildren'] as $i => $v) {
                $list[$index]['firstChildren'][$i]['time'] = date('Y-m-d', $v['addtime']);
                foreach ($new_list as $j => $item) {
                    if ($v['id'] == $item['user_id']) {
//                            $list[$index]['secondChildren'] = $new_list[$j]['firstChildren'];
                        $res = array_merge($res, $new_list[$j]['firstChildren']);
                    }
                }
            }
            $list[$index]['secondChildren'] = $res;
        }
        $new_list = $list;
        foreach ($list as $index => $value) {
            $res = [];
            if (isset($value['secondChildren']) && is_array($value['secondChildren'])) {
                foreach ($value['secondChildren'] as $i => $v) {
                    $list[$index]['secondChildren'][$i]['time'] = date('Y-m-d', $v['addtime']);
                    foreach ($new_list as $j => $item) {
                        if ($v['id'] == $item['user_id']) {
//                            $list[$index]['thirdChildren'] = $new_list[$j]['firstChildren'];
                            $res = array_merge($res, $new_list[$j]['firstChildren']);
                        }
                    }
                }
            }
            $list[$index]['thirdChildren'] = $res;
        }
        return $list;
    }


    public function getCount()
    {
        $list = Share::find()
            ->select([
                'sum(case when status = 0 then 1 else 0 end) count_1',
                'sum(case when status = 1 then 1 else 0 end) count_2',
                'sum(case when status != 2 then 1 else 0 end) total'
            ])
            ->where(['is_delete' => 0, 'store_id' => $this->store_id])->asArray()->one();
        return $list;
    }

    public function excelFields()
    {
        $list = [
            [
                'key' => 'id',
                'value' => '用户ID',
                'selected' => 0,
            ],
            [
                'key' => 'nickname',
                'value' => '用户昵称',
                'selected' => 0,
            ],
            [
                'key' => 'name',
                'value' => '姓名',
                'selected' => 0,
            ],
            [
                'key' => 'mobile',
                'value' => '手机号',
                'selected' => 0,
            ],
            [
                'key' => 'addtime',
                'value' => '申请时间',
                'selected' => 0,
            ],
            [
                'key' => 'time',
                'value' => '审核时间',
                'selected' => 0,
            ],
            [
                'key' => 'status',
                'value' => '审核状态',
                'selected' => 0,
            ],
            [
                'key' => 'total_price',
                'value' => '累计佣金',
                'selected' => 0,
            ],
            [
                'key' => 'price',
                'value' => '可提现佣金',
                'selected' => 0,
            ],
            [
                'key' => 'order',
                'value' => '订单信息',
                'selected' => 0,
            ],
            [
                'key' => 'lower_user',
                'value' => '下级用户',
                'selected' => 0,
            ],
            [
                'key' => 'parent_nickname',
                'value' => '推荐人',
                'selected' => 0,
            ],
            [
                'key' => 'seller_comments',
                'value' => '备注信息',
                'selected' => 0,
            ],
        ];

        return $list;
    }

    public function getTeam1($user_id, $level)
    {
        $firstQuery = User::find()->alias('f')->select('f.*')
            ->where(['f.is_delete' => 0, 'f.parent_id' => $user_id, 'f.store_id' => $this->store_id]);

        $query = $firstQuery;
        if ($level > 1) {
            $secondQuery = User::find()->alias('s')->where(['s.is_delete' => 0, 's.store_id' => $this->store_id])
                ->innerJoin(['f' => $firstQuery], 'f.id=s.parent_id');

            $query = $secondQuery;
            if ($level > 2) {
                $thirdQuery = User::find()->alias('t')->where(['t.is_delete' => 0, 't.store_id' => $this->store_id])
                    ->innerJoin(['s' => $secondQuery], 's.id=t.parent_id');
                $query = $thirdQuery;
            }
        }

        $list = $query->asArray()->all();

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => $list
        ];
    }

}
