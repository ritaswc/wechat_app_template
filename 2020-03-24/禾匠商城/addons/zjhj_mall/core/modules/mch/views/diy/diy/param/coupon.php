<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/10/9
 * Time: 14:57
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">优惠券</div>
    </div>

    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>字体颜色</span></div>
        <div class="border-block start_click" data-param="color">
            <div :style="{backgroundColor:item.param.color}"></div>
        </div>
        <div>
            <input v-model="item.param.color" class="form-control" style="width: 7rem;">
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3"><span>已领取图</span></div>
        <div class="col-6 notice-block">
            <div class="img d-flex justify-content-center align-items-center">
                <div class="upload-group">
                    <input class="form-control file-input item-param" style="display: none;"
                           data-param="bg_1" v-model="item.param.bg_1">
                    <template v-if="item.param.bg_1">
                        <div class="upload-preview select-file text-center upload-preview">
                            <span class="upload-preview-tip">256&times;130</span>
                            <img class="upload-preview-img" :src="item.param.bg_1">
                        </div>
                    </template>
                    <template v-else>
                        <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        <div>建议尺寸<br>256*130</div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex p-2 bb-1">
        <div class="d-flex col-3"><span>未领取图</span></div>
        <div class="col-6 notice-block">
            <div class="img d-flex justify-content-center align-items-center">
                <div class="upload-group">
                    <input class="form-control file-input item-param" style="display: none;"
                           data-param="bg" v-model="item.param.bg">
                    <template v-if="item.param.bg">
                        <div class="upload-preview select-file text-center upload-preview">
                            <span class="upload-preview-tip">256&times;130</span>
                            <img class="upload-preview-img" :src="item.param.bg">
                        </div>
                    </template>
                    <template v-else>
                        <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        <div>建议尺寸<br>256*130</div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

