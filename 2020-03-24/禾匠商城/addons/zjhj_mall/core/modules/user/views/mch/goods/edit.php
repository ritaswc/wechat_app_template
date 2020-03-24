<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 10:49
 */

$urlManager = Yii::$app->urlManager;
$this->title = '商品编辑';
$staticBaseUrl = Yii::$app->request->baseUrl . '/statics';
$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl) {
    $returnUrl = $urlManager->createUrl(['user/mch/goods/index']);
}
?>
<style>

    .step-block {
        position: relative;
        transition: 200ms;
    }

    form .body {
        padding-top: 45px;
    }

    .step-block > div {
        padding: 20px;
        border: 1px solid #eee;
        transition: 200ms;
    }

    .step-block:hover {
        box-shadow: 0 1px 8px rgba(0, 0, 0, .15);
        z-index: 2;
    }

    .step-block:hover > div {
        border-color: #e3e3e3;
    }

    .step-block > div:first-child {
        padding: 20px;
        width: 120px;
        font-weight: bold;
        text-align: center;
        border-right: none;
    }

    .step-block .step-location {
        position: absolute;
        top: -122px;
        left: 0;
    }

    .step-block:first-child .step-location {
        top: -140px;
    }

    .edui-editor,
    #edui1_toolbarbox {
        z-index: 2 !important;
    }

    form .short-row {
        width: 380px;
    }

    form .form-group .col-3 {
        -webkit-box-flex: 0;
        -webkit-flex: 0 0 160px;
        -ms-flex: 0 0 160px;
        flex: 0 0 160px;
        max-width: 160px;
        width: 160px;
    }

    .cat-list .cat-item {
        max-width: 380px;
        background: #f5f7f9;
        padding: .35rem .7rem;
        margin-bottom: .5rem;
        border: 1px solid #f0f2f4;
    }

    .select-cat-list > div {
        margin-bottom: 1rem;
    }

    .select-cat-list .cat-item {
        display: inline-block;
        background: #f5f7f9;
        padding: .35rem .7rem;
        cursor: pointer;
        border: 1px solid #f5f7f9;
        transition: 150ms;
        float: left;
        margin-right: .5rem;
    }

    .select-cat-list .cat-item:hover {
        border: 1px solid #0275d8;
    }

    .select-cat-list .cat-item.checked {
        background: #0275d8;
        color: #fff;
        border: 1px solid #0275d8;
    }

    .publish-bar {
        position: fixed;
        bottom: 0;
        right: 0;
        z-index: 10;
        border: 1px solid #ccd0d4;
        left: 240px;
        text-align: center;
        padding: .5rem;
        background: #dde2e6;
    }

    .main-body {
        padding-bottom: 3.2rem !important;
    }

    .attr-group-list .attr-group-item:after {
        display: block;
        content: " ";
        height: 0;
        width: calc(100% + 2rem);
        margin-left: -1rem;
        border-bottom: 1px solid #eee;
    }

    .attr-group-list .attr-group-item {
        margin-bottom: 1rem;
    }

    .attr-group-list .attr-group-item:last-child {
        margin-bottom: 0;
    }

    .attr-group-list .attr-group-item:last-child:after {
        display: none;
    }

    .attr-item {
        display: inline-block;
        position: relative;
        background: #fff;
        padding: .25rem .5rem;
        margin-right: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #eee;
    }

    .attr-group-delete,
    .attr-item .attr-item-delete,
    .attr-row-delete-pic {
        display: inline-block;
        background: #fff;
        border: 1px solid #979797;
        color: #725755 !important;
        text-decoration: none !important;
        width: 1rem;
        height: 1rem;
        line-height: .75rem;
        text-align: center;
        transition: 150ms;
        transform: translateY(-.08rem);
    }

    .attr-group-delete:hover,
    .attr-item .attr-item-delete:hover,
    .attr-row-delete-pic:hover {
        border: 1px solid #ff4544;
        color: #fff !important;
        background: #ff4544;
    }

    td {
        cursor: default;
    }

    .input-td {
        padding: 0 .5rem !important;
        width: 8rem;
        vertical-align: middle;
    }

    .input-td input {
        display: inline-block;
        margin: 0;
        width: 100%;
        border: none;
        color: inherit;
        text-align: center;
        cursor: text;
        height: 100%;
    }

    .input-td input:focus {
        outline: none;
    }

</style>
<div class="panel" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $returnUrl ?>">

            <div class="step-block" flex="dir:left box:first" v-if="cat_list">
                <div>
                    <span>选择分类</span>
                </div>
                <div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">系统分类</label>
                        </div>
                        <div class="col-9">
                            <select class="form-control short-row" name="store_cat_id">
                                <option value="">--</option>
                                <template v-for="cat in store_cat_list">
                                    <option v-if="cat.selected" selected :value="cat.id">{{cat.name}}</option>
                                    <option v-else :value="cat.id">{{cat.name}}</option>
                                    <template v-if="cat.list && cat.list.length">
                                        <template v-for="scat in cat.list">
                                            <option v-if="scat.selected" selected :value="scat.id">&nbsp;&nbsp;&nbsp;&nbsp;{{scat.name}}</option>
                                            <option v-else :value="scat.id">&nbsp;&nbsp;&nbsp;&nbsp;{{scat.name}}
                                            </option>
                                        </template>
                                    </template>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label required">商品分类</label>
                        </div>
                        <div class="col-9">
                            <div class="cat-list">
                                <template v-for="(item,index) in cat_list">
                                    <div v-if="item.checked" class="cat-item" flex="dir:left box:last">
                                        <input type="hidden" name="cat_id[]" :value="item.id">
                                        <div>{{item.name}}</div>
                                        <div><a :data-index="index"
                                                :data-parent-index="-1"
                                                class="btn btn-sm btn-secondary uncheck-cat"
                                                href="javascript:">移除</a></div>
                                    </div>
                                    <template v-if="item.list && item.list.length>0">
                                        <template v-for="(sub_item,sub_index) in item.list">
                                            <div v-if="sub_item.checked" class="cat-item" flex="dir:left box:last">
                                                <input type="hidden" name="cat_id[]" :value="sub_item.id">
                                                <div>{{sub_item.name}}</div>
                                                <div><a :data-index="sub_index"
                                                        :data-parent-index="index"
                                                        class="btn btn-sm btn-secondary uncheck-cat"
                                                        href="javascript:">移除</a></div>
                                            </div>
                                        </template>
                                    </template>
                                </template>
                            </div>
                            <a class="btn btn-secondary select-cat" href="javascript:">选择分类</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-block" flex="dir:left box:first" v-if="cat_list">
                <div>
                    <span>一键导入</span>
                </div>
                <div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">淘宝一键采集</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group short-row">
                                <input class="form-control copy-url" placeholder="请输入淘宝商品详情地址连接">
                                <span class="input-group-btn">
                                    <a class="btn btn-secondary copy-btn" href="javascript:">立即获取</a>
                                </span>
                            </div>
                            <div class="short-row text-muted fs-sm">
                                例如：商品链接为:http://item.taobao.com/item.htm?id=522155891308
                                或:http://detail.tmall.com/item.htm?id=522155891308
                            </div>
                            <div class="short-row text-muted fs-sm">若不使用，则该项为空</div>
                            <div class="copy-error text-danger fs-sm" hidden></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-block" flex="dir:left box:first" v-if="model">
                <div>
                    <span>基本信息</span>
                    <span class="step-location" id="step2"></span>
                </div>
                <div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">商品名称</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control short-row" type="text" name="name"
                                   v-model="model.name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">单位</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control short-row" type="text" name="unit"
                                   v-model="model.unit">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">商品排序</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control short-row" type="number" min="0" step="1" name="mch_sort"
                                   v-model="model.mch_sort">
                            <div class="text-muted fs-sm">排序按升序排列</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">重量</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group short-row">
                                <input type="number" step="0.01" class="form-control"
                                       name="weight"
                                       v-model="model.weight">
                                <span class="input-group-addon">克<span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label required">商品缩略图</label>
                        </div>
                        <div class="col-9">
                            <div class="upload-group short-row">
                                <div class="input-group">
                                    <input class="form-control file-input" name="cover_pic"
                                           onchange="app.model.cover_pic=this.value"
                                           v-model="model.cover_pic">
                                    <span class="input-group-btn">
                                        <a class="btn btn-secondary upload-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="上传文件">
                                            <span class="iconfont icon-cloudupload"></span>
                                        </a>
                                    </span>
                                    <span class="input-group-btn">
                                        <a class="btn btn-secondary delete-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="删除文件">
                                            <span class="iconfont icon-close"></span>
                                        </a>
                                    </span>
                                </div>
                                <div class="upload-preview text-center upload-preview">
                                    <span class="upload-preview-tip">325&times;325</span>
                                    <img class="upload-preview-img" :src="model.cover_pic">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label required">商品组图</label>
                        </div>
                        <div class="col-9">

                            <div class="upload-group multiple short-row">
                                <div class="input-group">
                                    <input class="form-control file-input" readonly>
                                    <span class="input-group-btn">
                                        <a class="btn btn-secondary upload-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="上传文件">
                                            <span class="iconfont icon-cloudupload"></span>
                                        </a>
                                    </span>
                                </div>
                                <div class="upload-preview-list">
                                    <template v-if="model.goods_pic_list && model.goods_pic_list.length>0">
                                        <div v-for="(item,i) in model.goods_pic_list"
                                             class="upload-preview text-center">
                                            <input type="hidden" class="file-item-input"
                                                   name="goods_pic_list[]"
                                                   :value="item">
                                            <span class="file-item-delete">&times;</span>
                                            <span class="upload-preview-tip">750&times;750</span>
                                            <img class="upload-preview-img" :src="item">
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="upload-preview text-center">
                                            <input type="hidden" class="file-item-input" name="goods_pic_list[]">
                                            <span class="file-item-delete">&times;</span>
                                            <span class="upload-preview-tip">750&times;750</span>
                                            <img class="upload-preview-img" src="">
                                        </div>
                                    </template>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">售价</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group short-row">
                                <input type="number" step="0.01" class="form-control"
                                       name="price" min="0.01"
                                       v-model="model.price">
                                <span class="input-group-addon">元</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">原价</label>
                        </div>
                        <div class="col-9">
                            <input type="number" step="0.01" class="form-control short-row"
                                   name="original_price" min="0"
                                   v-model="model.original_price">
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">服务内容</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control short-row" name="service"
                                   v-model="model.service">
                            <div class="fs-sm text-muted">例子：正品保障,极速发货,7天退换货。多个请使用英文逗号<i
                                    style="display: inline-block;padding: 0.25rem;background: #e3e3e3;border-radius: .25rem;line-height: .5rem">,</i>分隔
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">运费设置</label>
                        </div>
                        <div class="col-9">
                            <select v-model="model.freight" class="form-control short-row" name="freight">
                                <option value="0">默认模板</option>
                                <option v-for="(r,i) in postage_riles" :value="r.id">{{r.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">单品满件包邮</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group short-row">
                                <input type="number" class="form-control short-row" name="full_cut[pieces]"
                                       v-model="model.full_cut.pieces">
                                <span class="input-group-addon">件</span>
                            </div>
                            <div class="fs-sm text-muted">如果设置0或空，则不支持满件包邮</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">单品满额包邮</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group short-row">
                                <input type="number" step="0.01" class="form-control short-row"
                                       name="full_cut[forehead]"
                                       v-model="model.full_cut.forehead">
                                <span class="input-group-addon">元</span>
                            </div>
                            <div class="fs-sm text-muted">如果设置0或空，则不支持满额包邮</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-block" flex="dir:left box:first" v-if="model">
                <div>
                    <span>规格库存</span>
                    <span class="step-location" id="step3"></span>
                </div>
                <div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">是否启用规格</label>
                        </div>
                        <div class="col-9">
                            <label class="radio-label">
                                <input type="radio" name="use_attr" value="0" v-model="model.use_attr">
                                <span class="label-icon"></span>
                                <span class="label-text">不启用</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="use_attr" value="1" v-model="model.use_attr">
                                <span class="label-icon"></span>
                                <span class="label-text">启用</span>
                            </label>
                        </div>
                    </div>

                    <template v-if="model.use_attr==0">
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class=" col-form-label required">商品库存</label>
                            </div>
                            <div class="col-9">
                                <div class="input-group short-row">
                                    <input type="number" step="1" min="0"
                                           class="form-control short-row"
                                           name="goods_num"
                                           v-model="model.goods_num">
                                    <span class="input-group-addon">件</span>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>

                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class=" col-form-label required">规格编辑</label>
                            </div>
                            <div class="col-9">
                                <div v-if="attr_group_list && attr_group_list.length>0"
                                     class="panel mb-3" style="background: #f5f7f9">
                                    <div class="panel-body">
                                        <div class="attr-group-list">
                                            <div v-for="(attr_group,group_index) in attr_group_list"
                                                 class="attr-group-item">
                                                <div class="mb-3">
                                                    <a :data-group-index="group_index"
                                                       class="attr-group-delete" href="javascript:">×</a>
                                                    <b>{{attr_group.attr_group_name}}：</b>
                                                    <div class="form-inline d-inline-block">
                                                        <input class="form-control form-control-sm add-attr-input mr-1"
                                                               placeholder="如红色、M码、套餐1">
                                                        <a class="btn btn-sm add-attr-btn btn-secondary"
                                                           :data-group-index="group_index" href="javascript:">添加值</a>
                                                    </div>
                                                </div>
                                                <div class="attr-list">
                                                    <div v-for="(attr,attr_index) in attr_group.attr_list"
                                                         class="attr-item">
                                                        <a :data-group-index="group_index"
                                                           :data-attr-index="attr_index"
                                                           class="attr-item-delete"
                                                           href="javascript:">×</a>
                                                        <span>{{attr.attr_name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <input class="form-control add-attr-group-input mr-2"
                                           placeholder="如颜色、尺码、套餐、型号">
                                    <a class="btn add-attr-group-btn btn-secondary" href="javascript:">添加组</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row" v-if="attr_row_list && attr_row_list.length>0">
                            <div class="col-3 text-right">
                                <label class=" col-form-label">规格库存设置</label>
                            </div>
                            <div class="col-9">
                                <div v-if="attr_error" class="alert alert-warning rounded-0">{{attr_error}}</div>
                                <div class="form-inline mb-2">
                                    <label>批量设置：</label>
                                    <input style="width: 6rem" class="form-control all-attr-row-price mr-2"
                                           placeholder="价格">
                                    <input style="width: 6rem" class="form-control all-attr-row-num mr-2"
                                           placeholder="库存">
                                    <input style="width: 8rem" class="form-control all-attr-row-no mr-2"
                                           placeholder="编号">
                                    <a class="btn btn-secondary set-all-attr-row" href="javascript:">确定</a>
                                </div>
                                <table class="table table-bordered table-sm mb-0 bg-white">
                                    <tr>
                                        <template v-for="group in attr_group_list">
                                            <th v-if="group.attr_list && group.attr_list.length>0"
                                                class="text-center">
                                                {{group.attr_group_name}}
                                            </th>
                                        </template>
                                        <th class="text-center">价格</th>
                                        <th class="text-center">库存</th>
                                        <th class="text-center">编号</th>
                                        <th class="text-center">图片</th>
                                    </tr>
                                    <tr v-for="(attr_row,row_index) in attr_row_list">
                                        <td v-for="(attr,attr_index) in attr_row.attr_list"
                                            class="text-center">
                                            {{attr.attr_name}}
                                            <input :name="'attr['+row_index+'][attr_list]['+attr_index+'][attr_id]'"
                                                   :value="attr.attr_id?attr.attr_id:''"
                                                   type="hidden">
                                            <input :name="'attr['+row_index+'][attr_list]['+attr_index+'][attr_name]'"
                                                   :value="attr.attr_name?attr.attr_name:''"
                                                   type="hidden">
                                            <input
                                                :name="'attr['+row_index+'][attr_list]['+attr_index+'][attr_group_name]'"
                                                :value="attr.attr_group_name?attr.attr_group_name:''"
                                                type="hidden">
                                        </td>
                                        <td class="text-center input-td">
                                            <input :data-row-index="row_index"
                                                   :value="attr_row.price"
                                                   :name="'attr['+row_index+'][price]'"
                                                   class="attr-row-price" min="0" step="0.01" type="number">
                                        </td>
                                        <td class="text-center input-td">
                                            <input :data-row-index="row_index"
                                                   :value="attr_row.num"
                                                   :name="'attr['+row_index+'][num]'"
                                                   class="attr-row-num" min="0" step="1" type="number">
                                        </td>
                                        <td class="text-center input-td">
                                            <input :data-row-index="row_index"
                                                   :value="attr_row.no"
                                                   :name="'attr['+row_index+'][no]'"
                                                   class="attr-row-no">
                                        </td>
                                        <td class="text-center" style="vertical-align: middle">
                                            <template v-if="attr_row.pic">
                                                <div>
                                                    <input :data-row-index="row_index"
                                                           :value="attr_row.pic"
                                                           :name="'attr['+row_index+'][pic]'">
                                                    <img :src="attr_row.pic"
                                                         style="height: 1.5rem;width: 1.5rem;border-radius: .15rem">
                                                    <a :data-row-index="row_index"
                                                       class="attr-row-delete-pic"
                                                       href="javascript:">×</a>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <a :data-row-index="row_index"
                                                   class="btn btn-secondary btn-sm attr-row-upload-pic"
                                                   href="javascript:">上传</a>
                                            </template>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </template>

                </div>
            </div>

            <div class="step-block" flex="dir:left box:first" v-if="model" v-if="setting">
                <div>
                    <span>营销设置</span>
                    <span class="step-location" id="step5"></span>
                </div>
                <div>
                    <div v-if="setting.is_share == 1">
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class=" col-form-label required">分销设置</label>
                            </div>
                            <div class="col-9">
                                <label class="radio-label">
                                    <input type="radio" name="individual_share" value="0"
                                           v-model="model.individual_share">
                                    <span class="label-icon"></span>
                                    <span class="label-text">不启用</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="individual_share" value="1"
                                           v-model="model.individual_share">
                                    <span class="label-icon"></span>
                                    <span class="label-text">启用</span>
                                </label>
                            </div>
                        </div>
                        <div v-if="model.individual_share == 1">
                            <div class="form-group row share-commission">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label required">分销佣金类型</label>
                                </div>
                                <div class="col-9 col-form-label">
                                    <label class="radio-label share-type">
                                        <input v-model="model.share_type"
                                               name="share_type"
                                               value="0"
                                               type="radio"
                                               class="custom-control-input">
                                        <span class="label-icon"></span>
                                        <span class="label-text">百分比</span>
                                    </label>
                                    <label class="radio-label share-type">
                                        <input v-model="model.share_type" name="share_type" value="1" type="radio"
                                               class="custom-control-input">
                                        <span class="label-icon"></span>
                                        <span class="label-text">固定金额</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row share-commission">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label required">单独分销设置</label>
                                </div>
                                <div class="col-9">
                                    <div class="short-row">
                                        <div class="input-group mb-3">
                                            <span class="input-group-addon">一级佣金</span>
                                            <input name="share_commission_first" v-model="model.share_commission_first"
                                                   class="form-control"
                                                   type="number"
                                                   step="0.01"
                                                   min="0" max="100">
                                    <span
                                        class="input-group-addon percent">{{model.share_type == 1 ? "元" : "%"}}</span>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-addon">二级佣金</span>
                                            <input name="share_commission_second"
                                                   v-model="model.share_commission_second"
                                                   class="form-control"
                                                   type="number"
                                                   step="0.01"
                                                   min="0" max="100">
                                    <span
                                        class="input-group-addon percent">{{model.share_type == 1 ? "元" : "%"}}</span>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-addon">三级佣金</span>
                                            <input name="share_commission_third" v-model="model.share_commission_third"
                                                   class="form-control"
                                                   type="number"
                                                   step="0.01"
                                                   min="0" max="100">
                                            <span class="input-group-addon percent">{{model.share_type == 1 ? "元" : "%"}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-block" flex="dir:left box:first" v-if="model">
                <div>
                    <span>图文详情</span>
                    <span class="step-location" id="step4"></span>
                </div>
                <div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">图文详情</label>
                        </div>
                        <div class="col-9">
                            <textarea class="short-row" id="editor"
                                      name="detail"><?= $goods['detail'] ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="publish-bar">
                <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                <input type="button" class="btn btn-default ml-4" 
                       name="Submit" onclick="javascript:history.back(-1);" value="返回">
            </div>
        </form>

        <!-- 选择分类 -->
        <div class="modal fade" id="catModal" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="panel mt-0">
                        <div class="panel-body select-cat-list">
                            <div v-for="(item,index) in cat_list" class="clearfix">
                                <div :class="item.checked?'cat-item checked':'cat-item'"
                                     :data-parent-index="-1"
                                     :data-index="index"
                                     :data-id="item.id">
                                    <span>{{item.name}}</span>
                                </div>
                                <template v-if="item.list">
                                    <template v-for="(sub_item,sub_index) in item.list">
                                        <div :class="sub_item.checked?'cat-item checked':'cat-item'"
                                             :data-parent-index="index"
                                             :data-index="sub_index"
                                             :data-id="sub_item.id">
                                            <span>{{sub_item.name}}</span>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <a class="btn btn-primary" data-dismiss="modal" href="javascript:">确认</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js?v=1.9.6"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js?v=1.9.6"></script>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            store_cat_list: [],
            cat_list: null,
            attr_group_list: [],
            attr_row_list: [],
            model: null,
            setting: null,
            attr_group_count: 0,
            old_checked_attr_list: [],
        },
        computed: {
            attr_error: function () {
                var error = false;
                for (var i in this.attr_group_list) {
                    if (!this.attr_group_list[i].attr_list || !this.attr_group_list[i].attr_list.length) {
                        error = '规格组“' + this.attr_group_list[i].attr_group_name + '”没有值，将不会被保存';
                        break;
                    }
                }
                return error;
            },
        },
    });

    var ue = null;

    //加载页面数据
    $.loading({content: '加载中'});
    $.ajax({
        dataType: 'json',
        success: function (res) {
            $.loadingHide();
            if (res.code == 0) {
                app.store_cat_list = res.data.store_cat_list;
                app.cat_list = res.data.cat_list;
                app.postage_riles = res.data.postage_riles;
                app.model = res.data.model;
                app.setting = res.data.setting;
                ue = UE.getEditor('editor', {
                    serverUrl: "<?=$urlManager->createUrl(['upload/ue'])?>",
                    enableAutoSave: false,
                    saveInterval: 1000 * 3600,
                    enableContextMenu: false,
                    autoHeightEnabled: false,
                });
                setTimeout(function () {
                    if (res.data.model.detail)
                        ue.setContent(res.data.model.detail + "");
                }, 300);
                setTimeout(function () {
                    app.attr_group_list = res.data.attr_group_list;
                    app.attr_row_list = res.data.attr_row_list;
                }, 300);
            } else {
                $.alert({
                    content: res.msg,
                });
            }
        }
    });

    //弹出分类选择框
    $(document).on('click', '.select-cat', function () {
        $('#catModal').modal('show');
    });

    //选择分类
    $(document).on('click', '.select-cat-list .cat-item', function () {
        var parent_index = $(this).attr('data-parent-index');
        var index = $(this).attr('data-index');
        if (parent_index == -1) {
            if (app.cat_list[index].checked)
                app.cat_list[index].checked = false;
            else
                app.cat_list[index].checked = true;
        } else {
            if (app.cat_list[parent_index].list[index].checked)
                app.cat_list[parent_index].list[index].checked = false;
            else
                app.cat_list[parent_index].list[index].checked = true;
        }
    });

    //移除分类
    $(document).on('click', '.uncheck-cat', function () {
        var parent_index = $(this).attr('data-parent-index');
        var index = $(this).attr('data-index');
        if (parent_index == -1) {
            app.cat_list[index].checked = false;
        } else {
            app.cat_list[parent_index].list[index].checked = false;
        }
    });


    //规格组组合生成
    function attrGroupGenerate(attr_group_list) {
        if (!attr_group_list.length)
            return [];
        var result = [];
        var results = [];
        var new_group_list = [];

        for (var i in attr_group_list) {
            if (!attr_group_list[i].attr_list || !attr_group_list[i].attr_list.length)
                continue;
            new_group_list.push(attr_group_list[i].attr_list);
        }

        function doExchange(arr, depth) {
            if (!arr[depth])
                return;
            for (var i = 0; i < arr[depth].length; i++) {
                result[depth] = arr[depth][i];
                if (depth != arr.length - 1) {
                    doExchange(arr, depth + 1)
                } else {
                    var row = {
                        attr_list: result,
                        num: 0,
                        price: 0,
                        no: '',
                        pic: '',
                    };
                    results.push(JSON.stringify(row));
                }
            }
        }

        function test(arr) {
            doExchange(arr, 0);
        }

        test(new_group_list);
        var new_results = [];
        for (var i in results) {
            new_results.push(JSON.parse(results[i]));
        }

        return new_results;
    }

    app.attr_row_list = attrGroupGenerate(app.attr_group_list);

    //添加规格组
    $(document).on('click', '.add-attr-group-btn', function () {
        var group_name = $('.add-attr-group-input').val();
        if (group_name == '')
            return;
        var in_array = false;
        for (var i in app.attr_group_list) {
            if (app.attr_group_list[i].attr_group_name == group_name) {
                in_array = true;
                break;
            }
        }
        if (in_array)
            return;
        app.attr_group_list.push({
            attr_group_name: group_name,
            attr_list: [],
        });
        $('.add-attr-group-input').val('');
    });

    //添加规格值
    $(document).on('click', '.add-attr-btn', function () {
        var group_index = $(this).attr('data-group-index');
        var attr_name = $(this).parent().find('.add-attr-input').val();
        if (attr_name == '')
            return;
        var in_array = false;
        for (var i in app.attr_group_list[group_index].attr_list) {
            if (app.attr_group_list[group_index].attr_list[i].attr_name == attr_name) {
                in_array = true;
                break;
            }
        }
        if (in_array)
            return;
        app.attr_group_list[group_index].attr_list.push({
            attr_name: attr_name,
            attr_group_name: app.attr_group_list[group_index].attr_group_name,
        });

        // 如果是单规格的，添加新规格时不清空原先的数据
        app.old_checked_attr_list = app.attr_row_list;
        app.attr_group_count = app.attr_group_list.length;
        var attrList = attrGroupGenerate(app.attr_group_list);
        if (app.attr_group_list.length === 1) {
            for (var i in attrList) {
                if (i > app.old_checked_attr_list.length - 1) {
                    app.old_checked_attr_list.push(attrList[i])
                }
            }
            var newCheckedAttrList = app.old_checked_attr_list;
        } else if (app.attr_group_list.length === app.attr_group_count) {
            for (var pi in attrList) {
                var pAttrName = '';
                for (var pj in attrList[pi].attr_list) {
                    pAttrName += attrList[pi].attr_list[pj].attr_name
                }
                for (var ci in app.old_checked_attr_list) {
                    var cAttrName = '';
                    for (var cj in app.old_checked_attr_list[ci].attr_list) {
                        cAttrName += app.old_checked_attr_list[ci].attr_list[cj].attr_name;
                    }
                    if (pAttrName === cAttrName) {
                        attrList[pi] = app.old_checked_attr_list[ci];
                    }
                }
            }
            var newCheckedAttrList = attrList;
        } else {
            var newCheckedAttrList = attrList;
        }

        $('.add-attr-input').val('');
//        app.attr_row_list = attrGroupGenerate(newCheckedAttrList);
        app.attr_row_list = newCheckedAttrList;
    });

    //删除规格组
    $(document).on('click', '.attr-group-delete', function () {
        var group_index = $(this).attr('data-group-index');
        app.attr_group_list.splice(group_index, 1);
        app.attr_row_list = attrGroupGenerate(app.attr_group_list);
    });

    //删除规格值
    $(document).on('click', '.attr-item-delete', function () {
        var group_index = $(this).attr('data-group-index');
        var attr_index = $(this).attr('data-attr-index');
        app.attr_group_list[group_index].attr_list.splice(attr_index, 1);


        // 如果是单规格的，添加新规格时不清空原先的数据
        app.old_checked_attr_list = app.attr_row_list;
        app.attr_group_count = app.attr_group_list.length;
        var attrList = attrGroupGenerate(app.attr_group_list);
        if (app.attr_group_list.length === 1) {
            var newCheckedAttrList = [];
            for (var i in app.attr_group_list[0].attr_list) {
                var attrName = app.attr_group_list[0].attr_list[i].attr_name;
                for (j in app.old_checked_attr_list) {
                    var oldAttrName = app.old_checked_attr_list[j].attr_list[0].attr_name;
                    if (attrName === oldAttrName) {
                        newCheckedAttrList.push(app.old_checked_attr_list[j]);
                        break;
                    }
                }
            }
        } else if (app.attr_group_list.length === app.attr_group_count) {
            for (var pi in attrList) {
                var pAttrName = '';
                for (var pj in attrList[pi].attr_list) {
                    pAttrName += attrList[pi].attr_list[pj].attr_name
                }
                for (var ci in app.old_checked_attr_list) {
                    var cAttrName = '';
                    for (var cj in app.old_checked_attr_list[ci].attr_list) {
                        cAttrName += app.old_checked_attr_list[ci].attr_list[cj].attr_name;
                    }
                    if (pAttrName === cAttrName) {
                        attrList[pi] = app.old_checked_attr_list[ci];
                    }
                }
            }
            var newCheckedAttrList = attrList;
        } else {
            var newCheckedAttrList = attrList;
        }

//        app.attr_row_list = attrGroupGenerate(app.attr_group_list);
        app.attr_row_list = newCheckedAttrList;
    });

    //规格价格修改
    $(document).on('input', '.attr-row-price', function () {
        var row_index = $(this).attr('data-row-index');
        app.attr_row_list[row_index].price = $(this).val();
    });

    //规格库存修改
    $(document).on('input', '.attr-row-num', function () {
        var row_index = $(this).attr('data-row-index');
        app.attr_row_list[row_index].num = $(this).val();
    });

    //规格编号修改
    $(document).on('input', '.attr-row-no', function () {
        var row_index = $(this).attr('data-row-index');
        app.attr_row_list[row_index].no = $(this).val();
    });

    //批量设置价格、库存、编号
    $(document).on('click', '.set-all-attr-row', function () {
        var price = parseFloat($('.all-attr-row-price').val()).toFixed(2);
        var num = parseInt($('.all-attr-row-num').val());
        var no = $.trim($('.all-attr-row-no').val());
        for (var i in app.attr_row_list) {
            if (price !== '' && !isNaN(price))
                app.attr_row_list[i].price = price;
            if (num !== '' && !isNaN(num))
                app.attr_row_list[i].num = num;
            if (no !== '')
                app.attr_row_list[i].no = no;
        }
        $('.all-attr-row-price').val('');
        $('.all-attr-row-num').val('');
        $('.all-attr-row-no').val('');
    });

    //规格图片上传
    $(document).on('click', '.attr-row-upload-pic', function () {
        var btn = $(this);
        var index = $(this).attr('data-row-index');
        $.upload_file({
            accept: 'image/*',
            start: function () {
                btn.btnLoading('上传中');
            },
            success: function (res) {
                if (res.code == 0) {
                    app.attr_row_list[index].pic = res.data.url;
                } else {
                    $.alert({
                        content: res.msg,
                    });
                }
            },
            complete: function () {
                btn.btnReset();
            }
        });
    });

    //规格图片删除
    $(document).on('click', '.attr-row-delete-pic', function () {
        var row_index = $(this).attr('data-row-index');
        app.attr_row_list[row_index].pic = '';
    });

    $(document).on('click', '.copy-btn', function () {
        var btn = $(this);
        var url = $(btn.parent().prev()[0]).val();
        var error = $('.copy-error');
        error.prop('hidden', true);
        if (url == '' || url == undefined) {
            error.prop('hidden', false).html('请填写宝贝链接');
            return;
        }
        btn.btnLoading('信息获取中');
        $.myLoading();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/goods/copy'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                url: url,
            },
            success: function (res) {
                $.myLoadingHide();
                btn.btnReset();
                if (res.code == 0) {
                    app.model.name = res.data.title;
                    app.model.virtual_sales = res.data.sale_count;
                    app.model.price = res.data.sale_price;
                    app.model.original_price = res.data.price;
                    app.attr_group_list = res.data.attr_group_list;
                    app.attr_row_list = attrGroupGenerate(res.data.attr_group_list);
                    ue = UE.getEditor('editor', {
                        serverUrl: "<?=$urlManager->createUrl(['upload/ue'])?>",
                        enableAutoSave: false,
                        saveInterval: 1000 * 3600,
                        enableContextMenu: false,
                        autoHeightEnabled: false,
                    });
                    ue.setContent(res.data.detail_info + "");
                    var pic = res.data.picsPath;
                    if (pic) {
                        app.model.cover_pic = pic[0];
                        if (pic.length > 1) {
                            app.model.goods_pic_list = pic;
                        }
                    }
                } else {
                    error.prop('hidden', false).html(res.msg);
                }
            }
        });
    });

</script>