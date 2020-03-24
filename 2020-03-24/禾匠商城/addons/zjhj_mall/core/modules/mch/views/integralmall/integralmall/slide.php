<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/14
 * Time: 17:43
 */
defined('YII_ENV') or exit('Access Denied');
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '积分商城轮播图';
?>

<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/slide-edit']) ?>">添加轮播图</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <?php if ($list != null) : ?>
            <?php foreach ($list as $index => $value) : ?>
            <div class="card mb-3" style="display: inline-block;width: 300px">
                <div class="card-img-top" data-responsive="750:400"
                     style="background-image: url(<?= $value['pic_url'] ?>);background-size: cover;background-position: center"></div>
                <div class="card-body p-3">
                    <p>标题：<?= $value['title'] ?></p>
                    <div style="white-space: nowrap;overflow: hidden;word-break: break-all;text-overflow: ellipsis;">链接：<?= $value['page_url'] ?></div>
                </div>
                <div class="card-footer text-muted">
                    <a class="btn btn-sm btn-primary"
                       href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/slide-edit', 'id' => $value['id']]) ?>">修改</a>
                    <a class="btn btn-sm btn-danger del"
                       href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/slide-del', 'id' => $value['id']]) ?>">删除</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif ?>
    </div>
</div>

<script>
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.confirm({
            content: '确认删除？',
            confirm: function () {
                $.loading();
                $.ajax({
                    url: a.attr('href'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        $.loadingHide();
                        $.alert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0) {
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });
            }
        });
        return false;
    });
</script>