<?php
defined('YII_ENV') or exit('Access Denied');
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '拼团首页轮播图';
$this->params['active_nav_group'] = 10;
$this->params['is_group'] = 1;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <a href="<?= $urlManager->createUrl(['mch/group/pt/slide-edit']) ?>" class="btn btn-primary mb-3">
            <i class="iconfont icon-playlistadd"></i>添加轮播图</a>
        <div style="overflow-x: hidden">
            <div class="row">
                <?php foreach ($list as $index => $value) : ?>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card mb-3">
                            <div class="card-img-top" data-responsive="750:400"
                                 style="background-image: url(<?= $value['pic_url'] ?>);background-size: cover;background-position: center"></div>
                            <div class="card-body p-3">
                                <p>标题：<?= $value['title'] ?></p>
                                <div>链接：<?= $value['page_url'] ?></div>
                            </div>
                            <div class="card-footer text-muted">
                                <a class="btn btn-sm btn-primary"
                                   href="<?= $urlManager->createUrl(['mch/group/pt/slide-edit', 'id' => $value['id']]) ?>">修改</a>
                                <a class="btn btn-sm btn-danger del"
                                   href="<?= $urlManager->createUrl(['mch/group/pt/slide-del', 'id' => $value['id']]) ?>">删除</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
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
    </div>
</div>
<script>
    $(document).on('click', '.del', function () {
        if (confirm("是否删除？")) {
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    alert(res.msg);
                    if (res.code == 0) {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });
</script>