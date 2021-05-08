<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '秒杀';
$this->params['active_nav_group'] = 10;
?>
<style>
    .date-table td {
        vertical-align: top;
    }

    .date-table .start-time {
        display: inline-block;
        background: #444;
        color: #fff;
        padding: 3px 6px;
        font-size: .75rem;
        font-weight: bold;
    }

    .date-table .goods-list {

    }

    .date-table .goods-item {
        padding: .35rem .5rem;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        border: 1px solid #e3e3e3;
        border-bottom-width: 0;
    }

    .date-table .goods-item:last-child {
        border-bottom-width: 1px;
    }

</style>
<div class="date-table">
    <div class="row mb-3">
        <div class="col-2">
            <b>时间</b>
        </div>
        <div class="col-10">
            <b>商品</b>
        </div>
    </div>
    <?php foreach ($list as $item) : ?>
        <div class="row mb-3">
            <div class="col-2">
                <div class="start-time"><?= $item['start_time'] < 10 ? ('0' . $item['start_time'] . ':00') : ($item['start_time'] . ':00') ?></div>
            </div>
            <div class="col-10">
                <div class="goods-list">
                    <?php foreach ($item['goods_list'] as $goods) : ?>
                        <div class="goods-item"><?= $goods['name'] ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>