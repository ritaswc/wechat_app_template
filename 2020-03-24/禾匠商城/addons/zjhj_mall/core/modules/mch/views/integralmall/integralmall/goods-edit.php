<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 10:49
 */

$urlManager = Yii::$app->urlManager;
$this->title = '积分商品编辑';
$staticBaseUrl = Yii::$app->request->baseUrl . '/statics';
?>
<script src="<?= $staticBaseUrl ?>/mch/js/uploadVideo.js"></script>
<style>
    .cat-box {
        border: 1px solid rgba(0, 0, 0, .15);
    }

    .cat-box .row {
        margin: 0;
        padding: 0;
    }

    .cat-box .col-6 {
        padding: 0;
    }

    .cat-box .cat-list {
        border-right: 1px solid rgba(0, 0, 0, .15);
        overflow-x: hidden;
        overflow-y: auto;
        height: 10rem;
    }

    .cat-box .cat-item {
        border-bottom: 1px solid rgba(0, 0, 0, .1);
        padding: .5rem 1rem;
        display: block;
        margin: 0;
    }

    .cat-box .cat-item:last-child {
        border-bottom: none;
    }

    .cat-box .cat-item:hover {
        background: rgba(0, 0, 0, .05);
    }

    .cat-box .cat-item.active {
        background: rgb(2, 117, 216);
        color: #fff;
    }

    .cat-box .cat-item input {
        display: none;
    }

    form {
    }

    form .head {
        position: fixed;
        top: 100px;
        right: 1rem;
        left: calc(240px + 1rem);
        z-index: 9;
        padding-top: 1rem;
        background: #f5f7f9;
        padding-bottom: 1rem;
    }

    form .head .head-content {
        background: #fff;
        border: 1px solid #eee;
        height: 40px;
    }

    .head-step {
        height: 100%;
        padding: 0 20px;
    }

    .step-block {
        position: relative;
    }

    form .body {
        padding-top: 45px;
    }

    .step-block > div {
        padding: 20px;
        background: #fff;
        border: 1px solid #eee;
        margin-bottom: 5px;
    }

    .step-block > div:first-child {
        padding: 20px;
        width: 120px;
        margin-right: 5px;
        font-weight: bold;
        text-align: center;
    }

    .step-block .step-location {
        position: absolute;
        top: -172px;
        left: 0;
    }

    .step-block:first-child .step-location {
        top: -190px;
    }

    form .foot {
        text-align: center;
        background: #fff;
        border: 1px solid #eee;
        padding: 1rem;
    }

    .edui-editor,
    #edui1_toolbarbox {
        z-index: 2 !important;
    }

    form .short-row {
        width: 380px;
    }

    .form {
        background: none;
        width: 100%;
        max-width: 100%;
    }

    .attr-group {
        border: 1px solid #eee;
        padding: .5rem .75rem;
        margin-bottom: .5rem;
        border-radius: .15rem;
    }

    .attr-group-delete {
        display: inline-block;
        background: #eee;
        color: #fff;
        width: 1rem;
        height: 1rem;
        text-align: center;
        line-height: 1rem;
        border-radius: 999px;
    }

    .attr-group-delete:hover {
        background: #ff4544;
        color: #fff;
        text-decoration: none;
    }

    .attr-list > div {
        vertical-align: top;
    }

    .attr-item {
        display: inline-block;
        background: #eee;
        margin-right: 1rem;
        margin-top: .5rem;
        overflow: hidden;
    }

    .attr-item .attr-name {
        padding: .15rem .75rem;
        display: inline-block;
    }

    .attr-item .attr-delete {
        padding: .35rem .75rem;
        background: #d4cece;
        color: #fff;
        font-size: 1rem;
        font-weight: bold;
    }

    .attr-item .attr-delete:hover {
        text-decoration: none;
        color: #fff;
        background: #ff4544;
    }

    .panel {
        margin-top: calc(40px + 1rem);
    }

    form .form-group .col-3 {
        -webkit-box-flex: 0;
        -webkit-flex: 0 0 160px;
        -ms-flex: 0 0 160px;
        flex: 0 0 160px;
        max-width: 160px;
        width: 160px;
    }
</style>

<div id="one_menu_bar">
    <div id="tab_bar">
        <ul>
            <li class="tab_bar_item" id="tab1" onclick="myclick(1)" style="background-color: #eeeeee">
                基础设置
            </li>
            <!--            <li class="tab_bar_item" id="tab2" onclick="myclick(2)">-->
            <!--                多规格分销价-->
            <!--            </li>-->
            <!--            <li class="tab_bar_item" id="tab3" onclick="myclick(3)">-->
            <!--                多规格会员价-->
            <!--            </li>-->
        </ul>
    </div>

    <form id="page" class="form auto-form" method="post" autocomplete="off"
          data-return="<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods']) ?>">
        <div class="tab_css" id="tab1_content" style="display: block">
            <div class="panel mb-3">
                <div class="panel-header"><?= $this->title ?></div>
                <div class="panel-body">

                    <div class="head">
                        <div class="head-content" flex="dir:left">
                            <a flex="cross:center" class="head-step" href="#step1">选择分类</a>
                            <a flex="cross:center" class="head-step" href="#step2">基本信息</a>
                            <a flex="cross:center" class="head-step" href="#step3">规格/库存</a>
                            <a flex="cross:center" class="head-step" href="#step4">商品详情</a>
                        </div>
                    </div>
                    <div class="step-block" flex="dir:left box:first">
                        <div>
                            <span>选择分类</span>
                            <span class="step-location" id="step1"></span>
                        </div>
                        <div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label required">商品分类</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group short-row">
                                        <select class="form-control parent" name="model[cat_id]">
                                            <option value="">请选择分类</option>
                                            <?php if ($cat != null) : ?>
                                                <?php foreach ($cat as $value) : ?>
                                                    <option
                                                            value="<?= $value['id'] ?>" <?= $value['id'] == $goods['cat_id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">淘宝一键采集</label>
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
                            <div class="form-group row"hidden>
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">京东一键采集</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group short-row">
                                        <input class="form-control copy-url" placeholder="请输入京东商品详情地址连接">
                                        <span class="input-group-btn">
                                    <a class="btn btn-secondary copy-btn" href="javascript:">立即获取</a>
                                </span>
                                    </div>
                                    <div class="short-row text-muted fs-sm">
                                        例如：商品链接为:https://item.jd.com/5346660.html
                                    </div>
                                    <div class="short-row text-muted fs-sm">若不使用，则该项为空</div>
                                    <div class="copy-error text-danger fs-sm" hidden></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">商城商品拉取</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group short-row">
                                        <input class="form-control copy-mall-id" name="mall_id" type="number"
                                               placeholder="请输入商城商品ID">
                                        <span class="input-group-btn">
                                    <a class="btn btn-secondary mall-copy-btn" href="javascript:">立即获取</a>
                                </span>
                                    </div>
                                    <div class="short-row text-muted fs-sm">若不使用，则该项为空</div>
                                    <div class="copy-error text-danger fs-sm" hidden></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-block" flex="dir:left box:first">
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
                                    <input class="form-control short-row" type="text" name="model[name]"
                                           value="<?= $goods['name'] ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">单位</label>
                                </div>
                                <div class="col-9">
                                    <input class="form-control short-row" type="text" name="model[unit]"
                                           value="<?= $goods['unit'] ? $goods['unit'] : '件' ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">商品排序</label>
                                </div>
                                <div class="col-9">
                                    <input class="form-control short-row" type="text" name="model[sort]"
                                           value="<?= $goods['sort'] ?: 100 ?>">
                                    <div class="text-muted fs-sm">排序按升序排列</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">已出售量</label>
                                </div>
                                <div class="col-9">
                                    <input class="form-control short-row" type="number" name="model[virtual_sales]"
                                           value="<?= $goods['virtual_sales'] ?>">
                                    <div class="text-muted fs-sm">前端展示的销量=实际销量+已出售量</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">重量</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group short-row">
                                        <input type="number" step="0.01" class="form-control"
                                               name="model[weight]"
                                               value="<?= $goods['weight'] ? $goods['weight'] : 0 ?>">
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
                                            <input class="form-control file-input" name="model[cover_pic]"
                                                   value="<?= $goods['cover_pic'] ?>">
                                            <span class="input-group-btn">
                                <a class="btn btn-secondary upload-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="上传文件">
                                    <span class="iconfont icon-cloudupload"></span>
                                </a>
                            </span>
                                            <span class="input-group-btn">
                                <a class="btn btn-secondary select-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="从文件库选择">
                                    <span class="iconfont icon-viewmodule"></span>
                                </a>
                            </span>
                                            <span class="input-group-btn">
                                <a class="btn btn-secondary delete-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="删除文件">
                                    <span class="iconfont icon-close"></span>
                                </a>
                            </span>
                                        </div>
                                        <div class="upload-preview text-center upload-preview">
                                            <span class="upload-preview-tip">324&times;324</span>
                                            <img class="upload-preview-img" src="<?= $goods['cover_pic'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class="col-form-label required">商品图片</label>
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
                                            <span class="input-group-btn">
                                        <a class="btn btn-secondary select-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="从文件库选择">
                                            <span class="iconfont icon-viewmodule"></span>
                                        </a>
                                    </span>
                                        </div>
                                        <div class="upload-preview-list">
                                            <?php if (count($goods['goods_pic_list']) > 0) : ?>
                                                <?php foreach ($goods['goods_pic_list'] as $item) : ?>
                                                    <div class="upload-preview text-center">
                                                        <input type="hidden" class="file-item-input"
                                                               name="model[goods_pic_list][]"
                                                               value="<?= $item ?>">
                                                        <span class="file-item-delete">&times;</span>
                                                        <span class="upload-preview-tip">750&times;750</span>
                                                        <img class="upload-preview-img" src="<?= $item ?>">
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <div class="upload-preview text-center">
                                                    <input type="hidden" class="file-item-input"
                                                           name="model[goods_pic_list][]">
                                                    <span class="file-item-delete">&times;</span>
                                                    <span class="upload-preview-tip">750&times;750</span>
                                                    <img class="upload-preview-img" src="">
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">售价</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group short-row">
                                        <input type="number" class="form-control"
                                               min="0"
                                               name="model[price]"
                                               value="<?= $goods['price'] ? $goods['price'] : '' ?>">
                                        <span class="input-group-addon">元</span>
                                    </div>
                                    <div class="fs-sm text-muted">不填表示0元</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label required">所需积分</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group short-row">
                                        <input type="number" step="1" class="form-control"
                                               name="model[integral]" min="1"
                                               value="<?= $goods['integral'] ? $goods['integral'] : '' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label required">用户可兑换数量</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group short-row">
                                        <input type="number" step="1" class="form-control"
                                               name="model[user_num]" min="1"
                                               value="<?= $goods['user_num'] ? $goods['user_num'] : '' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label required">原价</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group short-row">
                                        <input type="number" step="0.01" class="form-control short-row"
                                               name="model[original_price]" min="0"
                                               value="<?= $goods['original_price'] ? $goods['original_price'] : 1 ?>">
                                        <span class="input-group-addon">元</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">成本价</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group short-row">
                                        <input type="number" step="0.01" class="form-control"
                                               name="model[cost_price]" min="0.01"
                                               value="<?= $goods['cost_price'] ? $goods['cost_price'] : 1 ?>">
                                        <span class="input-group-addon">元</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">服务内容</label>
                                </div>
                                <div class="col-9">
                                    <input class="form-control short-row" name="model[service]"
                                           value="<?= $goods['service'] ?>">
                                    <div class="fs-sm text-muted">例子：正品保障,极速发货,7天退换货。多个请使用英文逗号<kbd>,</kbd>分隔</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">运费设置</label>
                                </div>
                                <div class="col-9">
                                    <select class="form-control short-row" name="model[freight]">
                                        <option value="0">默认模板</option>
                                        <?php if ($postageRiles != null) : ?>
                                            <?php foreach ($postageRiles as $p) : ?>
                                                <option
                                                        value="<?= $p->id ?>" <?= $p->id == $goods['freight'] ? 'selected' : '' ?>><?= $p->name ?></option>
                                            <?php endforeach; ?>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
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
                                               value="<?= $goods->goods_num ?>">
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
                            <div class="form-group row">
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
                            <div class="attr-edit-block">
                                <div class="form-group row">
                                    <div class="col-3 text-right">
                                        <label class=" col-form-label required">规格组和规格值</label>
                                    </div>
                                    <div class="col-9">
                                        <div class="input-group short-row mb-2" v-if="attr_group_list.length<3">
                                            <span class="input-group-addon">规格组</span>
                                            <input class="form-control add-attr-group-input" placeholder="如颜色、尺码、套餐">
                                            <span class="input-group-btn">
                                    <a class="btn btn-secondary add-attr-group-btn" href="javascript:">添加</a>
                                </span>
                                        </div>
                                        <div v-else class="mb-2">最多只可添加3个规格组</div>
                                        <div v-for="(attr_group,i) in attr_group_list" class="attr-group">
                                            <div>
                                                <b>{{attr_group.attr_group_name}}</b>
                                                <a v-bind:index="i" href="javascript:" class="attr-group-delete">×</a>
                                            </div>
                                            <div class="attr-list">
                                                <div v-for="(attr,j) in attr_group.attr_list" class="attr-item">
                                                    <span class="attr-name">{{attr.attr_name}}</span>
                                                    <a v-bind:group-index="i" v-bind:index="j" class="attr-delete"
                                                       href="javascript:">×</a>
                                                </div>
                                                <div style="display: inline-block;width: 200px;margin-top: .5rem">
                                                    <div class="input-group attr-input-group" style="border-radius: 0">
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
                                                            <input class="form-control form-control-sm" type="number"
                                                                   style="width: 60px">
                                                            <span class="input-group-addon"><a href="javascript:"
                                                                                               class="bat"
                                                                                               data-index="0">设置</a></span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group">
                                                            <span>价格</span>
                                                            <input class="form-control form-control-sm" type="number"
                                                                   style="width: 60px">
                                                            <span class="input-group-addon"><a href="javascript:"
                                                                                               class="bat"
                                                                                               data-index="1">设置</a></span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group">
                                                            <span>所需积分</span>
                                                            <input class="form-control form-control-sm" type="number"
                                                                   style="width: 60px">
                                                            <span class="input-group-addon"><a href="javascript:"
                                                                                               class="bat"
                                                                                               data-index="3">设置</a></span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group">
                                                            <span>货号</span>
                                                            <input class="form-control form-control-sm"
                                                                   style="width: 60px">
                                                            <span class="input-group-addon"><a href="javascript:"
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
                                                        <input class="form-control form-control-sm" type="number"
                                                               min="0"
                                                               step="1" style="width: 60px"
                                                               v-bind:name="'attr['+index+'][num]'"
                                                               v-bind:value="item.num">
                                                    </td>
                                                    <td>
                                                        <input class="form-control form-control-sm" type="number"
                                                               min="0"
                                                               step="0.01" style="width: 70px"
                                                               v-bind:name="'attr['+index+'][price]'"
                                                               v-bind:value="item.price">
                                                    </td>
                                                    <td>
                                                        <input class="form-control form-control-sm" type="number"
                                                               min="0"
                                                               step="0.01" style="width: 70px"
                                                               v-bind:name="'attr['+index+'][integral]'"
                                                               v-bind:value="item.integral">
                                                    </td>
                                                    <td>
                                                        <input class="form-control form-control-sm" style="width: 100px"
                                                               v-bind:name="'attr['+index+'][no]'"
                                                               v-bind:value="item.no">
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm"
                                                             v-bind:data-index="index">
                                                            <input class="form-control form-control-sm"
                                                                   style="width: 40px"
                                                                   v-bind:name="'attr['+index+'][pic]'"
                                                                   v-model="item.pic">
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
                    <div class="step-block" flex="dir:left box:first">
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
                                      name="model[detail]"><?= $goods['detail'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--多规格分销价-->


        <!--多规格会员价-->


        <div style="margin-left: 0;" class="form-group row text-center">
            <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
            <input type="button" class="btn btn-default ml-4"
                   name="Submit" onclick="javascript:history.back(-1);" value="返回">
        </div>
    </form>
</div>

<?= $this->render('/layouts/attrs/common', [
    'page_type' => 'INTEGRALMALL',
    'goods' => $goods
]) ?>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js"></script>
<script>
    var Map = function () {
        this._data = [];
        this.set = function (key, val) {
            for (var i in this._data) {
                if (this._data[i].key == key) {
                    this._data[i].val = val;
                    return true;
                }
            }
            this._data.push({
                key: key,
                val: val,
            });
            return true;
        };
        this.get = function (key) {
            for (var i in this._data) {
                if (this._data[i].key == key)
                    return this._data[i].val;
            }
            return null;
        };
        this.delete = function (key) {
            for (var i in this._data) {
                if (this._data[i].key == key) {
                    this._data.splice(i, 1);
                }
            }
            return true;
        };
    };
    var map = new Map();
    var page = new Vue({
        el: "#page",
        data: {
            attr_group_list: JSON.parse('<?=json_encode($goods->getAttrData(), JSON_UNESCAPED_UNICODE)?>'),//可选规格数据
            checked_attr_list: JSON.parse('<?=json_encode($goods->getCheckedAttrData(), JSON_UNESCAPED_UNICODE)?>'),//已选规格数据
            use_attr: <?= $goods['use_attr'] ? $goods['use_attr'] : 0 ?>
        }
    });

    var ue = UE.getEditor('editor', {
        serverUrl: "<?=$urlManager->createUrl(['upload/ue'])?>",
        enableAutoSave: false,
        saveInterval: 1000 * 3600,
        enableContextMenu: false,
        autoHeightEnabled: false,
    });
    $(document).on("change", ".cat-item input", function () {
        if ($(this).prop("checked")) {
            $(".cat-item").removeClass("active");
            $(this).parent(".cat-item").addClass("active");
        } else {
            $(this).parent(".cat-item").removeClass("active");
        }
    });


    $(document).on("click", ".cat-confirm", function () {
        var cat_name = $.trim($(".cat-item.active").text());
        var cat_id = $(".cat-item.active input").val();
        if (cat_name && cat_id) {
            $(".cat-name").val(cat_name);
            $(".cat-id").val(cat_id);
        }
        $("#catModal").modal("hide");
    });
    $(document).on("change", ".attr-select", function () {
        var name = $(this).attr("data-name");
        var id = $(this).val();
        if ($(this).prop("checked")) {
        } else {
        }
    });
    $(document).on("click", ".add-attr-group-btn", function () {
        var name = $(".add-attr-group-input").val();
        name = $.trim(name);
        if (name == "")
            return;
        page.attr_group_list.push({
            attr_group_name: name,
            attr_list: [],
        });
        $(".add-attr-group-input").val("");
        page.checked_attr_list = getAttrList();
    });
    $(document).on("click", ".add-attr-btn", function () {
        var name = $(this).parents(".attr-input-group").find(".add-attr-input").val();
        var index = $(this).attr("index");
        name = $.trim(name);
        if (name == "")
            return;
        page.attr_group_list[index].attr_list.push({
            attr_name: name,
        });
        $(this).parents(".attr-input-group").find(".add-attr-input").val("");
        page.checked_attr_list = getAttrList();
    });
    $(document).on("click", ".attr-group-delete", function () {
        var index = $(this).attr("index");
        page.attr_group_list.splice(index, 1);
        page.checked_attr_list = getAttrList();
    });
    $(document).on("click", ".attr-delete", function () {
        var index = $(this).attr("index");
        var group_index = $(this).attr("group-index");
        page.attr_group_list[group_index].attr_list.splice(index, 1);
        page.checked_attr_list = getAttrList();
    });

    function getAttrList() {
        var array = [];
        for (var i in page.attr_group_list) {
            for (var j in page.attr_group_list[i].attr_list) {
                var object = {
                    attr_group_name: page.attr_group_list[i].attr_group_name,
                    attr_id: null,
                    attr_name: page.attr_group_list[i].attr_list[j].attr_name,
                };
                if (!array[i])
                    array[i] = [];
                array[i].push(object);
            }
        }
        var len = array.length;
        var results = [];
        var indexs = {};

        function specialSort(start) {
            start++;
            if (start > len - 1) {
                return;
            }
            if (!indexs[start]) {
                indexs[start] = 0;
            }
            if (!(array[start] instanceof Array)) {
                array[start] = [array[start]];
            }
            for (indexs[start] = 0; indexs[start] < array[start].length; indexs[start]++) {
                specialSort(start);
                if (start == len - 1) {
                    var temp = [];
                    for (var i = len - 1; i >= 0; i--) {
                        if (!(array[start - i] instanceof Array)) {
                            array[start - i] = [array[start - i]];
                        }
                        if (array[start - i][indexs[start - i]]) {
                            temp.push(array[start - i][indexs[start - i]]);
                        }
                    }
                    var key = [];
                    for (var i in temp) {
                        key.push(temp[i].attr_id);
                    }
                    var oldVal = map.get(key.sort().toString());
                    if (oldVal) {
                        results.push({
                            num: oldVal.num,
                            price: oldVal.price,
                            single: oldVal.single,
                            no: oldVal.no,
                            pic: oldVal.pic,
                            attr_list: temp
                        });
                    } else {
                        results.push({
                            num: 0,
                            price: 0,
                            single: 0,
                            no: '',
                            pic: '',
                            attr_list: temp
                        });
                    }
                }
            }
        }

        specialSort(-1);
        return results;
    }

    $(document).on("change", "input[name='model[individual_share]']", function () {
        setShareCommission();
    });
    setShareCommission();

    function setShareCommission() {
        if ($("input[name='model[individual_share]']:checked").val() == 1) {
            $(".share-commission").show();
        } else {
            $(".share-commission").hide();
        }
    }

    //分销佣金选择
    $(document).on('click', '.share-type', function () {
        var price_type = $(this).children('input');
        if ($(price_type).val() == 1) {
            $('.percent').html('元');
        } else {
            $('.percent').html('%');
        }
    })

    function checkUseAttr() {
        if ($('.use-attr').length == 0)
            return;
        if ($('.use-attr').prop('checked')) {
            $('input[name="model[goods_num]"]').val(0).prop('readonly', true);
            $('input[name="model[goods_no]"]').val(0).prop('readonly', true);
            $('.attr-edit-block').show();
        } else {
            $('input[name="model[goods_num]"]').prop('readonly', false);
            $('input[name="model[goods_no]"]').prop('readonly', false);
            $('.attr-edit-block').hide();
        }
    }

    $(document).on('change', '.use-attr', function () {
        checkUseAttr();
    });
    checkUseAttr();

</script>
<script>
    $(document).on('change', '.video', function () {
        $('.video-check').attr('href', this.value);
    });
    //日期时间选择器
    laydate.render({
        elem: '#limit_time'
        , type: 'datetime'
    });
</script>
<script>
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
                    $("input[name='model[name]']").val(res.data.title);
                    $("input[name='model[virtual_sales]']").val(res.data.sale_count);
                    $("input[name='model[price]']").val(res.data.sale_price);
                    $("input[name='model[original_price]']").val(res.data.price);
                    console.log(res.data.attr_group_list);
                    page.attr_group_list = res.data.attr_group_list;
                    page.checked_attr_list = getAttrList();
                    ue.setContent(res.data.detail_info + "");
                    var pic = res.data.picsPath;

                    if (pic) {
                        var cover_pic = $("input[name='model[cover_pic]']");
                        var cover_pic_next = $(cover_pic.parent().next('.upload-preview')[0]).children('.upload-preview-img');
                        cover_pic.val(pic[0]);
                        $(cover_pic_next).prop('src', pic[0]);
                    }
                    if (pic.length > 1) {
                        var goods_pic_list = $(".upload-preview-list");
                        goods_pic_list.empty();
                        $(pic).each(function (i) {
                            if (i == 0) {
                                return true;
                            }
                            var goods_pic = ' <div class="upload-preview text-center">' +
                                '<input type="hidden" class="file-item-input" name="model[goods_pic_list][]" value="' + pic[i] + '"> ' +
                                '<span class="file-item-delete">&times;</span> <span class="upload-preview-tip">750&times;750</span> ' +
                                '<img class="upload-preview-img" src="' + pic[i] + '"> ' +
                                '</div>';
                            goods_pic_list.append(goods_pic);
                        });
                    }
                } else {
                    error.prop('hidden', false).html(res.msg);
                }
            }
        });
    });

    $(document).on('click', '.mall-copy-btn', function () {
        var mall_id = $('.copy-mall-id').val();
        var btn = $(this);
        var error = $('.copy-error');
        error.prop('hidden', true);
        if (mall_id == '' || mall_id == undefined) {
            error.prop('hidden', false).html('请填写商城商品ID');
            return;
        }
        btn.btnLoading('信息获取中');
        $.myLoading();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/group/goods/copy'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                mall_id: mall_id,
            },
            success: function (res) {
                $('.no-mall-get').hide();
                $('.mall-get').show();
                $.myLoadingHide();
                btn.btnReset();
                if (res.code == 0) {
                    $("input[name='model[name]']").val(res.data.name);
                    $("input[name='model[virtual_sales]']").val(res.data.virtual_sales);
                    $("input[name='model[price]']").val(res.data.price);
                    $("input[name='model[original_price]']").val(res.data.original_price);
                    $("input[name='model[unit]']").val(res.data.unit);
                    $("input[name='model[weight]']").val(res.data.weight);
                    $("input[name='model[service]']").val(res.data.service);
                    $("input[name='model[sort]']").val(res.data.sort);
                    page.attr_group_list = JSON.parse(res.data.attr_group_list, true);//可选规格数据
                    page.checked_attr_list = JSON.parse(res.data.checked_attr_list, true);//已选规格数据
                    ue.setContent(res.data.detail + "");
                    var pic = res.data.pic;

                    if (res.data.use_attr == 1) {
                        $('.use-attr').prop('checked', true);
                        $('input[name="model[goods_num]"]').val(0).prop('readonly', true);
                        $('.attr-edit-block').show();
                    }

                    if (pic) {
                        var cover_pic = $("input[name='model[cover_pic]']");
                        var cover_pic_next = $(cover_pic.parent().next('.upload-preview')[0]).children('.upload-preview-img');
                        cover_pic.val(res.data.cover_pic);
                        $(cover_pic_next).prop('src', res.data.cover_pic);
                    }
                    console.log(pic);
                    if (pic.length >= 1) {
                        var goods_pic_list = $(".upload-preview-list");
                        goods_pic_list.empty();
                        $(pic).each(function (i) {
//                            if (i == 0) {
//                                return true;
//                            }
                            var goods_pic = ' <div class="upload-preview text-center">' +
                                '<input type="hidden" class="file-item-input" name="model[goods_pic_list][]" value="' + pic[i] + '"> ' +
                                '<span class="file-item-delete">&times;</span> <span class="upload-preview-tip">750&times;750</span> ' +
                                '<img class="upload-preview-img" src="' + pic[i] + '"> ' +
                                '</div>';
                            goods_pic_list.append(goods_pic);
                        });
                    }

                } else {
                    error.prop('hidden', false).html(res.msg);
                }
            }
        });
    });
</script>

<!-- 规格图片 -->
<script>
    $(document).on('click', '.upload-attr-pic', function () {
        var btn = $(this);
        var input = btn.parents('.input-group').find('.form-control');
        var index = btn.parents('.input-group').attr('data-index');
        $.upload_file({
            accept: 'image/*',
            start: function (res) {
                btn.btnLoading('');
            },
            success: function (res) {
                if (res.code === 1) {
                    $.alert({
                        content: res.msg
                    });
                    return;
                }
                input.val(res.data.url).trigger('change');
                page.checked_attr_list[index].pic = res.data.url;
            },
            complete: function (res) {
                btn.btnReset();
            },
        });
    });
    $(document).on('click', '.select-attr-pic', function () {
        var btn = $(this);
        var input = btn.parents('.input-group').find('.form-control');
        var index = btn.parents('.input-group').attr('data-index');
        $.select_file({
            success: function (res) {
                input.val(res.url).trigger('change');
                page.checked_attr_list[index].pic = res.url;
            }
        });
    });
    $(document).on('click', '.delete-attr-pic', function () {
        var btn = $(this);
        var input = btn.parents('.input-group').find('.form-control');
        var index = btn.parents('.input-group').attr('data-index');
        input.val('').trigger('change');
        page.checked_attr_list[index].pic = '';
    });
    $(document).on('click', '.bat', function () {
        var type = $(this).data('index');
        var val = $($(this).parent().prev('input')).val();
        for (var i in page.checked_attr_list) {
            if (type == 0) {
                page.checked_attr_list[i].num = val
            } else if (type == 1) {
                page.checked_attr_list[i].price = val
            }
            else if (type == 2) {
                page.checked_attr_list[i].no = val
            }
            else if (type == 3) {
                Vue.set(page.checked_attr_list[i], 'integral', val)
            }
        }
    });
</script>