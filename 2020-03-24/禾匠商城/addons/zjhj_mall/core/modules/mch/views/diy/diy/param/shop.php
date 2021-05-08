<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/11/13
 * Time: 11:04
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div>
    <div class="d-flex p-2">
        <div class="col">门店</div>
    </div>
    <div class="bb-1 p-2">
        <div class="d-flex col"><span>添加门店</span></div>
        <div class="d-flex flex-wrap banner-one">
            <template v-for="(v, k) in item.param.list">
                <div class="img-list" style="margin: 0 10px 10px 0;" :data-k="k">
                    <img :src="v.pic_url" style="width: 100%;height: 100%;">
                    <div class="chacha chacha-goods"></div>
                </div>
            </template>
            <div data-key="0" data-type="shop"
                 class="goods-add img-list d-flex justify-content-center align-items-center">
                +
            </div>
        </div>
    </div>
    <div class="bb-1 p-2">
        <div class="d-flex col"><span>显示内容</span></div>
        <div class="d-flex flex-wrap col">
            <div>
                <label class="checkbox-label checkbox-block"
                       data-param="name">
                    <input type="checkbox" :checked="item.param.name == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">门店名称</span>
                </label>
                <label class="checkbox-label checkbox-block"
                       data-param="score">
                    <input type="checkbox" :checked="item.param.score == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">评分</span>
                </label>
                <label class="checkbox-label checkbox-block"
                       data-param="mobile">
                    <input type="checkbox" :checked="item.param.mobile == 1 ? true : ''">
                    <span class="label-icon"></span>
                    <span class="label-text">电话</span>
                </label>
            </div>
        </div>
    </div>
</div>



