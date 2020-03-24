<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 */
defined('YII_ENV') or exit('Access Denied');
$this->title = '商品管理';
?>

<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <form method="get" style="margin: -.3rem 0" class="float-right form-inline d-inline-block">
            <a class="btn btn-secondary" href="javascript:" data-toggle="modal"
               data-target=".update-goods-num-modal">更新库存</a>
            <select style="width: 10rem" class="form-control" name="cat_id">
                <option value="">选择分类</option>
                <?php foreach ($cat_list as $c) : ?>
                    <option <?= isset($get['cat_id']) && $get['cat_id'] == $c->id ? 'selected' : '' ?>
                            value="<?= $c->id ?>"><?= $c->name ?></option>
                    <?php foreach ($c->childrenList as $sc) : ?>
                        <option <?= isset($get['cat_id']) && $get['cat_id'] == $sc->id ? 'selected' : '' ?>
                                value="<?= $sc->id ?>">└─<?= $sc->name ?></option>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </select>
            <?php foreach ($get as $name => $value) :
                if (!in_array($name, ['cat_id', 'keyword',])) : ?>
                <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
                <?php endif;
            endforeach; ?>
            <input class="form-control mr-1" placeholder="商品名" name="keyword" value="<?= $keyword ?>">
            <button class="btn btn-secondary">搜索</button>
        </form>
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>商品</th>
                <th>库存</th>
                <th>排序</th>
                <th>上架</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php if (is_array($list) && count($list) > 0) : ?>
                <?php foreach ($list as $i => $item) : ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td><?= $item->name ?></td>
                        <td><?= $item->getNum() ?></td>
                        <td><?= $item->mch_sort ?></td>
                        <td>
                            <label class="switch-label" style="line-height: inherit">
                                <?php if ($item->status == 1) : ?>
                                    <span class="label-text">上架</span>
                                    <span>|</span>
                                    <a class="goods-status-switch-0" data-id="<?= $item->id?>"
                                       href="javascript:">下架</a>
                                <?php else : ?>
                                    <a class="apply"
                                       href="<?= Yii::$app->urlManager->createUrl(['user/mch/goods/apply', 'id' => $item->id]) ?>">申请上架</a>
                                <?php endif; ?>
                            </label>
                        </td>
                        <td>
                            <a href="<?= Yii::$app->urlManager->createUrl(['user/mch/goods/edit', 'id' => $item->id,]) ?>">编辑</a>
                            <span>|</span>
                            <a class="del-btn"
                               href="<?= Yii::$app->urlManager->createUrl(['user/mch/goods/delete', 'id' => $item->id,]) ?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center p-5 text-muted">暂无商品</td>
                </tr>
            <?php endif; ?>
        </table>
        <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
</div>

<div class="modal fade update-goods-num-modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="panel">
            <div class="panel-header">
                <span>更新商品库存</span>
                <div class="float-right">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="panel-body">
                <div class="alert alert-info rounded-0">当商品列表显示的库存与商品详情的库存不一致时，需要更新商品库存。</div>
                <div class="alert alert-success rounded-0 update-goods-num-res" style="display: none"></div>
                <a class="btn btn-primary update-goods-num-start" href="javascript:">开始更新</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('change', '.goods-status-switch', function () {
        var checkbox = $(this);
        var id = checkbox.attr('data-id');
        var checked = checkbox.prop('checked');
        $.loading('正在处理');
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['user/mch/goods/set-status'])?>',
            data: {
                id: id,
                status: checked ? 1 : 0,
            },
            dataType: 'json',
            success: function (res) {
                $.loadingHide();
                if (res.code != 0) {
                    $.alert({
                        content: res.msg,
                        confirm: function () {
                            checkbox.prop('checked', !checked);
                        }
                    });
                } else {
                    $.toast({
                        content: res.msg,
                    });
                    location.reload();
                }
            }
        });
    });

    $(document).on('click', '.update-goods-num-start', function () {
        var btn = $(this);
        btn.btnLoading('正在更新');
        $('.update-goods-num-res').hide();

        function update(offset) {
            $.ajax({
                url: '<?=Yii::$app->urlManager->createUrl(['user/mch/goods/update-goods-num'])?>',
                dataType: 'json',
                data: {
                    offset: offset,
                },
                success: function (res) {
                    if (res.code == 0) {
                        if (res.continue == 1) {
                            update(offset + 10);
                        } else {
                            $('.update-goods-num-res').html(res.msg).show();
                            btn.btnReset();
                        }
                    }
                }, error: function () {
                    btn.btnReset();
                }
            });
        }

        update(0);
    });


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

    $(document).on('click', '.apply', function () {
        var btn = $(this);
        $.confirm({
            content: '确认提交申请？',
            confirm: function () {
                $.loading('正在处理');
                $.ajax({
                    url: btn.attr('href'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        $.alert({
                            content: res.msg,
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

    $(document).on('click', '.goods-status-switch-0', function () {
        var checkbox = $(this);
        var id = checkbox.attr('data-id');
        $.loading('正在处理');
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['user/mch/goods/set-status'])?>',
            data: {
                id: id,
                status: 0,
            },
            dataType: 'json',
            success: function (res) {
                $.loadingHide();
                if (res.code != 0) {
                } else {
                    $.toast({
                        content: res.msg,
                    });
                    location.reload();
                }
            }
        });
    });
</script>