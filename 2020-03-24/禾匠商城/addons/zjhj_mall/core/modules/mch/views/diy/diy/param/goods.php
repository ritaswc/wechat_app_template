<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/10/9
 * Time: 18:02
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">商品</div>
    </div>
    <div class="bb-1 p-2">
        <div class="d-flex col align-items-center"><span>是否展示分类</span></div>
        <label class="radio-label radio-block"
               data-param="is_cat" data-item="1">
            <input type="radio" name="is_cat" :checked="item.param.is_cat == 1 ? true : ''">
            <span class="label-icon"></span>
            <span class="label-text">是</span>
        </label>
        <label class="radio-label radio-block"
               data-param="is_cat" data-item="0">
            <input type="radio" name="is_cat" :checked="item.param.is_cat == 0 ? true : ''">
            <span class="label-icon"></span>
            <span class="label-text">否</span>
        </label>
    </div>
    <template v-if="item.param.is_cat == 1">
        <div class="bb-1 p-2">
            <div class="d-flex col align-items-center"><span>分类模板</span></div>
            <label class="radio-label radio-block"
                   data-param="cat_position" data-item="0">
                <input type="radio" name="cat_position" :checked="item.param.cat_position == 0 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">顶部菜单</span>
            </label>
            <label class="radio-label radio-block"
                   data-param="cat_position" data-item="1">
                <input type="radio" name="cat_position" :checked="item.param.cat_position == 1 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">左侧菜单</span>
            </label>
        </div>
        <template v-if="item.param.cat_position == 1">
        </template>
        <template v-else>
            <div class="bb-1 p-2">
                <div class="d-flex col align-items-center"><span>菜单样式</span></div>
                <div class="fs-sm text-danger col">注：当只有一个分类时，顶部分类不显示</div>
                <label class="radio-label radio-block"
                       data-param="cat_style" data-item="0">
                    <input type="radio" name="cat_style" :checked="item.param.cat_style == 0 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">样式一</span>
                </label>
                <label class="radio-label radio-block"
                       data-param="cat_style" data-item="1">
                    <input type="radio" name="cat_style" :checked="item.param.cat_style == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">样式二</span>
                </label>
            </div>
        </template>
        <div class="bb-1 p-2">
            <div class="" style="width: calc(100% - 30px);">
                <template v-for="(value,key) in item.param.list">
                    <div class="d-flex mb-2" style="position: relative">
                        <div class="banner-one param-key" :data-key="key">
                            <div class="form-group row mt-4">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">商品分类</label>
                                </div>
                                <div class="form-group-label col-sm-9">
                                    <label class="col-form-label">{{value.cat_name}}</label>
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">菜单名称</label>
                                </div>
                                <div class="form-group-label col-sm-9">
                                    <input class="form-control"
                                           v-model="value.name" data-param="name">
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">商品显示</label>
                                    <br>
                                    <label class="col-form-label text-danger fs-sm">最多显示30个商品</label>
                                </div>
                                <div class="col-sm-9 d-flex flex-wrap">
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
                                               max="30"
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
                                            <div :data-key="key" v-if="value.goods_list.length < 30"
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
                <div class="cat-add list-add d-flex justify-content-center" v-if="item.param.list.length < 8">+添加分类
                </div>
                <div>最多添加8个分类</div>
            </div>
        </div>
    </template>
    <template v-else>
        <div class="bb-1 p-2">
            <div class="d-flex col"><span>添加商品</span></div>
            <div class="d-flex flex-wrap banner-one">
                <template v-for="(v, k) in item.param.list[0].goods_list">
                    <div class="img-list" style="margin: 0 10px 10px 0;" :data-k="k">
                        <img :src="v.pic_url" style="width: 100%;height: 100%;">
                        <div class="chacha chacha-goods"></div>
                    </div>
                </template>
                <div data-key="0"
                     class="goods-add img-list d-flex justify-content-center align-items-center">
                    +
                </div>
            </div>
        </div>
    </template>
    <template v-if="item.param.cat_position == 1 && item.param.is_cat == 1">
    </template>
    <template v-else>
        <div class="p-2 bb-1">
            <div class="d-flex col"><span>列表样式</span></div>
            <div class="d-flex flex-wrap">
                <template v-for="(value,key) in defaultList.goods.list_style">
                    <label class="radio-label radio-block"
                           data-param="list_style" :data-item="value.param">
                        <input type="radio" name="list_style"
                               :checked="item.param.list_style == value.param ? true : ''">
                        <span class="label-icon"></span>
                        <span class="label-text" style="width: 4rem;text-align: left">{{value.name}}</span>
                    </label>
                </template>
            </div>
        </div>
        <div class="p-2 bb-1">
            <div class="d-flex col align-items-center"><span>填充方式</span></div>
            <label class="radio-label radio-block"
                   data-param="fill" data-item="1">
                <input type="radio" name="fill" :checked="item.param.fill == 1 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">填充</span>
            </label>
            <label class="radio-label radio-block"
                   data-param="fill" data-item="0">
                <input type="radio" name="fill" :checked="item.param.fill == 0 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">留白</span>
            </label>
        </div>
        <div class="bb-1 p-2" v-if="item.param.list_style == 0">
            <div class="d-flex col align-items-center"><span>显示比例</span></div>
            <label class="radio-label radio-block"
                   data-param="per" data-item="0">
                <input type="radio" name="per" :checked="item.param.per == 0 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">1:1</span>
            </label>
            <label class="radio-label radio-block"
                   data-param="per" data-item="1">
                <input type="radio" name="per" :checked="item.param.per == 1 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">3:2</span>
            </label>
        </div>
        <div class="bb-1 p-2">
            <div class="d-flex col align-items-center"><span>显示样式</span></div>
            <label class="radio-label radio-block"
                   data-param="style" data-item="0">
                <input type="radio" name="style" :checked="item.param.style == 0 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">常规</span>
            </label>
            <label class="radio-label radio-block"
                   data-param="style" data-item="1">
                <input type="radio" name="style" :checked="item.param.style == 1 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">边框</span>
            </label>
            <template v-if="item.param.list_style != 1">
                <label class="radio-label radio-block"
                       data-param="style" data-item="2">
                    <input type="radio" name="style" :checked="item.param.style == 2 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">居中显示</span>
                </label>
                <label class="radio-label radio-block"
                       data-param="style" data-item="3">
                    <input type="radio" name="style" :checked="item.param.style == 3 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">边框居中</span>
                </label>
            </template>
        </div>
    </template>
    <div class="bb-1 p-2">
        <div class="d-flex col"><span>显示内容</span></div>
        <div class="d-flex flex-wrap col">
            <label class="checkbox-label checkbox-block"
                   data-param="name">
                <input type="checkbox" :checked="item.param.name == 1 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">商品名称</span>
            </label>
            <label class="checkbox-label checkbox-block"
                   data-param="price">
                <input type="checkbox" :checked="item.param.price == 1 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">商品价格</span>
            </label>
            <div class="col-12" style="padding: 0;"
                 v-if="(item.param.style < 2 || item.param.list_style == 1) && item.param.list_style < 3">
                <label class="checkbox-label checkbox-block"
                       data-param="buy">
                    <input type="checkbox" :checked="item.param.buy == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">购买按钮</span>
                </label>
                <template v-if="item.param.buy == 1">
                    <div>
                        <label class="radio-label radio-block"
                               data-param="buy_list" data-item="0">
                            <input type="radio" name="buy_list" :checked="item.param.buy_list == 0 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">购物车</span>
                        </label>
                        <label class="radio-label radio-block"
                               data-param="buy_list" data-item="1">
                            <input type="radio" name="buy_list" :checked="item.param.buy_list == 1 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">加号</span>
                        </label>
                        <label class="radio-label radio-block"
                               data-param="buy_list" data-item="2">
                            <input type="radio" name="buy_list" :checked="item.param.buy_list == 2 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">文字样式1</span>
                        </label>
                        <label class="radio-label radio-block"
                               data-param="buy_list" data-item="3">
                            <input type="radio" name="buy_list" :checked="item.param.buy_list == 3 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">文字样式2</span>
                        </label>
                        <label class="radio-label radio-block"
                               data-param="buy_list" data-item="4">
                            <input type="radio" name="buy_list" :checked="item.param.buy_list == 4 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">文字样式3</span>
                        </label>
                        <label class="radio-label radio-block"
                               data-param="buy_list" data-item="5">
                            <input type="radio" name="buy_list" :checked="item.param.buy_list == 5 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">文字样式4</span>
                        </label>
                    </div>
                    <div v-if="item.param.buy_list >= 2">
                        <input v-model="item.param.buy_content" class="form-control col-4" placeholder="推荐2~5个字">
                    </div>
                </template>
            </div>
            <div class="col-12" style="padding: 0;">
                <label class="checkbox-label checkbox-block"
                       data-param="mark">
                    <input type="checkbox" :checked="item.param.mark == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">商品角标</span>
                </label>
                <template v-if="item.param.mark == 1">
                    <div>
                        <label class="radio-label radio-block"
                               data-param="mark_list" data-item="0">
                            <input type="radio" name="mark_list" :checked="item.param.mark_list == 0 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">热销</span>
                        </label>
                        <label class="radio-label radio-block"
                               data-param="mark_list" data-item="1">
                            <input type="radio" name="mark_list" :checked="item.param.mark_list == 1 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">新品</span>
                        </label>
                        <label class="radio-label radio-block"
                               data-param="mark_list" data-item="2">
                            <input type="radio" name="mark_list" :checked="item.param.mark_list == 2 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">折扣</span>
                        </label>
                        <label class="radio-label radio-block"
                               data-param="mark_list" data-item="3">
                            <input type="radio" name="mark_list" :checked="item.param.mark_list == 3 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">推荐</span>
                        </label>
                        <label class="radio-label radio-block"
                               data-param="mark_list" data-item="4">
                            <input type="radio" name="mark_list" :checked="item.param.mark_list == 4 ? true : ''">
                            <span class="label-icon"></span>
                            <span class="label-text">自定义</span>
                        </label>
                    </div>
                    <div v-if="item.param.mark_list == 4"
                         style="width: 64px;height: 64px;border: 1px dashed #eeeeee;font-size: 10px;padding: 5px 0;text-align: center">
                        <div class="upload-group" v-if="item.param.mark_url">
                            <input class="form-control file-input item-param" style="display: none;"
                                   data-param="mark_url" v-model="item.param.mark_url">
                            <div class="select-file text-center"
                                 style="color: #5CB3FD;cursor: pointer;">
                                <img style="width: 100%;height:100%;" :src="item.param.mark_url">
                            </div>
                        </div>
                        <div class="upload-group" v-else>
                            <input class="form-control file-input item-param" style="display: none;"
                                   data-param="mark_url">
                            <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                            <div>建议尺寸64*64</div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
