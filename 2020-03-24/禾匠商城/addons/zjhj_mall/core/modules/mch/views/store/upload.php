<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '上传设置';
$this->params['active_nav_group'] = 1;
?>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">上传存储方式</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control" name="storage_type" v-model="storage_type">
                        <option value="">无（当前服务器）</option>
                        <option value="qcloud">腾讯云COS</option>
                        <option value="qiniu">七牛云存储</option>
                        <option value="aliyun">阿里云OSS</option>
                    </select>
                </div>
            </div>

            <div v-bind:hidden="storage_type!='aliyun'?true:false" class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">阿里云OSS配置</label>
                </div>
                <div class="col-sm-6">
                    <label>存储空间名称（Bucket）</label>
                    <input class="form-control mb-3" name="aliyun[bucket]"
                           value="<?= $model->aliyun['bucket'] ?>">
                    <div class="text-muted fs-sm mb-3">请设置存储空间为公共读</div>
                    <label>Endpoint（或自定义域名）</label>
                    <input class="form-control" name="aliyun[domain]"
                           value="<?= $model->aliyun['domain'] ?>">
                    <div class="text-muted fs-sm mb-3">例子：http://oss-cn-hangzhou.aliyuncs.com，<span class="text-danger">请加上http://或https://，结尾不需要/</span></div>
                    <label>是否开启自定义域名</label>
                    <div>
                        <label class="radio-label">
                            <input id="radio1"
                                   value="0" <?=$model->aliyun['CNAME'] == 0?"checked":""?>
                                   name="aliyun[CNAME]" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">否</span>
                        </label>
                        <label class="radio-label">
                            <input id="radio2"
                                   value="1" <?=$model->aliyun['CNAME'] == 1?"checked":""?>
                                   name="aliyun[CNAME]" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">是</span>
                        </label>
                    </div>

                    <label>Access Key ID</label>
                    <input class="form-control mb-3" name="aliyun[access_key]"
                           value="<?= $model->aliyun['access_key'] ?>">
                    <label> Access Key Secret</label>
                    <input class="form-control mb-3" name="aliyun[secret_key]"
                           value="<?= $model->aliyun['secret_key'] ?>">
                    <label>图片样式接口（选填）</label>
                    <input class="form-control" name="aliyun[style_api]"
                           value="<?= $model->aliyun['style_api'] ?>">
                    <div class="text-muted fs-sm mb-3">例子：sample.jpg?x-oss-process=style/stylename</div>
                </div>
            </div>

            <div v-bind:hidden="storage_type!='qcloud'?true:false" class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">腾讯云COS配置</label>
                </div>
                <div class="col-sm-6">
                    <label>存储空间名称（Bucket）</label>
                    <input class="form-control mb-3" name="qcloud[bucket]"
                           value="<?= $model->qcloud['bucket'] ?>">
                    <label>默认域名</label>
                    <input class="form-control" name="qcloud[region]"
                           value="<?= $model->qcloud['region'] ?>">
                    <div class="text-muted fs-sm mb-3">例子：bucket-appid.cossh.myqcloud.com或者bucket-appid.cos.ap-shanghai.myqcloud.com</div>
                    <label>自定义域名</label>
                    <input class="form-control" name="qcloud[domain]"
                           value="<?= $model->qcloud['domain'] ?>">
                    <div class="text-muted fs-sm mb-3">若没有设置，则不用填写</div>
                    <div class="text-muted fs-sm mb-3">例子：http://abstehdsdw.bkt.clouddn.com，<span class="text-danger">请加上http://或https://，结尾不需要/</span></div>
                    <label>SecretId</label>
                    <input class="form-control mb-3" name="qcloud[secret_id]"
                           value="<?= $model->qcloud['secret_id'] ?>">
                    <label>SecretKey</label>
                    <input class="form-control mb-3" name="qcloud[secret_key]"
                           value="<?= $model->qcloud['secret_key'] ?>">
                </div>
            </div>

            <div v-bind:hidden="storage_type!='qiniu'?true:false" class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">七牛云存储配置</label>
                </div>
                <div class="col-sm-6">
                    <label>存储空间名称（Bucket）</label>
                    <input class="form-control mb-3" name="qiniu[bucket]"
                           value="<?= $model->qiniu['bucket'] ?>">
                    <label>绑定域名（或测试域名）</label>
                    <input class="form-control" name="qiniu[domain]"
                           value="<?= $model->qiniu['domain'] ?>">
                    <div class="text-muted fs-sm mb-3">例子：http://abstehdsdw.bkt.clouddn.com，<span class="text-danger">请加上http://或https://，结尾不需要/</span></div>
                    <label>AccessKey（AK）</label>
                    <input class="form-control mb-3" name="qiniu[access_key]"
                           value="<?= $model->qiniu['access_key'] ?>">
                    <label>SecretKey（SK）</label>
                    <input class="form-control mb-3" name="qiniu[secret_key]"
                           value="<?= $model->qiniu['secret_key'] ?>">
                    <label>图片样式接口（选填）</label>
                    <input class="form-control" name="qiniu[style_api]"
                           value="<?= $model->qiniu['style_api'] ?>">
                    <div class="text-muted fs-sm mb-3">例子：imageView2/0/w/1080/h/1080/q/85|imageslim</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var app = new Vue({
        el: "#app",
        data: {
            storage_type: "<?=$model->storage_type ?>",
        },
    });
</script>