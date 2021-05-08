<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\diy;


use app\models\DiyPage;
use app\models\DiyTemplate;
use app\models\Goods;
use app\models\MiaoshaGoods;
use app\models\Model;
use app\models\MsGoods;
use app\modules\mch\models\MchModel;
use app\utils\DiyGoods;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class DiyTemplateForm extends MchModel
{
    public $limit;
    public $page;
    public $id;

    public function rules()
    {
        return [
            [['limit', 'page', 'id'], 'integer'],
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function getList()
    {
        $query = DiyTemplate::find()->where([
            'is_delete' => Model::IS_DELETE_FALSE,
            'store_id' => $this->getCurrentStoreId()
        ]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)
            ->asArray()->all();


        if ($list) {
            return [
                'list' => $list,
                'pagination' => $pagination
            ];
        }
    }

    /**
     * 模板详情
     * @return \app\hejiang\ValidationErrorResponse|array
     */
    public function detail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $detail = DiyTemplate::find()->where([
            'is_delete' => Model::IS_DELETE_FALSE,
            'store_id' => $this->getCurrentStoreId(),
            'id' => $this->id,
        ])->asArray()->one();
        $data['modules_list'] = $this->getModules();
        if (!$detail) {
            return [
                'code' => 1,
                'msg' => '模板不存在',
                'data' => $data,
            ];
        }

        if (!$detail['template']) {
            $detail['template'] = [];
        }
        $detail['template'] = ($detail['template'] && $detail['template'] != 'null') ? json_decode($detail['template'], true) : [];
        foreach ($detail['template'] as &$value) {
            if ($value['type'] == 'float') {
                $value['param']['web_service_url'] = urldecode($value['param']['web_service_url']);
            }
        }
        DiyGoods::getTemplate($detail['template']);
        $data['detail'] = $detail;
        return [
            'code' => 0,
            'msg' => '请求成功',
            'data' => $data,
        ];
    }

    // 删除模板
    public function delete()
    {
        $detail = DiyTemplate::findOne($this->id);
        if (!$detail) {
            return [
                'code' => 1,
                'msg' => '模板不存在！'
            ];
        }

        $diyPage = DiyPage::find()->where(['template_id' => $detail->id, 'is_delete' => Model::IS_DELETE_FALSE])->one();
        if ($diyPage) {
            return [
                'code' => 1,
                'msg' => '模板有页面使用，无法删除！'
            ];
        }

        $detail->is_delete = 1;
        if ($detail->save()) {
            return [
                'code' => 0,
                'msg' => '删除成功！'
            ];
        }

    }

    private function getModules()
    {
        $default =  [
            [
                'id' => 'base',
                'name' => '基础组件',
                'class' => 'pb-4',
                'sub' =>
                    [

                        [
                            'name' => '搜索',
                            'type' => 'search',
                        ],

                        [
                            'name' => '导航图标',
                            'type' => 'nav',
                        ],

                        [
                            'name' => '轮播广告',
                            'type' => 'banner',
                        ],

                        [
                            'name' => '公告',
                            'type' => 'notice',
                        ],

                        [
                            'name' => '专题',
                            'type' => 'topic',
                        ],

                        [
                            'name' => '关联链接',
                            'type' => 'link',
                        ],

                        [
                            'name' => '图片广告',
                            'type' => 'rubik',
                        ],

                        [
                            'name' => '视频',
                            'type' => 'video',
                        ],

                        [
                            'name' => '商品',
                            'type' => 'goods',
                        ],

                        [
                            'name' => '门店',
                            'type' => 'shop',
                        ],
                    ],
            ],
            [
                'id' => 'auth',
                'name' => '营销组件',
                'class' => 'pb-4',
                'sub' =>
                    [

                        [
                            'name' => '优惠券',
                            'type' => 'coupon',
                        ],

                        [
                            'name' => '倒计时',
                            'type' => 'time',
                        ],

                        [
                            'name' => '拼团',
                            'type' => 'pintuan',
                        ],

                        [
                            'name' => '秒杀',
                            'type' => 'miaosha',
                        ],

                        [
                            'name' => '预约',
                            'type' => 'book',
                        ],

                        [
                            'name' => '好店推荐',
                            'type' => 'mch',
                        ],

                        [
                            'name' => '砍价',
                            'type' => 'bargain',
                        ],

                        [
                            'name' => '积分商城',
                            'type' => 'integral',
                        ],

                        [
                            'name' => '抽奖',
                            'type' => 'lottery',
                        ],
                    ],
            ],
            [
                'id' => 'other',
                'class' => 'pb-4',
                'name' => '其他组件',
                'sub' =>
                    [
                        [
                            'name' => '空白占位',
                            'type' => 'line',
                        ],
                        [
                            'name' => '流量主',
                            'type' => 'ad',
                        ],
                        [
                            'name' => '弹窗广告',
                            'type' => 'modal',
                        ],
                        [
                            'name' => '快捷导航',
                            'type' => 'float',
                        ],
                    ],
            ],
        ];
        $plugin = DiyGoods::getDiyAuth();

        // 去除无权限的组件
        $list = [];
        foreach ($default as $key) {
            if($key['id'] == 'auth') {
                $item = [];
                foreach ($key['sub'] as $value) {
                    if(in_array($value['type'], $plugin)) {
                        $item[] = $value;
                    }
                }
                $key['sub'] = $item;
            }
            $list[] = $key;
        }
        return $list;
    }
}