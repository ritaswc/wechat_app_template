<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '专题';
$this->params['active_nav_group'] = 8;
?>
<style>
    .cover-pic {
        display: block;
        width: 8rem; 
        height: 5rem;
        background-size: cover;
        background-position: center;
    }

    .table tbody tr td {
        vertical-align: middle;
    }

</style> 
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
    </div>
              
    <form method="get">
        <?php $_s = ['type'] ?>
        <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
            <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
        <?php endforeach; ?>
        <div class="float-left mb-4 mr-5">
            <a class="btn btn-primary edit-modal" href="<?= $urlManager->createUrl(['mch/topic/edit']) ?>">添加专题</a>
        </div>
        <div class="mt-3">
            <select style="display:inline;max-width:10%" class="form-control" name="type">
                <option value=0 <?= $_GET['type']==0?'selected':'' ?> >全部</option>
                <?php foreach($select as $v): ?>
                    <option <?= $_GET['type']==$v['typeid']?'selected':'' ?> value="<?= $v['typeid'] ?>"> <?= $v['name'] ?></option>
                <?php endforeach; ?>
            </select> 
            <button style="margin-bottom: 6px;" class="btn btn-primary mr-3 ml-2">筛选</button>
        </div>
    </form>        

    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th style="min-width:60px">分类</th>
                <th>封面图</th>
                <th>专题</th>
                <th>是否精选</th>
                <th class="text-center">排序</th>
                <th class="text-center">布局方式</th>
                <th class="text-center">操作</th>
            </tr>
            </thead>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?php if($item['name']){echo $item['name'];}else{echo '全部';} ?> </td>
                    <td>
                    <div></div>
                        <div class="cover-pic" style="background-image: url('<?= $item['cover_pic'] ?>')"></div>
                    </td>

                    <td>
                        <div style="max-width: 30rem">
                            <div class="mb-2 text-overflow-ellipsis"><?= $item['title'] ?></div>
                            <div class="text-muted fs-sm mb-2 text-overflow-ellipsis"><?= $item['sub_title'] ?></div>
                            <div class="text-muted fs-sm"><?= date('Y-m-d H:i:s', $item['addtime']) ?></div>
                        </div>
                    </td>
                    <td>
                        <?php if( ($item['is_chosen']) == 1 ): ?>精选<?php else: ?>不精选<?php endif; ?>
                    </td>
                    <td class="text-center"><?= $item['sort'] ?></td>
                    <td class="text-center"><?= $item['layout'] == 0 ? '小图模式' : '大图模式' ?></td>
                    <td class="text-center">
                        <div class="mb-2">
                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['mch/topic/edit', 'id' => $item['id']]) ?>">编辑</a>
                        </div>
                        <div>
                            <a class="btn btn-sm btn-danger delete-btn"
                               href="<?= $urlManager->createUrl(['mch/topic/delete', 'id' => $item['id']]) ?>">删除</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
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
            <div class="text-muted">共<?= $pagination->totalCount ?>条数据</div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".delete-btn", function () {
        var url = $(this).attr("href");
        $.confirm({
            content: "确认删除？",
            confirm: function () {
                $.loading();
                $.ajax({
                    url: url,
                    dataType: "json",
                    success: function (res) {
                        location.reload();
                    }
                });
            }
        });
        return false;
    });
</script>