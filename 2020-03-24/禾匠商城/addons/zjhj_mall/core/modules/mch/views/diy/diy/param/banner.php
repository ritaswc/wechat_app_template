<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/20
 * Time: 19:15
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">轮播图</div>
    </div>

    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>样式</span></div>
        <div :class="'banner-block ' + (item.param.style == 1 ? 'active' : '')" data-param="style" data-item="1">
            <div class="style-1"></div>
            <div class="text">样式一</div>
        </div>
        <div :class="'banner-block ml-4 ' + (item.param.style == 2 ? 'active' : '')" data-param="style" data-item="2">
            <div class="d-flex align-items-center justify-content-center style-2">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="text">样式二</div>
        </div>
    </div>

    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>填充方式</span></div>
        <div :class="'border-block radio-block ml-4 ' + (item.param.fill == 0 ? 'active' : '')"
             data-param="fill" data-item="0">
            <div>留白</div>
        </div>
        <div :class="'border-block radio-block ' + (item.param.fill == 1 ? 'active' : '')"
             data-param="fill" data-item="1">
            <div>填充</div>
        </div>
    </div>
    <div class="d-flex p-2 bb-1">
        <div class="col">
            <a class="btn btn-primary nav-banner-add"
               data-param="banner" href="javascript:">批量拉取轮播图</a>
        </div>
    </div>
    <div class="d-flex p-2 bb-1">
        <div class="d-flex col-4 align-items-center"><span>轮播图高度</span></div>
        <div class="input-block col-6">
            <div class="input-group">
                <input class="form-control input-num" type="number" min="1"
                       v-model="item.param.height" data-param="height">
                <span class="input-group-addon">px</span>
            </div>
        </div>
    </div>
    <div class="p-2 bb-1">
        <div class="d-flex col align-items-center" v-if="item.param.style == 1"><span>宽度固定750px</span></div>
        <div class="d-flex col align-items-center" v-if="item.param.style == 2"><span>宽度固定590px</span></div>
    </div>

    <div class="d-flex p-2">
        <div style="width: calc(100% - 2rem);">
            <template v-for="(value,key) in item.param.list">
                <div class="d-flex mb-2" style="position: relative">
                    <div class="banner-one param-key d-flex justify-content-center" :data-key="key">
                        <div>
                            <div class="img d-flex justify-content-center align-items-center" v-if="value.pic_url"
                                 style="margin: auto;">
                                <div class="upload-group">
                                    <input class="form-control file-input model-param" style="display: none;"
                                           data-param="pic_url" v-model="value.pic_url">
                                    <div class="upload-preview select-file text-center upload-preview"
                                         style="color: #5CB3FD;cursor: pointer;width: 300px;height:144px;background-color: #f7f7f7">
                                        <img class="upload-preview-img" :src="value.pic_url">
                                    </div>
                                </div>
                            </div>
                            <div class="img d-flex justify-content-center align-items-center" v-else
                                 style="margin: auto;">
                                <div class="upload-group">
                                    <input class="form-control file-input model-param" style="display: none;"
                                           data-param="pic_url">
                                    <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                                    <div style="color: #353535;">推荐尺寸750*360</div>
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">标题</label>
                                </div>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" v-model="value.title">
                                </div>
                            </div>
                            <div class="mt-4 form-group row ">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">链接</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group page-link-input">
                                        <input class="form-control link-name model-param" data-param="page_name"
                                               readonly v-model="value.page_name">
                                        <input class="form-control link-input model-param" type="hidden"
                                               data-param="page_url"
                                               readonly v-model="value.page_url">
                                        <input class="link-open-type model-param" data-param="open_type"
                                               v-model="value.open_type" type="hidden">
                                    <span class="input-group-btn">
                                        <a class="btn btn-secondary pick-link-btn" href="javascript:">选择链接</a>
                                    </span>
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
            <div class="banner-add list-add d-flex justify-content-center">+添加轮播图</div>
        </div>
    </div>
</div>

