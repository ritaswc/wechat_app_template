<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/10
 * Time: 11:34
 */
namespace app\modules\mch\models\integralmall;

use app\modules\mch\models\MchModel;
use app\models\IntegralCat;
use yii\data\Pagination;

class IntegralCatForm extends MchModel
{
    public $name;
    public $store_id;
    public $pic_url;
    public $sort;
    public $cat;

    public function rules()
    {
        return [
            [['name', 'store_id', 'pic_url'], 'required'],
            [['sort'], 'integer','min'=>0,'max'=>999999],
            [['pic_url'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '分类名称',
            'store_id' => '商城ID',
            'pic_url' => '分类图片url',
            'sort' => '排序',
        ];
    }
    public function save()
    {
        if ($this->validate()) {
            $cat = $this->cat;
            if ($cat->isNewRecord) {
                $cat->is_delete = 0;
                $cat->addtime = time();
            }
            $cat->name = $this->name;
            $cat->pic_url = $this->pic_url;
            $cat->sort = $this->sort;
            $cat->store_id = $this->store_id;
            if ($cat->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return $this->getErrorResponse($cat);
            }
        } else {
            return $this->errorResponse;
        }
    }

    public function getList($store_id)
    {
        $query = IntegralCat::find()
            ->andWhere(['is_delete'=>0,'store_id'=>$store_id]);
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 10]);
        $list = $query->orderBy('sort ASC')
            ->offset($p->offset)
            ->limit($p->limit)
            ->all();
        return [$list, $p];
    }
}
