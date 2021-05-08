<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 13:42
 */
defined('YII_ENV') or exit('Access Denied');

use \app\models\User;
use \app\models\Level;

/* @var \app\models\User $user */
/* @var \app\models\Level[] $level */
$urlManager = Yii::$app->urlManager;
$this->title = '用户编辑';
$this->params['active_nav_group'] = 4;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body" id="app">
        <div class="">
            <form method="post" class="form auto-form" autocomplete="off"
                  return="<?= $urlManager->createUrl(['mch/user/index']) ?>">
                <div class="form-body">
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label">用户</label>
                        </div>
                        <div class="col-5">
                            <div>
                                <img src="<?= $user->avatar_url ?>" style="width: 50px;height:50px;">
                                <span><?= $user->nickname ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label required">会员等级</label>
                        </div>
                        <div class="col-2">
                            <select class="form-control" name="level">
                                <option value="-1" <?= $user->level == -1 ? "selected" : "" ?>>普通用户</option>
                                <?php foreach ($level as $value) : ?>
                                    <option
                                            value="<?= $value->level ?>" <?= ($value->level == $user->level) ? "selected" : "" ?>><?= $value->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label">上级</label>
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" name="parent_id"
                                   style="display: none;" value="<?= $user->parent_id ? $user->parent_id : 0; ?>">

                            <div style="width: 250px;" flex="dir:left box:last">
                                <label class="col-form-label parent-name"><?= $user->parent_id == 0 ? '总店' : $user->parent->nickname ?></label>
                                <a data-toggle="modal" data-target="#searchShare" href="javascript:"
                                   class="btn btn-outline-primary">修改</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">加入黑名单</label>
                        </div>
                        <div class="col-sm-6">
                            <label class="radio-label">
                                <input id="radio2" <?= $user->blacklist == 1 ? 'checked' : null ?>
                                       value="1"
                                       name="blacklist" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">开启</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio2" <?= $user->blacklist == 0 ? 'checked' : null ?>
                                       value="0"
                                       name="blacklist" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">关闭</span>
                            </label>
                            <div style="color: red;">加入黑名单后，用户将无法下单</div>
                        </div>
                    </div>

                    <?php if ($user->is_distributor == 1): ?>
                        <div class="form-group row">
                            <div class="form-group-label col-2 text-right">
                                <label class="col-form-label">累计佣金</label>
                            </div>
                            <div class="col-5">
                                <label class="col-form-label"><?= $user->total_price ?></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-2 text-right">
                                <label class="col-form-label">可提现佣金</label>
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control" name="price" placeholder="请输入联系方式"
                                       style="width:250px;"
                                       value="<?= $user->price ? $user->price : ''; ?>">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label">联系方式</label>
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" name="contact_way" placeholder="请输入联系方式"
                                   style="width:250px;" value="<?= $user->contact_way ? $user->contact_way : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label">备注</label>
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" name="comments" placeholder="请输入备注"
                                   style="width:250px;" value="<?= $user->comments ? $user->comments : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label">注册时间</label>
                        </div>
                        <div class="col-5">
                            <label class="col-form-label"><?= date('Y-m-d H:i', $user->addtime); ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                        </div>
                        <div class="col-5">
                            <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                            <input type="button" class="btn btn-default ml-4"
                                   name="Submit" onclick="javascript:history.back(-1);" value="返回">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- 查找分销商 -->
        <div class="modal fade" data-backdrop="static" id="searchShare" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">查找分销商</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= $urlManager->createUrl(['mch/user/get-share']) ?>"
                              class="input-group search-form" method="get">
                            <input name="keyword" class="form-control" placeholder="分销商昵称">
                            <input name="user_id" class="form-control" style="display: none" value="<?= $user->id ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary submit-btn">查找</button>
                            </span>
                        </form>
                        <div v-if="list==null" class="text-muted text-center p-5">请输入分销商昵称名称查</div>
                        <template v-else>
                            <div v-if="list.length==0" class="text-muted text-center p-5">未查找到相关分销商</div>
                            <template v-else>
                                <div class="goods-item row mt-3 mb-3">
                                    <div class="col-2">头像</div>
                                    <div class="col-5">
                                        <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">
                                            分销商昵称
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">姓名
                                        </div>
                                    </div>
                                    <div class="col-2 text-right">
                                        <a href="javascript:">操作</a>
                                    </div>
                                </div>
                                <div class="goods-item row mt-3 mb-3">
                                    <div class="col-2">
                                    </div>
                                    <div class="col-5">
                                        <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">总店
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">总店
                                        </div>
                                    </div>
                                    <div class="col-2 text-right">
                                        <a href="javascript:" class="select" data-index="-1">选择</a>
                                    </div>
                                </div>
                                <div class="goods-item row mt-3 mb-3" v-for="(item,index) in list">
                                    <div class="col-2">
                                        <img :src="item.avatar_url" style="width: 20px;height: 20px;">
                                    </div>
                                    <div class="col-5">
                                        <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">
                                            {{item.nickname}}
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">
                                            {{item.name}}
                                        </div>
                                    </div>
                                    <div class="col-2 text-right">
                                        <a href="javascript:" class="select" v-bind:data-index="index">选择</a>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            list: null
        }
    });

    $(document).on("submit", ".search-form", function () {
        var form = $(this);
        var btn = form.find(".submit-btn");
        btn.btnLoading("正在查找");
        $.ajax({
            url: form.attr("action"),
            type: "get",
            dataType: "json",
            data: form.serialize(),
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.list = res.data.list;
                }
            }
        });
        return false;
    });

    $(document).on('click', '.select', function () {
        var index = $(this).data('index');
        if (index == -1) {
            $("input[name='parent_id']").val(0);
            $(".parent-name").text('总店');
        } else {
            var parent = app.list[index];
            $("input[name='parent_id']").val(parent.id);
            $(".parent-name").text(parent.nickname);
        }
        $("#searchShare").modal('hide');
    });
</script>