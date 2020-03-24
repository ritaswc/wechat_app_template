<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/13
 * Time: 14:26
 */

namespace app\modules\mch\models;

use app\models\ActionLog;
use app\models\Model;
use yii\data\Pagination;

class ActionLogForm extends MchModel
{
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page', 'limit'], 'integer'],
            [['page',], 'default', 'value' => 1,],
            [['limit',], 'default', 'value' => 20,],
        ];
    }

    public function getActionLogList()
    {
        $query = ActionLog::find()->where([
            'is_delete' => Model::IS_DELETE_FALSE,
            'store_id' => $this->getCurrentStoreId(),
            'type' => 0,
        ]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = $query->orderBy('addtime DESC')->limit($pagination->limit)->offset($pagination->offset)
            ->all();

        return [
            'list' => $this->transformData($list),
            'pagination' => $pagination
        ];
    }

    public function transformData($list)
    {
        foreach ($list as $item) {
            switch ($item->action_type) {
                case 'INSERT':
                    $item['action_type'] = '添加';
                    break;
                case 'UPDATE':
                    $item['action_type'] = '更新';
                    break;
                case 'DESTROY':
                    $item['action_type'] = '删除';
                    break;
                case 'LOGIN':
                    $item['action_type'] = '登录';
                    break;
                default:
                    $item['action_type'] = '未知';
                    break;
            }
        }

        return $list;
    }
}
