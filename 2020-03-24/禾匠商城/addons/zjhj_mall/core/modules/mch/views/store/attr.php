<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:14
 */

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '商品规格';
$this->params['active_nav_group'] = 2;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['mch/store/index']) ?>">我的商城</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3">

    <a href="javascript:" data-toggle="modal" data-target="#attrAddModal" class="btn btn-primary mb-3"><i
                class="iconfont icon-playlistadd"></i>添加规格</a>
    <table class="table table-bordered bg-white">
        <thead>
        <tr>
            <th>ID</th>
            <th>规格分类</th>
            <th>规格名称</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($attr_list as $index => $item) : ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= $item['attr_group_name'] ?></td>
                <td style="vertical-align: middle" class="pt-0 pb-0">
                    <input data-id="<?= $item['id'] ?>" readonly class="form-control border-0 attr-edit"
                           value="<?= $item['attr_name'] ?>">
                </td>
                <td>
                    <a class="btn btn-sm btn-primary attr-update" href="javascript:"><i
                                class="iconfont icon-bordercolor"></i>修改</a>
                    <a data-id="<?= $item['id'] ?>" class="btn btn-sm btn-danger attr-delete" href="javascript:"><i
                                class="iconfont icon-close"></i>删除</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!--添加规格-->
    <div class="modal fade" id="attrAddModal" data-backdrop="static">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">添加规格</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="attr_group_name" placeholder="规格分类，如颜色、尺码">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php foreach ($attr_group_list as $attr_group) : ?>
                                    <a class="dropdown-item" href="javascript:"><?= $attr_group->attr_group_name ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>

                    <input class="form-control" id="attr_name" placeholder="规格名称，如红色、橙色">
                    <div class="form-error text-danger mt-3" style="display: none">ddd</div>
                    <div class="form-success text-success mt-3" style="display: none">sss</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary save-attr-btn">提交</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on("click", ".save-attr-btn", function () {
        var btn = $(this);
        var attr_group_name = $("#attr_group_name").val();
        var attr_name = $("#attr_name").val();
        var error = btn.parents(".modal").find(".form-error");
        var success = btn.parents(".modal").find(".form-success");
        btn.btnLoading("正在提交");
        success.hide();
        error.hide();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/store/attr-add'])?>",
            type: "post",
            data: {
                _csrf: _csrf,
                attr_group_name: attr_group_name,
                attr_name: attr_name,
            },
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    success.html(res.msg).show();
                    setTimeout(function () {
                        btn.parents(".modal").modal("hide");
                        btn.btnReset();
                        success.hide();
                        location.reload();
                    }, 1000);
                }
                if (res.code == 1) {
                    error.html(res.msg).show();
                    btn.btnReset();
                }
            }
        });
    });

    $(document).on("click", ".attr-update", function () {
        var edit = $(this).parents("tr").find(".attr-edit")
        edit.prop("readonly", false);
        edit[0].focus();
        edit.attr("data-oldval", edit.val());
    });

    $(document).on("click", ".attr-delete", function () {
        var btn = $(this);
        var attr_id = btn.attr("data-id");
        var tr = btn.parents("tr");
        if (!confirm("确认删除？"))
            return false;
        btn.btnLoading("正在删除");
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/store/attr-delete'])?>",
            data: {
                attr_id: attr_id,
            },
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    tr.remove();
                }
                if (res.code == 1) {
                    btn.btnReset();
                }
            }
        });
    });

    $(document).on("blur", "tr .attr-edit", function () {
        var edit = $(this);
        var attr_id = edit.attr("data-id");
        var oldval = $.trim(edit.attr("data-oldval"));
        var newval = $.trim(edit.val());
        edit.prop("readonly", true);
        if (oldval != newval) {
            $.ajax({
                url: "<?=$urlManager->createUrl(['mch/store/attr-update'])?>",
                type: "post",
                dataType: "json",
                data: {
                    _csrf: _csrf,
                    attr_id: attr_id,
                    attr_name: newval,
                },
                success: function (res) {
                    if (res.code == 1) {
                        edit.val(oldval);
                    }
                }
            });
        }

    });

</script>