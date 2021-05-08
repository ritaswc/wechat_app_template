<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5
 * Time: 14:29
 */
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$statics = Yii::$app->request->baseUrl . '/statics';
$urlManager = Yii::$app->urlManager;
$this->title = '面单打印编辑';
$this->params['active_nav_group'] = 1;
?>

<div style="height: 0;overflow: hidden">
    <script language="JavaScript" src="<?= $statics ?>/mch/js/LodopFuncs.js"></script>
    <object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
        <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
    </object>
</div>

<div class="panel mb-3" id="sender">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $urlManager->createUrl(['mch/store/express']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">选择快递公司</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control express-select" name="model[express_id]">
                        <?php foreach ($express as $index => $value) : ?>
                            <option data-code="<?=$value['code']?>" value="<?= $value['id'] ?>" <?=$value['id'] == $list['express_id'] ? "selected" : ""?>>
                                <?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">电子面单客户账号</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[customer_name]"
                           value="<?= $list['customer_name'] ?>">
                    <div class="text-muted fs-sm">与快递网点申请</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">电子面单密码</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[customer_pwd]"
                           value="<?= $list['customer_pwd'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">月结编码</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[month_code]" value="<?= $list['month_code'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">网点编码</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[send_site]" value="<?= $list['send_site'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">网点名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[send_name]" value="<?= $list['send_name'] ?>">
                </div>
            </div>
            <div class="form-group row" v-if="template_size_list[express_code]">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">电子面单模板规格</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control template-select" name="model[template_size]" v-model="template_size">
                        <option v-for="(item, index) in template_size_list[express_code]"

                                :value="item.value">{{item.name}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">发件人公司</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[company]"
                           value="<?= $sender->company ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">发件人名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[name]" value="<?= $sender->name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">发件人电话</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[tel]" value="<?= $sender->tel ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">发件人手机</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[mobile]"
                           value="<?= $sender->mobile ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">发件人邮编</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[post_code]"
                           value="<?= $sender->post_code ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">发件人地区</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <select class="form-control province" style="float: left;"
                                name="model[province]">
                            <option v-for="(item,index) in province"
                                    :value="item.name" :data-index="index">{{item.name}}
                            </option>
                        </select>
                        <select class="form-control city" style="float: left;" name="model[city]">
                            <option v-for="(item,index) in city"
                                    :value="item.name" :data-index="index">{{item.name}}
                            </option>
                        </select>
                        <select class="form-control area" style="float: left;" name="model[exp_area]">
                            <option v-for="(item,index) in area"
                                    :value="item.name" :data-index="index">{{item.name}}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">发件人详细地址</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[address]" placeholder="请填写详细地址"
                           value="<?= $sender->address ?>">
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
        el: '#sender',
        data: {
            province:<?=$district?>,
            city: [],
            area: [],
            sender_province: "<?=$sender->province?>",
            sender_city: "<?=$sender->city?>",
            sender_area: "<?=$sender->exp_area?>",
            template_size_list: <?= $template_size_list?>,
            express_code: "<?= $express_code?>",
            template_size: "<?= $list['template_size']?>"
        }
    });
    app.city = app.province[0].list;
    app.area = app.city[0].list;
    $(app.province).each(function (i) {
        if (app.province[i].name == app.sender_province) {
            app.city = app.province[i].list;
            return true;
        }
    });
    $(app.city).each(function (i) {
        if (app.city[i].name == app.sender_city) {
            app.area = app.city[i].list;
            return true;
        }
    });
    $(document).ready(function () {
        $('.province').find('option').each(function (i) {
            if ($(this).val() == app.sender_province) {
                $(this).prop('selected', 'selected');
                return true;
            }
        });
        $('.city').find('option').each(function (i) {
            if ($(this).val() == app.sender_city) {
                $(this).prop('selected', 'selected');
                return true;
            }
        });
        $('.area').find('option').each(function (i) {
            if ($(this).val() == app.sender_area) {
                $(this).prop('selected', 'selected');
                return true;
            }
        });
        $(document).on('change', '.province', function () {
            var index = $(this).find('option:selected').data('index');
            app.city = app.province[index].list;
            app.area = app.city[0].list;
        });
        $(document).on('change', '.city', function () {
            var index = $(this).find('option:selected').data('index');
            app.area = app.city[index].list;
        });
    });
    $(document).on('change', '.province', function () {
        var index = $(this).find('option:selected').data('index');
        app.city = app.province[index].list;
        app.area = app.city[0].list;
    });
    $(document).on('change', '.city', function () {
        var index = $(this).find('option:selected').data('index');
        app.area = app.city[index].list;
    });
    $(document).on('change', '.express-select', function () {
        app.express_code = $(this).children(':selected').data('code');
        app.template_size = '';
    });
</script>