<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/29
 * Time: 15:55
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col-8">图片广告</div>
    </div>

    <div class="d-flex p-2 flex-wrap bb-1">
        <div class="d-flex col-3"><span>样式</span></div>
        <div class="d-flex flex-wrap col-9">
            <div :class="'rubik-block rubik-list-0 radio-block  ' + (item.param.style == 0 ? 'active' : '')"
                 data-param="style" data-item="0">
                <div>
                    <div class="rubik-0"></div>
                </div>
                <div class="text">1张图</div>
            </div>
            <div :class="'rubik-block rubik-list-1 radio-block  ' + (item.param.style == 1 ? 'active' : '')"
                 data-param="style" data-item="1">
                <div>
                    <div class="rubik-0"></div>
                    <div class="rubik-1"></div>
                </div>
                <div class="text">2张图</div>
            </div>
            <div :class="'rubik-block rubik-list-2 radio-block ' + (item.param.style == 2 ? 'active' : '')"
                 data-param="style" data-item="2">
                <div class="d-flex align-items-center justify-content-center style-2">
                    <div class="rubik-0"></div>
                    <div class="rubik-1"></div>
                    <div class="rubik-2"></div>
                </div>
                <div class="text">3张图</div>
            </div>
            <div :class="'rubik-block rubik-list-3 radio-block ' + (item.param.style == 3 ? 'active' : '')"
                 data-param="style" data-item="3">
                <div class="d-flex align-items-center justify-content-center style-3">
                    <div class="rubik-0"></div>
                    <div class="rubik-1"></div>
                    <div class="rubik-2"></div>
                    <div class="rubik-3"></div>
                </div>
                <div class="text">4张图</div>
            </div>
            <div :class="'rubik-block rubik-list-4 radio-block ' + (item.param.style == 4 ? 'active' : '')"
                 data-param="style" data-item="4">
                <div class="d-flex align-items-center justify-content-center style-4">
                    <div class="rubik-0"></div>
                    <div class="rubik-1"></div>
                    <div class="rubik-2"></div>
                    <div class="rubik-3"></div>
                </div>
                <div class="text">2张平分</div>
            </div>
            <div :class="'rubik-block rubik-list-5 radio-block ' + (item.param.style == 5 ? 'active' : '')"
                 data-param="style" data-item="5">
                <div class="d-flex align-items-center justify-content-center style-5">
                    <div class="rubik-0"></div>
                    <div class="rubik-1"></div>
                    <div class="rubik-2"></div>
                    <div class="rubik-3"></div>
                </div>
                <div class="text">3张平分</div>
            </div>
            <div :class="'rubik-block rubik-list-6 radio-block ' + (item.param.style == 6 ? 'active' : '')"
                 data-param="style" data-item="6">
                <div class="d-flex align-items-center justify-content-center style-6">
                    <div class="rubik-0"></div>
                    <div class="rubik-1"></div>
                    <div class="rubik-2"></div>
                    <div class="rubik-3"></div>
                </div>
                <div class="text">4张左右平分</div>
            </div>
            <div :class="'rubik-block rubik-list-7 radio-block ' + (item.param.style == 7 ? 'active' : '')"
                 data-param="style" data-item="7">
                <div class="d-flex align-items-center justify-content-center style-7">
                    <div class="rubik-0"></div>
                    <div class="rubik-1"></div>
                    <div class="rubik-2"></div>
                    <div class="rubik-3"></div>
                </div>
                <div class="text">4张上下平分</div>
            </div>
            <div :class="'rubik-block rubik-list-8 radio-block ' + (item.param.style == 8 ? 'active' : '')"
                 data-param="style" data-item="8">
                <div class="d-flex align-items-center justify-content-center style-8">
                    <div class="rubik-0"></div>
                    <div class="rubik-1"></div>
                    <div class="rubik-2"></div>
                    <div class="rubik-3"></div>
                    <div class="rubik-4"></div>
                    <div class="rubik-5"></div>
                </div>
                <div class="text">自定义魔方</div>
            </div>
        </div>
    </div>

    <div class="p-2 bb-1" v-if="item.param.style > 0">
        <div class="d-flex align-items-center"><span>图片间隙</span></div>
        <div class="input-block">
            <div class="input-group">
                <input class="form-control" type="number" min="0"
                       v-model="item.param.space" data-param="space">
                <span class="input-group-addon">px</span>
            </div>
        </div>
    </div>

    <div class="p-2 bb-1">
        <div class="d-flex align-items-center">
            <span>图片魔方拉取</span>
            <span class="fs-sm text-danger">（根据图片魔方ID进行拉取）</span>
        </div>
        <div class="input-block">
            <div class="input-group">
                <input class="form-control" placeholder="请输入图片魔方的ID">
                <a href="javascript:" class="input-group-addon btn btn-primary rubik-select">拉取</a>
            </div>
        </div>
    </div>

    <template v-if="item.param.style == 8">
        <div class="d-flex p-2">
            <div class="d-flex col-3 align-items-center"><span>魔方宽度</span></div>
            <div class="input-block col-6">
                <div class="input-group">
                    <select class="form-control param-wh" v-model="item.param.width">
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                    <span class="input-group-addon">列</span>
                </div>
            </div>
        </div>
        <div class="d-flex p-2">
            <div class="d-flex col-3 align-items-center"><span>魔方高度</span></div>
            <div class="input-block col-6">
                <div class="input-group">
                    <select class="form-control param-wh" v-model="item.param.height">
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                    <span class="input-group-addon">行</span>
                </div>
            </div>
        </div>
    </template>

    <div class="p-2">
        <div class="d-flex col-3"><span>布局</span></div>
        <div class="col-12">
            <template v-if="item.param.style < 8">
                <div class="pic-list d-flex align-items-center rubik-list upload-group"
                     :style="{minHeight:item.param.minHeight / 4 + 'px','background-color': '#fffff','width':'375px'}">
                    <template v-for="(value,key) in item.param.list">
                        <input class="form-control file-input model-param" style="display: none;"
                               data-param="pic_url" v-model="value.pic_url">
                        <div :class="'select-file-db param-key fill-1 d-flex justify-content-center align-items-center ' + (key == param_key ? 'border-active-1' : 'border-active-0')"
                             :data-key="key"
                             :style="{'backgroundImage':'url('+value.pic_url+')','left':value.left / 2 + 'px','top':value.top / 2 + 'px','width':value.width / 2 + 'px','height':(value.height == -1 ? 'auto' : value.height / 2 + 'px'),'position':item.param.style == 0 ? 'relative' : ''}">
                            <div class="select-file" style="display: none"></div>
                            <div style="color: #5CB3FD;" v-if="!value.pic_url">
                                <div v-if="item.param.style == 0">
                                    宽度750；高度不限
                                </div>
                                <div v-else>
                                    {{value.width}}*{{value.height}}
                                </div>
                            </div>
                            <template v-else>
                                <img @load="imagesLoad" class="pic-list-img" :src="value.pic_url"
                                     :style="{'width':item.param.style == 0 ? '100%' : '70px'}"
                                     style="display:block; visibility: hidden;">
                            </template>
                        </div>
                    </template>
                </div>
            </template>
            <template v-else>
                <div style="padding-right: 20px;" :style="{'width': 'calc(21px + ' + (375/(item.param.width)).toFixed(0)*item.param.width + 'px)'}">
                    <div class="rubik-custom d-flex flex-wrap upload-group">
                        <template v-for="(value, key) in (item.param.width * item.param.height)">
                            <div class="rubik-custom-one d-flex justify-content-center align-items-center"
                                 :style="{'width':(375/(item.param.width)).toFixed(0)+'px','height':(375/(item.param.width)).toFixed(0)+'px'}">

                            </div>
                        </template>
                        <div class=" pic-list rubik-list" style="position: absolute; left: 0;top: 0;min-height: 0;">
                            <template v-for="(value,key) in item.param.list">
                                <input class="form-control file-input model-param" style="display: none;"
                                       data-param="pic_url" v-model="value.pic_url">
                                <div :class="'select-file-db param-key fill-1 d-flex justify-content-center align-items-center ' + (key == param_key ? 'border-active-1' : 'border-active-0')"
                                     :data-key="key" style="background-color: #E6F4FF;overflow: inherit"
                                     :style="{'backgroundImage':'url('+value.pic_url+')','left':value.left / 2 + 'px','top':value.top / 2 + 'px','width':value.width / 2 + 'px','height':value.height / 2 + 'px'}">
                                    <div class="select-file" style="display: none"></div>
                                    <div style="color: #5CB3FD;" v-if="!value.pic_url">
                                        {{value.width}}*{{value.height}}
                                    </div>
                                    <div class="chacha chacha-rubik"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
            <div>双击虚线框内可以选择图片</div>
        </div>
    </div>

    <div class="p-2" v-if="param_key > -1">
        <div class="col-12"><span>链接页面</span></div>
        <div class="col-12">
            <div class="nav-one param-key d-flex justify-content-center" :data-key="param_key">
                <div class="nav-left">
                    <div class="img d-flex justify-content-center align-items-center" v-if="item.param.list[param_key].pic_url">
                        <div class="upload-group">
                            <input class="form-control file-input model-param" style="display: none;"
                                   data-param="pic_url" v-model="item.param.list[param_key].pic_url">
                            <div class="select-file" style="color: #5CB3FD;cursor: pointer">
                                <img class="img" :src="item.param.list[param_key].pic_url">
                            </div>
                        </div>
                    </div>
                    <div class="img d-flex justify-content-center align-items-center" v-else>
                        <div class="upload-group">
                            <input class="form-control file-input model-param" style="display: none;"
                                   data-param="pic_url">
                            <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        </div>
                    </div>
                </div>
                <div class="nav-right">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-4 text-right">
                            <label class="col-form-label">链接页面</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="page-link-input">
                                <input class="form-control link-name model-param pick-link-btn" data-param="page_name"
                                       readonly v-model="item.param.list[param_key].page_name">
                                <input class="form-control link-input model-param"
                                       data-param="url" type="hidden"
                                       readonly v-model="item.param.list[param_key].url" style="cursor: pointer">
                                <input class="link-open-type model-param" data-param="open_type"
                                       v-model="item.param.list[param_key].open_type" type="hidden">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


