<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

$attrs = isset($attr) && !empty($attr) ? Yii::$app->serializer->decode($attr) : [];

?>

<div style="background-color: #fff;padding: 20px;margin-top: 15px;margin-bottom: 15px;">

    <div class="form-group row">
        <div class="col-3 text-right">
            <label class=" col-form-label">是否享受会员折扣</label>
        </div>
        <div class="col-9">
            <label class="radio-label">
                <input <?= $goods['is_level'] == 0 ? 'checked' : null ?>
                        value="0" name="model[is_level]" type="radio"
                        class="custom-control-input">
                <span class="label-icon"></span>
                <span class="label-text">关闭</span>
            </label>
            <label class="radio-label">
                <input <?= $goods['is_level'] == 1 ? 'checked' : null ?>
                        value="1" name="model[is_level]" type="radio"
                        class="custom-control-input">
                <span class="label-icon"></span>
                <span class="label-text">开启</span>
            </label>
        </div>
    </div>

    <div class="member_price_box">
        <div v-if="use_attr">
            <div v-if="checked_attr_list.length > 0" style="background-color: #fff;padding-top: 2em;"
                 class="form-group row">
                <div class="col-12">
                    <div>
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
                                <?php foreach ($levelList as $level): ?>
                                    <th>
                                        <div class="input-group">
                                            <span><?= $level['name'] ?></span>
                                        </div>
                                    </th>
                                <?php endforeach; ?>
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

                                <?php foreach ($levelList as $level): ?>
                                    <td>
                                        <input type="number" class="form-control form-control-sm"
                                               style="width: 70px"
                                               v-bind:name="'attr['+index+'][<?= 'member' . $level['level'] ?>]'"
                                               v-model="item.<?= 'member' . $level['level'] ?>"
                                               v-on:change="change(item,index)">
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </table>
                        <div>
                            <label style="color: red;">批量设置(0.01 ~ N)</label>
                            <div>
                                <?php foreach ($levelList as $level) : ?>
                                    <label><?= $level['name'] ?></label>
                                    <input type="number" class="<?= 'member' . $level['level'] ?>">
                                <?php endforeach; ?>
                                <a class="btn btn-primary set-member-price" href="javascript:">设置</a>
                                <a class="btn btn-danger delete-member-price" href="javascript:">清空</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else="" style="color: red;text-align: center;">请先在 ->商品详情 添加商品规格</div>
        </div>
        <div style="width: 100%;text-align: center;" v-else>
            <div class="col-9">
                <div>
                    <table class="table table-bordered attr-table">
                        <thead>
                        <tr>
                            <th>
                                规格
                            </th>
                            <?php foreach ($levelList as $level): ?>
                                <th>
                                    <div class="input-group">
                                        <span><?= $level['name'] ?></span>
                                    </div>
                                </th>
                            <?php endforeach; ?>
                        <tr>
                            <td>默认</td>
                            <?php foreach ($levelList as $level): ?>
                                <td>
                                    <input value="<?= $attrs[0]['member' . $level['level']] ?>"
                                           name="<?= 'member' . $level['level'] ?>" type="number"
                                           class="form-control form-control-sm"
                                           style="width: 70px">
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="color: red;">如需设置多规格会员价，请在 ->商品编辑 勾选使用规格</div>
        </div>
    </div>
</div>