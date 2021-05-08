<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
/** @var \app\models\alipay\TplMsgForm $model */
defined('YII_ENV') or exit('Access Denied');
$this->title = '模版消息';
?>

<style>
    .point {
        color: red;
    }
    .form-group-label {
        flex: 0 0 249px;
    }
</style>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?><span class="point">(可复制相应模板ID到支付宝小程序后台搜索)</span></div>
    <div class="panel-body">
        <form class="auto-form" method="post">

            <fieldset style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px; <?= $model['store']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    商城
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">订单支付(模板ID: AT0001 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="pay_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['store']['pay_tpl']) ? $model['store']['pay_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">用户支付完成后向用户发送消息，
                            <a data-toggle="modal" data-target="#tip_pay_tpl"
                               href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_pay_tpl">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/pay_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">订单取消(模板ID: AT0027 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="revoke_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['store']['revoke_tpl']) ? $model['store']['revoke_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">用户取消订单后向用户发送消息，若订单已付款则在后台审核通过后向用户发送消息，<a data-toggle="modal"
                                                                                               data-target="#tip_revoke_tpl"
                                                                                               href="javascript:">查看模板消息格式</a>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="tip_revoke_tpl">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/revoke_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">订单发货(模板ID: AT0011 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="send_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['store']['send_tpl']) ? $model['store']['send_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">后台发货后向用户发送消息，<a data-toggle="modal" data-target="#tip_send_tpl"
                                                                      href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_send_tpl">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/send_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">订单退款(模板ID: AT0003 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="refund_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['store']['refund_tpl']) ? $model['store']['refund_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">退款订单后台处理完成后向用户发送消息，<a data-toggle="modal"
                                                                            data-target="#tip_refund_tpl"
                                                                            href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_refund_tpl">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/refund_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $model['share']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    分销
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">提现成功(模板ID: AT0112 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="cash_success_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['share']['cash_success_tpl']) ? $model['share']['cash_success_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">提现转账处理完成后向用户发送消息，<a data-toggle="modal"
                                                                          data-target="#tip_cash_success_tpl"
                                                                          href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_cash_success_tpl">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/cash_success_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">提现失败(模板ID: AT0037 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="cash_fail_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['share']['cash_fail_tpl']) ? $model['share']['cash_fail_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">提现失败向用户发送消息，<a data-toggle="modal"
                                                                     data-target="#tip_cash_fail_tpl"
                                                                     href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_cash_fail_tpl">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/cash_fail_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">分销审核(模板ID: AT0044 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="apply_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['share']['apply_tpl']) ? $model['share']['apply_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">分销审核结果向用户发送消息，<a data-toggle="modal" data-target="#tip_apply_tpl"
                                                                       href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_apply_tpl">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/apply_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px; <?= $model['pintuan']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    拼团
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">拼团成功(模板ID: AT0151 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="pt_success_notice" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['pintuan']['pt_success_notice']) ? $model['pintuan']['pt_success_notice'] : '' ?>">
                        <div class="text-muted fs-sm">拼团成功通知，<a data-toggle="modal" data-target="#tip_pt_success_notice"
                                                                href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_pt_success_notice">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/pt_success_notice.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">拼团失败(模板ID: AT0150 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="pt_fail_notice" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['pintuan']['pt_fail_notice']) ? $model['pintuan']['pt_fail_notice'] : '' ?>">
                        <div class="text-muted fs-sm">拼团失败通知，<a data-toggle="modal" data-target="#tip_pt_fail_notice"
                                                                href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_pt_fail_notice">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/pt_fail_notice.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $model['book']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    预约
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">预约成功(模板ID: AT0001 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="yy_success_notice" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['book']['yy_success_notice']) ? $model['book']['yy_success_notice'] : '' ?>">
                        <div class="text-muted fs-sm">预约成功通知，<a data-toggle="modal" data-target="#tip_yy_success_notice"
                                                                href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_yy_success_notice">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/yy_success_notice.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">预约失败(模板ID: AT0003 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="yy_refund_notice" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['book']['yy_refund_notice']) ? $model['book']['yy_refund_notice'] : '' ?>">
                        <div class="text-muted fs-sm">预约失败退款通知，<a data-toggle="modal"
                                                                  data-target="#tip_yy_refund_notice"
                                                                  href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_yy_refund_notice">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/yy_refund_notice.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $model['mch']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    多商户
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">多商户入驻审核(模板ID: AT0044 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="mch_tpl_1" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['mch']['mch_tpl_1']) ? $model['mch']['mch_tpl_1'] : '' ?>">
                        <div class="text-muted fs-sm">入驻审核模板消息，<a data-toggle="modal" data-target="#tip_mch_tpl_1"
                                                                  href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_mch_tpl_1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/mch_tpl_1.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">多商户下单(模板ID: AT0008 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="mch_tpl_2" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['mch']['mch_tpl_2']) ? $model['mch']['mch_tpl_2'] : '' ?>">
                        <div class="text-muted fs-sm">下单模板消息，<a data-toggle="modal" data-target="#tip_mch_tpl_2"
                                                                href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_mch_tpl_2">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">请按照如下参数配置模板消息：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay/mch_tpl_2.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset
                    style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $model['fxhb']['is_show'] ? 'display: none' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    裂变拆红包
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">拆红包成功消息(模板ID:  )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="fxhb_msg_id" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['fxhb']['fxhb_msg_id']) ? $model['fxhb']['fxhb_msg_id'] : '' ?>">
                        <div class="text-muted fs-sm">拆红包成功消息，<a data-toggle="modal" data-target="#tpl_msg_id"
                                                                  href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tpl_msg_id">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">模板消息格式：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?=Yii::$app->request->baseUrl?>/statics/images/fxhb/tplmsg.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset
                    style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $model['lottery']['is_show'] ? 'display: none' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    抽奖结果
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">抽奖结果消息(模板ID:  )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="bargain_msg_id" placeholder="请输入模版 ID ..."
                               value="<?= isset($model['lottery']['bargain_msg_id']) ? $model['lottery']['bargain_msg_id'] : '' ?>">
                        <div class="text-muted fs-sm">抽奖结果消息，<a data-toggle="modal" data-target="#tpl_msg_id"
                                                                 href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tpl_msg_id">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">模板消息格式：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?=Yii::$app->request->baseUrl?>/statics/images/fxhb/tplmsg.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset
                    style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $model['lottery']['is_show'] ? 'display: none' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    参与活动(砍价)
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">活动参与成功(模板ID:  )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="activity_success_msg_id" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['activity']['activity_success_msg_id']) ? $tplMsg['activity']['activity_success_msg_id'] : '' ?>">
                        <div class="text-muted fs-sm">活动参与成功通知，<a data-toggle="modal" data-target="#tip_yy_success_notice"
                                                                  href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_yy_success_notice">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">模板消息格式：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/yy_success_notice.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">活动参与失败(模板ID:  )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="activity_refund_msg_id" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['activity']['activity_refund_msg_id']) ? $tplMsg['activity']['activity_refund_msg_id'] : '' ?>">
                        <div class="text-muted fs-sm">活动参与失败通知，<a data-toggle="modal"
                                                                  data-target="#tip_yy_refund_notice"
                                                                  href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_yy_refund_notice">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">模板消息格式：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img style="max-width: 100%"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/yy_refund_notice.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="form-group row">
                <div style="margin-left: 7px;" class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>

        </form>
    </div>
</div>

