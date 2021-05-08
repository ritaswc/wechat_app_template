<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/27
 * Time: 19:32
 */

namespace app\modules\mch\models;

use app\models\Topic;

/**
 * @property Topic $model
 */
class TopicEditForm extends MchModel
{
    public $model;

    public $store_id;
    public $title;
    public $sub_title;
    public $cover_pic;
    public $content;
    public $virtual_read_count;
    public $virtual_agree_count;
    public $virtual_favorite_count;
    public $layout;
    public $sort;
    public $type;
    public $is_chosen;
    public $qrcode_pic;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'cover_pic','is_chosen','type', 'qrcode_pic'], 'required'],
            [['virtual_read_count', 'virtual_agree_count', 'virtual_favorite_count', 'sort',], 'integer','min'=>0 ,'max'=>99999999],
            [['cover_pic', 'qrcode_pic', 'content'], 'string'],
            [['title', 'sub_title'], 'string', 'max' => 255],
            [['virtual_read_count', 'virtual_agree_count', 'virtual_favorite_count',], 'default', 'value' => 0],
            [['sort'], 'default', 'value' => 1000],
            [['layout'],'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'title' => '标题',
            'sub_title' => '副标题',
            'cover_pic' => '封面图片',
            'content' => '专题内容',
            'read_count' => '阅读量',
            'virtual_read_count' => '虚拟阅读量',
            'virtual_agree_count' => '虚拟点赞数',
            'virtual_favorite_count' => '虚拟收藏量',
            'layout' => '布局方式：0=小图，1=大图模式',
            'sort' => '排序：升序',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'type' => '类型',
            'is_chosen' => '精选',
            'qrcode_pic' => '海报分享图',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->model->attributes = $this->attributes;
        $this->model->store_id = $this->store_id;
        
        if ($this->model->isNewRecord) {
            $this->model->read_count = 0;
            $this->model->agree_count = 0;
            $this->model->addtime = time();
        }
        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
