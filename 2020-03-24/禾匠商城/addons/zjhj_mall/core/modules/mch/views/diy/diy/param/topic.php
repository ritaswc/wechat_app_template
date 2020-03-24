<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/27
 * Time: 16:17
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">专题</div>
    </div>

    <div class="bb-1 p-2">
        <div class="d-flex col align-items-center"><span>专题样式</span></div>
        <label class="radio-label radio-block"
               data-param="style" data-item="0">
            <input type="radio" name="style" :checked="item.param.style == 0 ? true : ''">
            <span class="label-icon"></span>
            <span class="label-text">简易模式</span>
        </label>
        <label class="radio-label radio-block"
               data-param="style" data-item="1">
            <input type="radio" name="style" :checked="item.param.style == 1 ? true : ''">
            <span class="label-icon"></span>
            <span class="label-text">列表模式</span>
        </label>
    </div>

    <template v-if="item.param.style == 0">
        <div class="d-flex p-2">
            <div class="d-flex col-4 align-items-center"><span>显示行数</span></div>
            <div class="input-block col-6">
                <select class="form-control" v-model="item.param.count">
                    <option>1</option>
                    <option>2</option>
                </select>
            </div>
        </div>

        <div class="d-flex p-2" v-if="item.param.count == 1">
            <div class="d-flex col-4 align-items-center"><span>专题logo<br>（1行）</span></div>
            <div class="notice-block col-6">
                <div class="img d-flex justify-content-center align-items-center" v-if="item.param.logo_1">
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="logo_1" v-model="item.param.logo_1">
                        <div class="upload-preview select-file text-center upload-preview">
                            <span class="upload-preview-tip">104&times;32</span>
                            <img class="upload-preview-img" :src="item.param.logo_1">
                        </div>
                    </div>
                </div>
                <div class="img d-flex justify-content-center align-items-center" v-else>
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="logo_1">
                        <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        <div>建议尺寸<br>104*32</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex p-2" v-if="item.param.count == 2">
            <div class="d-flex col-4 align-items-center"><span>专题logo<br>（2行）</span></div>
            <div class="notice-block col-6">
                <div class="img d-flex justify-content-center align-items-center" v-if="item.param.logo_2">
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="logo_2" v-model="item.param.logo_2">
                        <div class="upload-preview select-file text-center upload-preview">
                            <span class="upload-preview-tip">104&times;50</span>
                            <img class="upload-preview-img" :src="item.param.logo_2">
                        </div>
                    </div>
                </div>
                <div class="img d-flex justify-content-center align-items-center" v-else>
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="logo_2">
                        <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        <div>建议尺寸<br>104*50</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex p-2">
            <div class="d-flex col-4 align-items-center"><span>专题标签</span></div>
            <div class="notice-block col-6">
                <div class="img d-flex justify-content-center align-items-center" v-if="item.param.heated">
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="heated" v-model="item.param.heated">
                        <div class="upload-preview select-file text-center upload-preview">
                            <span class="upload-preview-tip">54&times;28</span>
                            <img class="upload-preview-img" :src="item.param.heated">
                        </div>
                    </div>
                </div>
                <div class="img d-flex justify-content-center align-items-center" v-else>
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="heated">
                        <div class="select-file" style="color: #5CB3FD;">+添加图片</div>
                        <div>建议尺寸<br>54*28</div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template v-else>
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
                <div class="" style="width: calc(100% - 30px);">
                    <template v-for="(value,key) in item.param.list">
                        <div class="d-flex mb-2" style="position: relative">
                            <div class="banner-one param-key" :data-key="key">
                                <div class="form-group row mt-4">
                                    <div class="form-group-label col-sm-3 text-right">
                                        <label class="col-form-label">专题分类</label>
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
                                        <label class="col-form-label">专题显示</label>
                                        <br>
                                        <label class="col-form-label text-danger fs-sm">最多显示10个专题</label>
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
                                                <span class="label-text">自定义显示专题</span>
                                            </label>
                                            <div class="d-flex flex-wrap" v-if="value.goods_style == 2">
                                                <template v-for="(v, k) in value.goods_list">
                                                    <div class="img-list" style="margin: 0 10px 10px 0;" :data-key="key"
                                                         :data-k="k">
                                                        <img :src="v.cover_pic" style="width: 100%;height: 100%;">
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
                    <div class="cat-add list-add d-flex justify-content-center" v-if="item.param.list.length < 8">+添加专题分类
                    </div>
                    <div>最多添加8个专题分类</div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="bb-1 p-2">
                <div class="d-flex col"><span>添加专题</span></div>
                <div class="d-flex flex-wrap banner-one">
                    <template v-for="(v, k) in item.param.list[0].goods_list" v-if="k < 8">
                        <div class="img-list" style="margin: 0 10px 10px 0;" :data-k="k">
                            <img :src="v.cover_pic" style="width: 100%;height: 100%;">
                            <div class="chacha chacha-goods"></div>
                        </div>
                    </template>
                    <div data-key="0" v-if="item.param.list[0].goods_list.length < 8"
                         class="goods-add img-list d-flex justify-content-center align-items-center">
                        +
                    </div>
                </div>
            </div>
        </template>
    </template>
</div>


