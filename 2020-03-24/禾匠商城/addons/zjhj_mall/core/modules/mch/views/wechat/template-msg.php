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
    .form-group-label {
        flex: 0 0 249px;
    }
    .point {
        color: red;
    }
</style>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?><span class="point">(可复制相应模板编号到微信小程序后台搜索)</span></div>
    <div class="panel-body">
        <form class="auto-form" method="post">

            <fieldset
                    style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px; <?= $tplMsg['store']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    关联模块：<span style="color: red;">商城</span>
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">订单支付(模板编号: AT0009 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="pay_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['store']['pay_tpl']) ? $tplMsg['store']['pay_tpl'] : '' ?>">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/pay_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">订单取消(模板编号: AT0024 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="revoke_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['store']['revoke_tpl']) ? $tplMsg['store']['revoke_tpl'] : '' ?>">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/revoke_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">订单发货(模板编号: AT0007 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="send_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['store']['send_tpl']) ? $tplMsg['store']['send_tpl'] : '' ?>">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/send_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">订单退款(模板编号: AT0036 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="refund_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['store']['refund_tpl']) ? $tplMsg['store']['refund_tpl'] : '' ?>">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/refund_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">报名成功通知(模板编号: AT0027 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="activity_success_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['store']['activity_success_tpl']) ? $tplMsg['store']['activity_success_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">报名成功通知，<a data-toggle="modal" data-target="#tip_activity_success_notice"
                                                                  href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_activity_success_notice">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/activity_success_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">报名失败通知(模板编号: AT0028 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="activity_refund_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['store']['activity_refund_tpl']) ? $tplMsg['store']['activity_refund_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">报名失败通知，<a data-toggle="modal"
                                                                  data-target="#tip_activity_refund_notice"
                                                                  href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_activity_refund_notice">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/activity_refund_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">账户变动通知(模板编号: AT0677 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="tpl_msg_id" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['store']['account_change_tpl']) ? $tplMsg['store']['account_change_tpl'] : '' ?>">
                        <div class="text-muted fs-sm">账户变动通知，<a data-toggle="modal" data-target="#tip_fxhb_tpl_1"
                                                                href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_fxhb_tpl_1">
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
                                     src="<?=Yii::$app->request->baseUrl?>/statics/images/tplmsg/fxhb.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">审核结果通知(模板编号: AT0146 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="mch_tpl_1" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['store']['apply']) ? $tplMsg['store']['apply'] : '' ?>">
                        <div class="text-muted fs-sm">审核结果通知，<a data-toggle="modal" data-target="#tip_mch_tpl_1"
                                                                href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_mch_tpl_1">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/mch-tpl-1.png">
                            </div>
                        </div>
                    </div>
                </div>

            </fieldset>

            <fieldset style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $tplMsg['share']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    关联模块：<span style="color: red;">分销</span>
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">提现成功(模板编号: AT0830 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="cash_success_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['share']['cash_success_tpl']) ? $tplMsg['share']['cash_success_tpl'] : '' ?>">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/cash_success_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">提现失败(模板编号: AT1242 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="cash_fail_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['share']['cash_fail_tpl']) ? $tplMsg['share']['cash_fail_tpl'] : '' ?>">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/cash_fail_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">分销审核(模板编号: AT0674 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="apply_tpl" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['share']['apply_tpl']) ? $tplMsg['share']['apply_tpl'] : '' ?>">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/apply_tpl.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset
                    style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px; <?= $tplMsg['pintuan']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    关联模块：<span style="color: red;">拼团</span>
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">拼团成功通知(模板编号: AT0051 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="pintuan_success_notice" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['pintuan']['pintuan_success_notice']) ? $tplMsg['pintuan']['pintuan_success_notice'] : '' ?>">
                        <div class="text-muted fs-sm">拼团成功通知，<a data-toggle="modal" data-target="#tip_pt_success_notice"
                                                                href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_pt_success_notice">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/pt_success_notice.png">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">拼团失败通知(模板编号: AT0310 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="pintuan_fail_notice" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['pintuan']['pintuan_fail_notice']) ? $tplMsg['pintuan']['pintuan_fail_notice'] : '' ?>">
                        <div class="text-muted fs-sm">拼团失败通知，<a data-toggle="modal" data-target="#tip_pt_fail_notice"
                                                                href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_pt_fail_notice">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/pt_fail_notice.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset
                    style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $tplMsg['book']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    关联模块：<span style="color: red;">预约</span>
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">预约成功(模板编号: AT0009 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="yy_success_notice" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['book']['success_notice']) ? $tplMsg['book']['success_notice'] : '' ?>">
                        <div class="text-muted fs-sm">预约成功通知，<a data-toggle="modal" data-target="#tip_yy_success_notice"
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
                        <label class="col-form-label">预约失败(模板编号: AT0036 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="yy_refund_notice" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['book']['refund_notice']) ? $tplMsg['book']['refund_notice'] : '' ?>">
                        <div class="text-muted fs-sm">预约失败退款通知，<a data-toggle="modal"
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

            <fieldset
                    style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $tplMsg['mch']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    关联模块：<span style="color: red;">多商户</span>
                </legend>

                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">新订单通知(模板编号: AT0079 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="mch_tpl_2" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['mch']['order']) ? $tplMsg['mch']['order'] : '' ?>">
                        <div class="text-muted fs-sm">新订单通知，<a data-toggle="modal" data-target="#tip_mch_tpl_2"
                                                                href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>
                <div class="modal fade" id="tip_mch_tpl_2">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/mch-tpl-2.png">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset
                    style="margin: 8px; border: 1px solid silver; padding: 8px; border-radius: 4px;<?= $tplMsg['lottery']['is_show'] ? '' : 'display: none' ?>">
                <legend style="color:#333333; font-size:0.8em; font-weight:bold;">
                    关联模块：<span style="color: red;">抽奖</span>
                </legend>
                <div class="form-group row">
                    <div class="form-group-label col-sm-3 text-right">
                        <label class="col-form-label">中奖结果通知(模板编号: AT1186 )</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="lottery_success_notice" placeholder="请输入模版 ID ..."
                               value="<?= isset($tplMsg['lottery']['lottery_success_notice']) ? $tplMsg['lottery']['lottery_success_notice'] : '' ?>">
                        <div class="text-muted fs-sm">中奖结果通知，<a data-toggle="modal" data-target="#tip_lot_tpl_1"
                                                                  href="javascript:">查看模板消息格式</a></div>
                    </div>
                </div>

                <div class="modal fade" id="tip_lot_tpl_1">
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
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/lottery_success_tpl.png">
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

