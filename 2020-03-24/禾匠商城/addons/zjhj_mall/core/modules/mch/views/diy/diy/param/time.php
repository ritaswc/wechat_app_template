<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/10/30
 * Time: 17:00
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col-8">倒计时</div>
    </div>

    <div class="p-4 bb-1">
        <div class="d-flex align-items-center mb-2">
            <div>图片<span style="color: #999;">(建议尺寸：宽度750，高度不限)</span>
            </div>
        </div>
        <div class="notice-block">
            <div class="img d-flex justify-content-center align-items-center">
                <div class="upload-group">
                    <input class="form-control file-input item-param" style="display: none;"
                           data-param="pic_url" v-model="item.param.pic_url">
                    <template v-if="item.param.pic_url">
                        <div class="upload-preview select-file text-center upload-preview">
                            <span class="upload-preview-tip"></span>
                            <img class="upload-preview-img" :src="item.param.pic_url">
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
        <div class="d-flex align-items-center mb-2"><span>活动链接</span></div>
        <div class="input-block">
            <div class="page-link-input">
                <input class="form-control link-name item-param pick-link-btn" data-param="page_name"
                       readonly v-model="item.param.page_name">
                <input class="form-control link-input item-param"
                       data-param="url" placeholder="请选择链接" type="hidden"
                       readonly v-model="item.param.url" style="cursor: pointer">
                <input class="link-open-type item-param" data-param="open_type"
                       v-model="item.param.open_type" type="hidden">
            </div>
        </div>
    </div>

    <div class="p-4 bb-1">
        <div class="d-flex align-items-center mb-2"><span>开始时间</span></div>
        <div class="input-block">
            <div class="page-link-input">
                <input class="form-control item-param date_start"
                       data-param="date_start" placeholder="请选择链接" v-model="item.param.date_start"
                       style="cursor: pointer">
            </div>
        </div>
    </div>

    <div class="p-4 bb-1">
        <div class="d-flex align-items-center mb-2"><span>结束时间</span></div>
        <div class="input-block">
            <div class="page-link-input">
                <input class="form-control item-param date_end"
                       data-param="date_end" placeholder="请选择链接" v-model="item.param.date_end"
                       style="cursor: pointer">
            </div>
        </div>
    </div>
</div>