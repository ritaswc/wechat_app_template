<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/11/22
 * Time: 15:02
 */
$urlManager = Yii::$app->urlManager;
$this->title = '拼团';
$this->params['active_nav_group'] = 10;
$this->params['is_group'] = 1;
?>
<style>
    .data-block {
        background: #fff;
        padding: 0 1.5rem;
        display: block;
        border-radius: .35rem;
        text-decoration: none;
        color: inherit;
        margin-bottom: 2rem;
        height: 8rem;
    }

    .data-block.block-big {
        height: 18rem;
    }

    .data-block > div {
        height: 100%;
    }

    .data-block .title {
        text-align: right;
    }

    .data-block .iconfont {
        font-size: 4rem;
    }

    .data-block .content {
        font-size: 1.5rem;
        margin-bottom: .5rem;
        text-align: right;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .data-block:hover {
        text-decoration: none;
        color: inherit;
    }

    .data-block.green {
        background: #1ab394;
        color: #fff !important;
    }

    .data-block.blue {
        background: rgb(35, 198, 200);;
        color: #fff !important;
    }
</style>

<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a flex="cross:center" class="breadcrumb-item" href="<?= $urlManager->createUrl(['mch/store']) ?>">我的商城</a>
            <span flex="cross:center" class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body pt-5 pb-5 pl-3 pr-3">
    <div class="container-fluid">
        <div class="row">

            <!-- 总订单数据 -->
            <div class="col-xl-3 col-lg-6">
                <a href="javascript:" class="data-block green">
                    <div flex="dir:left box:first cross:center">
                        <div>
                            <i class="iconfont icon-zhexiantu"></i>
                        </div>
                        <div>
                            <div class="content"><?= $store_data['all_count']['all'] ?>笔</div>
                            <div class="title">总订单量</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6">
                <a href="javascript:" class="data-block green">
                    <div flex="dir:left box:first cross:center">
                        <div>
                            <i class="iconfont icon-zhexiantu"></i>
                        </div>
                        <div>
                            <div class="content"><?= $store_data['all_count']['1day'] ?>笔</div>
                            <div class="title">今日订单</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6">
                <a href="javascript:" class="data-block green">
                    <div flex="dir:left box:first cross:center">
                        <div>
                            <i class="iconfont icon-zhexiantu"></i>
                        </div>
                        <div>
                            <div class="content"><?= $store_data['all_count']['7day'] ?>笔</div>
                            <div class="title">7日订单</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6">
                <a href="javascript:" class="data-block green">
                    <div flex="dir:left box:first cross:center">
                        <div>
                            <i class="iconfont icon-zhexiantu"></i>
                        </div>
                        <div>
                            <div class="content"><?= $store_data['all_count']['30day'] ?>笔</div>
                            <div class="title">30日订单</div>
                        </div>
                    </div>
                </a>
            </div>



        </div>

    </div>
</div>
