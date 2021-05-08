<?php
defined('YII_ENV') or exit('Access Denied');

use \app\models\User;
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '步数挑战';
$this->params['active_nav_group'] = 4;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <a class="btn btn-primary mb-3" href="<?= $urlManager->createUrl(['mch/step/default/activity-edit']) ?>">添加挑战</a>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>名称</th>
                    <th>活动时间</th>
                    <th>参与人数</th>
                    <th>缴纳金</th>                    
                    <th>奖金池</th>
                    <th>挑战步数</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <?php foreach ($list as $u) : ?>
                <tr>
                    <td><?= $u['name'];?></td>
                    <td><?= $u['open_date'];?></td>
                    <td><?= $u['people_num']; ?></td>
                    <td><?= $u['bail_currency'];?></td>    
                    <td><?= $u['currency_num'] + $u['currency'];?></td>                                       
                    <td><?= $u['step_num'];?></td>
                    <td class="nowrap">
                        <?php if ($u['type'] == 1) : ?>
                            <span class="badge badge-danger">已结算</span>
                        <?php elseif ($u['type'] == 2) : ?>
                            <span class="badge badge-info">已解散</span>
                        <?php elseif ($u['type'] == 0 && $u['status']==0) : ?>
                            <span class="badge badge-default">已关闭</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $u['id'] ?>,'up');">开启</a>
                        <?php elseif ($u['type'] == 0 && $u['status']==1) : ?>
                            <span class="badge badge-success">已开启</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $u['id'] ?>,'down');">关闭</a>
                        <?php endif ?>
                    </td>
                    <td>
                       <a class="btn btn-sm btn-success"
                       href="<?= $urlManager->createUrl(['mch/step/default/partake-list', 'id' =>$u['id']]) ?>">参与详情</a>
                       <!--  <a class="btn btn-sm btn-primary"  href="<?= $urlManager->createUrl(['mch/step/default/activity-edit', 'id' =>$u['id']]) ?>">编辑</a> -->

                        <?php if ($u['type'] == 0) : ?>
                            <a class="btn btn-sm btn-info del" href="javascript:"
                                data-content="是否解散？(将返还缴纳金)"
                                data-url="<?= $urlManager->createUrl(['mch/step/default/activity-disband', 'id' => $u['id']]) ?>">解散活动</a>
                        <?php else : ?>
                            <a class="btn btn-sm btn-danger del" href="javascript:"
                                data-content="是否删除？"
                                data-url="<?= $urlManager->createUrl(['mch/step/default/activity-destroy', 'id' => $u['id']]) ?>">删除</a>
                        <?php endif ?>
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
                url: "<?= $urlManager->createUrl(['mch/step/default/status-edit']) ?>",
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
