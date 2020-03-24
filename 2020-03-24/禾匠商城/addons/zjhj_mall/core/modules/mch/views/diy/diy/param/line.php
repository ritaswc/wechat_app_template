<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/29
 * Time: 14:24
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">空白占位</div>
    </div>
    <div class="text-danger p-2">注：空白占位图中上下的白边，<br>实际效果中是没有的，仅做选择用途</div>
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
        <div class="d-flex col-4 align-items-center"><span>高度</span></div>
        <div class="input-block col-6">
            <div class="input-group">
                <input class="form-control input-num" type="number" min="1"
                       v-model="item.param.height" data-param="height">
                <span class="input-group-addon">px</span>
            </div>
        </div>
    </div>
</div>



