<style>
    .table {
        max-width: 500rem;
    }

    tr {
        max-width: 500rem;
    }

    .td-1 {
        width: 50%;
    }

    .td-2 {
        width: 40%;
    }

    .td-3 {
        width: 10%;
    }
</style>

<p>尊敬的:<b><?php echo $store_name; ?></b></p>
<?php if ($type != 3) : ?>
    <h1>您有一个新的订单</h1>
    <p>订单号：<?= $order['order_no'] ?></p>
    <table class="table">
        <thead>
        <tr>
            <td class="td-1">商品名</td>
            <td class="td-2">规格</td>
            <td class="td-3">数量</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($goods_list as $k => $v) : ?>
            <tr>
                <td class="td-1"><?= $v['name'] ?></td>
                <td class="td-2">
                    <?php foreach (json_decode($v['attr'], true) as $index => $value) : ?>
                        <span style="font-size: 10px;"><?= $value['attr_group_name'] ?>
                            ：<?= $value['attr_name'] ?></span>
                    <?php endforeach; ?>
                </td>
                <td class="td-3"><?= $v['num'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if ($order['name']) : ?>
        <p>收货人：<?= $order['name'] ?></p>
    <?php endif; ?>
    <?php if ($order['mobile']) : ?>
        <p>收货人电话：<?= $order['mobile'] ?></p>
    <?php endif; ?>
    <?php if ($order['address']) : ?>
        <p>收货地址：<?= $order['address'] ?></p>
    <?php endif; ?>
<?php else : ?>
    <h1>您有一个新的预约</h1>
    <p>订单号：<?= $order['order_no'] ?></p>
    <div>商品名称：<?=$goods_list['name']?></div>
<?php endif; ?>
<p>下单时间：<?= date('Y-m-d H:i:s', $order['addtime']); ?></p>
<p>请及时进入商城处理</p>
