<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/8
 * Time: 14:30
 */

$urlManager = Yii::$app->urlManager;
$this->title = '积分商城设置';
//$this->params['active_nav_group'] = 10;
$returnUrl = Yii::$app->request->referrer;
?>
<style>
    .nav{
        margin-left: 6rem;
        border-bottom: 1px #E5E5E5 solid;
    }
    #page .nav-item{
        border: 1px #E5E5E5 solid;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        margin-bottom: -1px;
        height: 3rem;
        line-height: 3rem;
        padding-left: 1rem;
        padding-right: 1rem;
        cursor: pointer;
    }
    .pointer{
        background-color: #33AAFF;
        color: white;
    }

    #integral_add{
        height: 2.5rem;
        color: #fff;
        background:#33aaff;
        border-radius: 0.2rem;
        cursor: pointer;
    }

    .panel-body .title{
        width: 100%;
        border-radius: 0.2rem;
        border: 1px #C9CBCB solid;
    }

    .toggle{
        display: none;
    }
</style>
<div class="panel mb-3" id="page">
    <div class="panel-header"><?= $this->title ?></div>
    <ul class="nav mt-3" id="tab">
        <li class="nav-item tabs pointer" id="tab2" value="2">
            设置签到规则
        </li>
        <li class="nav-item tabs" id="tab1" value="1">
            设置积分说明
        </li>
    </ul>
    <div class="panel-body mt-5" id="page">
        <form class="form auto-form" method="post" autocomplete="off" id="container"
              return="<?= $urlManager->createUrl(['mch/integralmall/integralmall/setting']) ?>">
            <div id="content1" class="toggle">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label">积分说明</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel-group row" id="accordion">
                            <div class="panel panel-default col-9">
                                <div class="panel-heading">
                                    <div class="input-group">
                                        <span class="input-group-addon">标题</span>
                                        <input type="text" class="form-control" id="title" placeholder="如：什么是积分？">
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <span class="input-group-addon title" for="name">内容</span>
                                    <textarea class="form-control" rows="8" id="content" placeholder="如：积分是如何如何如何"></textarea>
                                        <span class="input-group-addon mt-2 mb-1" id="integral_add">
                                            添加
                                        </span>
                                </div>
                            </div>
                            <div class="fs-sm text-danger col-3">
                                <div>需设置签到规则后才能填写积分说明</div>
                                <div class="mt-5">注：</div>
                                <div class="mt-2">1、多条内容请使用中文逗号隔开</div>
                                <div class="mt-2">2、点击添加后点击保存即可，可添加多条后一并保存</div>
                            </div>
                        </div>
                        <div id="app">
                            <div v-for="(attr_group,i) in integral_list">
                                <div>标题：{{attr_group.title}}&nbsp;&nbsp;&nbsp;
                                    <a v-bind:group-index="i" class="attr-title-delete"
                                       href="javascript:">×</a>
                                    <input type="hidden" v-bind:name="'attr['+i+'][title]'" v-bind:value="attr_group.title">
                                </div>
                                <div>内容：{{attr_group.content}}&nbsp;&nbsp;&nbsp;
                                    <input type="hidden" v-bind:name="'attr['+i+'][content]'"
                                           v-bind:value="attr_group.content">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($integral_list != null) : ?>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class=" col-form-label">积分说明</label>
                        </div>
                        <div class="col-sm-4">
                            <table class="table table-bordered attr-table">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>标题</th>
                                    <th>内容</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <?php foreach ($integral_list as $index => $value) : ?>
                                    <tr>
                                        <td><?= $index ?></td>
                                        <td><?= $value->title ?></td>
                                        <td>
                                            <div style="width: 145px;overflow: hidden;text-overflow: ellipsis;"
                                                 title="<?= $value->content ?>"><?= $value->content ?></div>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary attr-edit" data-toggle="modal"
                                               data-target="#attrAddModal" href="javascript:"
                                               data-content="<?= $value->content ?>"
                                               data-title="<?= $value->title ?>"
                                               data-index="<?= $index ?>"> 修改</a>
                                            <a class="btn btn-sm btn-danger del attr-id" data-index="<?= $index ?>"
                                               href="javascript:">删除</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                <?php endif ?>
                <div class="modal fade" id="attrAddModal" data-backdrop="static">
                    <div class="modal-dialog modal-sm" role="document" style="margin-top: 200px;max-width: 400px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <b class="modal-title">修改积分说明</b>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group">
                                    <span class="input-group-addon">标题</span>
                                    <input type="text" class="form-control" id="data-title">
                                </div>
                                <br>
                                <span class="input-group-addon title" for="name">内容</span>
                                <textarea class="form-control" rows="6" id="data-content"></textarea>
                                <input type="hidden" id="data-index">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                                <button type="button" class="btn btn-primary save-rechange">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content2">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">签到规则</label>
                    </div>
                    <div class="col-sm-4">
                        <textarea class="form-control" rows="3" id="content" placeholder="如：每天签到如何如何" 
                                  name="model[register_rule]"><?= $setting->register_rule ? $setting->register_rule : ''; ?></textarea>
                        <div class="fs-sm text-danger">注：多条内容请使用中文逗号隔开</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">每日签到获得分数</label>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control" name="model[register_integral]" placeholder="签到获得分数必须是整数" 
                               value="<?= $setting->register_integral ? $setting->register_integral : ''; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">连续签到天数</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">需连续签到</span>
                            <input class="form-control" name="model[register_continuation]" placeholder="连续签到天数必须是整数"
                               value="<?= $setting->register_continuation ? $setting->register_continuation : ''; ?>">
                            <span class="input-group-addon">天</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">连续签到奖励积分</label>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control" name="model[register_reward]" placeholder="连续签到奖励积分必须是整数"
                               value="<?= $setting->register_reward ? $setting->register_reward : ''; ?>">
                        <div class="fs-sm text-danger">注：只要满足连续签到天数，中间没有断签，就一直赠送奖励积分；</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                </div>
                <div class="col-9">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var page = new Vue({
        el: "#app",
        data: {
            integral_list: []
        }
    });
    $(document).on("click", "#integral_add", function () {
        var title = $("#title").val();
        var content = $("#content").val();
        title = $.trim(title);
        if (title == "" || content == "") {
            $.myAlert({
                content: '请先填写积分说明'
            });
            return;
        }
        page.integral_list.push({
            title: title,
            content: content
        });
        $("#title").val("");
        $("#content").val("");
    });

    $(document).on("click", ".attr-title-delete", function () {
        var group_index = $(this).attr("group-index");
        page.integral_list.splice(group_index, 1);
    });
    $(document).on("click", ".attr-id", function () {
        var btn = $(this);
        $.confirm({
            content:'是否删除？',
            confirm:function(){
                btn.btnLoading(btn.text());
                var index = btn.attr("data-index");
                $.ajax({
                    url: "<?=$urlManager->createUrl(['mch/integralmall/integralmall/attr-delete'])?>",
                    type: 'get',
                    data: {
                        id: index,
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.myAlert({
                                title: '提示',
                                content: res.msg
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on("click", ".attr-edit", function () {
        var title = $(this).attr("data-title");
        var content = $(this).attr("data-content");
        var index = $(this).attr("data-index");
        $("#data-title").val(title);
        $("#data-content").val(content);
        $("#data-index").val(index);
    });

    $(document).on("click", "#tab1", function () {
        $('#tab1').addClass('pointer');
        $('#tab2').removeClass('pointer');
    });
    $(document).on("click", "#tab2", function () {
        $('#tab2').addClass('pointer');
        $('#tab1').removeClass('pointer');
    });

    $("ul#tab > li ").click(function(){
        var num = $(this).val();
        $("#content"+num).removeClass('toggle');
        if(num % 2 == 0){
            $("#content1").addClass('toggle')
        }else{
            $("#content2").addClass('toggle')
        }
    })

    $(document).on('click', '.save-rechange', function () {
        var title = $('#data-title').val();
        var content = $('#data-content').val();
        var index = $('#data-index').val();
        var btn = $(this);
        btn.btnLoading();
        $.ajax({
            url: "<?= Yii::$app->urlManager->createUrl(['mch/integralmall/integralmall/attr-edit']) ?>",
            type: 'get',
            dataType: 'json',
            data: {index: index, content: content, title: title},
            success: function (res) {
                if (res.code == 0) {
                    window.location.reload();
                } else {
                    $.myAlert({
                        title: '提示',
                        content: res.msg
                    });
                }
            }
        });
    });
</script>
