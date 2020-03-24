<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/11/14
 * Time: 17:07
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">好店推荐</div>
    </div>
    <div class="bb-1 p-2">
        <div class="d-flex col align-items-center"><span>是否展示商品</span></div>
        <label class="radio-label radio-block"
               data-param="is_goods" data-item="1">
            <input type="radio" name="is_goods" :checked="item.param.is_goods == 1 ? true : ''">
            <span class="label-icon"></span>
            <span class="label-text">是</span>
        </label>
        <label class="radio-label radio-block"
               data-param="is_goods" data-item="0">
            <input type="radio" name="is_goods" :checked="item.param.is_goods == 0 ? true : ''">
            <span class="label-icon"></span>
            <span class="label-text">否</span>
        </label>
    </div>
    <template v-if="item.param.is_goods == 1">
        <div class="bb-1 p-2">
            <div class="" style="width: calc(100% - 30px);">
                <template v-for="(value,key) in item.param.list">
                    <div class="d-flex mb-2" style="position: relative">
                        <div class="banner-one param-key" :data-key="key">
                            <div class="form-group row mt-4">
                                <div class="form-group-label col-sm-4 text-right">
                                    <label class="col-form-label">商户名称</label>
                                </div>
                                <div class="form-group-label col-sm-8">
                                    <label class="col-form-label">{{value.name}}</label>
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <div class="form-group-label col-sm-4 text-right">
                                    <label class="col-form-label">商品显示</label>
                                    <br>
                                    <label class="col-form-label text-danger fs-sm">最多显示10个商品</label>
                                </div>
                                <div class="col-sm-8 d-flex flex-wrap">
                                    <label class="radio-label list-radio-block"
                                           data-param="goods_style" data-item="0">
                                        <input type="radio" :name="'goods_style_' + key"
                                               :checked="value.goods_style == 0 ? true : ''">
                                        <span class="label-icon"></span>
                                        <span class="label-text">全部</span>
                                    </label>
                                    <label class="radio-label list-radio-block"
                                           data-param="goods_style" data-item="1">
                                        <input type="radio" :name="'goods_style_' + key"
                                               :checked="value.goods_style == 1 ? true : ''">
                                        <span class="label-icon"></span>
                                    <span class="label-text">
                                        <input class="form-control input-num" style="width: 70px;" type="number" min="1"
                                               max="10"
                                               v-model="value.goods_num" data-param="goods_num">
                                    </span>
                                    </label>
                                    <div class="col-12" style="padding: 0;">
                                        <label class="radio-label list-radio-block"
                                               data-param="goods_style" data-item="2">
                                            <input type="radio" :name="'goods_style_' + key"
                                                   :checked="value.goods_style == 2 ? true : ''">
                                            <span class="label-icon"></span>
                                            <span class="label-text">自定义商品</span>
                                        </label>
                                        <div class="d-flex flex-wrap" v-if="value.goods_style == 2">
                                            <template v-for="(v, k) in value.goods_list">
                                                <div class="img-list" style="margin: 0 10px 10px 0;" :data-key="key"
                                                     :data-k="k">
                                                    <img :src="v.pic_url" style="width: 100%;height: 100%;">
                                                    <div class="chacha chacha-goods"></div>
                                                </div>
                                            </template>
                                            <div :data-key="key" v-if="value.goods_list.length < 10"
                                                 class="goods-add img-list d-flex justify-content-center align-items-center">
                                                +
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block-handle" :data-key="key">
                            <div class="handle list-delete">
                                <img src="<?= $statics ?>/mch/images/x.png">
                            </div>
                            <div class="handle list-up" v-if="key > 0">
                                <img src="<?= $statics ?>/mch/images/up.png">
                            </div>
                            <div class="handle list-down" style="transform: rotate(180deg);"
                                 v-if="key < item.param.list.length-1">
                                <img src="<?= $statics ?>/mch/images/up.png">
                            </div>
                        </div>
                    </div>
                </template>
                <div class="mch-add list-add d-flex justify-content-center" v-if="item.param.list.length < 8">+添加商户</div>
                <div>最多添加8个商户</div>
            </div>
        </div>
    </template>
    <template v-else>
        <div class="bb-1 p-2">
            <div class="d-flex col">
                <span>添加商户</span>
                <span class="fs-sm text-danger">最多添加8个商户</span>
            </div>
            <div class="d-flex flex-wrap banner-one">
                <template v-for="(v, k) in item.param.list">
                    <div class="img-list" style="margin: 0 10px 10px 0;" :data-k="k">
                        <img :src="v.pic_url" style="width: 100%;height: 100%;">
                        <div class="chacha chacha-mch"></div>
                    </div>
                </template>
                <div data-key="0" v-if="item.param.list.length < 8"
                     class="mch-add img-list d-flex justify-content-center align-items-center">
                    +
                </div>
            </div>
        </div>
    </template>
    <div class="bb-1 p-2" v-if="item.param.is_goods == 1">
        <div class="d-flex col"><span>显示内容</span></div>
        <div class="d-flex flex-wrap col">
            <label class="checkbox-label checkbox-block"
                   data-param="price">
                <input type="checkbox" :checked="item.param.price == 1 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">商品价格</span>
            </label>
        </div>
    </div>
</div>

