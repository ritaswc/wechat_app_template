<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '商品秒杀详情';
$this->params['active_nav_group'] = 10;
?>
<style>
    .miaosha-item:hover {
        box-shadow: 0 1px 1px 1px rgba(0, 0, 0, .2);
    }
</style>
<div class="panel mb-3">
    <div class="panel-header">商品秒杀详情：<?= $list[0]['name'] ?></div>
    <div class="panel-body">
        <form method="get" class="input-group mb-3" style="max-width: 30rem;">
            <input type="hidden" name="r" value="<?= Yii::$app->request->get('r') ?>">
            <input type="hidden" name="goods_id" value="<?= Yii::$app->request->get('goods_id') ?>">
            <span class="input-group-addon">日期查找</span>
            <input class="form-control" id="date_begin" value="<?= $date_begin ?>" name="date_begin">
            <span class="input-group-addon">~</span>
            <input class="form-control" id="date_end" value="<?= $date_end ?>" name="date_end">
            <span class="input-group-btn">
                    <button class="btn btn-secondary">查找</button>
                </span>
        </form>
        <?php foreach ($list as $item) : ?>
            <?php $item['attr'] = json_decode($item['attr'], true); ?>
            <table class="card-block table bg-white table-bordered">
                <thead>
                <tr>
                    <td colspan="<?= count($item['attr'][0]['attr_list']) + 2 ?>">
                        <span class="mr-3">秒杀日期：<?= $item['open_date'] ?></span>
                        <span class="mr-3">秒杀时间：<?= $item['start_time'] < 10 ? '0' . $item['start_time'] : $item['start_time'] ?>
                            :00~<?= $item['start_time'] < 10 ? '0' . $item['start_time'] : $item['start_time'] ?>
                            :59</span>
                        <span class="mr-3">限购数量：<?= $item['buy_max'] == 0 ? '不限购' : ($item['buy_max'] . '件') ?></span>
                        <span class="mr-3">限购单数：<?= $item['buy_limit'] == 0 ? '不限单' : ($item['buy_limit'] . '单') ?></span>
                        <div class="float-right">
                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['mch/miaosha/goods-detail-edit', 'id' => $item['id']]) ?>">编辑</a>
                            <a class="btn btn-sm btn-danger delete-btn"
                               href="<?= $urlManager->createUrl(['mch/miaosha/miaosha-delete', 'id' => $item['id']]) ?>">删除</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th colspan="<?= count($item['attr'][0]['attr_list']) ?>">规格</th>
                    <th>秒杀价</th>
                    <th>数量</th>
                </tr>
                </thead>
                <?php foreach ($item['attr'] as $index => $attr_item) : ?>
                    <tr>
                        <?php foreach ($attr_item['attr_list'] as $attr) : ?>
                            <td><?= $attr['attr_name'] ?></td>
                        <?php endforeach; ?>
                        <td>
                            <input type="number" data-id="<?=$item['id']?>" data-value="<?=$attr_item['miaosha_price']?>" data-index="<?=$index?>" class="miaosha_price" step="0.01" value="<?= $attr_item['miaosha_price'] ?>">
                        </td>
                        <td>
                            <input type="number" data-id="<?=$item['id']?>" data-value="<?=$attr_item['miaosha_num']?>" data-index="<?=$index?>" class="miaosha_num" step="0.01" value="<?= $attr_item['miaosha_num'] ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?php echo \yii\widgets\LinkPager::widget([
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
    $(document).on("click", ".delete-btn", function () {
        var url = $(this).attr("href");
        $.confirm({
            content: "确认删除？",
            confirm: function () {
                $.loading();
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function (res) {
                        location.reload();
                    }
                });
            }
        });
        return false;
    });

    $(document).on('blur',".miaosha_price",function () {
        var id = $(this).data('id');
        var index = $(this).data('index');
        var price = $(this).val();
        var old = $(this).data('value');
        if(price<=0){
          $(this).val(old);
          return
        }

        if(price == old)
            return;
        var page = this;
        layer.msg('加载中', {
            icon: 16,
            shade: 0.01,
            time:1000,
        });
        $.ajax({
            url: "<?= Yii::$app->urlManager->createUrl(['mch/miaosha/miaosha-price-edit'])?>",
            data:{id:id,index:index,price:price},
            type: "get",
            dataType: "json",
            success: function (res) {
                $(page).data('value',price);
                $(page).val(price);
            }
        });
        return false;
    });

    $(document).on('blur',".miaosha_num",function () {
        var id = $(this).data('id');
        var index = $(this).data('index');
        var num = $(this).val();
        var old = $(this).data('value');
        if(num == old)
            return;

        var page = this;
        layer.msg('加载中', {
            icon: 16,
            shade: 0.01,
            time:1000,
        });
        $.ajax({
            url: "<?= Yii::$app->urlManager->createUrl(['mch/miaosha/miaosha-num-edit'])?>",
            data:{id:id,index:index,num:num},
            type: "get",
            dataType: "json",
            success: function (res) {
                $(page).data('value',num);
                $(page).val(num);
            }
        });
        return false;
    });

    $.datetimepicker.setLocale('zh');

    $(function () {
        $('#date_begin').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $('#date_end').val() ? $('#date_end').val() : false
                })
            },
            timepicker: false
        });
        $('#date_end').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    minDate: $('#date_begin').val() ? $('#date_begin').val() : false
                })
            },
            timepicker: false
        });
    });

</script>