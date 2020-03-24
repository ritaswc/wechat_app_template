<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/5
 * Time: 17:38
 */

namespace app\models\common\admin\store;

use app\models\Model;
use app\models\UploadConfig;

/**
 * @property UploadConfig $model
 */
class CommonStoreUpload extends Model
{
    public $model;

    public $store_id;
    public $storage_type;
    public $aliyun;
    public $qcloud;
    public $qiniu;

    public function rules()
    {
        return [
            [['storage_type',], 'string',],
            [['aliyun', 'qcloud', 'qiniu'], 'default', 'value' => (object)[],],
        ];
    }

    public function save()
    {
        $this->model->storage_type = $this->storage_type;

        // 七牛云存储
        foreach ($this->qiniu as $k => $v) {
            $this->qiniu[$k] = trim($v);
        }
        $this->qiniu['domain'] = trim($this->qiniu['domain'], '/');

        $this->model->qiniu = \Yii::$app->serializer->encode($this->qiniu);


        // 阿里云oss存储
        foreach ($this->aliyun as $k => $v) {
            $this->aliyun[$k] = trim($v);
        }
        $this->aliyun['domain'] = trim($this->aliyun['domain'], '/');


        $this->model->aliyun = \Yii::$app->serializer->encode($this->aliyun);

        // 腾讯云cos存储
        foreach ($this->qcloud as $k => $v) {
            $this->qcloud[$k] = trim($v);
        }
        $this->qcloud['domain'] = trim($this->qcloud['domain'], '/');
        $this->model->qcloud = \Yii::$app->serializer->encode($this->qcloud);

        if ($this->storage_type == 'qcloud') {
            preg_match('/(.*?)-(\d+)\.cos\.?(.*?)\.myqcloud\.com/', $this->qcloud['region'], $region);
            if (!$region || $region == 0) {
                return [
                    'code'=>1,
                    'msg'=>'默认域名不正确'
                ];
            }
        }
        if ($this->model->isNewRecord) {
            $this->model->store_id = 0;
            $this->model->is_delete = 0;
            $this->model->addtime = time();
        }
        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
