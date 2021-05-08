<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 11:15
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>

<div>
    <div class="d-flex p-2 bb-1">
        <div class="col">快捷导航</div>
    </div>
    <div class="bb-1 p-2">
        <div class="d-flex col align-items-center"><span>数据来源</span></div>
        <label class="radio-label radio-block"
               data-param="style" data-item="0">
            <input type="radio" name="style" :checked="item.param.style == 0 ? true : ''">
            <span class="label-icon"></span>
            <span class="label-text">使用商城配置</span>
        </label>
        <label class="radio-label radio-block" style="margin-left: 0;"
               data-param="style" data-item="1">
            <input type="radio" name="style" :checked="item.param.style == 1 ? true : ''">
            <span class="label-icon"></span>
            <span class="label-text">自定义配置</span>
        </label>
    </div>
    <template v-if="item.param.style == 1">
        <div class="bb-1 p-2">
            <div class="d-flex col align-items-center"><span>导航样式</span></div>
            <label class="radio-label radio-block"
                   data-param="cat_style" data-item="2">
                <input type="radio" name="cat_style" :checked="item.param.cat_style == 2 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">关闭</span>
            </label>
            <label class="radio-label radio-block" style="margin-left: 0;"
                   data-param="cat_style" data-item="0">
                <input type="radio" name="cat_style" :checked="item.param.cat_style == 0 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">样式1（点击收起）</span>
            </label>
            <label class="radio-label radio-block" style="margin-left: 0;"
                   data-param="cat_style" data-item="1">
                <input type="radio" name="cat_style" :checked="item.param.cat_style == 1 ? true : ''">
                <span class="label-icon"></span>
                <span class="label-text">样式2（全部显示）</span>
            </label>
        </div>

        <template v-if="item.param.cat_style == 0">
            <div class="p-4 bb-1">
                <div class="d-flex align-items-center mb-2">
                    <div>收起图标<span style="color: #999;">(建议尺寸：80×80)</span>
                    </div>
                </div>
                <div class="notice-block">
                    <div class="img d-flex justify-content-center align-items-center">
                        <div class="upload-group">
                            <input class="form-control file-input item-param" style="display: none;"
                                   data-param="close" v-model="item.param.close">
                            <template v-if="item.param.close">
                                <div class="upload-preview select-file text-center upload-preview">
                                    <span class="upload-preview-tip"></span>
                                    <img class="upload-preview-img" :src="item.param.close"
                                         style="width: 80px; height: 80px;">
                                </div>
                            </template>
                            <template v-else>
                                <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bb-1">
                <div class="d-flex align-items-center mb-2">
                    <div>展开图标<span style="color: #999;">(建议尺寸：80×80)</span>
                    </div>
                </div>
                <div class="notice-block">
                    <div class="img d-flex justify-content-center align-items-center">
                        <div class="upload-group">
                            <input class="form-control file-input item-param" style="display: none;"
                                   data-param="open" v-model="item.param.open">
                            <template v-if="item.param.open">
                                <div class="upload-preview select-file text-center upload-preview">
                                    <span class="upload-preview-tip"></span>
                                    <img class="upload-preview-img" :src="item.param.open"
                                         style="width: 80px; height: 80px;">
                                </div>
                            </template>
                            <template v-else>
                                <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="p-4 bb-1">
            <div class="d-flex align-items-center mb-2">
                <div>返回首页图标<span style="color: #999;">(建议尺寸：80×80)</span>
                </div>
            </div>
            <div class="notice-block">
                <div class="img d-flex justify-content-center align-items-center">
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="home_img" v-model="item.param.home_img">
                        <template v-if="item.param.home_img">
                            <div class="upload-preview select-file text-center upload-preview">
                                <span class="upload-preview-tip"></span>
                                <img class="upload-preview-img" :src="item.param.home_img"
                                     style="width: 80px; height: 80px;">
                            </div>
                        </template>
                        <template v-else>
                            <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bb-1">
            <div class="d-flex align-items-center mb-2">
                <div>在线客服</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label class="radio-label radio-block"
                       data-param="show_customer_service" data-item="0">
                    <input type="radio" name="show_customer_service"
                           :checked="item.param.show_customer_service == 0 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">关闭</span>
                </label>
                <label class="radio-label radio-block" style="margin-left: 0;"
                       data-param="show_customer_service" data-item="1">
                    <input type="radio" name="show_customer_service"
                           :checked="item.param.show_customer_service == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">开启</span>
                </label>
            </div>
            <div class="d-flex align-items-center mb-2">
                <div>图片<span style="color: #999;">(建议尺寸：80×80)</span></div>
            </div>
            <div class="notice-block">
                <div class="img d-flex justify-content-center align-items-center">
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="service" v-model="item.param.service">
                        <template v-if="item.param.service">
                            <div class="upload-preview select-file text-center upload-preview">
                                <span class="upload-preview-tip"></span>
                                <img class="upload-preview-img" :src="item.param.service"
                                     style="width: 80px; height: 80px;">
                            </div>
                        </template>
                        <template v-else>
                            <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bb-1">
            <div class="d-flex align-items-center mb-2">
                <div>一键拨号</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label class="radio-label radio-block"
                       data-param="dial" data-item="0">
                    <input type="radio" name="dial" :checked="item.param.dial == 0 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">关闭</span>
                </label>
                <label class="radio-label radio-block" style="margin-left: 0;"
                       data-param="dial" data-item="1">
                    <input type="radio" name="dial" :checked="item.param.dial == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">开启</span>
                </label>
            </div>
            <div class="mb-2">联系电话</div>
            <div class="mb-2">
                <input class="form-control" v-model="item.param.dial_tel">
            </div>
            <div class="mb-2">图片<span style="color: #999;">(建议尺寸：80×80)</span></div>
            <div class="notice-block">
                <div class="img d-flex justify-content-center align-items-center">
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="dial_pic" v-model="item.param.dial_pic">
                        <template v-if="item.param.dial_pic">
                            <div class="upload-preview select-file text-center upload-preview">
                                <span class="upload-preview-tip"></span>
                                <img class="upload-preview-img" :src="item.param.dial_pic"
                                     style="width: 80px; height: 80px;">
                            </div>
                        </template>
                        <template v-else>
                            <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bb-1">
            <div class="d-flex align-items-center mb-2">
                <div>客服外链</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label class="radio-label radio-block"
                       data-param="web_service_status" data-item="0">
                    <input type="radio" name="web_service_status"
                           :checked="item.param.web_service_status == 0 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">关闭</span>
                </label>
                <label class="radio-label radio-block" style="margin-left: 0;"
                       data-param="web_service_status" data-item="1">
                    <input type="radio" name="web_service_status"
                           :checked="item.param.web_service_status == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">开启</span>
                </label>
            </div>
            <div class="mb-2">外链网址</div>
            <div class="mb-2">
                <input class="form-control" v-model="item.param.web_service_url">
            </div>
            <div class="mb-2">图片<span style="color: #999;">(建议尺寸：80×80)</span></div>
            <div class="notice-block">
                <div class="img d-flex justify-content-center align-items-center">
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="web_service" v-model="item.param.web_service">
                        <template v-if="item.param.web_service">
                            <div class="upload-preview select-file text-center upload-preview">
                                <span class="upload-preview-tip"></span>
                                <img class="upload-preview-img" :src="item.param.web_service"
                                     style="width: 80px; height: 80px;">
                            </div>
                        </template>
                        <template v-else>
                            <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bb-1">
            <div class="d-flex align-items-center mb-2">
                <div>跳转小程序</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label class="radio-label radio-block"
                       data-param="wxapp_status" data-item="0">
                    <input type="radio" name="wxapp_status" :checked="item.param.wxapp_status == 0 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">关闭</span>
                </label>
                <label class="radio-label radio-block" style="margin-left: 0;"
                       data-param="wxapp_status" data-item="1">
                    <input type="radio" name="wxapp_status" :checked="item.param.wxapp_status == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">开启</span>
                </label>
            </div>
            <div class="mb-2">跳转小程序appid</div>
            <div class="mb-2">
                <input class="form-control" v-model="item.param.appid">
            </div>
            <div class="mb-2">跳转小程序路径</div>
            <div class="mb-2">
                <input class="form-control" v-model="item.param.path">
            </div>
            <div class="mb-2">图片<span style="color: #999;">(建议尺寸：80×80)</span></div>
            <div class="notice-block">
                <div class="img d-flex justify-content-center align-items-center">
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="pic_url" v-model="item.param.pic_url">
                        <template v-if="item.param.pic_url">
                            <div class="upload-preview select-file text-center upload-preview">
                                <span class="upload-preview-tip"></span>
                                <img class="upload-preview-img" :src="item.param.pic_url"
                                     style="width: 80px; height: 80px;">
                            </div>
                        </template>
                        <template v-else>
                            <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bb-1">
            <div class="d-flex align-items-center mb-2">
                <div>一键导航</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label class="radio-label radio-block"
                       data-param="quick_map_status" data-item="0">
                    <input type="radio" name="quick_map_status" :checked="item.param.quick_map_status == 0 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">关闭</span>
                </label>
                <label class="radio-label radio-block" style="margin-left: 0;"
                       data-param="quick_map_status" data-item="1">
                    <input type="radio" name="quick_map_status" :checked="item.param.quick_map_status == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">开启</span>
                </label>
            </div>
            <div class="mb-2">详细地址</div>
            <div class="mb-2">
                <input class="form-control" v-model="item.param.address">
            </div>
            <div class="mb-2">经纬度</div>
            <div class="mb-2">
                <input class="form-control" v-model="item.param.lal">
            </div>
            <div class="mb-2">图片<span style="color: #999;">(建议尺寸：80×80)</span></div>
            <div class="notice-block">
                <div class="img d-flex justify-content-center align-items-center">
                    <div class="upload-group">
                        <input class="form-control file-input item-param" style="display: none;"
                               data-param="icon" v-model="item.param.pic_url">
                        <template v-if="item.param.icon">
                            <div class="upload-preview select-file text-center upload-preview">
                                <span class="upload-preview-tip"></span>
                                <img class="upload-preview-img" :src="item.param.icon"
                                     style="width: 80px; height: 80px;">
                            </div>
                        </template>
                        <template v-else>
                            <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="form-group row map">
                <div class="input-group" style="margin-top: 20px;">
                    <input class="form-control region" type="text" placeholder="城市">
                    <span class="input-group-addon ">和</span>
                    <input class="form-control keyword" type="text" placeholder="关键字">
                    <a class="input-group-addon float-search" href="javascript:">搜索</a>
                </div>
                <div class="text-info">搜索时城市和关键字必填</div>
                <div class="text-info">点击地图上的蓝色点，获取经纬度</div>
                <div class="text-danger map-error mb-3" style="display: none">错误信息</div>
                <div id="container" style="min-width:400px;min-height:400px;"></div>
            </div>
        </div>
    </template>
</div>