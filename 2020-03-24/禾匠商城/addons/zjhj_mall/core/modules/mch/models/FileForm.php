<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/12
 * Time: 16:05
 */

namespace app\modules\mch\models;

use app\models\FileGroup;

class FileForm extends MchModel
{
    public $store_id;

    public $group;

    /**
     * 获得分组数据
     */
    public function getData()
    {
        $data = FileGroup::find()->where(['store_id' => $this->store_id, 'is_delete' => 0])->asArray()->all();
        $default_data = $this->defaultData();
        if (!$data) {
            return [
                'code'=>0,
                'data' => $default_data
            ];
        } else {
            return [
                'code'=>0,
                'data' => array_merge($default_data, $data)
            ];
        }
    }

    private function defaultData()
    {
        return [
            [
                'id' => -1,
                'name' => '全部',
                'is_default' => 1,
            ],
            [
                'id' => 0,
                'name' => '未分组',
                'is_default' => 1,
            ],
        ];
    }

    /**
     * 保存分组数据
     */
    public function saveData()
    {
        $data = FileGroup::find()->where(['store_id' => $this->store_id, 'is_delete' => 0])->asArray()->all();
        $group = json_decode($this->group, true);
        if (!$data) {
            foreach ($group as $index => $value) {
                if ($index == 0 || $index == 1) {
                    continue;
                }
                $model = new FileGroup();
                $model->name = $value['name'];
                $model->store_id = $this->store_id;
                $model->is_default = 0;
                $model->is_delete = 0;
                $model->addtime = time();
                $model->save();
            }
        } else {
            foreach ($group as $index => $value) {
                if ($index == 0 || $index == 1) {
                    continue;
                }
                if (isset($value['id'])) {
                    foreach ($data as $key => $item) {
                        if ($item['id'] == $value['id']) {
                            if ($value['name'] != $item['name'] || $value['is_delete'] != $item['is_delete']) {
                                $file = FileGroup::findOne($value['id']);
                                $file->attributes = $value;
                                $file->save();
                            }
                        }
                    }
                } else {
                    $model = new FileGroup();
                    $model->name = $value['name'];
                    $model->store_id = $this->store_id;
                    $model->is_default = 0;
                    $model->is_delete = 0;
                    $model->addtime = time();
                    $model->save();
                }
            }
        }
        return [
            'code' => 0,
            'msg' => '成功'
        ];
    }

    //保存单个分组信息
    public function save()
    {
        $group = json_decode($this->group, true);
        if (isset($group['id'])) {
            $model = FileGroup::findOne($group['id']);
            $model->is_delete = $group['is_delete'];
            $model->is_default = $group['is_default'];
        } else {
            $model = new FileGroup();
            $model->is_delete = 0;
            $model->addtime = time();
            $model->store_id = $this->store_id;
            $model->is_default = 0;
        }
        $model->name = $group['name'];
        if ($model->save()) {
            return $this->getData();
        } else {
            return $this->getErrorResponse($model);
        }
    }
}
