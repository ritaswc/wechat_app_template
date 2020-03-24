<?php
defined('YII_ENV') or exit('Access Denied');

use \app\models\User;
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager; 
$this->title = '流量主列表';
$this->params['active_nav_group'] = 4;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <a class="btn btn-primary mb-3" href="<?= $urlManager->createUrl(['mch/step/default/ad-edit']) ?>">添加流量主</a>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>位置</th>
                    <th>广告单元ID</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <?php foreach ($list as $u) : ?>
                <tr>
                    <td><?= $u['id']; ?></td>
                    <td>     
                        <?= $u['type'] == 1 ? '步数宝首页' : '' ?>
                        <?= $u['type'] == 2 ? '挑战底部' : '' ?>
                        <?= $u['type'] == 3 ? '排行榜底部' : '' ?>
                    </td>                                       
                    <td><?= $u['unit_id'];?></td>
                    <td class="nowrap">
                        <?php if ($u['status']==0) : ?>
                            <span class="badge badge-default">已关闭</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $u['id'] ?>,'up');">开启</a>
                        <?php elseif($u['status']==1) : ?>
                            <span class="badge badge-success">已开启</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $u['id'] ?>,'down');">关闭</a>
                        <?php endif ?>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-primary" <?= $u['open_date'] <> date('Y-m-d',time()) ? false : hidden; ?>
                            href="<?= $urlManager->createUrl(['mch/step/default/ad-edit', 'id' =>$u['id']]) ?>">编辑</a>
                        <a class="btn btn-sm btn-danger del" href="javascript:"
                            data-content="是否删除？"
                            data-url="<?= $urlManager->createUrl(['mch/step/default/ad-destroy', 'id' => $u['id']]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
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
        </div> 
    </div>
</div>
<script>
    function upDown(id,status){
        if(status=='down'){
            var text = '关闭';
            status = 0;
        }else if(status=='up'){
            var text = '开启';
            status = 1;
        }else{return};
        if (confirm("是否" + text + "？")) {
            $.ajax({
                url: "<?= $urlManager->createUrl(['mch/step/default/ad-status']) ?>",
                type: 'post',
                dataType: 'json',
                data: {
                    id:id,
                    status:status,
                    _csrf:_csrf
                },
                success: function (res) {
                    if (res.code == 0) {
                        window.location.reload();
                    }
                    if (res.code == 1) {
                        alert(res.msg);
                        if (res.return_url) {
                            location.href = res.return_url;
                        }
                    }
                }
            });
        }
        return false;
    }
</script>
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
