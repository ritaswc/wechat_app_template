<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/29
 * Time: 18:57
 */

namespace app\modules\api\models;

use app\models\TopicFavorite;

class TopicFavoriteForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $topic_id;
    public $action;

    public function rules()
    {
        return [
            [['topic_id', 'action'], 'required'],
            [['action'], 'in', 'range' => [0, 1]],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if ($this->action == 0) {
            TopicFavorite::updateAll([
                'is_delete' => 1
            ], [
                'user_id' => $this->user_id,
                'topic_id' => $this->topic_id,
                'is_delete' => 0,
                'store_id' => $this->store_id,
            ]);
            return [
                'code' => 0,
                'msg' => '已取消收藏',
            ];
        }
        $favorite = TopicFavorite::findOne([
            'user_id' => $this->user_id,
            'topic_id' => $this->topic_id,
            'is_delete' => 0,
            'store_id' => $this->store_id,
        ]);
        if ($favorite) {
            return [
                'code' => 0,
                'msg' => '收藏成功',
            ];
        }
        $favorite = new TopicFavorite();
        $favorite->attributes = $this->attributes;
        $favorite->addtime = time();
        if ($favorite->save()) {
            return [
                'code' => 0,
                'msg' => '收藏成功',
            ];
        }
        return $this->getErrorResponse($favorite);
    }
}
