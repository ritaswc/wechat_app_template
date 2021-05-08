<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
$urlManager = Yii::$app->urlManager;
$singleAttr = isset($attr) && !empty($attr) ? Yii::$app->serializer->decode($attr) : [];
?>

<div class="attr_share_price">
    <div class="col-9 row col-form-label">
        <div class="col-3">
            <label class=" col-form-label required">是否开启单独分销</label>
        </div>
        <div class="col-9 col-form-label">
            <label class="radio-label">
                <input <?= $goods['individual_share'] == 0 ? 'checked' : null ?>
                        value="0" name="model[individual_share]" type="radio"
                        class="custom-control-input">
                <span class="label-icon"></span>
                <span class="label-text">不开启</span>
            </label>
            <label class="radio-label">
                <input <?= $goods['individual_share'] == 1 ? 'checked' : null ?>
                        value="1" name="model[individual_share]" type="radio"
                        class="custom-control-input">
                <span class="label-icon"></span>
                <span class="label-text">开启</span>
            </label>
        </div>
    </div>

    <div class="share_box1">
        <div class="col-9 col-form-label row share_setting">
            <div class="col-3">
                <label class="col-form-label required">分销类型</label>
            </div>
            <div class="col-9 col-form-label">
                <label class="radio-label">
                    <input <?= $goods['attr_setting_type'] == 0 ? 'checked' : null ?>
                            value="0" name="model[attr_setting_type]" type="radio"
                            class="custom-control-input">
                    <span class="label-icon"></span>
                    <span class="label-text">普通设置</span>
                </label>
                <label <?= get_plugin_type() == 2 ? 'hidden' : '' ?> class="radio-label">
                    <input <?= $goods['attr_setting_type'] == 1 ? 'checked' : null ?>
                            value="1" name="model[attr_setting_type]" type="radio"
                            class="custom-control-input">
                    <span class="label-icon"></span>
                    <span class="label-text">详细设置</span>
                </label>
            </div>
        </div>

        <div class="col-9 col-form-label row share-type_setting">
            <div class="col-3">
                <label class=" col-form-label required">分销佣金类型</label>
            </div>
            <div class="col-9 col-form-label">
                <label class="radio-label share-type">
                    <input <?= $goods->share_type == 0 ? 'checked' : null ?>
                            name="model[share_type]"
                            value="0"
                            type="radio"
                            class="custom-control-input">
                    <span class="label-icon"></span>
                    <span class="label-text">百分比</span>
                </label>
                <label class="radio-label share-type">
                    <input <?= $goods->share_type == 1 ? 'checked' : null ?>
                            name="model[share_type]"
                            value="1"
                            type="radio"
                            class="custom-control-input">
                    <span class="label-icon"></span>
                    <span class="label-text">固定金额</span>
                </label>
            </div>
        </div>

        <!--        普通设置-->
        <div class="col-9 row share-commission">
            <div class="col-3">
                <label class=" col-form-label required">单独分销设置</label>
            </div>
            <div class="col-9">
                <div class="short-row">
                    <div class="input-group mb-3">
                        <span class="input-group-addon">一级佣金</span>
                        <input name="model[share_commission_first]"
                               value="<?= $goods['share_commission_first'] ?>"
                               class="form-control"
                               type="number"
                               step="0.01"
                               min="0">
                        <span
                                class="input-group-addon percent"><?= $goods->share_type == 1 ? "元" : "%" ?></span>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-addon">二级佣金</span>
                        <input name="model[share_commission_second]"
                               value="<?= $goods['share_commission_second'] ?>"
                               class="form-control"
                               type="number"
                               step="0.01"
                               min="0">
                        <span
                                class="input-group-addon percent"><?= $goods->share_type == 1 ? "元" : "%" ?></span>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-addon">三级佣金</span>
                        <input name="model[share_commission_third]"
                               value="<?= $goods['share_commission_third'] ?>"
                               class="form-control"
                               type="number"
                               step="0.01"
                               min="0">
                        <span
                                class="input-group-addon percent"><?= $goods->share_type == 1 ? "元" : "%" ?></span>
                    </div>
                    <div class="fs-sm">
                        <a href="<?= $urlManager->createUrl(['mch/share/basic']) ?>"
                           target="_blank">分销层级</a>的优先级高于商品单独的分销比例，例：层级只开二级分销，那商品的单独分销比例只有二级有效
                    </div>
                </div>
            </div>
        </div>

        <!--        详细设置-->
        <div class="detail_share_setting">
            <div v-if="use_attr == 1">
                <div v-if="checked_attr_list.length > 0" class="form-group row">
                    <div class="col-12">
                        <div v-if="attr_group_list && attr_group_list.length>0">
                            <table class="table table-bordered attr-table">
                                <thead>
                                <tr>
                                    <th v-for="(attr_group,i) in attr_group_list"
                                        v-if="attr_group.attr_list && attr_group.attr_list.length>0">
                                        {{attr_group.attr_group_name}}
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span>库存</span>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span>价格</span>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span>货号</span>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span>一级佣金</span>
                                            <span style="display: <?= $goods->share_type == 1 ? 'none' : 'block' ?>"  class="bfb">（%）</span>
                                            <span style="display: <?= $goods->share_type == 0 ? 'none' : 'block' ?>" class="yuan">（元）</span>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span>二级佣金</span>
                                            <span style="display: <?= $goods->share_type == 1 ? 'none' : 'block' ?>"  class="bfb">（%）</span>
                                            <span style="display: <?= $goods->share_type == 0 ? 'none' : 'block' ?>" class="yuan">（元）</span>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span>三级佣金</span>
                                            <span style="display: <?= $goods->share_type == 1 ? 'none' : 'block' ?>"  class="bfb">（%）</span>
                                            <span style="display: <?= $goods->share_type == 0 ? 'none' : 'block' ?>" class="yuan">（元）</span>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tr v-for="(item,index) in checked_attr_list">
                                    <td v-for="(attr,attr_index) in item.attr_list">
                                        <input type="hidden"
                                               v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_id]'"
                                               v-bind:value="attr.attr_id">

                                        <input type="hidden" style="width: 40px"
                                               v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_name]'"
                                               v-bind:value="attr.attr_name">

                                        <input type="hidden" style="width: 40px"
                                               v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_group_name]'"
                                               v-bind:value="attr.attr_group_name">
                                        <span>{{attr.attr_name}}</span>
                                    </td>
                                    <td>
                                        <input disabled class="form-control form-control-sm" type="number"
                                               min="0"
                                               step="1" style="width: 60px;"
                                               v-bind:name="'attr['+index+'][num]'"
                                               v-model="item.num" v-on:change="change(item,index)">
                                    </td>
                                    <td>
                                        <input disabled class="form-control form-control-sm" type="number"
                                               min="0"
                                               step="0.01" style="width: 70px"
                                               v-bind:name="'attr['+index+'][price]'"
                                               v-model="item.price"
                                               v-on:change="change(item,index)">
                                    </td>
                                    <td>
                                        <input disabled class="form-control form-control-sm"
                                               style="width: 100px"
                                               v-bind:name="'attr['+index+'][no]'"
                                               v-model="item.no" v-on:change="change(item,index)">
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm"
                                               style="width: 100px"
                                               type="number"
                                               v-bind:name="'attr['+index+'][share_commission_first]'"
                                               v-model="item.share_commission_first"
                                               v-on:change="change(item,index)">
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm"
                                               style="width: 100px"
                                               type="number"
                                               v-bind:name="'attr['+index+'][share_commission_second]'"
                                               v-model="item.share_commission_second"
                                               v-on:change="change(item,index)">
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm"
                                               type="number"
                                               style="width: 100px"
                                               v-bind:name="'attr['+index+'][share_commission_third]'"
                                               v-model="item.share_commission_third"
                                               v-on:change="change(item,index)">
                                    </td>
                                </tr>
                            </table>
                            <div>
                                <label style="color: red;">批量设置(0.01 ~ N)</label>
                                <div>
                                    <label>一级佣金</label>
                                    <input type="number"  class="share_price_one">
                                    <label>二级佣金</label>
                                    <input type="number" class="share_price_two">
                                    <label>三级佣金</label>
                                    <input type="number" class="share_price_three">
                                    <a class="btn btn-primary set-share-price" href="javascript:">设置</a>
                                    <a class="btn btn-primary delete-share-price" href="javascript:">清空</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else="" style="color: red;text-align: center;">请先在 ->商品详情 添加商品规格</div>
            </div>
            <div v-else style="width: 100%;line-height: 80px;text-align: center;margin-top: 20px;">
                <div class="col-9 row">
                    <div class="col-3">
                        <label class=" col-form-label required">单规格分销设置</label>
                    </div>
                    <div class="col-9">
                        <div class="short-row">
                            <div class="input-group mb-3">
                                <span class="input-group-addon">一级佣金</span>
                                <input name="model[single_share_commission_first]"
                                       value="<?= $singleAttr[0]['share_commission_first'] ?>"
                                       class="form-control"
                                       type="number"
                                       step="0.01"
                                       min="0">
                                <span
                                        class="input-group-addon percent"><?= $goods->share_type == 1 ? "元" : "%" ?></span>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-addon">二级佣金</span>
                                <input name="model[single_share_commission_second]"
                                       value="<?= $singleAttr[0]['share_commission_second'] ?>"
                                       class="form-control"
                                       type="number"
                                       step="0.01"
                                       min="0">
                                <span
                                        class="input-group-addon percent"><?= $goods->share_type == 1 ? "元" : "%" ?></span>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-addon">三级佣金</span>
                                <input name="model[single_share_commission_third]"
                                       value="<?= $singleAttr[0]['share_commission_third'] ?>"
                                       class="form-control"
                                       type="number"
                                       step="0.01"
                                       min="0">
                                <span
                                        class="input-group-addon percent"><?= $goods->share_type == 1 ? "元" : "%" ?></span>
                            </div>
                            <div style="color: red;">
                                如需设置多规格分销价，请在 ->商品编辑 勾选使用规格
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
