<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/18
 * Time: 10:58
 */
$urlManager = Yii::$app->urlManager;
$this->title = '模板管理';
?>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/diy/diy/edit']) ?>">添加模板</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>模板ID</th>
                <th>名称</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            <?php if (count($list) > 0): ?>
                <?php foreach ($list as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['name'] ?></td>
                        <td><?= date('Y-m-d H:i:s', $item['addtime']) ?></td>
                        <td>
                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['mch/diy/diy/edit', 'id' => $item['id']]) ?>">编辑</a>
                            <a class="btn btn-sm btn-danger delete" href="javascript:"
                               data-content="是否删除？"
                               data-url="<?= $urlManager->createUrl(['mch/diy/diy/delete', 'id' => $item['id']]) ?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </thead>
        </table>

        <?php if ($pagination): ?>
            <div class="text-center">
                <nav aria-label="Page navigation example">
                    <?php echo \yii\widgets\LinkPager::widget([
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
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).on('click', '.delete', function () {
        var self = $(this);
        $.myConfirm({
            content: self.data('content'),
            confirm: function () {
                $.ajax({
                    url: self.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.myAlert({
                                title: "提示",
                                content: res.msg
                            })
                        }
                    }
                })
            }
        })
    });
</script>

