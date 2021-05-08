<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/27
 * Time: 18:42
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">关联链接</div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>启用图标</span></div>
        <div class="input-block col-6">
            <template v-if="item.param.is_icon == 1">
                <div class="switch d-flex justify-content-center" data-param="is_icon" data-value="1">
                    <div style="width: 50%;text-align: center"></div>
                    <div style="width: 50%;text-align: center">开</div>
                    <div class="switch-one"></div>
                </div>
            </template>
            <template v-else>
                <div class="switch d-flex justify-content-center" style="background-color: #eeeeee;color:#353535"
                     data-param="is_icon" data-value="0">
                    <div style="width: 50%;text-align: center">关</div>
                    <div style="width: 50%;text-align: center"></div>
                    <div class="switch-one" style="left: 24px;"></div>
                </div>
            </template>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>箭头开关</span></div>
        <div class="input-block col-6">
            <template v-if="item.param.is_jiantou == 1">
                <div class="switch d-flex justify-content-center" data-param="is_jiantou" data-value="1">
                    <div style="width: 50%;text-align: center"></div>
                    <div style="width: 50%;text-align: center">开</div>
                    <div class="switch-one"></div>
                </div>
            </template>
            <template v-else>
                <div class="switch d-flex justify-content-center" style="background-color: #eeeeee;color:#353535"
                     data-param="is_jiantou" data-value="0">
                    <div style="width: 50%;text-align: center">关</div>
                    <div style="width: 50%;text-align: center"></div>
                    <div class="switch-one" style="left: 24px;"></div>
                </div>
            </template>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>标题</span></div>
        <div class="input-block col-6">
            <input class="form-control" type="text"
                   v-model="item.param.name" data-param="name">
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>文本位置</span></div>
        <div class="input-block col-6">
            <div class="input-group">
                <input class="form-control" type="number" min="1"
                       v-model="item.param.left" data-param="left">
                <span class="input-group-addon">px</span>
            </div>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>链接页面</span></div>
        <div class="input-block col-6">
            <div class="page-link-input">
                <input class="form-control link-name item-param pick-link-btn"
                       data-param="page_name"
                       readonly v-model="item.param.page_name" style="cursor: pointer">
                <input class="form-control link-input item-param"
                       data-param="url" type="hidden"
                       readonly v-model="item.param.url" style="cursor: pointer">
                <input class="link-open-type item-param" data-param="open_type"
                       v-model="item.param.open_type" type="hidden">
            </div>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>图标</span></div>
        <div class="notice-block col-6">
            <div class="img d-flex justify-content-center align-items-center">
                <div class="upload-group">
                    <input class="form-control file-input item-param" style="display: none;"
                           data-param="icon" v-model="item.param.icon">
                    <template v-if="item.param.icon">
                        <div class="upload-preview select-file text-center upload-preview">
                            <img class="upload-preview-img" :src="item.param.icon">
                        </div>
                    </template>
                    <template v-else>
                        <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>图标位置</span></div>
        <div class="input-block col-6">
            <div class="input-group">
                <input class="form-control" type="number" min="1"
                       v-model="item.param.position">
                <span class="input-group-addon">px</span>
            </div>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4 align-items-center"><span>文本颜色</span></div>
        <div class="border-block start_click" data-param="color">
            <div :style="{backgroundColor:item.param.color}"></div>
        </div>
        <div>
            <input v-model="item.param.color" class="form-control" style="width: 7rem;">
        </div>
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
</div>


