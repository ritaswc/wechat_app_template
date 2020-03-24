<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 10:40
 */
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '商品服务管理';
$this->params['active_nav_group'] = 9;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/store/service-edit']) ?>">添加商品服务</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>服务内容</th>
                <th>默认服务</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php if (count($list) > 0): ?>
                <?php foreach ($list as $item) : ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['service'] ?></td>
                        <td>
                            <?php if ($item['is_default'] == true) : ?>
                                <label class="switch-label">
                                    <input class="is_switch" type="checkbox" name="is_default" checked data-id="<?= $item['id'] ?>">
                                    <span class="label-icon"></span>
                                    <span class="label-text"></span>
                                </label>
                            <?php else : ?>
                                <label class="switch-label">
                                    <input class="is_switch" type="checkbox" name="is_default" data-id="<?= $item['id'] ?>">
                                    <span class="label-icon"></span>
                                    <span class="label-text"></span>
                                </label>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm"
                               href="<?= $urlManager->createUrl(['mch/store/service-edit', 'id' => $item['id']]) ?>">编辑</a>
                            <a class="btn btn-danger btn-sm del" href="javascript:"
                               data-url="<?= $urlManager->createUrl(['mch/store/service-del', 'id' => $item['id']]) ?>"
                               data-content="是否删除？">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>
<script>
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.myAlert({
                                title: "提示",
                                content: res.msg
                            });
                        }
                    }
                });
            }
        });
        return false;
    });


    $(document).on('change', 'input[class=is_switch]', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('name');
        var status = 0;
        if ($(this).prop('checked'))
            status = 1;
        else
            status = 0;
        $.loading();
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['mch/store/set-default'])?>',
            dataType: 'json',
            data: {
                id: id,
                status: status,
                type: name
            },
            success: function (res) {
                $.toast({
                    content: res.msg,
                });

                if(res.code == 0) {
                    window.location.reload();
                }
            },
            complete: function () {
                $.loadingHide();
            }
        });
    });
</script>
