<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/12/7
 * Time: 20:05
 */

namespace app\modules\mch\models\gwd;

use app\models\common\api\CommonShoppingList;
use app\models\Goods;
use app\models\GwdBuyList;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\Order;
use app\models\PtOrder;
use app\models\User;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class BuyListForm extends MchModel
{
    public $orderType;
    public $keyword;
    public $id;//订单ID
    public $arrList; //订单ID数组

    public function rules()
    {
        return [
            [['id', 'orderType'], 'integer'],
            [['keyword'], 'string'],
            [['arrList'], 'safe']
        ];
    }

    /**
     * 购物订单列表
     */
    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $query = GwdBuyList::find()->where([
                'store_id' => $this->getCurrentStoreId(),
                'is_delete' => 0,
            ]);

            if (!empty($this->orderType) || $this->orderType === '0') {
                $query->andWhere(['type' => $this->orderType]);
            }

            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10]);

            $list = $query->orderBy('addtime DESC')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            foreach ($list as $k => $item) {
                if ($item['type'] == GwdBuyList::TYPE_STORE) {
                    $order = Order::find()->with('orderDetail')->where(['id' => $item['order_id']])->asArray()->one();
                    $list[$k]['order'] = $order;
                }
                if ($item['type'] == GwdBuyList::TYPE_MS) {
                    $order = MsOrder::find()->where(['id' => $item['order_id']])->asArray()->one();
                    $good = MsGoods::find()->where(['id' => $order['goods_id']])->select('id,name')->asArray()->one();
                    $list[$k]['order'] = $order;
                    $list[$k]['order']['orderDetail'][] = [
                        'id' => 1,
                        'order_id' => $order['id'],
                        'goods_id' => $order['goods_id'],
                        'num' => $order['num'],
                        'total_price' => $order['total_price'],
                        'addtime' => $order['addtime'],
                        'is_delete' => $order['is_delete'],
                        'attr' => $order['attr'],
                        'pic' => $order['pic'],
                        'name' => $good['name'],
                    ];
                }

                if ($item['type'] == GwdBuyList::TYPE_PT) {
                    $order = PtOrder::find()->with('orderDetail')->where(['id' => $item['order_id']])->asArray()->one();
                    $list[$k]['order'] = $order;
                }

                $user = User::find()->where(['id' => $item['user_id']])
                    ->select('id,nickname')->asArray()->one();

                $list[$k]['user'] = $user;
            }

            return [
                'list' => $list,
                'pagination' => $pagination,
            ];

        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }
    }


    public function getOrderList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $this->orderType = $this->orderType ? $this->orderType : 0;

        if ($this->orderType == GwdBuyList::TYPE_STORE) {
            $query = Order::find()->alias('o')->joinWith(['orderDetail' => function ($query) {
                if ($this->keyword) {
                    $query->andWhere(['like', 'g.name', $this->keyword]);
                }
            }]);
        } elseif ($this->orderType == GwdBuyList::TYPE_MS) {
            $query = MsOrder::find()->alias('o')->joinWith(['good' => function ($query) {
                if ($this->keyword) {
                    $query->alias('g')->andWhere(['like', 'g.name', $this->keyword]);
                }
            }]);
        } elseif ($this->orderType == GwdBuyList::TYPE_PT) {
            $query = PtOrder::find()->alias('o')->joinWith(['orderDetail' => function ($query) {
                if ($this->keyword) {
                    $query->andWhere(['like', 'g.name', $this->keyword]);
                }
            }]);
        } else {
            return [
                'code' => 1,
                'msg' => '订单类型不存在'
            ];
        }
        if ($this->keyword) {
            $query->andWhere(['like', 'o.order_no', $this->keyword]);
        }

        $query->andWhere([
            'o.store_id' => $this->getCurrentStoreId(),
            'o.is_delete' => 0,
            'o.is_pay' => 1,
        ])
            ->andWhere(['>=', 'o.addtime', time() - (90 * 24 * 60 * 60)])
            ->leftJoin(['b' => GwdBuyList::tableName()], 'b.order_id=o.id AND b.is_delete=0 AND b.type=' . $this->orderType)
            ->andWhere('ISNULL(b.id)');

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10]);

        $list = $query->orderBy('o.addtime DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        foreach ($list as $k => $item) {
            if ($this->orderType == GwdBuyList::TYPE_MS) {
                $good = MsGoods::find()->where(['id' => $item['goods_id']])->select('id,name,attr')->one();
                $list[$k]['orderDetail'][] = [
                    'id' => 1,
                    'order_id' => $item['id'],
                    'goods_id' => $item['goods_id'],
                    'num' => $item['num'],
                    'total_price' => $item['total_price'],
                    'addtime' => $item['addtime'],
                    'is_delete' => $item['is_delete'],
                    'attr' => $item['attr'],
                    'pic' => $item['pic'],
                    'name' => $good->name,
                    'goods_attr' => $good->attr,
                ];
            }
        }

        return [
            'list' => $list,
            'pagination' => $pagination,
        ];
    }

    public function store()
    {
        try {
            if (!$this->validate()) {
                return $this->getErrorResponse();
            }

            if ($this->orderType == GwdBuyList::TYPE_STORE) {
                $query = Order::find();
            } elseif ($this->orderType == GwdBuyList::TYPE_MS) {
                $query = MsOrder::find();
            } elseif ($this->orderType == GwdBuyList::TYPE_PT) {
                $query = PtOrder::find();
            } else {
                throw new \Exception('订单类型不存在');
            }

            $order = $query->where(['id' => $this->id, 'store_id' => $this->getCurrentStoreId()])->one();

            $wechatAccessToken = $this->getWechat()->getAccessToken();
            $res = CommonShoppingList::buyList($wechatAccessToken, $order, $this->orderType);

            if ($res->errorCode == 0) {
                return [
                    'code' => 0,
                    'msg' => '已同步到微信好物圈'
                ];
            }

            throw new \Exception('同步好物圈失败');

        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => '导入失败：该订单不符合条件,' . $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function batch()
    {
        try {
            if (!is_array($this->arrList)) {
                throw new \Exception('导入订单数据类型必须是数组');
            }

            $successCount = 0;
            $wechatAccessToken = $this->getWechat()->getAccessToken();
            foreach ($this->arrList as $item) {
                if ($this->orderType == GwdBuyList::TYPE_STORE) {
                    $query = Order::find();
                } elseif ($this->orderType == GwdBuyList::TYPE_MS) {
                    $query = MsOrder::find();
                } elseif ($this->orderType == GwdBuyList::TYPE_PT) {
                    $query = PtOrder::find();
                } else {
                    throw new \Exception('订单类型不存在');
                }

                $order = $query->where(['id' => $item, 'store_id' => $this->getCurrentStoreId()])->one();

                if (!$order) {
                    continue;
                }
                $res = CommonShoppingList::buyList($wechatAccessToken, $order, $this->orderType);
                if ($res->errorCode == 0) {
                    $successCount += 1;
                }
            }

            return [
                'code' => 0,
                'msg' => '操作成功,总共：' . count($this->arrList) . '条,成功：' . $successCount . '条,失败:' . ((int)count($this->arrList) - (int)$successCount) . '条'
            ];

        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function destroy()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        if ($this->orderType == GwdBuyList::TYPE_STORE) {
            $query = Order::find();
        } elseif ($this->orderType == GwdBuyList::TYPE_MS) {
            $query = MsOrder::find();
        } elseif ($this->orderType == GwdBuyList::TYPE_PT) {
            $query = PtOrder::find();
        } else {
            return [
                'code' => 1,
                'msg' => '订单类型不存在'
            ];
        }

        $order = $query->where(['id' => $this->id, 'store_id' => $this->getCurrentStoreId()])->one();

        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在'
            ];
        }

        $user = User::find()->where(['id' => $order->user_id])->select('id,wechat_open_id')->one();

        if (!$user) {
            return [
                'code' => 1,
                'msg' => '用户不存在'
            ];
        }

        $gwd = GwdBuyList::find()->where([
            'store_id' => $this->getCurrentStoreId(),
            'user_id' => $user->id,
            'order_id' => $order->id,
            'is_delete' => 0,
        ])->one();

        if (!$gwd) {
            return [
                'code' => 1,
                'msg' => '商城好物圈不存在'
            ];
        }

        $wechatAccessToken = $this->getWechat()->getAccessToken();
        $res = CommonShoppingList::destroyBuyGood($wechatAccessToken, $order, $this->orderType, $user);

        if (!$res) {
            return [
                'code' => 1,
                'msg' => '微信端好物圈删除失败'
            ];
        }

        $gwd->is_delete = 1;
        $res = $gwd->save();

        if (!$res) {
            return [
                'code' => 1,
                'msg' => '商城好物圈删除失败'
            ];
        }

        return [
            'code' => 0,
            'msg' => '好物圈删除成功'
        ];
    }

    public function updateOrderStatus()
    {
        try {
            if (!$this->validate()) {
                return $this->getErrorResponse();
            }

            if ($this->orderType == GwdBuyList::TYPE_STORE) {
                $query = Order::find();
            } elseif ($this->orderType == GwdBuyList::TYPE_MS) {
                $query = MsOrder::find();
            } elseif ($this->orderType == GwdBuyList::TYPE_PT) {
                $query = PtOrder::find();
            } else {
                throw new \Exception('订单类型不存在');
            }

            $order = $query->where(['id' => $this->id, 'store_id' => $this->getCurrentStoreId()])->one();

            $status = CommonShoppingList::getOrderStatus($order);

            $wechatAccessToken = $this->getWechat()->getAccessToken();
            $res = CommonShoppingList::updateBuyGood($wechatAccessToken, $order, $this->orderType, $status);

            if ($res->errorCode == 0) {
                return [
                    'code' => 0,
                    'msg' => '已更新到微信好物圈'
                ];
            }

            throw new \Exception('更新好物圈失败');

        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => '更新失败,' . $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }
}
