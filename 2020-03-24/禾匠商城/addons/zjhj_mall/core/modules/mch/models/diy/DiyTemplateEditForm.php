<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\diy;


use app\models\DiyTemplate;
use app\models\Option;
use app\modules\mch\models\MchModel;
use app\utils\DiyGoods;

class DiyTemplateEditForm extends MchModel
{
    public $list;
    public $template_id;
    public $name;
    public $type;


    public function rules()
    {
        return [
            [['name'], 'required'],
            [['template_id', 'type'], 'integer'],
            [['list'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '模板名称'
        ];
    }


    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {

            if ($this->template_id && $this->type == 0) {
                $detail = DiyTemplate::findOne($this->template_id);
                $detail->name = $this->name;
            } else {
                $detail = new DiyTemplate();
                $detail->store_id = $this->getCurrentStoreId();
                $detail->name = $this->name;
                $detail->addtime = time();
            }

            $list = json_decode($this->list, true);
            if(!$list) {
                $list = [];
            }

            // 用户拥有的组件权限
            $plugin = DiyGoods::getDiyAuth();
            $newList = [];
            foreach ($list as $item) {
                if(in_array($item['type'], $plugin)) {
                    $newList[] = $item;
                }
            }
            $list = $newList;

            foreach ($list as &$value) {
                if (in_array($value['type'], ['goods', 'miaosha', 'pintuan', 'bargain', 'book', 'lottery', 'mch', 'integral'])) {
                    foreach ($value['param']['list'] as &$item) {
                        if($item['goods_style'] == 2) {
                            $goodsList = [];
                            foreach ($item['goods_list'] as $v) {
                                $goodsList[] = [
                                    'id' => $v['id'],
                                    'goods_id' => $v['goods_id']
                                ];
                            }
                            $item['goods_list'] = $goodsList;
                        }
                    }
                    unset($item);
                }
                if(in_array($value['type'], ['shop'])) {
                    $shopList = [];
                    foreach ($value['param']['list'] as $item) {
                        $shopList[] = [
                            'id' => $item['id']
                        ];
                    }
                    $value['param']['list'] = $shopList;
                }
                if(in_array($value['type'], ['float'])) {
                    $value['param']['web_service_url'] = urlencode($value['param']['web_service_url']);
                }
            }
            unset($value);

            $data = Option::get('overrun', 0, 'admin', [
                'max_picture' => 1,
                'max_diy' => 20,
                'over_picture' => 0,
                'over_diy' => 0,
            ]);
            $max_diy = $data['max_diy'];
            if ($data['over_diy'] == 1) {
                $max_diy = -1;
            }
            if ($max_diy > 0) {
                array_splice($list, $max_diy);
            }
            $detail->template = json_encode($list,JSON_UNESCAPED_UNICODE);

            if ($detail->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功！',
                    'data' => [
                        'template_id' => $detail->id
                    ]
                ];
            }

        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }
    }
}