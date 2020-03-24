<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

$urlManager = Yii::$app->urlManager;
?>

<!-- 修改商品名称 -->
<div class="modal fade" id="editGoodsName">
    <div style="width: auto;" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">修改商品名称</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">

                <div class="form-group row">
                    <div style="margin-right: 10px;" class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label required">商品名称</label>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control goods-name" value="">
                    </div>
                </div>

                <input style="display: none;" class="goods-id" name="goodsId" value="">
                <input style="display: none;" class="goods-type" name="goodsType" value="">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-primary update-goods-name" href="javascript:">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var gn = new Vue({
        el: '#editGoodsName',
        data: {
            list:<?= Yii::$app->serializer->encode($goodsList) ?>
        }
    });
    // 弹框
    $(document).on("click", ".edit-good-name", function () {
        var goodsType = '<?= $goodsType ?>';
        var index = $(this).data('index');
        var name = gn.list[index].name;
        var goodsId = gn.list[index].id;

        $('.goods-name').val(name);
        $('.goods-type').val(goodsType);
        $('.goods-id').val(goodsId);
        $('#editGoodsName').modal('show');
    });

    $(document).on('click', '.update-goods-name', function () {
        $('.update-goods-name').btnLoading('更新中');
        var href = '<?= $urlManager->createUrl(['mch/goods/update-goods-name']) ?>';
        $.ajax({
            url: href,
            type: "post",
            data: {
                goodsId: $('.goods-id').val(),
                goodsType: $('.goods-type').val(),
                goodsName: $('.goods-name').val(),
                _csrf: _csrf
            },
            dataType: "json",
            success: function (res) {
                $('.update-goods-name').btnReset();
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        if (res.code == 0) {
                            location.reload();
                        }
                    }
                })
            }
        });
        return false;
    })

</script>
