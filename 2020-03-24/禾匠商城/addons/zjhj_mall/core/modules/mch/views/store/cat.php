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

<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/store/cat-edit']) ?>">添加分类</a>
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
                <tr class="nav-item1">
                    <td>
                        <?php if (count($cat->childrenList)>0) : ?>
                        <span class="trans" style="transform:rotate(90deg)">
                            <span class="nav-pointer iconfont icon-play_fill"></span>
                        </span>
                        <?php endif; ?>
                        <span><?= $cat['id']?></span>              
                    </td>
                    <td><?= $cat['name'] ?></td>
                    <td>
                        <?php if (!empty($cat['pic_url'])) : ?>
                            <img src="<?= $cat['pic_url'] ?>"
                                 style="width: 20px;height: 20px;">
                        <?php endif; ?>
                    </td>
                    <td><?= $cat['sort']; ?></td>
                    <td>

                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/store/cat-edit', 'id' => $cat['id']]) ?>">修改</a>
                        <a class="btn btn-sm btn-primary copy" data-clipboard-text="/pages/cat/cat"
                           href="javascript:" hidden>复制链接</a>
                        <a class="btn btn-sm btn-danger del"
                           href="<?= $urlManager->createUrl(['mch/store/cat-del', 'id' => $cat['id']]) ?>">删除</a>
                    </td>
                </tr>
                <?php foreach ($cat->childrenList as $sub_cat) : ?>
                    <tr style="display:none" class="bg-faded bg-<?php echo $index ?>">
                        <td><?= $sub_cat['id'] ?></td>
                        <td><span class="mr-2">●</span><?= $sub_cat['name'] ?></td>
                        <td>
                            <?php if (!empty($sub_cat['pic_url'])) : ?>
                                <img src="<?= $sub_cat['pic_url'] ?>"
                                     style="width: 20px;height: 20px;">
                            <?php endif; ?>
                        </td>
                        <td><?= $sub_cat['sort']; ?></td>
                        <td>

                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['mch/store/cat-edit', 'id' => $sub_cat['id']]) ?>">修改</a>
                            <a class="btn btn-sm btn-primary copy"
                               data-clipboard-text="/pages/list/list?cat_id=<?= $sub_cat['id'] ?>"
                               href="javascript:" hidden>复制链接</a>
                            <a class="btn btn-sm btn-danger del"
                               href="<?= $urlManager->createUrl(['mch/store/cat-del', 'id' => $sub_cat['id']]) ?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<script>
    $(document).on('click', '.nav-item1', function () {
        if($(this).find(".trans")[0].style.display=='inline-block'){
            $(this).find(".trans")[0].style.display='inline';
        }else{
            $(this).find(".trans")[0].style.display='inline-block';
        }
        $('.bg-'+$(this).index(".nav-item1")).toggle();
    }); 
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
<script>
    $(document).ready(function () {
        var clipboard = new Clipboard('.copy');
        clipboard.on('success', function (e) {
            $.myAlert({
                title: '提示',
                content: '复制成功'
            });
        });
        clipboard.on('error', function (e) {
            $.myAlert({
                title: '提示',
                content: '复制失败，请手动复制。链接为：' + e.text
            });
        });
    })
</script>