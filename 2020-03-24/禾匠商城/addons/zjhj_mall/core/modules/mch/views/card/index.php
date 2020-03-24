<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/25
 * Time: 9:24
 */
defined('YII_ENV') or exit('Access Denied');
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '卡券管理';
$this->params['active_nav_group'] = 12;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <a class="btn btn-primary mb-3" href="<?= $urlManager->createUrl(['mch/card/edit']) ?>">添加卡券</a>
        <div class=" bg-white" style="max-width: 70rem">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>卡券名称</td>
                    <td>卡券信息</td>
                    <td>创建时间</td>
                    <td>操作</td>
                </tr>
                </thead>
                <col style="width: 10%;">
                <col style="width: 20%;">
                <col style="width: 35%;">
                <col style="width: 20%;">
                <col style="width: 15%;">
                <tbody>
                <?php foreach ($list as $index => $value) : ?>
                    <tr>
                        <td><?= $value['id']; ?></td>
                        <td><?= $value['name']; ?></td>
                        <td>
                            <div class="info p-2" style="border: 1px solid #ddd;">
                                <div flex="dir:left box:first">
                                    <div class="mr-4" data-responsive="88:88" style="width:44px;
                                        background-image: url(<?= $value['pic_url'] ?>);background-size: cover;
                                        background-position: center;border-radius: 88px;"></div>
                                    <div flex="dir:left cross:center"><?= $value['content'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= date('Y-m-d H:i:s', $value['addtime']); ?></td>
                        <td>
                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['mch/card/edit', 'id' => $value['id']]) ?>">编辑</a>
                            <a class="btn btn-sm btn-danger del" href="javascript:"
                               data-content="是否删除？"
                               data-url="<?= $urlManager->createUrl(['mch/card/del', 'id' => $value['id']]) ?>">删除</a>
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
                <div class="text-muted">共<?= $row_count ?>条数据</div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on("click", ".del", function () {
        var a = $(this);
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: a.data('url'),
                    dataType: "json",
                    success: function (res) {
                        if (res.code == 0) {
                            location.reload();
                        } else {
                            $.myLoadingHide();
                            $.myAlert({
                                content: res.msg,
                            });
                        }
                    }
                });
            },
        });
        return false;
    });
</script>
