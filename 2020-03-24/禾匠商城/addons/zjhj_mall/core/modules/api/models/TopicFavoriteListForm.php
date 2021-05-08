<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/29
 * Time: 20:06
 */

namespace app\modules\api\models;

use app\models\Topic;
use app\models\TopicFavorite;
use yii\data\Pagination;

class TopicFavoriteListForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $page;

    public function rules()
    {
        return [
            [['page'], 'integer'],
            [['page'], 'default', 'value' => 1],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = TopicFavorite::find()->alias('tf')->leftJoin(['t' => Topic::tableName()], 'tf.topic_id=t.id')
            ->where([
                't.is_delete' => 0,
                'tf.is_delete' => 0,
                'tf.user_id' => $this->user_id,
                't.store_id' => $this->store_id,
            ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('tf.addtime DESC')->select('t.*')->asArray()->all();
        foreach ($list as $i => $item) {
            $read_count = intval($item['read_count'] + $item['virtual_read_count']);
            unset($list[$i]['read_count']);
            unset($list[$i]['virtual_read_count']);
            if ($read_count < 10000) {
                $read_count = $read_count . '人浏览';
            }
            if ($read_count >= 10000) {
                $read_count = intval($read_count / 10000) . '万+人浏览';
            }
            $goods_class = 'class="goods-link"';
            $goods_count = mb_substr_count($item['content'], $goods_class);
            unset($list[$i]['content']);
            $list[$i]['read_count'] = $read_count;
            if ($goods_count) {
                $list[$i]['goods_count'] = $goods_count . '件宝贝';
            }
        }

        return [
            'code' => 0,
            'data' => [
                'list' => $list,
            ],
        ];
    }
}
