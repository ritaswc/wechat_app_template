<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/27
 * Time: 14:39
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">公告</div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>公告名称</span></div>
        <div class="input-block col-9">
            <input class="form-control" type="text"
                   v-model="item.param.name" data-param="name">
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>公告内容</span></div>
        <div class="input-block col-9">
            <textarea class="form-control" style="min-height: 100px;" v-model="item.param.content"></textarea>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>公告图标</span></div>
        <div class="notice-block col-6">
            <div class="img d-flex justify-content-center align-items-center">
                <div class="upload-group">
                    <input class="form-control file-input item-param" style="display: none;"
                           data-param="icon" v-model="item.param.icon">
                    <template v-if="item.param.icon">
                        <div class="upload-preview select-file text-center upload-preview">
                            <span class="upload-preview-tip">36&times;36</span>
                            <img class="upload-preview-img" :src="item.param.icon">
                        </div>
                    </template>
                    <template v-else>
                        <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        <div>建议尺寸<br>36*36</div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>文本颜色</span></div>
        <div class="border-block start_click" data-param="color">
            <div :style="{backgroundColor:item.param.color}"></div>
        </div>
        <div>
            <input v-model="item.param.color" class="form-control" style="width: 7rem;">
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>背景颜色</span></div>
        <div class="border-block start_click" data-param="bg_color">
            <div :style="{backgroundColor:item.param.bg_color}"></div>
        </div>
        <div>
            <input v-model="item.param.bg_color" class="form-control" style="width: 7rem;">
        </div>
    </div>
</div>

