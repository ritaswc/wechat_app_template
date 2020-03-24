<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 10:20
 */
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;
use \app\models\Option;

/* @var \app\models\Printer $model */

$urlManager = Yii::$app->urlManager;
$this->title = '打印机编辑';
$this->params['active_nav_group'] = 13;
$returnUrl = $urlManager->createUrl(['mch/printer/list']);
?>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $returnUrl ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">打印机名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="name" value="<?= $model->name ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">打印机类型</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control" name="printer_type" v-model="printer_type">
                        <option value="kdt2">365云打印（编号kdt2）</option>
                        <option value="yilianyun-k4">易联云（易联云开放API接口v1.4.0）</option>
                        <option value="feie">飞鹅打印机</option>
                        <option value="gp">佳博云打印（GP-5890XIII/GP-5890XIV）</option>
                    </select>
                    <div class="text-muted fs-sm">目前支持365-kdt2云打印、易联云、飞鹅打印机、佳博云打印</div>
                </div>
            </div>

            <div v-if="printer_type == 'kdt2'">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">打印机编号</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[name]"
                               value="<?= $model->printer_setting['name'] ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">打印机密钥</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[key]"
                               value="<?= $model->printer_setting['key'] ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">打印机联数</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" min="1" max="9" name="printer_setting[time]"
                               value="<?= $model->printer_setting['time'] ?>">
                        <div class="text-muted fs-sm">同一订单，打印的次数</div>
                    </div>
                </div>

                <div class="form-group row" hidden>
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">URL</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[url]"
                               value="http://open.printcenter.cn:8080/addOrder">
                    </div>
                </div>
            </div>
            <div v-if="printer_type == 'yilianyun-k4'">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">终端号</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[machine_code]"
                               value="<?= $model->printer_setting['machine_code'] ?>">
                        <div class="text-muted fs-sm">打印机终端号</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">密钥</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[key]"
                               value="<?= $model->printer_setting['key'] ?>">
                        <div class="text-muted fs-sm">打印机终端密钥</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">用户ID</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[client_id]"
                               value="<?= $model->printer_setting['client_id'] ?>">
                        <div class="text-muted fs-sm">用户id（管理中心系统集成里获取）</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">apiKey</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[client_key]"
                               value="<?= $model->printer_setting['client_key'] ?>">
                        <div class="text-muted fs-sm">apiKey（管理中心系统集成里获取）</div>
                    </div>
                </div>
                <div class="form-group row" hidden>
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">URL</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[url]"
                               value="<?= $model->printer_setting['url'] ? $model->printer_setting['url'] : "http://open.10ss.net:8888" ?>">
                        <div class="text-muted fs-sm">API接口地址</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">打印联数</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" min="1" max="9" name="printer_setting[time]"
                               value="<?= $model->printer_setting['time'] ?>">
                        <div class="text-muted fs-sm">同一订单，打印的次数</div>
                    </div>
                </div>
            </div>
            <div v-if="printer_type == 'feie'">

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">USER</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[user]"
                               value="<?= $model->printer_setting['user'] ?>">
                        <div class="text-muted fs-sm">飞鹅云后台注册用户名</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">UKEY</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[ukey]"
                               value="<?= $model->printer_setting['ukey'] ?>">
                        <div class="text-muted fs-sm">飞鹅云后台登录生成的UKEY</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">打印机编号</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[sn]"
                               value="<?= $model->printer_setting['sn'] ?>">
                        <div class="text-muted fs-sm">打印机编号9位,查看飞鹅打印机底部贴纸上面的打印机编号</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">打印机key</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[key]"
                               value="<?= $model->printer_setting['key'] ?>">
                        <div class="text-muted fs-sm">查看飞鹅打印机底部贴纸上面的打印机key</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">打印联数</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" min="1" max="9" name="printer_setting[time]"
                               value="<?= $model->printer_setting['time'] ?>">
                        <div class="text-muted fs-sm">同一订单，打印的次数</div>
                    </div>
                </div>
            </div>

            <div v-if="printer_type == 'gp'">

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">API密钥</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[apiKey]"
                               value="<?= $model->printer_setting['apiKey'] ?>">
                        <div class="text-muted fs-sm">(云平台系统集成里获取)</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">商户编号</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[memberCode]"
                               value="<?= $model->printer_setting['memberCode'] ?>">
                        <div class="text-muted fs-sm">(云平台系统集成里获取)</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">终端编号</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="printer_setting[deviceNo]"
                               value="<?= $model->printer_setting['deviceNo'] ?>">
                        <div class="text-muted fs-sm">(云平台设备详情中获取)</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">打印联数</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" min="1" max="9" name="printer_setting[time]"
                               value="<?= $model->printer_setting['time'] ?>">
                        <div class="text-muted fs-sm">同一订单，打印的次数</div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    <input type="button" class="btn btn-default ml-4" 
                           name="Submit" onclick="javascript:history.back(-1);" value="返回">
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    var app = new Vue({
        el: "#app",
        data: {
            printer_type: "<?=$model->printer_type ? $model->printer_type : "kdt2"?>",
        },
    });
</script>