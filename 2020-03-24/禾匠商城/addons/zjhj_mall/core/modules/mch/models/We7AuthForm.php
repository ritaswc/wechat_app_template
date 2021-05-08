<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/19
 * Time: 15:53
 */

namespace app\modules\mch\models;

use app\models\We7UserAuth;

class We7AuthForm extends MchModel
{
    public $we7_user_id;
    public $we7_user_id_list;
    public $auth;

    public function rules()
    {
        return [
            [['we7_user_id',], 'required', 'on' => 'one'],
            [['we7_user_id_list',], 'required', 'on' => 'multiple', 'message' => '请选择用户再设置权限。'],
            [['auth'], 'safe'],
        ];
    }

    public function save()
    {
        if ($this->scenario == 'one') {
            return $this->saveOne();
        }
        if ($this->scenario == 'multiple') {
            return $this->saveMultiple();
        }
        return [
            'code' => 1,
            'msg' => '错误的操作。',
        ];
    }

    public function saveOne()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $model = We7UserAuth::findOne(['we7_user_id' => $this->we7_user_id]);
        if (!$model) {
            $model = new We7UserAuth();
            $model->we7_user_id = $this->we7_user_id;
        }
        if (empty($this->auth)) {
            $this->auth = [];
        }
        $model->auth = \Yii::$app->serializer->encode($this->auth);
        if ($model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        }
        return [
            'code' => 1,
            'msg' => '保存失败',
        ];
    }

    public function saveMultiple()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        foreach ($this->we7_user_id_list as $uid) {
            $model = We7UserAuth::findOne(['we7_user_id' => $uid]);
            if (!$model) {
                $model = new We7UserAuth();
                $model->we7_user_id = $uid;
            }
            if (empty($this->auth)) {
                $this->auth = [];
            }
            $model->auth = \Yii::$app->serializer->encode($this->auth);
            $model->save();
        }
        return [
            'code' => 0,
            'msg' => '保存成功',
        ];
    }
}
