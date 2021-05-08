<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:01
 */

namespace app\modules\mch\models;

use app\models\Cat;
use yii\data\Pagination;

class CatForm extends MchModel
{
    public $cat;

    public $store_id;
    public $parent_id;
    public $name;
    public $pic_url;
    public $big_pic_url;
    public $sort;
    public $advert_pic;
    public $advert_url;
    public $is_show;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'store_id', 'parent_id'], 'required'],
            [['store_id', 'is_show'], 'integer'],
            [['pic_url', 'big_pic_url', 'advert_pic', 'advert_url'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['sort'],'default','value'=>100],
            [['sort'],'integer','min'=>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '分类名称',
            'pic_url' => '分类图片url',
            'sort' => '排序',
            'advert_pic' => '广告图片',
            'advert_url' => '广告链接',
            'is_show' => '是否显示',
        ];
    }

    /**
     * @param $store_id
     * @return array
     * 获取列表数据
     */
    public function getList($store_id)
    {
        $query = Cat::find()->andWhere(['is_delete' => 0, 'store_id' => $store_id]);
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 20]);
        $list = $query
            ->orderBy('sort ASC')
            ->offset($p->offset)
            ->limit($p->limit)
            ->asArray()
            ->all();

        return [$list, $p];
    }

    /**
     * 编辑
     * @return array
     */
    public function save()
    {
        if ($this->validate()) {
            $parent_cat_exist = true;
            if ($this->parent_id) {
                $parent_cat_exist = Cat::find()->where([
                    'id' => $this->parent_id,
                    'store_id' => $this->store_id,
                    'is_delete' => 0,
                ])->exists();
            }
            if (!$parent_cat_exist) {
                return [
                    'code' => 1,
                    'msg' => '上级分类不存在，请重新选择'
                ];
            }
            $cat = $this->cat;
            if ($cat->isNewRecord) {
                $cat->is_delete = 0;
                $cat->addtime = time();
            }
            $cat->attributes = $this->attributes;
            return $cat->saveCat();
        } else {
            return $this->errorResponse;
        }
    }
}
