<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/11/27
 * Time: 9:32
 */

namespace app\modules\api\models\group;

use app\models\Article;
use app\models\common\CommonGoods;
use app\models\GoodsShare;
use app\models\Option;
use app\models\Order;
use app\models\PtGoods;
use app\models\PtGoodsPic;
use app\models\PtOrder;
use app\models\PtOrderComment;
use app\models\PtOrderDetail;
use app\models\User;
use app\modules\api\models\ApiModel;
use app\modules\mch\models\LevelListForm;
use app\utils\GetInfo;
use yii\data\Pagination;
use app\models\PtGoodsDetail;

class PtGoodsForm extends ApiModel
{
    public $page = 0;
    public $store_id;

    public $user_id;

    public $gid;

    public $limit;


    /**
     * @return array
     * 拼团商品列表
     */
    public function getList()
    {
        $page = \Yii::$app->request->get('page') ?: 1;
        $limit = (int)\Yii::$app->request->get('limit') ?: 10;
        $cid = \Yii::$app->request->get('cid');
        $query = PtGoods::find()
            ->andWhere(['is_delete' => 0, 'store_id' => $this->store_id, 'status' => 1])
            ->andWhere(['or', ['>', 'limit_time', time()], ['limit_time' => 0]]);
        if ((int)$cid) {
            // 分类
            $query->andWhere(['cat_id' => $cid]);
        } else {
            // 热销
            $query->andWhere(['is_hot' => 1]);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $limit, 'page' => $page - 1]);
        $list = $query
            ->offset($p->offset)
            ->limit($p->limit)
            ->orderBy('sort ASC')
            ->asArray()
            ->all();
        foreach ($list as $key => $goods) {
            $list[$key]['groupList'] = PtOrderDetail::find()
                ->select([
                    'u.avatar_url'
                ])
                ->alias('od')
                ->andWhere(['od.goods_id' => $goods['id']])
                ->leftJoin(['o' => PtOrder::tableName()], 'o.id=od.order_id')
                ->andWhere(['o.is_delete' => 0, 'o.is_pay' => 1, 'o.is_group' => 1, 'o.parent_id' => 0, 'o.is_success' => 0])
                ->andWhere(['>', 'o.limit_time', time()])
                ->leftJoin(['u' => User::tableName()], 'o.user_id=u.id')
                ->andWhere(['!=', 'o.user_id', $this->user_id])
                ->orderBy(['o.addtime' => 'DESC'])
                ->limit(3)
                ->asArray()
                ->all();
            $list[$key]['price'] = round($goods['price'], 2);
            $list[$key]['original_price'] = round($goods['original_price'], 2);
            $ptGoodsFind = PtGoods::find()->where(['id' => $goods['id']])->one();
            $list[$key]['virtual_sales'] += $ptGoodsFind->getSalesVolume();
        }


        return [
            'row_count' => intval($count),
            'page_count' => intval($p->pageCount),
            'page' => intval($page),
            'list' => $list,
        ];
    }


    /**
     * @return array
     * 关键字搜索
     */
    public function search()
    {
        $page = \Yii::$app->request->get('page') ?: 1;
        $limit = (int)\Yii::$app->request->get('limit') ?: 4;
        $keyword = \Yii::$app->request->get('keyword');

        if (empty($keyword)) {
            return;
        }


        $query = PtGoods::find()
            ->andWhere(['is_delete' => 0, 'store_id' => $this->store_id, 'status' => 1])
            ->andWhere(['or', ['>', 'limit_time', time()], ['limit_time' => 0]]);

        $query->andWhere(['like', 'name', $keyword]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $limit, 'page' => $page - 1]);
        $list = $query
            ->select('id,name,original_price,price,cover_pic,unit,cat_id,virtual_sales,group_num,')
            ->offset($p->offset)
            ->limit($p->limit)
            ->orderBy('sort ASC')
            ->asArray()
            ->all();

        foreach ($list as $key => $goods) {
            $list[$key]['groupList'] = PtOrderDetail::find()
                ->select([
                    'u.avatar_url'
                ])
                ->alias('od')
                ->andWhere(['od.goods_id' => $goods['id']])
                ->leftJoin(['o' => PtOrder::tableName()], 'o.id=od.order_id')
                ->andWhere(['o.is_delete' => 0, 'o.is_pay' => 1, 'o.is_group' => 1, 'o.parent_id' => 0, 'o.is_success' => 0])
                ->andWhere(['>', 'o.limit_time', time()])
                ->leftJoin(['u' => User::tableName()], 'o.user_id=u.id')
                ->andWhere(['!=', 'o.user_id', $this->user_id])
                ->orderBy(['o.addtime' => 'DESC'])
                ->limit(3)
                ->asArray()
                ->all();
            $ptGoodsFind = PtGoods::find()->where(['id' => $goods['id']])->one();
            $list[$key]['virtual_sales'] += $ptGoodsFind->getSalesVolume();
        }


        return [
            'row_count' => intval($count),
            'page_count' => intval($p->pageCount),
            'page' => intval($page),
            'list' => $list,
        ];
    }


    /**
     * @return mixed|string
     * 拼团商品详情
     */
    public function getInfo()
    {
        $info = PtGoods::find()
            ->andWhere(['is_delete' => 0, 'store_id' => $this->store_id, 'status' => 1, 'id' => $this->gid])
            ->andWhere(['or', ['>', 'limit_time', time()], ['limit_time' => 0]])
            ->asArray()
            ->one();
        $goods = PtGoods::find()
            ->andWhere(['or', ['>', 'limit_time', time()], ['limit_time' => 0]])
            ->andWhere(['is_delete' => 0, 'store_id' => $this->store_id, 'status' => 1, 'id' => $this->gid])->one();

        if (!$info) {
            return [
                'code' => 1,
                'msg' => '商品不存在或已下架',
            ];
        }
        $info['pic_list'] = PtGoodsPic::find()
            ->select('pic_url')
            ->andWhere(['goods_id' => $this->gid, 'is_delete' => 0])
            ->column();

        $info['attr'] = json_decode($info['attr'], true);
        $info['service'] = $info['service'] ? explode(',', $info['service']) : [];
        // 默认商品服务
        if (!$info['service']) {
            $option = Option::get('good_services', $this->store->id, 'admin', []);
            foreach ($option as $item) {
                if ($item['is_default'] == 1) {
                    $info['service'] = explode(',', $item['service']);
                    break;
                }
            }
        }
        $attr_group_list = $goods->getAttrGroupList();


        $attr_group_num = $goods->getAttrGroupListnum();

        $classGroup = PtGoodsDetail::find()->where('id = o.class_group')->select('group_num')->createCommand()->getRawSql();
        $goodsGroup = PtGoods::find()->where('id = od.goods_id')->select('group_num')->createCommand()->getRawSql();
        $surplusGruop = PtOrder::find()->where('(id = o.id or parent_id = o.id)')
            ->andWhere(['status' => 2, 'is_group' => 1])
            ->andWhere([
                'OR',
                ['is_pay' => 1],
                ['pay_type' => 2]
            ])->select('count(1)')->createCommand()->getRawSql();
        $groupList = PtOrderDetail::find()
            ->select([
                'u.avatar_url', 'u.nickname',
                'o.limit_time', 'o.id',
            ])->addSelect("(CASE when `o`.`class_group` > 0 THEN ({$classGroup}) ELSE ({$goodsGroup}) END - ({$surplusGruop})) as surplus")
            ->alias('od')
            ->andWhere(['od.goods_id' => $goods->id])
            ->leftJoin(['o' => PtOrder::tableName()], 'o.id=od.order_id')
            ->andWhere(['o.is_delete' => 0, 'o.is_group' => 1, 'o.parent_id' => 0, 'o.is_success' => 0, 'o.is_show' => 1])
            ->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]])
            ->andWhere(['>', 'o.limit_time', time()])
            ->leftJoin(['u' => User::tableName()], 'o.user_id=u.id')
            ->andWhere(['!=', 'o.user_id', $this->user_id])
            ->orderBy(['surplus' => SORT_ASC,'o.addtime' => 'DESC'])
            ->limit(10)
            ->asArray()
            ->all();
        foreach ($groupList as $key => $val) {
            $limit_time1 = [
                'days' => '00',
                'hours' => '00',
                'mins' => '00',
                'secs' => '00',
            ];

            $order = PtOrder::findOne($val['id']);
            $groupList[$key]['surplus'] = $order->getSurplusGruop();

            $timediff1 = $order->limit_time - time();
            $limit_time1['days'] = intval($timediff1 / 86400);
            //计算小时数
            $remain1 = $timediff1 % 86400;
            $limit_time1['hours'] = intval($remain1 / 3600);
            //计算分钟数
            $remain1 = $remain1 % 3600;
            $limit_time1['mins'] = intval($remain1 / 60);
            //计算秒数
            $limit_time1['secs'] = $remain1 % 60;

            $groupList[$key]['limit_time'] = $limit_time1;
            $groupList[$key]['limit_time_ms'] = explode('-', date('Y-m-d-H-i-s', $order->limit_time));
        }

        $limit_time = [
            'days' => '00',
            'hours' => '00',
            'mins' => '00',
            'secs' => '00',
        ];
        if (!empty($goods->limit_time)) {
            $timediff = $goods->limit_time - time();
            if ($timediff < 0) {
                return [
                    'code' => 1,
                    'msg' => '该商品拼团活动已经结束',
                ];
            }
        }

        $limit_time['days'] = intval($timediff / 86400);
        //计算小时数
        $remain = $timediff % 86400;
        $limit_time['hours'] = intval($remain / 3600);
        //计算分钟数
        $remain = $remain % 3600;
        $limit_time['mins'] = intval($remain / 60);
        //计算秒数
        $limit_time['secs'] = $remain % 60;
        $info['limit_time_ms'] = explode('-', date('Y-m-d-H-i-s', $info['limit_time']));
        // 销量
        $info['virtual_sales'] += $goods->getSalesVolume();
        $info['sales_volume'] = $info['virtual_sales'];


        // 获取拼团规则id
        $groupRuleId = Article::find()->andWhere([
            'store_id' => $this->store_id,
            'article_cat_id' => 3,
        ])->scalar();

        $comment = PtOrderComment::find()
            ->alias('c')
            ->select([
                'c.score', 'c.content', 'c.pic_list', 'c.addtime', 'c.is_virtual', 'c.virtual_user', 'c.virtual_avatar',
                'u.nickname', 'u.avatar_url',
                'od.attr'
            ])
            ->andWhere(['c.store_id' => $this->store_id, 'c.goods_id' => $info['id'], 'c.is_delete' => 0, 'c.is_hide' => 0])
            ->leftJoin(['u' => User::tableName()], 'u.id = c.user_id')
            ->leftJoin(['od' => PtOrderDetail::tableName()], 'od.id=c.order_detail_id')
            ->orderBy('c.addtime DESC')
            ->limit(2)
            ->asArray()
            ->all();

        foreach ($comment as $k => $v) {
            $comment[$k]['attr'] = json_decode($v['attr'], true);
            $comment[$k]['pic_list'] = json_decode($v['pic_list'], true);
            $comment[$k]['addtime'] = date('m月d日', $v['addtime']);

            if ($v['is_virtual'] == 1) {
                $v['nickname'] = $v['virtual_user'];
                $comment[$k]['avatar_url'] = $v['virtual_avatar'];

                unset($comment[$k]['virtual_avatar']);
                unset($comment[$k]['is_virtual']);
                unset($comment[$k]['virtual_user']);
            }
            $comment[$k]['nickname'] = $this->substr_cut($v['nickname']);
        }
        if (empty($comment)) {
            $comment = false;
        }

        $goods = PtGoods::findOne([
            'id' => $this->gid,
            'is_delete' => 0,
            'status' => 1,
            'store_id' => $this->store_id,
        ]);

        $detail = PtGoodsDetail::find()->where(['store_id' => $this->store_id, 'goods_id' => $info['id']])->all();

        $price = [];
        foreach ($detail as $v) {
            foreach (json_decode($v->attr, true) as $k1 => $v1) {
                if ($v1['price'] > 0) {
                    $price[] = $v1['price'];
                } else {
                    foreach ($info['attr'] as $k2 => $v2) {
                        if ($v1['attr_list'] == $v2['attr_list'] && $v2['price'] > 0) {
                            $price[] = $v2['price'];
                        } else {
                            $price[] = $info['price'];
                        }
                    }
                }
            }
        }

        foreach ($info['attr'] as $v) {
            if ($v['price'] > 0) {
                $price[] = $v['price'];
            } else {
                $price[] = $info['price'];

            }
            $single_price[] = sprintf('%.2f', $v['single']);
        };

        // 获取最高分销价 、最低会员价、当前会员价
        $goodsShare = GoodsShare::find()->where(['type' => GoodsShare::SHARE_GOODS_TYPE_PT, 'goods_id' => $goods->id])->one();
        $res = CommonGoods::getMMPrice([
            'attr' => $goods['attr'],
            'attr_setting_type' => $goodsShare['attr_setting_type'],
            'share_type' => $goodsShare['share_type'],
            'share_commission_first' => $goodsShare['share_commission_first'],
            'price' => $goods['price'],
            'individual_share' => $goodsShare['individual_share'],
            'is_level' => $goods['is_level'],
        ],['type' => 'PINTUAN']);

        $attr = json_decode($goods['attr'], true);
        $goodsPrice = $goods->price;
        $isMemberPrice = false;
        if ($res['user_is_member'] === true && count($attr) === 1 && $attr[0]['attr_list'][0]['attr_name'] == '默认') {
            $goodsPrice = $res['min_member_price'] ? $res['min_member_price'] : $goods->price;
            $isMemberPrice = true;
        }

        $info['max_price'] = sprintf('%.2f', max($price));
        $info['min_price'] = sprintf('%.2f', min($price));
        $info['max_share_price'] = sprintf('%.2f', $res['max_share_price']);
        $info['min_member_price'] = sprintf('%.2f', $res['min_member_price']);
        $info['num'] = $goods->getNum(json_decode($goods->attr, true));
        $info['single_price'] = sprintf('%.2f', min($single_price)) > 0 ? sprintf('%.2f', min($single_price)) : $goods->original_price;
        $info['group_price'] = sprintf('%.2f', $goodsPrice);
        $info['is_share'] = $res['is_share'];
        $info['is_level'] = $res['is_level'];
        $info['is_member_price'] = $isMemberPrice;

        $res_url = GetInfo::getVideoInfo($info['video_url']);
        $info['video_url'] = $res_url['url'];

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'info' => $info,
                'attr_group_list' => $attr_group_list,
                'attr_group_num' => $attr_group_num,
                'limit_time' => $limit_time,
                'groupList' => $groupList,
                'groupRuleId' => $groupRuleId,
                'comment' => $comment,
                'commentNum' => PtOrderComment::getCount($info['id'], $this->store_id),
            ],
        ];
    }

    /**
     * @return array
     * 评论列表
     */
    public function comment()
    {
        $query = PtOrderComment::find()
            ->alias('c')
            ->select([
                'c.score', 'c.content', 'c.pic_list', 'c.addtime', 'c.is_virtual', 'c.virtual_user', 'c.virtual_avatar',
                'u.nickname', 'u.avatar_url',
                'od.attr'
            ])
            ->andWhere(['c.store_id' => $this->store_id, 'c.goods_id' => $this->gid, 'c.is_delete' => 0, 'c.is_hide' => 0])
            ->leftJoin(['u' => User::tableName()], 'u.id = c.user_id')
            ->leftJoin(['od' => PtOrderDetail::tableName()], 'od.id=c.order_detail_id');
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => 20]);

        $comment = $query
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->orderBy('c.addtime DESC')
            ->asArray()
            ->all();
        foreach ($comment as $k => $v) {

            if ($v['is_virtual'] == 1) {
                $v['nickname'] = $v['virtual_user'];
                $comment[$k]['avatar_url'] = $v['virtual_avatar'];
                unset($comment[$k]['virtual_avatar']);
                unset($comment[$k]['is_virtual']);
                unset($comment[$k]['virtual_user']);
            }

            $comment[$k]['attr'] = json_decode($v['attr'], true);
            $comment[$k]['pic_list'] = json_decode($v['pic_list'], true);
            $comment[$k]['addtime'] = date('m月d日', $v['addtime']);
            $comment[$k]['nickname'] = $this->substr_cut($v['nickname']);
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'row_count' => $count,
                'page_count' => $pagination->pageCount,
                'comment' => $comment,
            ],
        ];
    }

    // 将用户名 做隐藏
    private function substr_cut($user_name)
    {
        $strlen = mb_strlen($user_name, 'utf-8');
        $firstStr = mb_substr($user_name, 0, 1, 'utf-8');
        $lastStr = mb_substr($user_name, -1, 1, 'utf-8');
        return $strlen <= 2 ? $firstStr . '*' : $firstStr . str_repeat("*", 2) . $lastStr;
    }
}
