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
use app\models\GwdLikeList;
use app\models\GwdLikeUser;
use app\models\User;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class LikeListForm extends MchModel
{
    public $type;
    public $keyword;
    public $id;
    public $arrList;
    public $like_id;

    public function rules()
    {
        return [
            [['id', 'type', 'like_id'], 'integer'],
            [['keyword'], 'string'],
            [['arrList'], 'safe']
        ];
    }

    /**
     * 想买购物订单列表
     */
    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $query = GwdLikeList::find()->alias('l')->where([
                'l.store_id' => $this->getCurrentStoreId(),
                'l.is_delete' => 0,
            ]);

            if ($this->keyword) {
                $query->andWhere(['like', 'name', $this->keyword]);
            }

            $userCount = GwdLikeUser::find()->alias('gu')->where('l.id=gu.like_id')->andWhere('gu.is_delete=0')->select('count(1)');

            $query->rightJoin(['g' => Goods::tableName()], 'g.id=l.good_id')
//                ->select('l.*,g.id AS good_id,g.name,g.cover_pic,g.price,g.goods_num,g.status');
                ->select(['l.*', 'good_id' => 'g.id', 'g.name', 'g.cover_pic', 'g.price', 'g.goods_num', 'g.status',
                    'user_count' => $userCount
                ]);

            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10]);

            $list = $query->orderBy(['user_count' => SORT_DESC, 'l.addtime' => SORT_DESC])
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

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

    public function getDetail()
    {
        $query = GwdLikeUser::find()->alias('glu')->where([
            'glu.like_id' => $this->id,
            'glu.is_delete' => 0,
        ]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 20]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->joinWith(['user' => function($query) {
                if ($this->keyword) {
                    $query->andWhere(['like', 'nickname', $this->keyword]);
                }
            }])
            ->asArray()
            ->all();

        return [
            'list' => $list,
            'pagination' => $pagination,
        ];
    }


    public function addGood()
    {
        try {
            if (!$this->validate()) {
                return $this->getErrorResponse();
            }

            $gwd = new GwdLikeList();
            $gwd->store_id = \Yii::$app->store->id;
            $gwd->good_id = $this->id;
            $gwd->addtime = (string)time();
            $gwd->type = 0; // 0.商城商品
            $res = $gwd->save();

            if (!$res) {
                \Yii::error($gwd);
                throw new \Exception('添加商品失败x01');
            }

            return [
                'code' => 0,
                'msg' => '添加商品成功'
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => '添加失败：,' . $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function destroyUser()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $gwdUser = GwdLikeUser::find()->where([
                'id' => $this->id,
                'is_delete' => 0,
            ])->one();

            if (!$gwdUser) {
                throw new \Exception('想买用户不存在');
            }

            $gwdLike = GwdLikeList::find()->where(['id' => $gwdUser->like_id])->one();
            if (!$gwdLike) {
                throw new \Exception('商品记录不存在');
            }


            $data[] = [
                'user_id' => $gwdUser->user_id,
                'good_id' => $gwdLike->good_id,
                'like_id' => $gwdUser->like_id,
            ];

            $wechatAccessToken = $this->getWechat()->getAccessToken();
            $res = CommonShoppingList::destroyCartGood($wechatAccessToken, $data, $this->getCurrentStoreId());

            if ($res->errorCode == 0) {
                return [
                    'code' => 0,
                    'msg' => '删除成功'
                ];
            }

            return [
                'code' => 0,
                'msg' => '删除失败x01'
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

    public function destroyGood()
    {
        $gwdUser = GwdLikeUser::find()->where([
            'like_id' => $this->id,
            'is_delete' => 0,
        ])->all();

        if (count($gwdUser) > 0) {
            return [
                'code' => 1,
                'msg' => '该商品还有用户未删除，请先删除用户'
            ];
        }

        $gwdLike = GwdLikeList::find()->where(['id' => $this->id])->one();
        if (!$gwdLike) {
            return [
                'code' => 1,
                'msg' => '该条记录不存在'
            ];
        }

        $gwdLike->is_delete = 1;
        $res = $gwdLike->save();

        if (!$res) {
            return [
                'code' => 1,
                'msg' => '删除失败'
            ];
        }

        return [
            'code' => 0,
            'msg' => '删除成功'
        ];
    }

    public function goods()
    {
        $query = Goods::find()->alias('g')->where([
            'g.store_id' => $this->getCurrentStoreId(),
            'g.is_delete' => 0,
            'g.status' => 1,
        ]);

        if ($this->keyword) {
            $query->andWhere(['like', 'g.name', $this->keyword]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->leftJoin(['l' => GwdLikeList::tableName()], 'l.good_id=g.id AND l.is_delete=0')
            ->andWhere('ISNULL(l.id)')
            ->asArray()
            ->all();

        return [
            'code' => 0,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
            ]
        ];
    }

    public function goodIds()
    {
        $list = Goods::find()->alias('g')
            ->where([
                'g.store_id' => $this->getCurrentStoreId(),
                'g.status' => 1,
                'g.is_delete' => 0
            ])
            ->leftJoin(['l' => GwdLikeList::tableName()], 'l.good_id=g.id AND l.is_delete = 0')
            ->andWhere('ISNULL(l.id)')
            ->select('g.id')->asArray()->all();

        return [
            'code' => 0,
            'msg' => '请求成功',
            'data' => [
                'list' => $list
            ]
        ];
    }

    public function getUsers()
    {
        $query = GwdLikeUser::find()->where([
            'like_id' => $this->id,
            'is_delete' => 0,
        ]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->with('user')
            ->asArray()
            ->all();


        return [
            'list' => $list,
            'pagination' => $pagination,
        ];
    }

    public function getUserList()
    {
        $query = User::find()->alias('u')->andWhere([
            'u.store_id' => $this->getCurrentStoreId(),
            'u.is_delete' => 0,
            'u.type' => 1,
        ]);

        if ($this->keyword) {
            $query->andWhere(['like', 'nickname', $this->keyword]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->leftJoin(['l' => GwdLikeUser::tableName()], 'l.user_id=u.id AND l.is_delete = 0 AND l.like_id=' . $this->id)
            ->andWhere('ISNULL(l.id)')
            ->asArray()
            ->all();

        return [
            'code' => 0,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination,
            ],
        ];
    }

    public function addUser()
    {
        try {
            $gwd = GwdLikeList::findOne($this->like_id);
            if (!$gwd) {
                throw new \Exception('此条想买好物圈数据不存在');
            }

            $user = User::find()->where(['id' => $this->id])->select('id,wechat_open_id,store_id')->one();
            if (!$user) {
                throw new \Exception('用户不存在');
            }


            $wechatAccessToken = $this->wechat->getAccessToken();
            $cart = (object)[
                'goods_id' => $gwd->good_id,
                'attr' => [],
                'addtime' => time(),
            ];

            $res = CommonShoppingList::cartList($wechatAccessToken, $cart, $user);

            if ($res->errorCode == 0) {
                return [
                    'code' => 0,
                    'msg' => '添加成功',
                ];
            }
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

    public function allUser()
    {
        $query = User::find()->alias('u')->andWhere([
            'u.store_id' => $this->getCurrentStoreId(),
            'u.is_delete' => 0,
            'u.type' => 1,
        ]);

        if ($this->keyword) {
            $query->andWhere(['like', 'nickname', $this->keyword]);
        }

        $list = $query->leftJoin(['l' => GwdLikeUser::tableName()], 'l.user_id=u.id AND l.like_id=' . $this->id)
            ->andWhere('ISNULL(l.id)')
            ->select('u.id')
            ->limit(50)
            ->asArray()
            ->all();

        return [
            'code' => 0,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
            ],
        ];
    }

    public function getLikeUsers()
    {
        $query = GwdLikeUser::find()->where([
            'like_id' => $this->id,
        ])->with('user');

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 30]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return [
            'code' => 0,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'modal_list' => [
                    'count' => $pagination->totalCount,
                    'page_count' => $pagination->pageCount,
                    'page' => $pagination->page + 1,
                    'page_url' => \Yii::$app->urlManager->createUrl(['mch/gwd/like-list/like-users']),
                ],
            ],
        ];
    }
}
