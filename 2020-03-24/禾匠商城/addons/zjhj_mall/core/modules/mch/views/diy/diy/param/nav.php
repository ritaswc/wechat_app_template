<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/26
 * Time: 18:48
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">导航图标</div>
    </div>

    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>背景颜色</span></div>
        <div class="border-block start_click" data-param="backgroundColor">
            <div :style="{backgroundColor:item.param.backgroundColor}"></div>
        </div>
        <div>
            <input v-model="item.param.backgroundColor" class="form-control" style="width: 7rem;">
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>一屏显示</span></div>
        <div class="input-block col-6">
            <select class="form-control" v-model="item.param.count" v-on:change="resetNav">
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>显示行数</span></div>
        <div class="input-block col-6">
            <div class="input-group">
                <input class="form-control input-num" type="number" min="1" max="30"
                       v-model="item.param.col" data-param="col" v-on:input="resetNav">
                <span class="input-group-addon">行</span>
            </div>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>左右滑动</span></div>
        <div :class="'border-block radio-block ' + (item.param.is_slide == 'false' ? '' : 'active')"
             data-param="is_slide" data-item="true">
            <div>是</div>
        </div>
        <div :class="'border-block radio-block ml-4 ' + (item.param.is_slide == 'false' ? 'active' : '')"
             data-param="is_slide" data-item="false">
            <div>否</div>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="col">
            <a class="btn btn-primary nav-banner-add"
               data-param="nav" href="javascript:">批量拉取导航图标</a>
        </div>
    </div>
    <div class="d-flex p-2">
        <div style="width: calc(100% - 30px)">
            <template v-for="(value,key) in item.param.list">
                <div class="d-flex mb-2" style="position: relative">
                    <div class="nav-one param-key d-flex justify-content-center" :data-key="key">
                        <div class="nav-left">
                            <div class="img d-flex justify-content-center align-items-center" v-if="value.pic_url">
                                <div class="upload-group">
                                    <input class="form-control file-input model-param" style="display: none;"
                                           data-param="pic_url" v-model="value.pic_url">
                                    <div class="select-file" style="color: #5CB3FD;cursor: pointer">
                                        <img class="img" :src="value.pic_url">
                                    </div>
                                </div>
                            </div>
                            <div class="img d-flex justify-content-center align-items-center" v-else>
                                <div class="upload-group">
                                    <input class="form-control file-input model-param" style="display: none;"
                                           data-param="pic_url">
                                    <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                                    <div>建议尺寸<br>88*88</div>
                                </div>
                            </div>
                        </div>
                        <div class="nav-right">
                            <div class="form-group row">
                                <div class="form-group-label col-sm-4 text-right">
                                    <label class="col-form-label">导航名称</label>
                                </div>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" v-model="value.name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group-label col-sm-4 text-right">
                                    <label class="col-form-label">链接页面</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="page-link-input">
                                        <input class="form-control link-name model-param pick-link-btn" data-param="page_name"
                                               readonly v-model="value.page_name">
                                        <input class="form-control link-input model-param"
                                               data-param="url" type="hidden"
                                               readonly v-model="value.url" style="cursor: pointer">
                                        <input class="link-open-type model-param" data-param="open_type"
                                               v-model="value.open_type" type="hidden">
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
            <div class="banner-add list-add d-flex justify-content-center">+添加导航</div>
        </div>
    </div>
</div>