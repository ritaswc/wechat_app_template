<?php

namespace app\modules\mch\models;

use app\models\Area;
use yii\data\Pagination;

/**
 * @property \app\models\Area $area
 */
class ArticleForm extends MchModel
{
    public $model;
    public $store_id;

    public $content;
    public $article_cat_id;
    public $sort;
    public $title;

    public function rules()
    {
        return [
            [['sort', 'title',], 'required'],
            [['store_id', 'article_cat_id'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['sort'], 'integer', 'min' => 0, 'max' => 99999999],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'article_cat_id' => '分类id：1=关于我们，2=服务中心',
            'title' => '标题',
            'content' => '内容',
            'sort' => '排序：升序',
            'is_delete' => 'Is Delete',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $model = $this->model;
        $model->attributes = $this->attributes;
        $model->store_id = $this->store_id;
        $model->article_cat_id = $this->article_cat_id;

        if($model->isNewRecord){
            $model->addtime = time();
        }
        if ($model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }

    }

}
