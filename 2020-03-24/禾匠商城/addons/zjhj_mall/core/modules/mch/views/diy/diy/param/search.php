<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/19
 * Time: 15:30
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">搜索</div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>框内颜色</span></div>
        <div class="border-block start_click" data-param="backgroundColor">
            <div :style="{backgroundColor:item.param.backgroundColor}"></div>
        </div>
        <div>
            <input v-model="item.param.backgroundColor" class="form-control" style="width: 7rem;">
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>背景颜色</span></div>
        <div class="border-block start_click" data-param="backgroundColorW">
            <div :style="{backgroundColor:item.param.backgroundColorW}"></div>
        </div>
        <div>
            <input v-model="item.param.backgroundColorW" class="form-control" style="width: 7rem;">
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>边框圆角</span></div>
        <div class="input-block col-6">
            <div class="input-group">
                <input class="form-control input-num" type="number" min="1" max="30"
                       v-model="item.param.borderRadius" data-param="borderRadius">
                <span class="input-group-addon">px</span>
            </div>
            <div class="text-danger">最大圆角30px</div>
        </div>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>文本內容</span></div>
        <div class="input-block col-6">
            <input class="form-control" type="text"
                   v-model="item.param.text" data-param="text">
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
        <div class="d-flex col-3 align-items-center"><span>文本位置</span></div>
        <div :class="'border-block radio-block ' + (item.param.textPosition == 'center' ? '' : 'active')"
             data-param="textPosition" data-item="left">
            <div>靠左</div>
        </div>
        <div :class="'border-block radio-block ml-4 ' + (item.param.textPosition == 'center' ? 'active' : '')"
             data-param="textPosition" data-item="center">
            <div>居中</div>
        </div>
    </div>

</div>
