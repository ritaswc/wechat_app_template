<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
defined('YII_ENV') or exit('Access Denied');
$this->title = '发送模板消息';
?>
<style>
    .card:hover {
        border-color: #7bb5de;
    }

    .card .form-control {
        border-radius: 0;
    }

    .card {
        position: relative;
    }

    .delete-tpl-btn {
        position: absolute;
        left: calc(100% + 1rem);
        top: 1px;
    }

    #addTpleModal .map-row {
        position: relative;
    }

    #addTpleModal .add-map-btn {
        position: absolute;
        right: calc(100% + 4px);
        top: 19px;
        border-radius: 999px;
        width: 20px;
        height: 20px;
        line-height: 17px;
        font-weight: bolder;
        color: #888;
    }

    .selected-user-list {
        border: 1px solid #e3e3e3;
        padding: 0 .5rem;
        max-height: 400px;
        overflow-y: auto;
        margin-top: 2px;
    }

    .search-user-result {
        position: absolute;
        left: 25%;
        top: 100%;
        right: 0;
        border: 1px solid #d6d6d6;
        border-top: none;
        background: rgba(255, 255, 255, 0.95);
        z-index: 10;
        box-shadow: 0 3px 2px rgba(0, 0, 0, .1);
        padding: 0 .5rem;
    }

    .selected-user-item,
    .search-user-item {
        padding: .5rem;
        background: #fff;
        margin: .5rem 0;
        cursor: pointer;
    }

    .search-user-item:hover {
        box-shadow: 0 1px 5px rgba(0, 0, 0, .25);
    }
</style>
<div class="alert alert-danger rounded-0" role="alert"><b>注意：群发模板消息有被封号的风险，请谨慎使用！</b></div>
<div class="alert alert-danger rounded-0" role="alert"><b>注意：批量发送的对象过多会导致请求超时部分消息发送不成功！</b></div>
<div class="alert alert-danger rounded-0" role="alert"><b>注意：模板消息只发送给7天内在小程序内浏览点击过的活跃用户</b></div>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="form-group row mb-4">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">发送对象</label>
            </div>
            <div class="col-sm-6">
                <div style="position: relative">
                    <div class="mb-1 col-form-label">
                        <label class="checkbox-label mr-0" style="line-height: inherit">
                            <input name="send_all" type="checkbox" v-model="send_all">
                            <span class="label-icon"></span>
                            <span class="label-text">所有用户</span>
                        </label>
                        <span class="text-muted">（发送给所有近期活跃的商城用户）</span>
                    </div>
                    <template v-if="send_all">
                        <div class="input-group">
                            <input class="form-control search-user-input" readonly placeholder="输入ID或昵称查找用户">
                            <span class="input-group-btn">
                            <a class="btn btn-secondary search-user-btn disabled" href="javascript:">查找</a>
                        </span>
                        </div>
                    </template>
                    <template v-else>
                        <div class="input-group">
                            <input class="form-control search-user-input" placeholder="输入ID或昵称查找用户">
                            <span class="input-group-btn">
                            <a class="btn btn-secondary search-user-btn" href="javascript:">查找</a>
                        </span>
                        </div>
                    </template>
                    <template v-if="result_user_list !== null">
                        <div class="search-user-result">
                            <div class="text-center p-3 text-muted" v-if="result_user_list.length==0">没找到相关用户</div>
                            <template v-else>
                                <div v-for="(user,i) in result_user_list" :data-index="i" flex="dir:left"
                                     class="search-user-item">
                                    <div>
                                        <img :src="user.avatar_url"
                                             style="width: 3rem;height: 3rem;margin-right: .5rem;border-radius: .25rem">
                                    </div>
                                    <div>
                                        <div>
                                            <span class="mr-3">ID:{{user.id}}</span>
                                            <span>昵称:{{user.nickname}}</span>
                                        </div>
                                        <div v-if="user.form_id" class="fs-sm text-muted">
                                            用户FormId：{{user.form_id}}
                                        </div>
                                        <div v-else class="fs-sm text-muted"><span style="color: red;">FormId为空，用户无法接收模板消息</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="text-center my-2">
                                <a href="javascript:"
                                   class="fs-sm text-muted p-2 close-search-user-result">[×关闭搜索结果]</a>
                            </div>
                        </div>
                    </template>
                </div>
                <div v-if="user_list && user_list.length" class="selected-user-list">
                    <div v-for="(user,i) in user_list" :data-index="i" flex="dir:left"
                         class="selected-user-item">
                        <div>
                            <img :src="user.avatar_url"
                                 style="width: 3rem;height: 3rem;margin-right: .5rem;border-radius: .25rem">
                        </div>
                        <div>
                            <div>
                                <span class="mr-3">ID:{{user.id}}</span>
                                <span>FormId:{{user.form_id}}</span>
                            </div>
                            <div v-if='user.form_id' class="fs-sm text-muted">FomrId：{{user.form_id}}</div>
                            <div v-else class="fs-sm text-muted"><span style="color: red;">FormId为空，用户无法接收模板消息</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">选择模板</label>
            </div>
            <div class="col-sm-6">
                <div v-if="tpl_list === null" class="alert alert-info">模板加载中...</div>
                <template v-else>
                    <div v-if="tpl_list.length == 0" class="alert alert-info">暂无模板</div>
                    <template v-for="(tpl,i) in tpl_list">
                        <div class="card mb-3 tpl-item">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="w-100" flex="dir:left box:last">
                                        <div>
                                            <div>{{tpl.name}}{{tpl.title_style == 1 ? "（首行大标题）" : '（首行小标题）'}}</div>
                                            <div class="fs-sm text-muted">{{tpl.tpl_id}}</div>
                                        </div>
                                        <div class="float-right">
                                            <label class="radio-label mr-0" style="line-height: inherit">
                                                <input name="checked_tpl" :value="i" type="radio">
                                                <span class="label-icon"></span>
                                                <span class="label-text">选择</span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="w-100">
                                        <div class="my-2" v-for="map in tpl.maps">
                                            <template v-if=" map.key == 'first' ">
                                                <input class="form-control" v-model="map.value" placeholder="标题">
                                            </template>
                                            <template v-if=" map.key == 'remark' ">
                                                <input class="form-control" v-model="map.value" placeholder="备注">
                                            </template>
                                            <template v-if=" map.key != 'first' && map.key != 'remark' ">
                                                <div flex="dir:left box:first">
                                                    <div class="col-form-label">{{map.name}}：</div>
                                                    <div>
                                                        <input class="form-control" v-model="map.value"
                                                               placeholder="输入内容">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <!--                                        <div class="my-2" flex="dir:left box:first">-->
                                        <!--                                            <div class="col-form-label">网址：</div>-->
                                        <!--                                            <div>-->
                                        <!--                                                <input class="form-control" v-model="tpl.url"-->
                                        <!--                                                       placeholder="输入网址">-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                        <div class="my-2" flex="dir:left box:first">-->
                                        <!--                                            <div class="col-form-label">小程序AppId：</div>-->
                                        <!--                                            <div>-->
                                        <!--                                                <input class="form-control" v-model="tpl.miniprogram.appid"-->
                                        <!--                                                       placeholder="输入小程序AppId">-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <div class="my-2" flex="dir:left box:first">
                                            <div class="col-form-label">小程序页面：</div>
                                            <div>
                                                <input class="form-control" v-model="tpl.miniprogram.pagepath"
                                                       placeholder="小程序页面，如：pages/index/index?id=1">
                                            </div>
                                        </div>
                                        <!--                                        <div class="my-2 text-muted">-->
                                        <!--                                            网址跳转和小程序跳转只能二选一，两者都填会优先跳转小程序，该小程序appid必须与发模板消息的公众号是绑定关联关系。-->
                                        <!--                                        </div>-->
                                    </div>
                                </li>
                            </ul>
                            <a class="btn btn-danger delete-tpl-btn" href="javascript:" title="删除模板"
                               :data-index="i"
                               :data-id="tpl.id">删除</a>
                        </div>
                    </template>
                </template>
                <div class="text-right">
                    <template v-if="!tpl_list || tpl_list.length < 3">
                        <a class="btn btn-secondary" href="javascript:" data-toggle="modal"
                           data-target="#addTpleModal">新增模板</a>
                    </template>
                    <template v-else>
                        <div>最多只能添加3条模板</div>
                    </template>
                </div>
            </div>
        </div>
        <form method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right"></div>
                <div class="col-sm-6">
                    <a class="btn btn-primary send-msg-btn" href="javascript:">发送</a>
                </div>
            </div>
        </form>

        <div class="form-group row" v-if="send_result">
            <div class="form-group-label col-sm-2 text-right"></div>
            <div class="col-sm-6">
                <div style="margin-bottom: 10px;" class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                         aria-valuemax="100" :style="{'width': progress_c_increment + '%'}">
                        {{progress_c_increment}}%
                    </div>
                </div>
                <div class="alert alert-info rounded-0">
                    <div><b>本次发送共{{send_result.count}}条，失败{{send_result.error_count}}条。</b></div>
                    <div v-if="send_result.error_count > 0">
                        <a class="show-error-list" href="javascript:">查看失败的数据</a>
                        <div class="error-list" style="display: none">
                            <div v-for="error in send_result.error_list" style="word-break: break-all" class="my-3">
                                {{error.msg}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 添加模板 Modal -->
    <div class="modal fade" id="addTpleModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">新增模板</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group row">
                        <div class="form-group-label col-sm-3">
                            <label class="col-form-label required">模板名称</label>
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control" v-model="edit_tpl.name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-3">
                            <label class="col-form-label required">模板ID</label>
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control" v-model="edit_tpl.tpl_id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-3">
                            <label class="col-form-label">首行样式</label>
                        </div>
                        <div class="col-sm-6">
                            <label class="radio-label">
                                <input id="radio2"
                                       value="0"
                                       v-model="edit_tpl.title_style"
                                       name="title_style" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">小标题</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio2"
                                       value="1"
                                       v-model="edit_tpl.title_style"
                                       name="title_style" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">大标题</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-3">
                            <label class="col-form-label">模板字段</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="mb-1 fs-sm" flex="dir:left">
                                <div class="w-50">字段名:<br>如标题/时间/内容</div>
                            </div>
                            <template v-for="(map,i) in edit_tpl.maps">
                                <div class="mb-1 map-row" flex="dir:left">
                                    <div>
                                        <input class="form-control" v-model="map.name">
                                    </div>
                                    <a v-if="edit_tpl.maps.length > 1" :data-index="i"
                                       class="btn btn-secondary delete-map-btn ml-1" href="javascript:">-</a>
                                    <a :data-index="i" class="btn btn-secondary add-map-btn btn-sm"
                                       href="javascript:">+</a>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="javascript:" class="btn btn-primary add-tpl-btn">确定添加</a>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    var tpl_index = null;
    var app = new Vue({
        el: '#app',
        data: {
            progress_c_increment: 0,//进度条当前进度
            send_all: false,
            open_id_list: [{value: ''}],
            user_list: [],
            edit_tpl: {
                title_style: 0,
                name: '',
                tpl_id: '',
                url: '',
                miniprogram: {
                    appid: '',
                    pagepath: '',
                },
                maps: [
                    {
                        key: 'keyword1',
                        name: '模板第1行',
                        value: '',
                    },
                    {
                        key: 'keyword2',
                        name: '模板第2行',
                        value: '',
                    },
                    {
                        key: 'keyword3',
                        name: '模板第3行',
                        value: '',
                    },
                    {
                        key: 'keyword4',
                        name: '模板第4行',
                        value: '',
                    },
                ],
            },
            tpl_list: null,
            result_user_list: null,
            send_result: {
                count: 0,
                error_count: 0,
                error_list: [],
            },
            userCount: 0,
        }
    });

    //查找用户
    $(document).on('click', '.search-user-btn', function () {
        var btn = $(this);
        var input = $('.search-user-input');
        app.result_user_list = null;
        btn.btnLoading();
        searchUser(input.val(), 1, function () {
            btn.btnReset();
        });
    });

    //关闭查找用户结果
    $(document).on('click', '.close-search-user-result', function () {
        app.result_user_list = null;
    });

    //选择查找的用户
    $(document).on('click', '.search-user-item', function () {
        var index = $(this).attr('data-index');
        var user = app.result_user_list[index];
        if (!user.form_id) {
            return;
        }
        var in_array = false;
        for (var i in app.user_list) {
            if (app.user_list[i].id == user.id) {
                in_array = true;
                break;
            }
        }
        if (!in_array) {
            app.user_list.push(user);
        }
    });

    //移除已选的用户
    $(document).on('click', '.selected-user-item', function () {
        var index = $(this).attr('data-index');
        app.user_list.splice(index, 1);
    });

    //查找用户
    function searchUser(keyword, page, cb) {
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['mch/wechat-platform/search-user'])?>',
            dataType: 'json',
            data: {
                keyword: keyword,
                page: page,
            },
            success: function (res) {
                if (res.code == 0) {
                    app.result_user_list = res.data.list;
                }
            },
            complete: function () {
                if (cb) {
                    cb();
                }
            }
        });
    }


    //加载模板列表
    $.ajax({
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.code == 0)
                app.tpl_list = res.data.tpl_list;
        }
    });

    //选择模板
    $(document).on('change', 'input[name=checked_tpl]', function () {
        tpl_index = $(this).val();
    });

    //添加模板字段
    $(document).on('click', '.add-map-btn', function () {
        var index = parseInt($(this).attr('data-index'));
        var mapLength = app.edit_tpl.maps.length + 1;
        app.edit_tpl.maps.splice(index + 1, 0,
            {
                key: '',
                name: '模板第' + mapLength + '行',
                value: '',
            });
    });

    //删除模板字段
    $(document).on('click', '.delete-map-btn', function () {
        var index = parseInt($(this).attr('data-index'));
        console.log('index->', index);
        app.edit_tpl.maps.splice(index, 1);
    });

    //添加模板
    $(document).on('click', '.add-tpl-btn', function () {
        var btn = $(this);
        var tpl = app.edit_tpl;
        if (!tpl.name) {
            $.toast({
                content: '请填写模板名称',
            });
            return;
        }
        if (!tpl.tpl_id) {
            $.toast({
                content: '请填写模板ID',
            });
            return;
        }
        btn.btnLoading();
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['mch/wechat-platform/add-tpl'])?>',
            method: 'post',
            dataType: 'json',
            data: {
                _csrf: _csrf,
                tpl: JSON.stringify(app.edit_tpl),
            },
            success: function (res) {
                if (res.code == 0) {
                    $('#addTpleModal').modal('hide');
                    $.toast({
                        content: res.msg,
                    });
                    app.tpl_list.push(res.data.tpl);
                } else {
                    $.toast({
                        content: res.msg,
                    });
                }
            },
            complete: function () {
                btn.btnReset();
            }
        });
    });

    //删除模板
    $(document).on('click', '.delete-tpl-btn', function () {
        var btn = $(this);
        var id = btn.attr('data-id');
        var index = btn.attr('data-index');
        $.confirm({
            content: '确认删除该模板？',
            confirm: function () {
                $.loading();
                $.ajax({
                    url: '<?=Yii::$app->urlManager->createUrl(['mch/wechat-platform/delete-tpl'])?>',
                    dataType: 'json',
                    data: {
                        id: id,
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            app.tpl_list.splice(index, 1);
                        }
                    },
                    complete: function () {
                        $.loadingHide();
                    }
                });
            }
        });
    });

    //发送模板消息
    $(document).on('click', '.send-msg-btn', function () {
        var btn = $(this);
        var open_id_list = [];
        for (var i in app.user_list) {
            open_id_list.push({
                'wechat_open_id': app.user_list[i].wechat_open_id,
                'form_id': app.user_list[i].form_id
            });
        }
        if (open_id_list.length === 0 && !app.send_all) {
            $.alert({
                content: '请选择发送对象。',
            });
            return;
        }
        if (tpl_index === null) {
            $.alert({
                content: '请选择模板。',
            });
            return;
        }

        app.progress_c_increment = 0;
        app.send_result = {
            count: 0,
            error_count: 0,
            error_list: [],
        };
        btn.btnLoading();
        if (!app.send_all) {
            app.userCount = app.user_list.length;
            for (var i in open_id_list) {
                sendMsg(open_id_list[i])
            }
            app.user_list = [];
        } else {
            $.ajax({
                url: '<?=Yii::$app->urlManager->createUrl(['mch/wechat-platform/user-list'])?>',
                type: 'post',
                dataType: 'json',
                data: {
                    _csrf: _csrf,
                },
                success: function (res) {
                    if (res.code == 0) {
                        var list = res.data.list;
                        app.userCount = list.length;
                        for (var i in list) {
                            sendMsg(list[i])
                        }
                    }
                },
                complete: function () {
                }
            });
        }

        $.toast({
            content: '发送完成',
        });
        btn.btnReset();
    });

    function sendMsg(user) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                _csrf: _csrf,
                send_all: app.send_all ? 1 : 0,
                user_info: JSON.stringify(user),
                tpl: JSON.stringify(app.tpl_list[tpl_index]),
            },
            success: function (res) {
                app.progress_c_increment = app.progress_c_increment + (100 / app.userCount);
                app.send_result.count += 1;
                if (res.code == 1) {
                    app.send_result.error_count += 1;
                    app.send_result.error_list.push({
                        msg: res.msg
                    })
                }
            },
            complete: function () {

            }
        });
    }

    //显示失败的结果
    $(document).on('click', '.show-error-list', function () {
        $('.error-list').show();
    });
</script>