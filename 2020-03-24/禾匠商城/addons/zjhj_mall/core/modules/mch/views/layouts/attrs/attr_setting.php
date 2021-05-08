<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

?>

<div class="step-block" flex="dir:left box:first">
    <div>
        <span>规格库存</span>
        <span class="step-location" id="step3"></span>
    </div>
    <div>
        <!-- 无规格 -->
        <div class="form-group row">
            <div class="col-3 text-right">
                <label class=" col-form-label required">商品库存</label>
            </div>
            <div class="col-9">
                <div class="input-group short-row">
                    <input class="form-control" name="model[goods_num]"
                           value="<?= $goods->getNum() ?>">
                    <span class="input-group-addon">件</span>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-3 text-right">
                <label class=" col-form-label">商品货号</label>
            </div>
            <div class="col-9">
                <div class="input-group short-row">
                    <input class="form-control" name="model[goods_no]"
                           value="<?= $goods->getGoodsNo() ?>">
                </div>
            </div>
        </div>

        <!-- 规格开关 -->
        <div class="form-group row" <?= get_plugin_type() == 2 ? 'hidden' : '' ?>>
            <div class="col-3 text-right">
                <label class="col-form-label">是否使用规格</label>
            </div>
            <div class="col-9 col-form-label">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox"
                           name="model[use_attr]"
                           value="1"
                        <?= $goods->use_attr ? 'checked' : null ?>
                           class="custom-control-input use-attr">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">使用规格</span>
                </label>
            </div>
        </div>

        <!-- 有规格 -->
        <div class="attr-edit-block" <?= get_plugin_type() == 2 ? 'hidden' : '' ?>>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">规格组和规格值</label>
                </div>
                <div class="col-9">
                    <div class="input-group short-row mb-2" v-if="attr_group_list.length<5">
                        <span class="input-group-addon">规格组</span>
                        <input class="form-control add-attr-group-input"
                               placeholder="如颜色、尺码、套餐">
                        <span class="input-group-btn">
                                    <a class="btn btn-secondary add-attr-group-btn" href="javascript:">添加</a>
                                </span>
                    </div>
                    <div v-else class="mb-2">最多只可添加5个规格组</div>
                    <div v-for="(attr_group,i) in attr_group_list" class="attr-group">
                        <div>
                            <b>{{attr_group.attr_group_name}}</b>
                            <a v-bind:index="i" href="javascript:"
                               class="attr-group-delete">×</a>
                        </div>
                        <div class="attr-list">
                            <div v-for="(attr,j) in attr_group.attr_list" class="attr-item">
                                <span class="attr-name">{{attr.attr_name}}</span>
                                <a v-bind:group-index="i" v-bind:index="j"
                                   class="attr-delete"
                                   href="javascript:">×</a>
                            </div>
                            <div style="display: inline-block;width: 200px;margin-top: .5rem">
                                <div class="input-group attr-input-group"
                                     style="border-radius: 0">
                                            <span class="input-group-addon"
                                                  style="padding: .35rem .35rem;font-size: .8rem">规格值</span>
                                    <input class="form-control form-control-sm add-attr-input"
                                           placeholder="如红色、白色">
                                    <span class="input-group-btn">
                                                <a v-bind:index="i" class="btn btn-secondary btn-sm add-attr-btn"
                                                   href="javascript:">添加</a>
                                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">价格和库存</label>
                </div>
                <div class="col-9">
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
                                        <input class="form-control form-control-sm"
                                               type="number"
                                               min="1"
                                               style="width: 60px">
                                        <span class="input-group-addon"><a
                                                    href="javascript:"
                                                    class="bat"
                                                    data-index="0">设置</a></span>
                                    </div>
                                </th>
                                <th>
                                    <div class="input-group">
                                        <span <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?>>价格</span>
                                        <span <?= in_array(get_plugin_type(), [0, 2]) ? 'hidden' : '' ?>>活力币</span>
                                        <input class="form-control form-control-sm"
                                               type="number"
                                               min="0.01"
                                               style="width: 60px">
                                        <span class="input-group-addon"><a
                                                    href="javascript:"
                                                    class="bat"
                                                    data-index="1">设置</a></span>
                                    </div>
                                </th>
                                <th>
                                    <div class="input-group">
                                        <span>货号</span>
                                        <input class="form-control form-control-sm"
                                               style="width: 60px">
                                        <span class="input-group-addon"><a
                                                    href="javascript:"
                                                    class="bat"
                                                    data-index="2">设置</a></span>
                                    </div>
                                </th>
                                <th>规格图片</th>
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
                                    <input class="form-control form-control-sm"
                                           type="number"
                                           min="0"
                                           step="1" style="width: 60px"
                                           v-bind:name="'attr['+index+'][num]'"
                                           v-model="item.num"
                                           v-on:change="change(item,index)">
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           type="number"
                                           min="0"
                                           step="0.01" style="width: 70px"
                                           v-bind:name="'attr['+index+'][price]'"
                                           v-model="item.price"
                                           v-on:change="change(item,index)">
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           style="width: 100px"
                                           v-bind:name="'attr['+index+'][no]'"
                                           v-model="item.no"
                                           v-on:change="change(item,index)">
                                </td>
                                <td>
                                    <div class="input-group input-group-sm"
                                         v-bind:data-index="index">
                                        <input class="form-control form-control-sm"
                                               style="width: 40px"
                                               v-bind:name="'attr['+index+'][pic]'"
                                               v-model="item.pic"
                                               v-on:change="change(item,index)">
                                        <span class="input-group-btn">
                                                    <a class="btn btn-secondary upload-attr-pic" href="javascript:"
                                                       data-toggle="tooltip"
                                                       data-placement="bottom" title="上传文件">
                                                        <span class="iconfont icon-cloudupload"></span>
                                                    </a>
                                                    </span>
                                        <span class="input-group-btn">
                                                        <a class="btn btn-secondary select-attr-pic" href="javascript:"
                                                           data-toggle="tooltip"
                                                           data-placement="bottom" title="从文件库选择">
                                                            <span class="iconfont icon-viewmodule"></span>
                                                        </a>
                                                    </span>
                                        <span class="input-group-btn">
                                                        <a class="btn btn-secondary delete-attr-pic" href="javascript:"
                                                           data-toggle="tooltip"
                                                           data-placement="bottom" title="删除文件">
                                                            <span class="iconfont icon-close"></span>
                                                        </a>
                                                    </span>
                                    </div>
                                    <img v-if="item.pic" v-bind:src="item.pic"
                                         style="width: 50px;height: 50px;margin: 2px 0;border-radius: 2px">
                                </td>
                            </tr>
                        </table>
                        <div class="text-muted fs-sm">规格价格0表示保持原售价</div>
                    </div>
                    <div v-else class="pt-2 text-muted">请先填写规格组和规格值</div>
                </div>
            </div>
        </div>

    </div>
</div>
