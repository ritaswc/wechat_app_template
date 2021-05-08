<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/2/28
 * Time: 16:26
 */

namespace app\modules\mch\models\mch;

use app\models\Mch;
use app\models\Model;
use app\models\User;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class MchListForm extends MchModel
{
    public $store_id;
    public $review_status;
    public $page;
    public $limit;
    public $keyword;
    public $platform;//所属平台
    public $mch_id;

    public function rules()
    {
        return [
            [['review_status', 'page', 'limit', 'mch_id'], 'integer'],
            [['keyword',], 'trim'],
            [['page',], 'default', 'value' => 1,],
            [['limit',], 'default', 'value' => 20,],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Mch::find()->alias('m')->leftJoin(['u' => User::tableName()], 'm.user_id=u.id')
            ->where([
                'm.is_delete' => 0,
                'm.store_id' => $this->store_id,
            ]);
        if ($this->keyword) {
            $query->andWhere([
                'OR',
                ['LIKE', 'm.realname', $this->keyword],
                ['LIKE', 'm.tel', $this->keyword],
                ['LIKE', 'm.name', $this->keyword],
                ['LIKE', 'u.nickname', $this->keyword],
            ]);
        }
        if ($this->review_status !== null) {
            $query->andWhere([
                'm.review_status' => $this->review_status,
            ]);
        }
        if (isset($this->platform)) {
            $query->andWhere(['u.platform' => $this->platform]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query->orderBy('m.sort,m.addtime DESC')->limit($pagination->limit)->offset($pagination->offset)
            ->select('m.*,u.nickname,u.platform,u.avatar_url')->asArray()->all();
        return [
            'code' => 0,
            'data' => [
                'list' => $list,
                'pagination' => $pagination,
                'adminUrl' => $this->getAdminUrl('mch')
            ],
        ];
    }

    public function delete()
    {
        $mch = Mch::findOne($this->mch_id);
        $mch->is_delete = Model::IS_DELETE_TRUE;

        if ($mch->save()) {
            return [
                'code' => 0,
                'msg' => '删除成功',
            ];
        }

        return [
            'code' => 1,
            'msg' => '删除失败',
        ];
    }
}
