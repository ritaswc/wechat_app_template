<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/28
 * Time: 14:11
 */

namespace app\modules\api\models;

use app\utils\GetInfo;
use app\hejiang\ApiResponse;
use app\models\Topic;
use app\models\TopicFavorite;
use yii\helpers\VarDumper;

class TopicForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $id;

    public function rules()
    {
        return [
            ['id', 'required'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $model = Topic::find()->where(['store_id' => $this->store_id, 'id' => $this->id, 'is_delete' => 0])
            ->select('id,title,read_count,virtual_read_count,content,addtime')->asArray()->one();

        if (empty($model)) {
            return new ApiResponse(1, '内容不存在');
        }
        Topic::updateAll(['read_count' => $model['read_count'] + 1], ['id' => $model['id']]);

        $model['read_count'] = intval($model['read_count']) + intval($model['virtual_read_count']);
        unset($model['virtual_read_count']);
        if ($model['read_count'] < 10000) {
            $model['read_count'] = $model['read_count'] . '人浏览';
        }
        if ($model['read_count'] >= 10000) {
            $model['read_count'] = intval($model['read_count'] / 10000) . '万+人浏览';
        }

        $model['addtime'] = date('Y-m-d', $model['addtime']);

        $favorite = TopicFavorite::findOne(['user_id' => $this->user_id, 'topic_id' => $model['id'], 'is_delete' => 0]);
        $model['is_favorite'] = $favorite ? 1 : 0;
        $model['content'] = $this->transTxvideo($model['content']);

        return new ApiResponse(0, 'success', $model);
    }

    private function transTxvideo($content)
    {
        preg_match_all("/https\:\/\/v\.qq\.com[^ '\"]+\.html[^ '\"]*/i", $content, $match_list);
        if (!is_array($match_list) || count($match_list) == 0) {
            return $content;
        }
        $url_list = $match_list[0];
        foreach ($url_list as $url) {
            $res = GetInfo::getVideoInfo($url);
            if ($res['code'] == 0) {
                $new_url = $res['url'];
                $content = str_replace('src="' . $url . '"', 'src="' . $new_url . '"', $content);
            }
        }
        return $content;
    }
}
