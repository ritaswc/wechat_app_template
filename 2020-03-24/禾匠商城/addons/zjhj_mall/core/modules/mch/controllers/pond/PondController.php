<?php
namespace app\modules\mch\controllers\pond;

use app\models\Pond;
use app\models\Coupon;
use app\modules\mch\models\MchModel;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\PondForm;
use app\models\PondSetting;
use app\modules\mch\models\PondSettingForm;
use app\models\Goods;
use app\models\AttrGroup;
use app\models\Attr;

class PondController extends Controller
{
    public function actionIndex()
    {
        $model = PondSetting::findOne([
            'store_id' => $this->store->id
        ]);

        if (!$model) {
            $model = new PondSetting();
        }
        if (\Yii::$app->request->isPost) {
            $form = new PondSettingForm();
            $form->store_id = $this->store->id;
            $form->model = $model;

            $data = \Yii::$app->request->post();

            $form->probability = $data['probability'];
            $form->oppty = $data['oppty'];
            $form->type = $data['type'];
            $form->deplete_register = $data['deplete_register'];
            $form->rule = $data['rule'];
            $form->title = $data['title'];
            $form->start_time = strtotime($data['start_time']);
            $form->end_time = strtotime($data['end_time']);
            return $form->save();
        }

        $list = Pond::find()
            ->where([
                'store_id' => $this->store->id, 
            ])->with(['gift' => function ($query) {
                $query->where([
                    'store_id' => $this->store->id,
                    'is_delete' => 0
                ]);
            }])->with(['coupon' => function ($query) {
                $query->where([
                    'store_id' => $this->store->id,
                    'is_delete' => 0
                ]);
            }])
            ->orderBy('orderby ASC,id ASC')
            ->all();
        return $this->render('index', [
            'list' => $list,
            'setting' => $model
        ]);
    }
 
    //抽奖---公用搜索商品 //TODO
    public function actionSearchGoods($keyword)
    {
        $keyword = trim($keyword);
        $query = Goods::find()->select('id,name,cat_id,price,attr')->where([
            'AND',
            ['LIKE', 'name', $keyword],
            ['mch_id' => 0],
            ['type' => 0],
            ['store_id' => $this->store->id],
            ['is_delete' => 0],
            ['status' => 1],
        ]);
        $list = $query->orderBy('id desc')->limit(30)->asArray()->all();
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => (object) [
                'list' => $list,
            ],
        ];
    }
    public function actionAttr($id)
    {

        $goods = Goods::findOne(['id' => $id, 'store_id' => $this->store->id, 'mch_id' => 0]);

        if (!$goods->attr) {
            return [];
        }
 
        $attr_data = json_decode($goods->attr, true);

        foreach ($attr_data as $i => $attr_data_item) {
            if (!isset($attr_data[$i]['no'])) {
                $attr_data[$i]['no'] = '';
            }
            if (!isset($attr_data[$i]['pic'])) {
                $attr_data[$i]['pic'] = '';
            }
            foreach ($attr_data[$i]['attr_list'] as $j => $attr_list) {
                $attr_group = $this->getAttrGroupByAttId($attr_data[$i]['attr_list'][$j]['attr_id']);
                $t = $attr_data[$i]['attr_list'][$j]['attr_name'];
                unset($attr_data[$i]['attr_list'][$j]['attr_name']);

                $attr_data[$i]['attr_list'][$j]['attr_group_name'] = $attr_group ? $attr_group->attr_group_name : null;
                $attr_data[$i]['attr_list'][$j]['attr_name'] = $t;
            }
        }
         return [
            'code'=>0,
            'data' => [
                'attr' => $attr_data
            ]
         ];
    }
    private function getAttrGroupByAttId($att_id)
    {
        $cache_key = 'get_attr_group_by_attr_id_' . $att_id;
        $attr_group = \Yii::$app->cache->get($cache_key);
        if ($attr_group) {
            return $attr_group;
        }
        $attr_group = AttrGroup::find()->alias('ag')
            ->where(['ag.id' => Attr::find()->select('attr_group_id')->distinct()->where(['id' => $att_id])])
            ->limit(1)->one();
        if (!$attr_group) {
            return $attr_group;
        }
        \Yii::$app->cache->set($cache_key, $attr_group, 10);
        return $attr_group;
    }

    public function actionEdit()
    {
        $coupon = Coupon::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->orderBy('sort ASC')->all();

        $query = Pond::find()
            ->where([
                'store_id' => $this->store->id,
            ])->with(['gift' => function ($query) {
                $query->where([
                    'store_id' => $this->store->id,
                    'is_delete' => 0
                ]);
            }])->with(['coupon' => function ($query) {
                $query->where([
                    'store_id' => $this->store->id,
                    'is_delete' => 0
                ]);
            }])
            ->orderBy('orderby ASC,id ASC');
   
        $count = 8-$query->count();
     
        $list = $this->simplifyData($query->all())?$this->simplifyData($query->all()):[];
        if ($count) {
            for ($i=0; $i<$count; $i++) {
                array_push($list, ['id'=>'','type'=>5,'image_url' => '']);
            }
        }
        $attrs = [];
        foreach ($list as $k => $v) {
            $list[$k]['attr'] = json_decode($v['attr'], true);
            if ($v['type']==4 && $v['gift_id']) {
                $attrs[$k] = $this->actionAttr($v['gift_id'])['data']['attr'];
            }
        }
        return $this->render('edit', [
            'list' => $list,
            'coupon' => $coupon,
            'attrs'=> $attrs,
        ]);
    }

    protected function simplifyData($data)
    {
        foreach ($data as $key => $val) {
            $newData[$key] = $val->attributes;
            if ($val->gift) {
                $newData[$key]['gift'] = $val->gift->attributes['name'];
            }
        }
        return $newData;
    }
    public function actionSubmit()
    {
        $data = \Yii::$app->request->post('data');
    
        $data = json_decode($data, true);

        foreach ($data as $k => $v) {
            $model = Pond::findOne([
            'store_id' => $this->store->id,
            'id' => $v['id'],
            ]);

            if (!$model) {
                $model = new Pond();
            }

            $form = new PondForm();

            $form->store_id = $this->store->id;
            $form->model = $model;
            $form->image_url = $v['image_url'];
            $form->orderby = $k +1;
            $form->type = $v['type'];
            $form->price = $v['price'];
            $form->stock = $v['stock'];
            $form->coupon_id = $v['coupon_id'];
            $form->num = $v['num'];
            $form->name = $v['name'];
            $form->attr =  \Yii::$app->serializer->encode($v['attr']);
       // $form->attr =  $v['attr'];
            $form->gift_id = $v['gift_id'];

            if (!$form->save()) {
                return (new Model())->getErrorResponse($form);
            }
        }
        return [
            'code' => 0,
            'msg' => '保存成功',
        ];
    }
}
