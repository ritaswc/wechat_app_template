<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
$this->title = '所售类目管理';
$url_manager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $url_manager->createUrl(['mch/mch/index/common-cat-edit']) ?>">添加类目</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <?php if (!$list || count($list) == 0) : ?>
            <div class="p-5 text-center text-muted">暂无类目，请添加类目以便商户申请入驻</div>
        <?php else : ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                </thead>
                <?php foreach ($list as $item) : ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['sort'] ?></td>
                        <td>
                            <a href="<?= $url_manager->createUrl(['mch/mch/index/common-cat-edit', 'id' => $item['id']]) ?>">编辑</a>
                            <span class="mx-1">|</span>
                            <a class="delete-btn"
                               href="<?= $url_manager->createUrl(['mch/mch/index/common-cat-delete', 'id' => $item['id']]) ?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
<script>
    $(document).on('click', '.delete-btn', function () {
        var href = $(this).attr('href');
        $.confirm({
            content: '确认删除？',
            confirm: function () {
                $.loading();
                $.ajax({
                    url: href,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        }
                    }
                });
            }
        });
        return false;
    });
</script>