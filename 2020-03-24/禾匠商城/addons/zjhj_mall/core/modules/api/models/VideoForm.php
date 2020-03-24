<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14
 * Time: 14:53
 */

namespace app\modules\api\models;

use app\utils\GetInfo;
use app\hejiang\ApiResponse;
use app\models\Video;
use yii\data\Pagination;

class VideoForm extends ApiModel
{

    public $store_id;
    public $page;
    public $limit;
    public function rules()
    {
        return [
            [['page', 'limit',], 'integer'],
            [['page',], 'default', 'value' => 1],
            [['limit',], 'default', 'value' => 10],
        ];
    }
    public function getList()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Video::find()->where(['is_delete'=>0,'store_id'=>$this->store_id]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)
            ->offset($pagination->offset)
            ->orderBy(['(sort+0)'=>SORT_ASC,'addtime'=>SORT_DESC])
            ->asArray()->all();
        foreach ($list as $index => $value) {
            $list[$index]['time'] = date('m月d日', $value['addtime']);
            $list[$index]['show'] = -1;
            if ($value['type'] == 1) {
                $res = GetInfo::getVideoInfo($value['url']);
                $list[$index]['url'] = $res['url'];
            }
            $list[$index]['title'] = $value['title'] ? $value['title'] : '未命名';
            $list[$index]['content'] = $value['content'] ? $value['content'] : '暂无信息';
        }
        $data = [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'list' => $list,
        ];
        return new ApiResponse(0, 'success', $data);
    }
}
