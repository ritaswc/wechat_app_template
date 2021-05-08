<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/19
 * Time: 9:02
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<template v-if="item.type == 'banner'">
    <template v-if="item.param.list.length == 0">
        <div class="block-default block-default-img d-flex justify-content-center align-items-center"
             :data-index="index">
            <div class="pic-list d-flex align-items-center"
                 :class="'pic-list-' + item.param.style">
                <div :class="'fill-' + item.param.fill"
                     :style="{'backgroundImage':'url(<?= $statics ?>/mch/images/default.png)','height':item.param.height/2+'px'}">
                </div>
            </div>
        </div>
    </template>
    <template v-else>
        <div class="block-default block-default-img"
             :data-index="index">
            <div :class="'pic-list d-flex align-items-center banner-carousel ' + 'pic-list-' + item.param.style"
                 :data-style="item.param.style">
                <template v-for="(value,key) in item.param.list">
                    <div :class="'fill-' + item.param.fill"
                         :style="{'backgroundImage':'url(' + value.pic_url + ')','height':item.param.height/2+'px'}"
                         :data-count="key">
                        <img @load="imagesLoad" class="pic-list-img" :src="value.pic_url"
                             style="display:block; visibility: hidden;">
                    </div>
                </template>
            </div>
        </div>
    </template>
</template>

<template v-if="item.type == 'search'">
    <div class="block-default block-default-search d-flex justify-content-center align-items-center" :data-index="index"
         :style="{backgroundColor:item.param.backgroundColorW}">
        <div
            :class="'search d-flex align-items-center ' + (item.param.textPosition == 'center' ? 'text-center' : '')"
            :style="{backgroundColor:item.param.backgroundColor,borderRadius:item.param.borderRadius/2 + 'px',color:item.param.color}">
            <div class="d-flex align-items-center w-100" :class="item.param.textPosition == 'center' ? 'justify-content-center' : ''">
                <img src="<?= $statics ?>/wxapp/images/icon-search.png">
                <div class="text text-more">{{item.param.text}}</div>
            </div>
        </div>
    </div>
</template>

<template v-if="item.type == 'nav'">
    <div class="block-default block-default-nav d-flex" :data-index="index">
        <div class="nav d-flex"
             :style="{backgroundColor:item.param.backgroundColor,overflowX:(item.param.is_slide == 'true' ? 'auto' : 'hidden'),'min-height':(item.param.list.length > 0 ? 'auto' : '100px')}">
            <template v-for="(value,key) in item.param.default_list">
                <div class="d-flex h-100 flex-wrap" style="flex-shrink: 0;width: auto;max-width: 100%;">
                    <template v-for="(v,k) in value">
                        <div :class="'nav-block nav-item-' + (item.param.count >= 5 ? 5 : item.param.count)">
                            <img :src="v.pic_url" v-if="v.pic_url">
                            <div v-else style="width: 42px;height: 42px;"></div>
                            <div class="text-more">{{v.name}}</div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</template>

<template v-if="item.type == 'notice'">
    <div class="block-default block-default-notice d-flex justify-content-center align-items-center"
         :data-index="index">
        <div class="notice d-flex align-items-center"
             :style="{backgroundColor:item.param.bg_color,color:item.param.color}">
            <div class="">
                <img :src="item.param.icon ? item.param.icon : defaultList.notice.icon">
                <span class="text">{{item.param.name}}</span>
            </div>
            <div class="col" style="padding-left: 20px;overflow: hidden;white-space: nowrap">{{item.param.content}}
            </div>
            <img style="width: 5px;height: 9px;margin-right: 0;margin-left: 8px;"
                 src="<?= $statics ?>/wxapp/images/icon-notice-jiantou.png">
        </div>
    </div>
</template>

<template v-if="item.type == 'topic'">
    <template v-if="item.param.style && item.param.style == 1">
        <div class="block-default block-default-topic"
             :data-index="index">
            <template v-if="item.param.is_cat == 1 && item.param.list.length != 1">
                <div class="d-flex align-items-center goods-cat" style="background-color: #fff;margin-bottom: 5px">
                    <template v-if="item.param.list.length > 0">
                        <template v-for="(value, key) in item.param.list">
                            <div
                                :class="'goods-cat-one goods-cat-one-0' + (key == 0 ? ' active' : '')">
                                <div>{{value.name}}</div>
                            </div>
                        </template>
                    </template>
                    <template v-else>
                        <template v-for="(value, key) in defaultList.topic.list">
                            <div class="goods-cat-one active goods-cat-one-0">
                                <div>{{value.cat_name}}一</div>
                            </div>
                            <div class="goods-cat-one">{{value.cat_name}}二</div>
                            <div class="goods-cat-one">{{value.cat_name}}三</div>
                            <div class="goods-cat-one">{{value.cat_name}}四</div>
                        </template>
                    </template>
                </div>
            </template>
            <div>
                <template
                    v-for="(value,key) in ((item.param.list.length > 0 && item.param.list[0].goods_list.length > 0) ? item.param.list[0].goods_list : defaultList.topic.list[0].goods_list)">
                    <template v-if="value.layout == 0">
                        <div class="d-flex flex-row topic-one">
                            <div class="d-flex flex-grow-1 flex-column">
                                <div class="flex-grow-1">
                                    <div class="text-more-2" style="-webkit-line-clamp:3;">{{value.title}}</div>
                                </div>
                                <div class="flex-grow-0" style="color: #888;font-size: 9px;">{{value.read_count}}人浏览
                                </div>
                            </div>
                            <div class="flex-grow-0">
                                <div class="topic-img-0" :style="{'backgroundImage':'url('+value.cover_pic+')'}"></div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="topic-one">
                            <div class="text-more-2">{{value.title}}</div>
                            <div class="topic-img-1" :style="{'backgroundImage':'url('+value.cover_pic+')'}"></div>
                            <div style="color: #888;font-size: 9px;">{{value.read_count}}人浏览</div>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </template>
    <template v-else>
        <div class="block-default block-default-topic d-flex justify-content-center align-items-center"
             :data-index="index">
            <div :class="'d-flex align-items-center topic-' + item.param.count">
                <img
                    :src="item.param['logo_'+item.param.count] ? item.param['logo_'+item.param.count] : defaultList.topic[['logo_'+item.param.count]]">
                <div class="">
                    <div>
                        <img style="width: 28.8px;height: 14.9px;"
                             :src="item.param.heated ? item.param.heated : defaultList.topic.heated">
                        <span class="text">这是一条专题示例</span>
                    </div>
                    <template v-if="item.param.count == 2">
                        <div>
                            <img style="width: 27px;height: 14px;"
                                 :src="item.param.heated ? item.param.heated : defaultList.topic.heated">
                            <span class="text">这是一条专题示例</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</template>

<template v-if="item.type == 'link'">
    <div class="block-default block-default-notice d-flex justify-content-center align-items-center"
         :data-index="index">
        <div class="notice d-flex align-items-center" style="padding: 0"
             :style="{backgroundColor:item.param.backgroundColor,color:item.param.color}">
                <div class="link-img d-flex align-items-center"
                     :style="{'backgroundImage':'url('+ (item.param.is_icon == 1 ? item.param.icon : '')+')', backgroundPosition:item.param.position/2 +'px'}">
                    <div class="text-more link-text" :style="{'padding-left':item.param.left/2+'px',width: 'calc(100%'+(item.param.is_jiantou == 1 ? ' - 17px' : '')+')'}" style="">
                        {{item.param.name}}
                    </div>
                    <img style="width: 5px;height: 9px;margin: 0;" src="<?= $statics ?>/wxapp/images/icon-jiantou-r.png"
                         v-if="item.param.is_jiantou == 1">
                </div>
        </div>
    </div>
</template>

<template v-if="item.type == 'line'">
    <div class="block-default block-default-line d-flex justify-content-center align-items-center"
         :data-index="index">
        <div class="line"
             :style="{'height':item.param.height/2+'px','backgroundColor':item.param.backgroundColor}"></div>
    </div>
</template>

<template v-if="item.type == 'ad'">
    <div class="block-default block-default-ad d-flex justify-content-center align-items-center"
         :data-index="index">这是一个流量主广告
    </div>
</template>

<template v-if="item.type == 'rubik'">
    <div class="block-default block-default-img"
         :data-index="index">
        <div class="pic-list d-flex align-items-center rubik-list" style="min-height: 180px"
             :style="{'minHeight':item.param.new_minHeight / 2+'px','margin':'-'+item.param.space+'px','width':'calc(100% + ' + item.param.space*2 + 'px)','height':'calc(100% + ' + item.param.space*2 + 'px)'}">
            <template v-for="(value,key) in item.param.list">
                <div
                    :style="{'left':value.new_left / 2+'px','top':value.new_top / 2+'px','width':value.new_width / 2+'px','height':(value.new_height == -1 ? 'auto' : value.new_height / 2+'px'),'position':item.param.style == 0 ? 'relative' : ''}">
                    <div class="fill-1" style="background-position: center;width: 100%;height: 100%;;"
                         :style="{'backgroundImage':'url('+value.pic_url+')'}">
                        <img @load="imagesLoad" class="pic-list-img" :src="value.pic_url"
                             :style="{'width':item.param.style == 0 ? '100%' : '70px'}"
                             style="display:block; visibility: hidden;">
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<template v-if="item.type == 'rubik1'">
    <div class="block-default block-default-img"
         :data-index="index">
        <div class="pic-list clearfix"
             :style="{'minHeight':item.param.new_minHeight,'margin':'-'+item.param.space+'px','width':'calc(100% + ' + item.param.space*2 + 'px + 4px)','height':'calc(100% + ' + item.param.space*2 + 'px)'}">
            <template v-for="(value,key) in item.param.list">
                <div style="float: left"
                     :style="{'width':value.new_width,'height':value.new_height,'margin':(value.width > 0 ? item.param.space+'px' : 0)}">
                    <div class="fill-1"
                         :style="{'backgroundImage':'url('+value.pic_url+')','width':'100%','height':'100%'}">
                        <img @load="imagesLoad" class="pic-list-img" :src="value.pic_url"
                             style="display:block; visibility: hidden;">
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<template v-if="item.type == 'video'">
    <div class="block-default block-default-video d-flex justify-content-center align-items-center"
         :data-index="index" :style="{'backgroundImage':'url('+item.param.pic_url+')'}">
        <img src="<?= $statics ?>/wxapp/images/video-play.png" style="width: 50px;height: 50px;">
    </div>
</template>

<template v-if="item.type == 'coupon'">
    <div class="block-default block-default-coupon d-flex"
         :data-index="index">
        <div class="coupon-one d-flex" :style="{'backgroundImage':'url('+item.param.bg+')','color':item.param.color}">
            <div style="width: 190px;">
                <div class="d-flex justify-content-center align-items-center" style="height: 40px;">￥<span
                        style="font-size: 19px">10.00</span></div>
                <div class="d-flex justify-content-center align-items-center" style="height: 25px;">满900可用</div>
            </div>
            <div>未领取</div>
        </div>
        <div class="coupon-one d-flex align-items-center"
             :style="{'backgroundImage':'url('+item.param.bg_1+')','color':item.param.color}">
            <div style="width: 190px;">
                <div class="d-flex justify-content-center align-items-center" style="height: 40px;">￥<span
                        style="font-size: 19px">10.00</span></div>
                <div class="d-flex justify-content-center align-items-center" style="height: 25px;">满900可用</div>
            </div>
            <div>已领取</div>
        </div>
        <div class="coupon-one d-flex align-items-center"
             :style="{'backgroundImage':'url('+item.param.bg+')','color':item.param.color}">
            <div style="width: 190px;">
                <div class="d-flex justify-content-center align-items-center" style="height: 40px;">￥<span
                        style="font-size: 19px">10.00</span></div>
                <div class="d-flex justify-content-center align-items-center" style="height: 25px;">满900可用</div>
            </div>
            <div>未领取</div>
        </div>
        <div class="coupon-one d-flex align-items-center"
             :style="{'backgroundImage':'url('+item.param.bg_1+')','color':item.param.color}">
            <div style="width: 190px;">
                <div class="d-flex justify-content-center align-items-center" style="height: 40px;">￥<span
                        style="font-size: 19px">10.00</span></div>
                <div class="d-flex justify-content-center align-items-center" style="height: 25px;">满900可用</div>
            </div>
            <div>已领取</div>
        </div>
    </div>
</template>

<template v-if="item.type == 'goods'">
    <?= $this->render('tempfile/goods.php'); ?>
</template>

<template v-if="item.type == 'time'">
    <div class="block-default"
         :data-index="index">
        <div :class="'time-img time-img-' +  (item.param.pic_url ? 1 : 0)">
            <img :src="item.param.pic_url">
        </div>
        <div style="width: 100%;height: 70px;background-size: contain;padding: 12px;color: #fff;"
             :style="{'backgroundImage':'url('+item.param.pic_url_1+')'}">
            <div>距离活动开始还有</div>
            <div>xx天xx小时xx分xx秒</div>
        </div>
    </div>
</template>

<template v-if="item.type == 'miaosha'">
    <?= $this->render('tempfile/goods.php'); ?>
</template>

<template v-if="item.type == 'pintuan'">
    <?= $this->render('tempfile/goods.php'); ?>
</template>

<template v-if="item.type == 'bargain'">
    <?= $this->render('tempfile/goods.php'); ?>
</template>

<template v-if="item.type == 'book'">
    <?= $this->render('tempfile/goods.php'); ?>
</template>

<template v-if="item.type == 'lottery'">
    <?= $this->render('tempfile/goods.php'); ?>
</template>

<template v-if="item.type == 'shop'">
    <div class="block-default" :data-index="index">
        <div class="shop">
            <template v-for="(value,key) in (item.param.list.length > 0 ? item.param.list : defaultList.shop.list)">
                <div class="shop-one d-flex">
                    <div class="d-flex align-items-center">
                        <img :src="value.pic_url">
                    </div>
                    <div class="col" style="min-width: 0;font-size:9px">
                        <div class="text-more" v-if="item.param.name == 1">{{value.name}}</div>
                        <div class="d-flex" style="margin-top: 10px;" v-if="item.param.score == 1">
                            <div>评分：</div>
                            <div>
                                <template v-for="v in value.score">
                                    <img :src="defaultList.shop.love"
                                         style="width: 10px;height: 10px;margin-right: 2px;">
                                </template>
                            </div>
                        </div>
                        <div style="margin-top: 11px;" v-if="item.param.mobile == 1">电话：{{value.mobile}}</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-center">
                                <img :src="defaultList.shop.navigate"
                                     style="width: 25px; height: 25px;border-radius: 0;margin-bottom: 8px;">
                            </div>
                            <div>一键导航</div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<template v-if="item.type == 'mch'">
    <div class="block-default" :data-index="index">
        <template v-if="item.param.is_goods == 0">
            <div class="mch">
                <div class="d-flex" style="overflow-x: auto">
                    <template
                        v-for="(value, key) in (item.param.list.length > 0 ? item.param.list : defaultList.mch.list)">
                        <div class="mch-one-0">
                            <div class="mch-img-0">
                                <img :src="value.pic_url" style="width: 100%;height:100%;">
                            </div>
                            <div class="text-center">{{value.name}}</div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
        <template v-else>
            <div>
                <template v-for="(value, key) in (item.param.list.length > 0 ? item.param.list : defaultList.mch.list)">
                    <div class="mch-one-1">
                        <div class="mch-top d-flex align-items-center">
                            <img :src="value.pic_url" style="width: 50px;height: 50px;">
                            <div class="flex-grow-1" style="padding: 0 12px;">
                                <div class="text-more">{{value.name}}</div>
                                <div style="color: #666">商品数：{{value.goods_count}}</div>
                            </div>
                            <div class="">
                                <div class="mch-btn">进店逛逛</div>
                            </div>
                        </div>
                        <div class="mch-goods d-flex">
                            <template
                                v-if="(value.goods_style == 2 && value.goods_list.length == 0) || (value.goods_style < 2 && value.goods_count == 0)">
                                <div class="w-100" style="padding: 8px;color: #888;text-align: center">暂无商品</div>
                            </template>
                            <template v-else>
                                <template v-for="(v, k) in value.goods_list">
                                    <div class="mch-goods-one flex-grow-0"
                                         :style="{'backgroundImage':'url('+v.pic_url+')'}">
                                        <div class="mch-goods-price text-more">￥{{v.price}}</div>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
</template>

<template v-if="item.type == 'integral'">
    <div class="block-default block-default-coupon d-flex" v-if="item.param.is_coupon == 1"
         :data-index="index">
        <div class="coupon-one d-flex" :style="{'backgroundImage':'url('+item.param.bg_1+')','color':item.param.color}" v-for="(value, key) in 3">
            <div style="width: 190px;">
                <div class="d-flex justify-content-center align-items-center" style="height: 40px;">￥<span
                        style="font-size: 19px">10.00</span></div>
                <div class="d-flex justify-content-center align-items-center" style="height: 25px;">满900可用</div>
            </div>
            <div style="width: 36px;line-height: 1.3;">立即兑换</div>
        </div>
    </div>
    <template v-if="item.param.is_goods == 1">
        <?= $this->render('tempfile/goods.php'); ?>
    </template>
</template>

<template v-if="item.type == 'modal'">
    <div style="width: 100%;height: 0;"></div>
</template>

<template v-if="item.type == 'float'">
    <div style="width: 100%;height: 0;"></div>
</template>