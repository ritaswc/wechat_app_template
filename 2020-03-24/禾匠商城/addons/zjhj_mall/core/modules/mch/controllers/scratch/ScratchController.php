<?php
namespace app\modules\mch\controllers\scratch;

use app\models\Coupon;
use app\models\Goods;
use app\models\AttrGroup;
use app\modules\mch\models\ScratchForm;
use app\modules\mch\models\ScratchSettingForm;
use app\modules\mch\controllers\Controller;
use app\models\Scratch;
use app\models\Attr;
use app\models\ScratchSetting;

use yii\data\Pagination;

class ScratchController extends Controller
{
    //奖品列表
    public function actionIndex()
    {
        $query = Scratch::find()
            ->where([
                'store_id' => $this->store->id,
                'is_delete' => 0
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
            }]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('id desc')->all();
        $list =  $this->simplifyData($list)?$this->simplifyData($list):[];

        foreach($list as $k=>$v){
            if ($v['type']==4 && $v['gift_id']) {
                $attrs = json_decode($v['attr'],true);
                $name = '';
                foreach($attrs as $v1){
                    $name .= $v1['attr_group_name'].':'.$v1['attr_name'].';';
                }
                $list[$k]['attrs'] = $name;
            }   
        }
        return $this->render('index',[
            'list' => $list,
            'pagination' => $pagination
        ]);
    }

    //增加
    public function actionEdit($id = null)
    {
        ///删除编辑
        /* $query = Scratch::find()
            ->where([
                'store_id' => $this->store->id,
                'is_delete' => 0,
                'id' =>$id
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
            }]);

        $list = $this->simplifyData($query->all())?$this->simplifyData($query->all())[0]:[];

        if ($list['type']==4 && $list['gift_id']) {
            $list['attr'] = json_decode($list['attr'],true);
            $attrs = $this->actionAttr($list['gift_id'])['data']['attr'];
        }
        */
        $attrs = [];
        $list = [];
        $model = Scratch::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
        ]);
        if (!$model) {
            $model = new Scratch();
        }
        $coupons = Coupon::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->orderBy('sort ASC')->all();


        if (\Yii::$app->request->isPost) {
            $post = json_decode(\Yii::$app->request->post()['data'],true);
            $post['attr'] = \Yii::$app->serializer->encode($post['attr']);
            $form = new ScratchForm();
            $form->attributes = $post;
            $form->store_id = $this->store->id;
            $form->model = $model;

            if ($form->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return (new Model())->getErrorResponse($model);
            }


        }else{
            if(empty($list)){
                $list['type'] = 1;
                $list['status'] = 0;
            }
            return $this->render('edit', [
                'coupons' => $coupons,
                'list' => $list,
                'attrs' => $attrs
            ]);


        }
    }

    //修改
    public function actionStock()
    {
        $post = \Yii::$app->request->post();
        $form = Scratch::findOne([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'id' =>$post['id']
        ]);
        if(!empty($form)){
            if($post['stock']!==null){
               $form->stock = $post['stock']; 
            }
            if($post['status']!==null){
                $form->status = $post['status']; 
            }
            $form->update_time = time();
            if($form->save()){
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            }else{
                return (new Model())->getErrorResponse($form);
            }
        }
  
    }

    ////规格查询
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

    //查找商品
    public function actionSearchGoods($keyword)
    {
        $keyword = trim($keyword);
        $query = Goods::find()->alias('c')->where([
            'AND',
            ['LIKE', 'c.name', $keyword],
            ['store_id' => $this->store->id, 'is_delete' => 0, 'status' => 1],
        ]);
        $list = $query->orderBy('c.name')->limit(30)->asArray()->select('id,cover_pic,name')->all();
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => (object)[
                'list' => $list,
            ],
        ];
    }


    //格式化
    protected function simplifyData($data)
    {
        foreach ($data as $key => $val) {
            $newData[$key] = $val->attributes;
            if ($val->gift) {
                $newData[$key]['gift'] = $val->gift->attributes['name'];
            }
            if ($val->coupon) {
                $newData[$key]['coupon'] = $val->coupon->attributes['name'];
            }
        }
        return $newData;
    }

    //删除
    public function actionDestroy($id)
    {
        $model = Scratch::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
        ]);
        if ($model) {
            $model->is_delete = 1;
            $model->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    //配置
    public function actionSetting(){
        $setting = ScratchSetting::findOne(['store_id' => $this->store->id]);

        if (!$setting) {
            $setting = new ScratchSetting();
        }
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $post['start_time'] = strtotime($post['start_time']);
            $post['end_time'] = strtotime($post['end_time']);

            $form = new ScratchSettingForm();
            $form->store_id = $this->store->id;
            $form->model = $setting;
            $form->attributes = $post;
            return $form->save();
        } else {
            return $this->render('setting', [
                'setting' => $setting,
            ]);
        }
    }
}
