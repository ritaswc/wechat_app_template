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
$this->title = '拼团机器人';
$this->params['active_nav_group'] = 10;
$this->params['is_group'] = 1;
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
        text-align: center;
    }

    .ellipsis {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    td.nowrap {
        white-space: nowrap;
        overflow: hidden;
    }

    .goods-pic {
        width: 3rem;
        height: 3rem;
        display: inline-block;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        $status = ['已下架', '已上架'];
        ?>
        <div class="mb-3 clearfix">
            <div class="float-left">
                <a href="<?= $urlManager->createUrl(['mch/group/robot/edit']) ?>" class="btn btn-primary"><i
                        class="iconfont icon-playlistadd"></i>添加机器人</a>
            </div>
        </div>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th style="text-align: left">&nbsp;&nbsp;ID</th>
                <th>机器人名</th>
                <th>机器人头像</th>
                <th>操作</th>
            </tr>
            </thead>
            <col style="width: 5%">
            <col style="width: 10%">
            <col style="width: 22%">
            <col style="width: 18%">
            <tbody>
            <?php foreach ($list as $index => $item) : ?>
                <tr>
                    <td class="nowrap" style="text-align: left"><?= $item->id ?></td>
                    <td class="nowrap"><?= $item->name ?></td>
                    <td class="p-0" style="vertical-align: middle">
                        <div class="goods-pic" style="background-image: url(<?= $item->pic ?>)"></div>
                    </td>
                    <td class="nowrap">
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/group/robot/edit', 'id' => $item->id]) ?>">修改</a>
                        <a class="btn btn-sm btn-danger del"
                           href="<?= $urlManager->createUrl(['mch/group/robot/del', 'id' => $item->id]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
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