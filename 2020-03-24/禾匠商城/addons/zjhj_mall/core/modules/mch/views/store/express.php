<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5
 * Time: 13:59
 */
defined('YII_ENV') or exit('Access Denied');

/* @var \app\models\Sender $sender */

use yii\widgets\LinkPager;

$statics = Yii::$app->request->baseUrl . '/statics';
$urlManager = Yii::$app->urlManager;
$this->title = '面单打印设置';
$this->params['active_nav_group'] = 1;
?>
<div style="height: 0;overflow: hidden">
    <script language="JavaScript" src="<?= $statics ?>/mch/js/LodopFuncs.js"></script>
    <object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
        <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
    </object>
</div>


<div class="panel mb-3">
    <div class="panel-header">
        <span>面单打印设置项</span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/store/express-edit']) ?>">添加设置项</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:" data-toggle="modal" data-target="#sender">设置发件人信息</a>
            </li>
            <li class="nav-item" hidden>
                <a class="nav-link" href="javascript:" onclick="CheckIsInstall()">检测是否安装打印插件</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <tr>
                <th>快递公司</th>
                <th>网点名称</th>
                <th>网点编码</th>
                <th>客户号</th>
                <th>发件人信息</th>
                <th>操作</th>
            </tr>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td><?= $value['name'] ?></td>
                    <td><?= $value['send_name'] ?></td>
                    <td><?= $value['send_site'] ?></td>
                    <td><?= $value['customer_name'] ?></td>
                    <td>
                        <div>名称：<?= $value['sender_name'] ?></div>
                        <div>联系方式：<?= $value['sender_tel'] ? $value['sender_tel'] : $value['sender_mobile'] ?></div>
                        <div>
                            地址：<?= $value['sender_province'] . $value['sender_city'] . $value['sender_area'] . $value['sender_address'] ?></div>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/store/express-edit', 'id' => $value['id']]) ?>">修改</a>

                        <a class="del btn btn-sm btn-danger" href="javascript:"
                           data-content="是否删除？"
                           data-url="<?= $urlManager->createUrl(['mch/store/express-del', 'id' => $value['id']]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="text-center">
            <?= LinkPager::widget(['pagination' => $pagination,]) ?>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="sender" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">默认发件人信息</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class=" auto-submit-form" method="post" autocomplete="off"
                      data-return="<?= $urlManager->createUrl(['mch/store/express']) ?>">
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">发件人公司</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" type="text" name="model[company]"
                                   value="<?= $sender->company ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label required">发件人名称</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" type="text" name="model[name]" value="<?= $sender->name ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">发件人电话</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" type="text" name="model[tel]" value="<?= $sender->tel ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">发件人手机</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" type="text" name="model[mobile]"
                                   value="<?= $sender->mobile ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">发件人邮编</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" type="text" name="model[post_code]"
                                   value="<?= $sender->post_code ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label required ">发件人地址</label>
                        </div>
                        <div class="col-9">
                            <div style="">
                                <select class="form-control col-4 province" style="float: left"
                                        name="model[province]">
                                    <option v-for="(item,index) in province"
                                            :value="item.name" :data-index="index">{{item.name}}
                                    </option>
                                </select>
                                <select class="form-control col-4 city" style="float: left" name="model[city]">
                                    <option v-for="(item,index) in city"
                                            :value="item.name" :data-index="index">{{item.name}}
                                    </option>
                                </select>
                                <select class="form-control col-4 area" style="float: left" name="model[exp_area]">
                                    <option v-for="(item,index) in area"
                                            :value="item.name" :data-index="index">{{item.name}}
                                    </option>
                                </select>
                                <div>
                                    <input class="form-control" type="text" name="model[address]"
                                           placeholder="请填写详细地址" value="<?= $sender->address ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-9 offset-sm-3">
                            <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                            <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                            <a class="btn btn-primary submit-btn" href="javascript:">保存</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="install" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">提示安装</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <font color='#FF00FF'>CLodop云打印服务(localhost本地)未安装启动!点击这里<a
                            href='http://www.mtsoftware.cn/download.html' target='_blank'>执行安装</a>,安装后请刷新页面。</font>
            </div>
        </div>
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
            sender_area: "<?=$sender->exp_area?>"
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
</script>
<script>
    var LODOP; //声明为全局变量
    //检测是否含有插件
    function CheckIsInstall() {
        try {
            var LODOP = getLodop();
            if (LODOP.VERSION) {
                if (LODOP.CVERSION)
                    $.myAlert({
                        content: "当前有C-Lodop云打印可用!\n C-Lodop版本:" + LODOP.CVERSION + "(内含Lodop" + LODOP.VERSION + ")"
                    });
                else
                    $.myAlert({
                        content: "本机已成功安装了Lodop控件！\n 版本号:" + LODOP.VERSION
                    });

            }
        } catch (err) {
        }
    }
</script>
<script>
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.confirm({
            title: "",
            content: a.data('content'),
            confirm: function () {
                $.loading();
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        $.loadingHide();
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.alert({
                                title: res.msg
                            });
                        }
                    }
                });

            }
        });
        return false;
    });
</script>
