<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/10/9
 * Time: 15:42
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div class="block-default block-default-goods"
     :data-index="index">
    <template v-if="item.param.is_cat == 1 && item.param.cat_position == 1 && item.type == 'goods'">
        <div class="d-flex flex-wrap" style="padding: 0 5px;">
            <div class="cat-position-left">
                <template
                    v-for="(value, key) in (item.param.list.length > 0 ? item.param.list : defaultList.goods.list)">
                    <div class="goods-cat-one">
                        <div class="text-more">{{value.name}}</div>
                    </div>
                </template>
            </div>
            <div style="width: 271px;background-color: #eee">
                <template
                    v-for="(value, key) in (item.param.list.length > 0 ? item.param.list : defaultList.goods.list)">
                    <div class="cat-position-right">
                        <div style="padding: 5px 10px">
                            <div class="text-more" style="width: 100px">{{value.name}}</div>
                        </div>
                        <template v-for="(v, k) in defaultList.goods.list[0].goods_list">
                            <div class="goods-cat-goods d-flex">
                                <div class="goods-img-1" style="width: 76px;">
                                    <div class="goods-img fill-1" data-per="0"
                                         :style="{'backgroundImage':'url('+v.pic_url+')'}">
                                        <img v-if="item.param.mark == 1" :src="item.param.mark_url">
                                    </div>
                                </div>
                                <div style="margin-left: 6px;width: 169px;">
                                    <div class="text-more" v-if="item.param.name==1">{{v.name}}</div>
                                    <div style="margin-top: 4px;color: #ff4544;" v-if="item.param.price==1">￥999.99
                                    </div>
                                    <div class="d-flex justify-content-end" v-if="item.param.buy==1">
                                        <div style="max-width: 100px;"
                                             v-if="item.param.buy_list >= 2">
                                            <div :class="'text-more buy-btn-' + item.param.buy_list"
                                                 style="width: auto;">{{item.param.buy_content ?
                                                item.param.buy_content : item.param.buy_default}}
                                            </div>
                                        </div>
                                        <div v-else style="width: 25px;">
                                            <div class="goods-img fill-1" data-per="0"
                                                 :style="{'backgroundImage':'url('+item.param.buy_content+')'}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </template>
    <template v-else>
        <template v-if="item.param.is_cat == 1 && item.param.list.length != 1">
            <div class="d-flex align-items-center goods-cat">
                <template v-if="item.param.list.length > 0">
                    <template v-for="(value, key) in item.param.list">
                        <div
                            :class="'goods-cat-one goods-cat-one-' + item.param.cat_style + (key == 0 ? ' active' : '')">
                            <div>{{value.name}}</div>
                        </div>
                    </template>
                </template>
                <template v-else>
                    <template v-for="(value, key) in defaultList.goods.list">
                        <div :class="'goods-cat-one active goods-cat-one-' + item.param.cat_style">
                            <div>{{value.cat_name}}一</div>
                        </div>
                        <div class="goods-cat-one">{{value.cat_name}}二</div>
                        <div class="goods-cat-one">{{value.cat_name}}三</div>
                        <div class="goods-cat-one">{{value.cat_name}}四</div>
                    </template>
                </template>
            </div>
        </template>
        <div :class="'d-flex ' + (item.param.list_style == 4 ? '' : 'flex-wrap')">
            <template
                v-for="(value,key) in ((item.param.list.length > 0 && item.param.list[0].goods_list.length > 0) ? item.param.list[0].goods_list : defaultList.goods.list[0].goods_list)">
                <div :class="'goods-one goods-one-' + item.param.list_style">
                    <div :class="'d-flex flex-wrap goods-border-' + item.param.style">
                        <div :class="'goods-img-' + item.param.list_style">
                            <div :class="'goods-img ' + ' fill-' + item.param.fill"
                                 :data-per="item.param.list_style == 0 ? item.param.per : 0"
                                 :style="{'backgroundImage':'url('+value.pic_url+')'}">
                                <img v-if="item.param.mark == 1" :src="item.param.mark_url">

                                <template
                                    v-if="(item.type == 'miaosha' || item.type == 'bargain' || item.type == 'lottery') && item.param.list_style != 1">
                                    <template v-if="item.param.time == 1">
                                        <div class="miaosha-time d-flex align-items-center justify-content-center"
                                             v-if="item.param.list_style == 0">
                                            <div class="col" style="padding: 0;font-size: 16px;text-align: left"
                                                 v-if="item.type == 'miaosha'">秒杀
                                            </div>
                                            <div class="col" style="padding: 0;font-size: 16px;text-align: left"
                                                 v-if="item.type == 'bargain'">砍价
                                            </div>
                                            <div class="col" style="padding: 0;font-size: 16px;text-align: left"
                                                 v-if="item.type == 'lottery'">抽奖
                                            </div>
                                            <div>距结束仅剩xx:xx:xx</div>
                                        </div>
                                        <div class="miaosha-time d-flex align-items-center justify-content-center"
                                             v-if="item.param.list_style == 1" style="height: 22px;">
                                            <div>仅剩xx:xx:xx</div>
                                        </div>
                                        <div class="miaosha-time d-flex align-items-center justify-content-center"
                                             v-if="item.param.list_style == 2" style="height: 22px;">
                                            <div>距结束仅剩xx:xx:xx</div>
                                        </div>
                                    </template>
                                </template>

                            </div>
                        </div>
                        <div
                            :class="'d-flex flex-wrap align-items-end goods-content goods-content-' + item.param.list_style">
                            <template
                                v-if="(item.type == 'miaosha' || item.type == 'bargain' || item.type == 'lottery') && item.param.list_style == 1 && item.param.time == 1">
                                <div v-if="item.param.name == 1" class="goods-name text-more" style="width:100%">
                                    {{value.name}}
                                </div>
                                <div class="d-flex align-items-center justify-content-center" style="height: 22px;font-size: 9px">
                                    <img src="<?= $statics ?>/wxapp/images/lottery_time.png"
                                         style="width: 12px;height: 12px;margin-right: 12px;">
                                    <div>距结束仅剩<span style="color: #ff4544;">xx:xx:xx</span></div>
                                </div>
                            </template>
                            <template v-else>
                                <div v-if="item.param.name == 1"
                                     :class="'goods-name ' + (item.param.list_style == 0 ? 'text-more' : 'text-more-2') "
                                     :style="{'width':((item.param.price == 0 && item.param.buy == 1 && item.param.list_style == 0) ? 'calc(100% - 25px)' : '100%')}">
                                    {{value.name}}
                                </div>
                            </template>

                            <template v-if="item.param.price == 1">
                                <template v-if="item.type == 'goods'">
                                    <div v-if="item.param.price == 1" class="d-flex align-items-end goods-price flex-grow-1">
                                        <div class="flex-grow-1">{{value.is_negotiable == 0 ? '￥'+value.price : '价格面议'}}</div>
                                    </div>
                                </template>

                                <template v-if="item.type == 'miaosha'">
                                    <template v-if="item.param.list_style == 0">
                                        <div class="d-flex align-items-end flex-grow-1" :class="item.param.style >= 2 ? 'justify-content-center' : ''">
                                            <div style="color: #ff4544;">{{value.price_content ? value.price_content :
                                                '秒杀价:'}}￥<span
                                                    style="font-size: 24px">{{value.price}}</span></div>
                                            <div style="text-decoration: line-through;color: #aaa;" v-if="item.param.style < 2">
                                                ￥{{value.original_price}}
                                            </div>
                                        </div>
                                    </template>
                                    <template v-if="item.param.list_style == 1">
                                        <div style="color: #ff4544;width: 100%;font-size: 9px;line-height: 1;">{{value.price_content ?
                                            value.price_content : '秒杀价:'}}￥<span
                                                style="font-size: 19px">{{value.price}}</span>
                                        </div>
                                        <div class="flex-grow-1" style="color: #aaa;font-size: 9px">{{value.original_price_content
                                            ? value.original_price_content : '售价:'}}￥{{value.original_price}}
                                        </div>
                                    </template>
                                    <template v-if="item.param.list_style == 2">
                                        <div style="color: #ff4544;width: 100%;">{{value.price_content ?
                                            value.price_content : '秒杀价:'}}￥<span
                                                style="font-size: 24px">{{value.price}}</span>
                                        </div>
                                        <div style="color: #aaa;width: 100%;;">{{value.original_price_content ?
                                            value.original_price_content : '售价:'}}￥{{value.original_price}}
                                        </div>
                                    </template>
                                </template>

                                <template v-if="item.type == 'pintuan'">
                                    <template v-if="item.param.list_style == 0">
                                        <div class="d-flex align-items-end flex-grow-1"
                                             :class="item.param.style >= 2 ? 'justify-content-center' : ''">
                                            <div style="color: #ff4544;">{{value.price_content ? value.price_content :
                                                '2人团:'}}￥<span
                                                    style="font-size: 24px">{{value.price}}</span></div>
                                            <div style="text-decoration: line-through;color: #aaa;" v-if="item.param.style < 2">
                                                ￥{{value.original_price}}
                                            </div>
                                        </div>
                                    </template>
                                    <template v-if="item.param.list_style == 1">
                                        <div style="color: #ff4544;width: 100%;font-size: 9px;line-height: 1;">{{value.price_content ?
                                            value.price_content : '2人团:'}}￥<span
                                                style="font-size: 19px">{{value.price}}</span>
                                        </div>
                                        <div class="flex-grow-1" style="color: #aaa;font-size: 9px">
                                            {{value.original_price_content ? value.original_price_content :
                                            '单买价:'}}￥{{value.original_price}}
                                        </div>
                                    </template>
                                    <template v-if="item.param.list_style == 2">
                                        <div style="color: #ff4544;width: 100%;">{{value.price_content ?
                                            value.price_content : '2人团:'}}￥<span
                                                style="font-size: 24px">{{value.price}}</span>
                                        </div>
                                        <div style="color: #aaa;width: 100%;;">{{value.original_price_content ?
                                            value.original_price_content : '单买价:'}}￥{{value.original_price}}
                                        </div>
                                    </template>
                                </template>
                            </template>

                            <template v-if="item.type == 'bargain'">
                                <template v-if="item.param.list_style == 0">
                                    <div class="d-flex align-items-end flex-grow-1"
                                         :class="item.param.style >= 2 ? 'justify-content-center' : ''">
                                        <div style="color: #ff4544;">{{value.price_content ? value.price_content :
                                            '最低价:'}}￥<span
                                                style="font-size: 24px">{{value.price}}</span></div>
                                        <div style="text-decoration: line-through;color: #aaa;" v-if="item.param.style < 2">
                                            ￥{{value.original_price}}
                                        </div>
                                    </div>
                                </template>
                                <template v-if="item.param.list_style == 1">
                                    <div style="color: #ff4544;width: 100%;font-size: 9px;line-height: 1;">{{value.price_content ? value.price_content
                                        : '最低价:'}}￥<span style="font-size: 19px">{{value.price}}</span>
                                    </div>
                                    <div class="flex-grow-1" style="color: #aaa;font-size: 9px">{{value.original_price_content ?
                                        value.original_price_content : '售价:'}}￥{{value.original_price}}
                                    </div>
                                </template>
                                <template v-if="item.param.list_style == 2">
                                    <div style="color: #ff4544;width: 100%;">{{value.price_content ? value.price_content
                                        : '最低价:'}}￥<span style="font-size: 24px">{{value.price}}</span>
                                    </div>
                                    <div style="color: #aaa;width: 100%;;">{{value.original_price_content ?
                                        value.original_price_content : '售价:'}}￥{{value.original_price}}
                                    </div>
                                </template>
                            </template>

                            <template v-if="item.type == 'book'">
                                <div v-if="item.param.price == 1" class="d-flex align-items-end goods-price flex-grow-1"
                                     :class="item.param.style >= 2 ? 'justify-content-center' : ''">
                                    <div class="flex-grow-1">{{value.price == 0 ? '免费' : '￥'+value.price}}</div>
                                </div>
                            </template>

                            <template v-if="item.type == 'lottery'">
                                <template v-if="item.param.list_style == 0">
                                    <div class="d-flex align-items-end flex-grow-1"
                                         :class="item.param.style >= 2 ? 'justify-content-center' : ''">
                                        <div style="color: #ff4544;font-size: 19px;line-height: 1;">免费</div>
                                        <div style="text-decoration: line-through;color: #aaa;" v-if="item.param.style < 2">
                                            ￥{{value.original_price}}
                                        </div>
                                    </div>
                                </template>
                                <template v-if="item.param.list_style == 1">
                                    <div style="color: #ff4544;width: 100%;font-size: 20px">免费</div>
                                    <div class="flex-grow-1" style="color: #aaa;">{{value.original_price_content ?
                                        value.original_price_content : '原价:'}}￥{{value.original_price}}
                                    </div>
                                </template>
                                <template v-if="item.param.list_style == 2">
                                    <div style="color: #ff4544;width: 100%;font-size: 20px">免费</div>
                                    <div style="color: #aaa;width: 100%;;">{{value.original_price_content ?
                                        value.original_price_content : '原价:'}}￥{{value.original_price}}
                                    </div>
                                </template>
                            </template>

                            <template v-if="item.type == 'integral'">
                                <template v-if="item.param.list_style == 0">
                                    <div class="d-flex align-items-end flex-grow-1"
                                         :class="item.param.style >= 2 ? 'justify-content-center' : ''">
                                        <div style="color: #ff4544;"><span style="font-size:16px">{{value.integral_content}}</span>
                                        </div>
                                    </div>
                                </template>
                                <template v-if="item.param.list_style == 1">
                                    <div style="color: #ff4544;width: 100%;"><span style="font-size: 16px">{{value.integral_content}}</span>
                                    </div>
                                    <div class="flex-grow-1" style="color: #aaa;">原价:￥{{value.original_price}}
                                    </div>
                                </template>
                                <template v-if="item.param.list_style == 2">
                                    <div style="color: #ff4544;width: 100%;"><span style="font-size:16px">{{value.integral_content}}</span>
                                    </div>
                                    <div style="color: #aaa;width: 100%;;">原价:￥{{value.original_price}}</div>
                                </template>
                            </template>

                            <template
                                v-if="(item.type == 'goods' || item.type == 'book' || item.param.list_style != 2) && value.is_negotiable == 0">
                                <div class="d-flex justify-content-end align-items-end buy-btn"
                                     v-if="item.param.buy == 1"
                                     :style="{'width':((item.param.price == 0 && (item.param.list_style == 1 || item.param.list_style == 2)) ? '100%' : 'auto')}">
                                    <template
                                        v-if="(item.param.style < 2 || item.param.list_style == 1) && item.param.list_style < 3">
                                        <div :class="'text-more buy-btn-' + item.param.buy_list" style="width: auto;"
                                             v-if="item.param.buy_list >= 2">{{item.param.buy_content ?
                                            item.param.buy_content : item.param.buy_default}}
                                        </div>
                                        <div v-else :class="'buy-btn-' + item.param.buy_list"
                                             :style="{'backgroundImage':'url('+item.param.buy_content+')'}">
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>