<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '阶级团列表';
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
        <div class="mb-3 clearfix">
            <div class="float-left">
                <a href="<?= $urlManager->createUrl(['mch/group/goods/standard-edit','goods_id' => $_GET['goods_id']]) ?>" class="btn btn-primary"><i
                        class="iconfont icon-playlistadd"></i>添加阶级团</a>
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
                </form>
            </div>
        </div>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th style="text-align: left">ID</th>
                <th>商品类型</th>
                <th class="text-left">商品名称</th>
                <th>团长优惠</th>
                <th>状态</th>
                <th>拼团时间</th>
                <th>拼团人数</th>
                <th>操作</th>
            </tr>
            </thead>
            <col style="width: 10%">
            <col style="width: 10%">
            <col style="width: 22%">
            <col style="width: 8%">
            <col style="width: 8%">
            <col style="width: 9%">
            <col style="width: 9%">
            <col style="width: 18%">
            <tbody>
            <?php foreach ($list as $index => $goods) : ?>
                <tr>
                    <td class="nowrap" style="text-align: left"><?= $goods['id'] ?></td>
                    <td class="nowrap"><?= $goods['cname'] ?></td>
                    <td class="text-left ellipsis"><?= $goods['name'] ?></td>
                    <td class="p-0" style="vertical-align: middle"><?= $goods['colonel'] ?></td>
                    <td class="nowrap">
                        <?php if ($goods['status'] == 1) : ?>
                           上架
                        <?php else : ?>
                            下架
                        <?php endif ?>
                    </td>
                    <td class="nowrap"><?= $goods['group_time'] ?>时
                    </td>
                    <td class="nowrap"><?= $goods['group_num'] ?></td>
                    <td class="nowrap">
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/group/goods/standard-edit', 'id' => $goods['id']]) ?>">修改</a>
                        <a class="btn btn-sm btn-danger del"
                           href="<?= $urlManager->createUrl(['mch/group/goods/standard-del', 'id' => $goods['id']]) ?>">删除</a>
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