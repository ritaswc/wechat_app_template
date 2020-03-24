<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common\admin\store;

use app\models\Model;
use app\models\Store;

class CommonAppDisabled extends Model
{
    public $id;
    public $status;
    public $storeIds;

    public function rules()
    {
        return [
            [['id', 'status'], 'required'],
            [['id', 'status'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '商城ID',
            'status' => '商城状态',
        ];
    }

    /**
     * 小程序商城禁用|启用 开关
     * @return array|mixed
     */
    public function disabled()
    {
        if ($this->validate() == false) {
            return $this->errorResponse;
        }

        $store = Store::find()
            ->where(['id' => $this->id])->one();

        if ($store) {
            $status = $this->status ? Store::STORE_STATUS_NOT_DISABLE : Store::STORE_STATUS_DISABLE;
            $store->status = $status;
            $store->save();

            return [
                'code' => 0,
                'msg' => '状态更新成功'
            ];
        }

        return [
            'code' => 1,
            'msg' => '状态更新失败'
        ];
    }

    /**
     * 禁用|解除禁用 多选
     */
    public function multiSelectDisabled()
    {
        if (empty($this->storeIds)) {
            return [
                'code' => 1,
                'msg' => '请选择商城'
            ];
        }

        Store::updateAll(['status' => $this->status], ['id' => $this->storeIds]);

        return [
            'code' => 0,
            'msg' => '更新成功'
        ];
    }


    /**
     * 全部禁用|解禁
     */
    public function allDisabled()
    {
        Store::updateAll(['status' => $this->status], ['is_delete' => Model::IS_DELETE_FALSE]);

        return [
            'code' => 0,
            'msg' => '更新成功'
        ];
    }
}
