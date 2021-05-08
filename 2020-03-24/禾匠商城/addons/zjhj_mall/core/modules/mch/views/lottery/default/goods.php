<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;
$urlManager = Yii::$app->urlManager;
$imgurl = Yii::$app->request->baseUrl;
$this->title = '抽奖列表';
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <a class="btn btn-primary mb-3" href="<?= $urlManager->createUrl(['mch/lottery/default/goods-edit']) ?>">添加抽奖</a>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th style="max-width:200px">奖品名称</th>
                <th>规格</th>        
                <th>中奖数量</th>
                <th>活动时间</th>
                <th>状态</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $index => $item) : ?>
                <tr>
                    <td class="nowrap"><?= $item['id'] ?></td>
                    <td class="nowrap text-left"><?= $item['goods']['name'] ?></td>
                    <td class="nowrap"><?= $item['attrs']; ?></td>
                    <td class="nowrap"> 
                        <?php if ($item['type'] == 1) : ?>
                            <?= $item['stock']; ?>
                        <?php else : ?>
                            <a href="#" data-toggle="modal" data-target="#myModal" onclick="edit_stock(<?= $item['id'] ?>,<?= $item['stock'] ?>)"><?= $item['stock']; ?></a>
                        <?php endif ?>
                    </td>
                    <td><?= date('Y:m:d H:i', $item['start_time']) ?>--<?= date('Y:m:d H:i', $item['end_time']) ?></td>
       
                    <td class="nowrap">
                        <?php if ($item['type'] == 1) : ?>
                            <span class="badge badge-danger">已开奖</span>
                        <?php elseif ($item['type'] == 0 && $item['status']==0) : ?>
                            <span class="badge badge-default">已关闭</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $item['id'] ?>,'up');">开启</a>
                        <?php elseif ($item['type'] == 0 && $item['status']==1 && $item['start_time']>time()) : ?>
                            <span class="badge badge-success">已开启</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $item['id'] ?>,'down');">关闭</a>
                            |
                            <span class="badge badge-info">未开始</span>
                        <?php elseif($item['type'] == 0 && $item['status']==1 && $item['end_time']<time()) : ?>
                            <span class="badge badge-success">已开启</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $item['id'] ?>,'down');">关闭</a>
                            |
                            <span class="badge badge-info">已结束</span>
                        <?php elseif($item['type'] == 0 && $item['status']==1) : ?>
                            <span class="badge badge-success">已开启</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $item['id'] ?>,'down');">关闭</a>
                            |
                            <span class="badge badge-info">进行中</span>
                        <?php endif ?>
                    </td>
                    <td><a href="#" data-toggle="modal" data-target="#myModalSort" onclick="edit_sort(<?= $item['id'] ?>,<?= $item['sort'] ?>)"><?= $item['sort']; ?></a></td>
                    <td>
                        <a class="btn btn-sm btn-success"
                       href="<?= $urlManager->createUrl(['mch/lottery/default/partake-list', 'id' =>$item['id']]) ?>">参与详情</a>
                        <a class="btn btn-sm btn-danger del" href="javascript:"
                            data-content="是否删除？"
                            data-url="<?= $urlManager->createUrl(['mch/lottery/default/goods-destroy', 'id' => $item['id']]) ?>">删除</a>
                        <a class="btn btn-sm btn-info"
                       href="<?= $urlManager->createUrl(['mch/lottery/default/detail', 'id' =>$item['id']]) ?>">预中奖设置</a>
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
        </div> 
    </div>

    <div class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true" id="myModal" style="margin-top:200px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="height:40px;">
                    <h5 class="modal-title">
                        修改中奖数量
                    </h5>
                </div>
                <div class="modal-body">
                     中奖数量：<input style="width:400px" type="number" step="1" name="stock" min="0" id="stock" value="">
                    <input type="hidden" value="" name="s_id" id="s_id">
                </div>
                <div class="modal-footer" style="height:60px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
                    <button type="button" class="btn btn-primary member">修改</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true" id="myModalSort" style="margin-top:200px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="height:40px;">
                    <h5 class="modal-title">
                        修改排序
                    </h5>
                </div>
                <div class="modal-body">
                     排序：<input style="width:400px" type="number" step="1" name="sort" min="0" id="sort" value="">
                    <input type="hidden" value="" name="s_id" id="s_id">
                </div>
                <div class="modal-footer" style="height:60px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
                    <button type="button" class="btn btn-primary member">修改</button>
                </div>
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

<script>
    function edit_sort($id,$sort){
        $("#s_id").val($id);
        $("#sort").val($sort);
    }

    function edit_stock($id,$stock){
        $("#s_id").val($id);
        $("#stock").val($stock);
    }

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
                url: "<?= $urlManager->createUrl(['mch/lottery/default/edit']) ?>",
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
    $(document).on("click", ".member", "click", function () {
        var btn = $(this);
        btn.btnLoading(btn.text());

        var id = $("#s_id").val();
        var stock = $("#stock").val();
        var sort = $("#sort").val();
        $.ajax({
            url: "<?= $urlManager->createUrl(['mch/lottery/default/edit']) ?>",
            type: 'post',
            dataType: 'json',
            data: {
                id:id,
                stock:stock,
                sort:sort,
                _csrf:_csrf
            },
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    $('#myModal').css('display','none');
                    $.myAlert({
                        content: "修改成功",confirm:function(e){
                            window.location.reload();
                        }
                    });
                }else{
                    $('#myModal').css('display','none');
                    $.myAlert({
                        content: res.msg,confirm:function(e){
                            window.location.reload();
                        }
                    });
                }
            }
        });
    })
</script>