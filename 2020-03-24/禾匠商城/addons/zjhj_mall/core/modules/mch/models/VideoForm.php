<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14
 * Time: 14:22
 */

namespace app\modules\mch\models;

use app\models\Video;
use yii\data\Pagination;

/**
 * @property \app\models\Video $video;
 */
class VideoForm extends MchModel
{

    public $video;
    public $store_id;
    public $title;
    public $sort;
    public $url;
    public $pic_url;
    public $content;
    public $type;

    public function rules()
    {
        return [
            [['url','pic_url','title'],'required'],
            [['store_id','type'],'integer'],
            [['url','pic_url'],'string'],
            [['title'], 'string', 'max' => 255],
            [['content'],'string','max'=>100],
            [['sort'],'integer','min'=>0,'max'=>999999],
            [['sort'],'default','value'=>100]
        ];
    }
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'store_id' => '商城id',
            'url' => '视频',
            'title' => '标题',
            'sort' => '排序',
            'is_delete' => '是否删除：0=未删除，1=已删除',
            'pic_url' => '封面图片',
            'content' => '详情介绍',
        ];
    }

    /**
     * 获取列表
     * @return array
     */
    public function getList($store_id)
    {
        $query = Video::find()->andWhere(['is_delete' => 0, 'store_id' => $store_id]);
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 20]);
        $list = $query
            ->orderBy(['(sort+0)'=>SORT_ASC])
            ->offset($p->offset)
            ->limit($p->limit)
            ->asArray()
            ->all();

        return [$list, $p];
    }
    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $video = $this->video;
        if ($video->isNewRecord) {
            $video->is_delete = 0;
            $video->addtime = time();
        }
        $video->attributes = $this->attributes;
        if ($video->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return [
                'code'=>1,
                'msg'=>'失败'
            ];
        }
    }
}
