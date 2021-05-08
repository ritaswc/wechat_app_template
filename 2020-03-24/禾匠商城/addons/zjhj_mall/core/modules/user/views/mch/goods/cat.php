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
$this->title = '商品分类';
$this->params['active_nav_group'] = 2;
?>
<style>
    .sub-icon {
        position: relative;
        display: inline-block;
        width: 1rem;
        height: 1rem;
    }

    .sub-icon:before,
    .sub-icon:after {
        content: " ";
        display: inline-block;
        position: absolute;
        background: #888;
        left: 0;
        top: 1px;
        width: 1px;
        height: 10px;
    }

    .sub-icon:before {
    }

    .sub-icon:after {
        height: 8px;
        transform: rotate(90deg);
        left: 4px;
        top: 6px;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createAbsoluteUrl(['user/mch/goods/cat-edit']) ?>">添加分类</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>分类名称</th>
                <th>图标</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cat_list as $index => $cat) : ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= $cat['name'] ?></td>
                    <td>
                        <?php if (!empty($cat['icon'])) : ?>
                            <img src="<?= $cat['icon'] ?>"
                                 style="width: 20px;height: 20px;">
                        <?php endif; ?>
                    </td>
                    <td><?= $cat['sort']; ?></td>
                    <td>
                        <a href="<?= $urlManager->createUrl(['user/mch/goods/cat-edit', 'id' => $cat['id']]) ?>">修改</a>
                        <span>|</span>
                        <a class="del-btn"
                           href="<?= $urlManager->createUrl(['user/mch/goods/cat-del', 'id' => $cat['id']]) ?>">删除</a>
                    </td>
                </tr>
                <?php foreach ($cat->childrenList as $sub_cat) : ?>
                    <tr class="bg-faded">
                        <td><?= $sub_cat['id'] ?></td>
                        <td><i class="sub-icon"></i><?= $sub_cat['name'] ?></td>
                        <td>
                            <?php if (!empty($sub_cat['pic_url'])) : ?>
                                <img src="<?= $sub_cat['pic_url'] ?>"
                                     style="width: 20px;height: 20px;">
                            <?php endif; ?>
                        </td>
                        <td><?= $sub_cat['sort']; ?></td>
                        <td>
                            <a href="<?= $urlManager->createUrl(['user/mch/goods/cat-edit', 'id' => $sub_cat['id']]) ?>">修改</a>
                            <span>|</span>
                            <a class="del-btn"
                               href="<?= $urlManager->createUrl(['user/mch/goods/cat-del', 'id' => $sub_cat['id']]) ?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<script>
    $(document).on('click', '.del-btn', function () {
        var btn = $(this);
        $.confirm({
            content: '确认删除？',
            confirm: function () {
                $.loading('正在处理');
                $.ajax({
                    url: btn.attr('href'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        $.alert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0) {
                                    window.location.reload();
                                }
                            }
                        });
                    },
                    complete: function () {
                        $.loadingHide();
                    }
                });
            }
        });
        return false;
    });
</script>