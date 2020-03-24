<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 9:50
 */

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$imgurl = Yii::$app->request->baseUrl;
$this->title = '想买用户列表';
$this->params['active_nav_group'] = 2;
$urlStr = get_plugin_url();
$show = true;
if (in_array(get_plugin_type(), [0])) {
    $show = true;
} else {
    $show = false;
}
?>
<style>

    table {
        table-layout: fixed;
    }

    th {
        text-align: center;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    td {
        vertical-align: inherit !important;
    }

    .goods-name {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .goods-pic {
        margin: 0 auto;
        width: 4rem;
        height: 4rem;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
        margin-right: 10px;
    }

    td.nowrap {
        white-space: nowrap;
        overflow: hidden;
    }
</style>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>

    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="float-left">
                <div class="dropdown float-right ml-2">
                    <div class="dropdown float-right ml-2">
                        <a href="javascript:" class="btn btn-secondary batch"
                           data-url="<?= $urlManager->createUrl([$urlStr . '/destroy-user']) ?>"
                           data-content="确认执行此操作?">批量删除用户</a>
                    </div>
                </div>
            </div>
            <div class="float-right">
                <form method="get">
                    <?php $_s = ['keyword'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>

                    <div class="input-group">
                        <input class="form-control" placeholder="用户昵称" name="keyword"
                               value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                        <span class="input-group-btn">
                    <button class="btn btn-primary">搜索</button>
                </span>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered bg-white table-hover">
            <thead>
            <tr>
                <th style="text-align: center;text-overflow:clip;">
                    <label class="checkbox-label" style="margin-right: 0px;">
                        <input type="checkbox" class="all-data">
                        <span class="label-icon"></span>
                    </label>
                </th>
                <th>
                    <span class="label-text">用户ID</span>
                </th>
                <th>头像</th>
                <th>昵称</th>
                <th>操作</th>
            </tr>
            <col style="width: 60px;">
            <col style="width: 80px;">
            <col style="width: 220px">
            <col style="width: 220px">
            <col style="width: 100%">
            </thead>
            <tbody>
            <?php foreach ($list as $index => $item) : ?>
                <tr style="text-align: center">
                    <td class="nowrap" style="text-align: center;">
                        <label class="checkbox-label" style="margin-right: 0px;">
                            <input type="checkbox"
                                   class="item-one"
                                   data-id='<?= $item['id'] ?>'
                                   value="<?= $item->id ?>">
                            <span class="label-icon"></span>
                        </label>
                    </td>
                    <td><span><?= $item['user']['id'] ?></span></td>
                    <td><img style="width: 55px;55px" src="<?= $item['user']['avatar_url'] ?>"></td>
                    <td><span><?= $item['user']['nickname'] ?></span></td>
                    <td class="order-tab-5">
                        <a class="btn btn-sm btn-danger destroy"
                           href="<?= $urlManager->createUrl([$urlStr . '/destroy-user', 'id' => $item['id']]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?php echo LinkPager::widget([
                    'pagination' => $pagination,
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'maxButtonCount' => 5,
                    'options' => [
                        'class' => 'pagination',
                    ],
                    'prevPageCssClass' => 'page-item',
                    'pageCssClass' => "page-item",
                    'nextPageCssClass' => 'page-item',
                    'firstPageCssClass' => 'page-item',
                    'lastPageCssClass' => 'page-item',
                    'linkOptions' => [
                        'class' => 'page-link',
                    ],
                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                ])
                ?>
            </nav>
            <div class="text-muted">共<?= $pagination->totalCount ?>条数据</div>
        </div>
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         data-backdrop="static"
         data-show="false">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div style="margin-bottom: 10px;" class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                             aria-valuemax="100" :style="{'width': progress_c_increment + '%'}">
                            {{progress_c_increment <= 100 ? progress_c_increment : 100}}%
                        </div>
                    </div>
                    <div>总共 {{count}} 条订单,成功{{send_result.count -
                        send_result.error_count}}条,失败{{send_result.error_count}}条
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-close"
                            data-dismiss="modal" id="closeModel">关闭
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    var app = new Vue({
        el: '#app',
        data: {
            progress_c_increment: 0,//进度条当前进度
            count: 0,
            send_result: {
                count: 0,
                error_count: 0,
                error_list: [],
            },
        }
    });


    $(document).on('click', '.destroy', function () {
        var btn = $(this);
        btn.btnLoading(btn.text())
        if (confirm("确认删除？")) {
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    alert(res.msg);
                    btn.btnReset();
                    if (res.code == 0) {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });

    $(document).on('click', '.all-data', function () {
        var checked = $(this).prop('checked');
        $('.item-one').prop('checked', checked);
        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });

    $(document).on('click', '.item-one', function () {
        var checked = $(this).prop('checked');
        var all = $('.item-one');
        var is_use = false;//只要有一个选中，批量按妞就可以使用
        var checkNum = 0;
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                checkNum += 1;
                is_use = true;
            }
        });
        if (checkNum == all.length) {
            $('.all-data').prop('checked', true);
        } else {
            $('.all-data').prop('checked', false);
        }
        if (is_use) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });

    $(document).on('click', '.batch', function () {
        var all = $('.item-one');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_all = false;
            }
        });
        if (is_all) {
            $.myAlert({
                content: "请先勾选订单"
            });
        }
    });


    $(document).on('click', '.is_use', function () {
        var a = $(this);
        var arrList = [];
        var all = $('.item-one');
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                arrList.push($(all[i]).data('id'));
            }
        });
        app.count = arrList.length;
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $('#myModal').modal('show');
                for (var i in arrList) {
                    $.ajax({
                        url: a.data('url'),
                        type: 'get',
                        dataType: 'json',
                        data: {
                            id: arrList[i],
                        },
                        success: function (res) {
                            app.progress_c_increment = Math.ceil(app.progress_c_increment + (100 / arrList.length));
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
            }
        });
    });

    $(document).on('click', '.modal-close', function () {
        window.location.reload();
    });
</script>