<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/10/9
 * Time: 9:30
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">视频</div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4"><span>封面图</span></div>
        <div class="video-block col-6 notice-block">
            <div class="img d-flex justify-content-center align-items-center">
                <div class="upload-group">
                    <input class="form-control file-input item-param" style="display: none;"
                           data-param="pic_url" v-model="item.param.pic_url">
                    <template v-if="item.param.pic_url">
                        <div class="upload-preview select-file text-center upload-preview">
                            <span class="upload-preview-tip">750&times;400</span>
                            <img class="upload-preview-img" :src="item.param.pic_url">
                        </div>
                    </template>
                    <template v-else>
                        <div class="select-file" style="color: #5CB3FD;cursor: pointer">+添加图片</div>
                        <div>建议尺寸<br>750*400</div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-4"><span>视频链接</span></div>
        <div class="video-block col-8">
            <div class="video-picker"
                 data-url="<?= $urlManager->createUrl(['upload/video']) ?>">
                <div class="input-group">
                    <input class="video-picker-input video form-control item-param"
                           data-index="video_url" v-model="item.param.url" data-param="url"
                           placeholder="请输入视频源地址或者选择上传视频">
                    <a href="javascript:" class="btn btn-secondary video-picker-btn">选择视频</a>
                </div>
                <div class="video-preview"></div>
                <div class="text-danger">支持格式mp4;支持编码H.264;</div>
                <div class="text-success">支持腾讯视频;例如：https://v.qq.com/x/page/h056607xye8.html</div>
                <div class="text-danger">视频大小不能超过<?= \app\models\UploadForm::getMaxUploadSize() ?>M</div>
            </div>
        </div>
    </div>
</div>

