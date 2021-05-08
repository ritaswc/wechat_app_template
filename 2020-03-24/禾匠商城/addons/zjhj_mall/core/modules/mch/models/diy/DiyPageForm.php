<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\diy;


use app\models\DiyPage;
use app\models\DiyTemplate;
use app\models\Model;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class DiyPageForm extends MchModel
{
    public $limit;
    public $page;
    public $id;
    public $status;

    public function rules()
    {
        return [
            [['limit', 'page', 'id', 'status'], 'integer'],
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function getList()
    {
        $query = DiyPage::find()->where([
            'is_delete' => Model::IS_DELETE_FALSE,
            'store_id' => $this->getCurrentStoreId()
        ])->with('template');

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)
            ->asArray()->all();


        if ($list) {
            return [
                'list' => $list,
                'pagination' => $pagination
            ];
        }
    }

    /**
     * 页面详情
     * @return \app\hejiang\ValidationErrorResponse|array
     */
    public function detail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $detail = [];
        if ($this->id > 0) {
            $detail = DiyPage::find()->where([
                'is_delete' => Model::IS_DELETE_FALSE,
                'store_id' => $this->getCurrentStoreId(),
                'id' => $this->id,
            ])->asArray()->one();

            if (!$detail) {
                return [
                    'code' => 1,
                    'msg' => '该页面不存在',
                    'data' => [],
                ];
            }
        }

        $templateList = DiyTemplate::find()->where([
            'is_delete' => Model::IS_DELETE_FALSE,
            'store_id' => $this->getCurrentStoreId(),
        ])->asArray()->all();

        return [
            'detail' => $detail,
            'templateList' => $templateList
        ];
    }

    // 删除页面
    public function delete()
    {
        $detail = DiyPage::findOne($this->id);

        if (!$detail) {
            return [
                'code' => 1,
                'msg' => '页面不存在'
            ];
        }

        $detail->is_delete = 1;
        if ($detail->save()) {
            return [
                'code' => 0,
                'msg' => '删除成功'
            ];
        }

    }

    public function updateStatus()
    {
        $detail = DiyPage::findOne($this->id);

        if (!$detail) {
            return [
                'code' => 1,
                'msg' => '页面不存在'
            ];
        }

        $detail->status = $this->status;
        if ($detail->save()) {
            return [
                'code' => 0,
                'msg' => '状态更新成功'
            ];
        }
    }

    public function updateIndex()
    {
        $detail = DiyPage::findOne($this->id);

        if (!$detail) {
            return [
                'code' => 1,
                'msg' => '页面不存在'
            ];
        }
        DiyPage::updateAll(['is_index' => 0],['store_id' => $this->store->id]);

        $detail->is_index = $this->status;
        if ($detail->save()) {
            return [
                'code' => 0,
                'msg' => '更新成功'
            ];
        }
    }

}