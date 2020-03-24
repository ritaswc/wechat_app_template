<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 18:01
 */


defined('YII_ENV') or exit('Access Denied');
/* @var $list \app\models\Setting */

/* @var $qrcode \app\models\Qrcode */

use yii\widgets\LinkPager;

$static = Yii::$app->request->baseUrl . '/statics';
$urlManager = Yii::$app->urlManager;
$this->title = '基础设置';
$this->params['active_nav_group'] = 5;
?>
<style>
    .help-block {
        display: block;
        margin-top: 5px;
        margin-bottom: 10px;
        color: #737373;
    }

    .short-row {
        max-width: 450px;
    }
</style>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off">
            <div class="form-body">
                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label required">分销层级</label>
                    </div>
                    <div class="col-9">
                        <label class="radio-label">
                            <input type="radio" name="model[level]"
                                   value="0" <?= ($list->level == 0 || $list->level == 4) ? "checked" : "" ?>>
                            <span class="label-icon"></span>
                            <span class="label-text">不开启</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="model[level]"
                                   value="1" <?= ($list->level == 1) ? "checked" : "" ?>>
                            <span class="label-icon"></span>
                            <span class="label-text">一级分销</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="model[level]"
                                   value="2" <?= ($list->level == 2) ? "checked" : "" ?>>
                            <span class="label-icon"></span>
                            <span class="label-text">二级分销</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="model[level]"
                                   value="3" <?= ($list->level == 3) ? "checked" : "" ?>>
                            <span class="label-icon"></span>
                            <span class="label-text">三级分销</span>
                        </label>
                        <!--2018-05-22 注释--风哀伤
                        <label class="radio-label">
                            <input type="radio" name="model[level]"
                                   value="4" <?= ($list->level == 4) ? "checked" : "" ?>>
                            <span class="label-icon"></span>
                            <span class="label-text">仅自购模式</span>
                        </label>-->
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label required">分销内购</label>
                    </div>
                    <div class="col-9">
                        <label class="radio-label">
                            <input type="radio" name="model[is_rebate]"
                                   value="0" <?= ($list->is_rebate == 0) ? "checked" : "" ?>>
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="model[is_rebate]"
                                   value="1" <?= ($list->is_rebate == 1) ? "checked" : "" ?>>
                            <span class="label-icon"></span>
                            <span class="label-text">开启</span>
                        </label>
                        <div class="fs-sm">开启分销内购，分销商自己购买商品，享受一级佣金，上级享受二级佣金，上上级享受三级佣金</div>
                    </div>
                </div>

                <div class="form-group row" style="border-bottom: 1px #ccc dashed">
                    <div class="col-3 text-right">
                        <label class=" col-form-label">上下线关系设置</label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label required">成为下线条件</label>
                    </div>
                    <div class="col-9">
                        <div>
                            <label class="radio-label">
                                <input type="radio" name="model[condition]"
                                       value="0" <?= ($list->condition == 0) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">首次点击链接</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="model[condition]"
                                       value="1" <?= ($list->condition == 1) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">首次下单</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="model[condition]"
                                       value="2" <?= ($list->condition == 2) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">首次付款</span>
                            </label>

                        </div>
                        <div class="help-block" hidden>首次点击分享链接： 可以自由设置分销商条件</div>
                    </div>
                </div>
                <div class="form-group row" style="border-bottom: 1px #ccc dashed">
                    <div class="col-3 text-right">
                        <label class=" col-form-label">分销资格设置</label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label required">成为分销商条件</label>
                    </div>
                    <div class="col-9">
                        <div>
                            <label class="radio-label">
                                <input type="radio" name="model[share_condition]"
                                       value="0" <?= ($list->share_condition == 0) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">无条件（需要审核）</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="model[share_condition]"
                                       value="1" <?= ($list->share_condition == 1) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">申请（需要审核）</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="model[share_condition]"
                                       value="2" <?= ($list->share_condition == 2) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">无需审核</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label">推广海报图</label>
                    </div>
                    <div class="col-9">
                        <a href="<?= $urlManager->createUrl(['mch/share/qrcode']) ?>"
                           class="btn btn-sm btn-primary">设置</a>

                    </div>
                </div>
                <div class="form-group row" style="border-bottom: 1px #ccc dashed">
                    <div class="col-3 text-right">
                        <label class=" col-form-label">分销佣金</label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class="col-form-label required">提现方式</label>
                    </div>
                    <div class="col-9">
                        <div>
                            <label class="checkbox-label">
                                <input type="checkbox" name="model[pay_type][wechat]" value="0"
                                    <?= ($list->pay_type == 2 || $list->pay_type == 0) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">微信支付</span>
                            </label>

                            <label class="checkbox-label">
                                <input type="checkbox" name="model[pay_type][alipay]" value="1"
                                    <?= ($list->pay_type == 2 || $list->pay_type == 1) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">支付宝支付</span>
                            </label>

                            <label class="checkbox-label">
                                <input type="checkbox" name="model[pay_type][bank]" value="3"
                                    <?= ($list->bank == 1) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">银行卡支付</span>
                            </label>

                            <label class="checkbox-label">
                                <input type="checkbox" name="model[pay_type][remaining_sum]" value="1"
                                    <?= ($list->remaining_sum == 1) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">余额支付</span>
                            </label>

                        </div>
                        <div>
                            <label class="col-form-label">微信自动支付，需要申请微信支付的企业付款到零钱功能</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label required">最少提现额度</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group short-row">
                            <input class="form-control" name="model[min_money]"
                                   value="<?= $list->min_money ? $list->min_money : 1 ?>">
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label required">每日提现上限</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group short-row">
                            <input type="number" min="0" step="0.01" class="form-control" name="model[cash_max_day]"
                                   value="<?= $option['cash_max_day'] ? $option['cash_max_day'] : 0 ?>">
                            <span class="input-group-addon">元</span>
                        </div>
                        <div class="text-muted fs-sm">0元表示不限制每日提现金额</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label">提现手续费</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group short-row">
                            <input type="number" min="0" step="0.01" class="form-control"
                                   name="model[cash_service_charge]"
                                   value="<?= $option['cash_service_charge'] ? $option['cash_service_charge'] : 0 ?>">
                            <span class="input-group-addon">%</span>
                        </div>
                        <div class="text-muted fs-sm">0表示不设置提现手续费</div>
                        <div class="text-muted fs-sm">
                            <span class="text-danger">提现手续费额外从提现中扣除</span><br>
                            例如：<span class="text-danger">10%</span>的提现手续费：<br>
                            提现<span class="text-danger">100</span>元，扣除手续费<span class="text-danger">10</span>元，
                            实际到手<span class="text-danger">90</span>元
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class="col-form-label required">消费自动成为分销商</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group short-row">
                            <input type="number" min="0" step="0.01" class="form-control" name="model[auto_share_val]"
                                   value="<?= $option['auto_share_val'] ? $option['auto_share_val'] : 0 ?>">
                            <span class="input-group-addon">元</span>
                        </div>
                        <div class="text-muted fs-sm">消费满指定金额(付款即生效,无需过售后)自动成为分销商，0元表示不自动</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class=" col-3 text-right">
                        <label class="col-form-label required">购买商品自动成为分销商</label>
                    </div>
                    <div class="col-9">
                        <div>
                            <label class="radio-label">
                                <input class="assign-good" type="radio" name="model[share_good_status]"
                                       value="0" <?= ($list->share_good_status == 0) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">关闭</span>
                            </label>
                            <label class="radio-label">
                                <input class="assign-good" type="radio" name="model[share_good_status]"
                                       value="1" <?= ($list->share_good_status == 1) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">任意商品</span>
                            </label>
                            <label class="radio-label">
                                <input class="assign-good" type="radio" name="model[share_good_status]"
                                       value="2" <?= ($list->share_good_status == 2) ? "checked" : "" ?>>
                                <span class="label-icon"></span>
                                <span class="label-text">指定商品</span>
                            </label>
                        </div>
                        <div style="display: <?= $list->share_good_status == 2 ? '' : 'none' ?>" id="select-assign-good" class="input-group short-row">
                            <input class="form-control search-goods-name" value="<?= $goodName ?>" readonly>
                            <input class="search-goods-id" type="hidden" value="<?= $list['share_good_id'] ?>" name="model[share_good_id]">
                            <span class="input-group-btn">
                                <a href="javascript:" class="btn btn-secondary search-goods" data-toggle="modal"
                                   data-target="#searchGoodsModal">选择商品</a>
                            </span>
                        </div>
                        <div class="text-muted fs-sm">购买商品付款即生效，无需过售后</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label">用户须知</label>
                    </div>
                    <div class="col-9">
                    <textarea class="form-control short-row" name="model[content]"
                              style="min-height: 150px;"><?= $list->content ?></textarea>
                    </div>
                </div>

                <div class="form-group row" style="border-bottom: 1px #ccc dashed">
                    <div class="col-3 text-right">
                        <label class=" col-form-label">分销协议</label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label">申请协议</label>
                    </div>
                    <div class="col-9">
                    <textarea class="form-control short-row" name="model[agree]"
                              style="min-height: 150px;"><?= $list->agree ?></textarea>
                    </div>
                </div>
                <div class="form-group row" style="border-bottom: 1px #ccc dashed">
                    <div class="col-3 text-right">
                        <label class=" col-form-label">背景图片</label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class="col-form-label">申请页面</label>
                    </div>
                    <div class="col-9">
                        <div class="upload-group short-row">
                            <div class="input-group">
                                <input class="form-control file-input" name="model[pic_url_1]"
                                       value="<?= $list['pic_url_1'] ?>">
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
                                <span class="upload-preview-tip">750&times;300</span>
                                <img class="upload-preview-img" src="<?= $list['pic_url_1'] ?>">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class="col-form-label">待审核页面</label>
                    </div>
                    <div class="col-9">
                        <div class="upload-group short-row">
                            <div class="input-group">
                                <input class="form-control file-input" name="model[pic_url_2]"
                                       value="<?= $list['pic_url_2'] ?>">
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
                                <span class="upload-preview-tip">750&times;300</span>
                                <img class="upload-preview-img" src="<?= $list['pic_url_2'] ?>">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-3 text-right">
                    </div>
                    <div class="col-9">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    </div>
                </div>
            </div>

        </form>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="apply_tpl" data-backdrop="static" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">如何获取分销审核通知模板消息id</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ol class="pl-3">
                        <li>
                            <div>进入微信小程序官方后台，找到模板库</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/0.png">
                            </div>
                        </li>
                        <li>
                            <div>查找指定模板（审核状态通知），点击选用</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/apply_tpl/1.png">
                            </div>
                        </li>
                        <li>
                            <div>选择下图关键词，并按下图调好顺序；点击提交</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/apply_tpl/2.png">
                            </div>
                        </li>
                        <li>
                            <div>复制模板ID</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/apply_tpl/3.png">
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <a href="javascript:" class="btn btn-secondary" data-dismiss="modal">关闭</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cash_success_tpl" data-backdrop="static" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">如何获取提现到账通知模板消息id</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ol class="pl-3">
                        <li>
                            <div>进入微信小程序官方后台，找到模板库</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/0.png">
                            </div>
                        </li>
                        <li>
                            <div>查找指定模板（提现到账通知），点击选用</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/cash_success_tpl/1.png">
                            </div>
                        </li>
                        <li>
                            <div>选择下图关键词，并按下图调好顺序；点击提交</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/cash_success_tpl/2.png">
                            </div>
                        </li>
                        <li>
                            <div>复制模板ID</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/cash_success_tpl/3.png">
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <a href="javascript:" class="btn btn-secondary" data-dismiss="modal">关闭</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="cash_fail_tpl" data-backdrop="static" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">如何获取提现失败通知模板消息id</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ol class="pl-3">
                        <li>
                            <div>进入微信小程序官方后台，找到模板库</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/0.png">
                            </div>
                        </li>
                        <li>
                            <div>查找指定模板（提现失败通知），点击选用</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/cash_fail_tpl/1.png">
                            </div>
                        </li>
                        <li>
                            <div>选择下图关键词，并按下图调好顺序；点击提交</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/cash_fail_tpl/2.png">
                            </div>
                        </li>
                        <li>
                            <div>复制模板ID</div>
                            <div style="text-align: center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/cash_fail_tpl/3.png">
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <a href="javascript:" class="btn btn-secondary" data-dismiss="modal">关闭</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" data-backdrop="static" id="searchGoodsModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">查找商品</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= $urlManager->createUrl(['mch/goods/goods-search']) ?>"
                          class="input-group  goods-search-form" method="get">
                        <input name="keyword" class="form-control" placeholder="商品名称">
                        <span class="input-group-btn">
                        <button class="btn btn-secondary submit-btn">查找</button>
                    </span>
                    </form>
                    <div v-if="goodsList==null" class="text-muted text-center p-5">请输入商品名称查找商品</div>
                    <template v-else>
                        <div v-if="goodsList.length==0" class="text-muted text-center p-5">未查找到相关商品</div>
                        <template v-else>
                            <div class="goods-item row mt-3 mb-3" v-for="(item,index) in goodsList">
                                <div class="col-8">
                                    <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">
                                        {{item.name}}
                                    </div>
                                </div>
                                <div class="col-2 text-right">￥{{item.price}}</div>
                                <div class="col-2 text-right">
                                    <a href="javascript:" class="goods-select" v-bind:index="index">选择</a>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var app = new Vue({
        el: "#app",
        data: {
            goodsList: null,
        },
    });

    $(document).on("click", ".assign-good", function () {
        var status = $(this).val()
        if (status == 2) {
            $('#select-assign-good').css('display', '');
        } else {
            $('#select-assign-good').css('display', 'none');
        }
    });

    $(document).on("submit", ".goods-search-form", function () {
        var form = $(this);
        var btn = form.find(".submit-btn");
        btn.btnLoading("正在查找");
        $.ajax({
            url: form.attr("action"),
            type: "get",
            dataType: "json",
            data: form.serialize(),
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.goodsList = res.data.list;
                }
            }
        });
        return false;
    });

    $(document).on("click", ".goods-select", function () {
        var index = $(this).attr("index");
        var goods = app.goodsList[index];
        $("#searchGoodsModal").modal("hide");
        $(".search-goods-name").val(goods.name);
        $(".search-goods-id").val(goods.id);
        for (var i in goods.attr) {
            goods.attr[i].miaosha_price = parseFloat(goods.attr[i].price == 0 ? goods.price : goods.attr[i].price);
            goods.attr[i].miaosha_num = goods.attr[i].num;
            goods.attr[i].sell_num = 0;
        }
        app.goods = goods;
    });
</script>
