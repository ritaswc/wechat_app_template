<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/10
 * Time: 10:19
 */
defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '商品详情';
$staticBaseUrl = Yii::$app->request->baseUrl . '/statics';
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
        top: -172px;
        left: 0;
    }

    .step-block:first-child .step-location {
        top: -190px;
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
            <div class="step-block" flex="dir:left box:first">
                <div>
                    <span>选择分类</span>
                </div>
                <div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">商品分类</label>
                        </div>
                        <div class="col-9">
                            <div class="cat-list">
                                <?php foreach ($cat_list as $value) : ?>
                                    <div class="cat-item" flex="dir:left box:last">
                                        <div><?= $value['name'] ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
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
                            <label class=" col-form-label">商品名称</label>
                        </div>
                        <div class="col-9">
                            <label class=" col-form-label"><?= $goods['name'] ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">单位</label>
                        </div>
                        <div class="col-9">
                            <label class=" col-form-label"><?= $goods['unit'] ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">商品排序</label>
                        </div>
                        <div class="col-9">
                            <label class=" col-form-label"><?= $goods['sort'] ?></label>
                            <div class="text-muted fs-sm">排序按升序排列</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">重量</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group short-row">
                                <label class=" col-form-label"><?= $goods['weight'] ?>克</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">商品缩略图</label>
                        </div>
                        <div class="col-9">
                            <div class="upload-group short-row">
                                <div class="upload-preview text-center upload-preview">
                                    <span class="upload-preview-tip">325&times;325</span>
                                    <img class="upload-preview-img" src="<?= $goods['cover_pic'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">商品组图</label>
                        </div>
                        <div class="col-9">
                            <div class="upload-preview-list">
                                <?php foreach ($goods_pic_list as $item) : ?>
                                    <div class="upload-preview text-center">
                                        <span class="file-item-delete">&times;</span>
                                        <span class="upload-preview-tip">750&times;750</span>
                                        <img class="upload-preview-img" src="<?= $item ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">售价</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group short-row">
                                <label class=" col-form-label"><?= $goods['price'] ?>元</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">原价</label>
                        </div>
                        <div class="col-9">
                            <label class=" col-form-label"><?= $goods['original_price'] ?>元</label>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">服务内容</label>
                        </div>
                        <div class="col-9">
                            <label class=" col-form-label"><?= $goods['service'] ? $goods['service'] : "无" ?></label>
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
                            <label class=" col-form-label"><?= $postage ?></label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">单品满件包邮</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group short-row">
                                <?php if ($goods['full_cut']['pieces']): ?>
                                    <label class=" col-form-label"><?= $goods['full_cut']['pieces'] ?>件</label>
                                <?php else : ?>
                                    <label class=" col-form-label">未设置</label>
                                <?php endif; ?>
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
                                <?php if ($goods['full_cut']['forehead']): ?>
                                    <label class=" col-form-label"><?= $goods['full_cut']['forehead'] ?>元</label>
                                <?php else : ?>
                                    <label class=" col-form-label">未设置</label>
                                <?php endif; ?>
                            </div>
                            <div class="fs-sm text-muted">如果设置0或空，则不支持满额包邮</div>
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
                    <?php if ($goods['use_attr'] == 0) : ?>
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class=" col-form-label">商品库存</label>
                            </div>
                            <div class="col-9">
                                <div class="input-group short-row">
                                    <label class=" col-form-label"><?= $goods['goods_num'] ?>件</label>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class=" col-form-label">规格库存设置</label>
                            </div>
                            <div class="col-9">
                                <table class="table table-bordered table-sm mb-0 bg-white">
                                    <tr>
                                        <?php foreach ($attr_group_list as $group) : ?>
                                            <th class="text-center"><?= $group['attr_group_name'] ?></th>
                                        <?php endforeach; ?>
                                        <th class="text-center">价格</th>
                                        <th class="text-center">库存</th>
                                        <th class="text-center">编号</th>
                                        <th class="text-center">图片</th>
                                    </tr>
                                    <?php foreach ($attr_row_list as $row_index => $attr_row) : ?>
                                        <tr class="text-center">
                                            <?php foreach ($attr_row['attr_list'] as $attr_index => $attr) : ?>
                                                <td><?= $attr['attr_name'] ?></td>
                                            <?php endforeach; ?>
                                            <td><?= $attr_row['price'] ?></td>
                                            <td><?= $attr_row['num'] ?></td>
                                            <td><?= $attr_row['no'] ?></td>
                                            <td>
                                                <?php if ($attr_row['pic']) : ?>
                                                    <img src="<?= $attr_row['pic'] ?>"
                                                         style="height: 1.5rem;width: 1.5rem;border-radius: .15rem">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>

                    <?php endif; ?>

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
                                      name="detail"><?= $goods['detail'] ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js?v=1.9.6"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js?v=1.9.6"></script>
<script>

    var ue = UE.getEditor('editor', {
        serverUrl: "<?=$urlManager->createUrl(['upload/ue'])?>",
        enableAutoSave: false,
        saveInterval: 1000 * 3600,
        enableContextMenu: false,
        autoHeightEnabled: false,
        toolbars: []
    });
</script>