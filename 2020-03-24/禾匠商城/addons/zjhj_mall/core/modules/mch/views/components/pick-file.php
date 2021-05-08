<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/6
 * Time: 15:40
 */
$urlManager = Yii::$app->urlManager;
$imgurl = Yii::$app->request->baseUrl;
?>
<style>
    .panel-body .left {
        max-width: 153px;
        width: 300px;
        border: 1px solid #eee;
        background-color: #f4f5f9;
    }

    .left .item {
        width: 100%;
        padding: 0 10px;
        line-height: 40px;
        cursor: pointer;
    }

    .text-more {
        display: inline-block;
        max-width: 70%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        word-break: break-all;
    }

    .left .item:first-child {
        background-color: #fff;
    }

    .left .item.active {
        background-color: #fff;
    }

    .left .item:hover {
        background-color: #fff;
    }

    .item-icon {
        width: 1rem;
        height: 1rem;
        line-height: 1;
        font-size: 1.3rem;
    }

    .file-item .mask {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 5;
        background-color: rgba(0, 0, 0, .5);
        text-align: center;
        background-image: url('<?=$imgurl?>/statics/images/icon-file-gou.png');
        background-size: 40px 40px;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>
<div class="modal fade" id="file_select_modal" data-backdrop="static">
    <div class="modal-dialog" role="document" style="max-width: 801px;">
        <div class="panel">
            <div class="panel-header">
                <span>选择文件</span>
                <a href="javascript:" class="panel-close cancel-group-list">&times;</a>
            </div>
            <div class="panel-body" id="file" style="display: none">
                <div class="mb-3 clearfix pb-3" style="border-bottom: 1px solid #eee;">
                    <div class="float-right">
                        <div flex="dir:left cross:center">
                            <div class="upload-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="checkbox" value="1">
                                    <span class="label-icon"></span>
                                    <span class="label-text">全选</span>
                                </label>
                            </div>
                            <a class="btn btn-danger ml-2 delete-file-group" href="javascript:">删除</a>
                            <div class="upload-group ml-2">
                                <a class="btn btn-primary upload-file-group" href="javascript:">上传图片</a>
                            </div>
                            <div class="dropdown ml-2">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    移动
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                     style="max-height: 200px;overflow-y: auto;width: 120px;min-width: 0;">
                                    <template v-for="(item,index) in group_list"
                                              v-if="item.is_default != 1 && item.is_delete == 0">
                                        <a href="javascript:void(0)"
                                           class="btn btn-secondary batch-group dropdown-item text-more"
                                           :data-index="index" style="width: 100%;">{{item.name}}</a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div flex="dir:left main:left" style="height: 480px;">
                    <div class="left mr-3" style="height: 100%;overflow-y: auto">
                        <div class="item text-center add-group"
                             style="border-bottom: 1px solid #ececec;color: #0275d8;">
                            <span class="item-icon iconfont icon-add1"></span>
                            添加分组
                        </div>
                        <template v-for="(item,index) in group_list" v-if="item.is_delete != 1">
                            <div :class="index==selected?'item selected-group active':'item selected-group'"
                                 :data-index="index">
                                <template v-if="index == edit_group">
                                    <div class="pt-3" flex="dir:left cross:center">
                                        <span class="item-icon iconfont icon-folder" style="color: #edce86;"></span>
                                        <input class="form-control ml-3 name-group" :value="item.name">
                                    </div>
                                    <div class="edit-block" flex="dir:left box:mean">
                                        <div class="text-primary text-center save-group">确定</div>
                                        <div class="text-danger text-center cancel-group">取消</div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div flex="dir:left cross:center">
                                        <span class="item-icon iconfont icon-folder mr-3"
                                              style="color: #edce86;"></span>
                                        <div class="text-more">{{item.name}}</div>
                                        <template v-if="item.is_default != 1">
                                        <span class="item-icon iconfont icon-setup setting-group"
                                              :data-index="index"
                                              style="color: #00a0e9;"></span>
                                        </template>
                                    </div>
                                    <template v-if="item.is_default != 1">
                                        <div class="edit-block" flex="dir:left box:mean"
                                             v-if="index == edit_setting">
                                            <div class="text-primary text-center edit-group" :data-index="index">编辑
                                            </div>
                                            <div class="text-danger text-center delete-group" :data-index="index">删除
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="file-list" style="width: 610px;overflow-y: auto;">
                        <template v-for="(item,index) in list">
                            <div class="file-item text-center" :data-name="item.file_url"
                                 :data-url="item.file_url" :data-index="index" style="position: relative">
                                <img :src="item.file_url"
                                     class="file-cover">
                                <div class='mask' v-if="item.selected == 1"></div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="file-loading text-center" style="display: none">
                    <img style="height: 1.14286rem;width: 1.14286rem"
                         src="<?= Yii::$app->request->baseUrl ?>/statics/images/loading-2.svg">
                </div>
                <div class="text-center">
                    <a style="display: none" href="javascript:" class="file-more">加载更多</a>
                </div>
                <div class="panel-footer text-center">
                    <div class="btn btn-primary text-center save-group-list">确定</div>
                    <div class="btn btn-danger text-center cancel-group-list">取消</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var group_url = '<?=$urlManager->createUrl(['mch/file/group'])?>';
    //添加分组
    $(document).on('click', '.add-group', function () {
        var group = {
            name: '未命名',
            is_default: 0
        };
        saveGroup(group);
    });

    //保存单个分组信息
    function saveGroup(group){
        $.ajax({
            url:"<?=$urlManager->createUrl(['mch/file/edit-group-one'])?>",
            dataType:'json',
            type:'post',
            data:{
                _csrf:_csrf,
                data:JSON.stringify(group)
            },
            success:function(res){
                if(res.code == 0){
                    file_app.group_list = res.data;
                }else{
                    $.myAlert({
                        content:res.smg
                    });
                }
            }
        });
    }

    //选择分组
    $(document).on('click', '.selected-group', function (event) {
        file_app.selected = $(this).data('index');
        $("input[name='checkbox']").prop('checked', false);
        file_app.edit_group = -1;
        file_app.edit_setting = -1;
        getGroupFile();
    });

    //打开设置
    $(document).on('click', '.setting-group', function (event) {
        event.stopPropagation();//阻止事件冒泡；
        var index = $(this).data('index');
        file_app.edit_group = -1;
        file_app.selected = index;
        if (file_app.edit_setting == index) {
            file_app.edit_setting = -1;
        } else {
            file_app.edit_setting = index;
        }
        getGroupFile();
    });

    //编辑分组
    $(document).on('click', '.edit-group', function (event) {
        event.stopPropagation();//阻止事件冒泡；
        var index = $(this).data('index');
        file_app.edit_group = -1;
        file_app.edit_setting = -1;
        file_app.edit_group = index;
    });

    //删除分组
    $(document).on('click', '.delete-group', function (event) {
        event.stopPropagation();//阻止事件冒泡；
        var index = $(this).data('index');
        file_app.edit_group = -1;
        file_app.edit_setting = -1;
        file_app.selected = 0;
        var group = file_app.group_list[index];
        if (group.id) {
            group.is_delete = '1';
        } else {
            file_app.group_list.splice(index, 1);
        }
        saveGroup(group);
    });

    //确定修改
    $(document).on('click', '.save-group', function (event) {
        event.stopPropagation();//阻止事件冒泡；
        var group = file_app.group_list[file_app.edit_group];
        group.name = $('.name-group').val();
        file_app.edit_group = -1;
        file_app.edit_setting = -1;
        saveGroup(group);
    });

    //取消修改
    $(document).on('click', '.cancel-group', function (event) {
        event.stopPropagation();//阻止事件冒泡；
        file_app.edit_group = -1;
        file_app.edit_setting = -1;
    });

    //阻止冒泡事件
    $(document).on('click', '.name-group', function (event) {
        event.stopPropagation();//阻止事件冒泡；
    });

    //保存
    $(document).on('click', '.save-group-list', function () {
        if (file_app.file_list.length > 0) {
            var item = file_app.file_list[0];
            if (typeof _file_select.success === 'function') {
                _file_select.success({
                    name: item.file_url,
                    url: item.file_url,
                });
            }
        }
        file_app.file_list = [];
        file_app.list = [];
        file_app.selected = 0;
        file_app.edit_group = -1;
        file_app.edit_setting = -1;
        $('#file_select_modal').modal('hide');
    });

    //取消
    $(document).on('click', '.cancel-group-list', function () {
        file_app.file_list = [];
        file_app.list = [];
        file_app.selected = 0;
        file_app.edit_group = -1;
        file_app.edit_setting = -1;
        $('#file_select_modal').modal('hide');
    });

    //全选图片
    $(document).on('change', "input[name='checkbox']", function () {
        var list = file_app.list;
        file_app.file_list = [];
        $(list).each(function (i) {
            if ($("input[name='checkbox']:checked").val() == 1) {
                list[i].selected = 1;
                var file = list[i];
                file_app.file_list.push(file);
            } else {
                list[i].selected = 0;
            }
        });
        addFile()
    });

    //选择图片
    $(document).on('click', '#file_select_modal .file-item', function () {
        var index = $(this).data('index');
        file_app.file_list = [];
        if (file_app.list[index].selected == 1) {
            file_app.list[index].selected = 0;
        } else {
            file_app.list[index].selected = 1;
        }
        $("input[name='checkbox']").prop('checked', false);
        addFile()
    });

    //选中的图片
    function addFile() {
        var list = file_app.list;
        file_app.file_list = [];
        $(list).each(function (i) {
            if (list[i].selected == 1) {
                var file = list[i];
                file_app.file_list.push(file);
            }
        });
    }

    //删除选中的图片
    $(document).on('click', '.delete-file-group', function () {
        var file_list = file_app.file_list;
        if (file_list.length == 0) {
            $.myAlert({
                content: '请先勾选需要删除的图片'
            });
            return false;
        }
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/file/delete'])?>",
            dataType: 'json',
            type: 'post',
            data: {
                _csrf: _csrf,
                data: JSON.stringify(file_list)
            },
            success: function (res) {
                if (res.code == 0) {
                    var list = file_app.list;
                    var new_list = [];
                    $(list).each(function (i) {
                        if (list[i].selected != 1) {
                            new_list.push(list[i]);
                        }
                    });
                    file_app.list = new_list;
                    file_app.file_list = [];
                    $("input[name='checkbox']").prop('checked', false);
                    $.myAlert({
                        content: '删除成功'
                    });
                } else {
                    $.myAlert({
                        content: res.msg
                    });
                }
            }
        });
    });

    //移动图片到某个分组
    $(document).on('click', '.batch-group', function () {
        var file_list = file_app.file_list;
        var index = $(this).data('index');
        if (file_list.length == 0) {
            $.myAlert({
                content: '请先勾选需要删除的图片'
            });
            return false;
        }
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/file/move'])?>",
            dataType: 'json',
            type: 'post',
            data: {
                _csrf: _csrf,
                data: JSON.stringify(file_list),
                group_id: file_app.group_list[index].id
            },
            success: function (res) {
                if (res.code == 0) {
                    file_app.file_list = [];
                    $("input[name='checkbox']").prop('checked', false);
                    file_app.selected = index;
                    getGroupFile();
                    $.myAlert({
                        content: '移动成功'
                    });
                } else {
                    $.myAlert({
                        content: res.msg
                    });
                }
            }
        });
    });

    //获得分组下的图片
    function getGroupFile() {
        var more_btn = $('#file_select_modal .file-more');
        var loading_block = $('#file_select_modal .file-loading');
        var group_id = file_app.group_list[file_app.selected].id;
        loading_block.show();
        more_btn.hide();
        $.ajax({
            url: _upload_file_list_url,
            data: {
                dataType: 'json',
                type: 'image',
                page: 1,
                group_id: group_id
            },
            success: function (res) {
                more_btn.attr('data-page', 2);
                loading_block.hide();
                more_btn.show();
                file_app.list = res.data.list;
            }
        });
    }

    //上传图片
    $(document).on('click', '.upload-group .upload-file-group', function () {
        var group_id = file_app.group_list[file_app.selected].id;
        if (group_id <= 0) {
            group_id = 0;
        }
        var btn = $(this);
        var group = btn.parents('.upload-group');
        var input = group.find('.file-input');
        var preview = group.find('.upload-preview');
        var preview_img = group.find('.upload-preview-img');
        $.upload_file({
            url: _upload_url + '&group_id=' + group_id,
            accept: group.attr('accept') || 'image/*',
            start: function () {
                btn.btnLoading(btn.text());
            },
            success: function (res) {
                btn.btnReset();
                if (res.code === 1) {
                    $.alert({
                        content: res.msg
                    });
                    return;
                }
                getGroupFile();
            },
        });
    });
</script>