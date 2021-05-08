<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/29
 * Time: 14:37
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">流量主</div>
    </div>
    <div class="text-danger p-2">
        <span>注：流量主广告需要申请开通流量主功能</span>
        <br>
        <a href="https://mp.weixin.qq.com" target="_blank">开通链接</a>
    </div>
    <div class="d-flex p-2">
        <div class="d-flex col-3 align-items-center"><span>广告位ID</span></div>
        <div class="input-block col-9">
            <input class="form-control input-num" type="text"
                   v-model="item.param.id" data-param="id">
        </div>
    </div>
</div>



