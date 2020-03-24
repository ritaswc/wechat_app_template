<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IDEA.
 * User: luwei
 * Date: 2017年9月20日
 * Time: 16点53分
 */
/* @var \app\models\WechatTemplateMessage $model */

$urlManager = Yii::$app->urlManager;
$this->title = '通知设置';
$this->params['active_nav_group'] = 1;
?>
<div class="panel">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form method="post" class="auto-form">

            <p class="text-warning">注：因微信限制，每个支付订单最多只能发送<b>3</b>条模板消息，如不需要发送模板消息的请留空。</p>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">支付通知模板消息id</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" value="<?= $model->pay_tpl ?>" name="pay_tpl">
                    <div class="fs-sm text-muted">
                        <span>用户支付完成后向用户发送消息，</span>
                        <a href="javascript:" data-toggle="modal" data-target="#pay_tpl">如何获取支付通知模板消息id</a>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">订单取消通知模板消息id</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" value="<?= $model->revoke_tpl ?>" name="revoke_tpl">
                    <div class="fs-sm text-muted">
                        <span>用户取消订单后向用户发送消息，若订单已付款则在后台审核通过后向用户发送消息，</span>
                        <a href="javascript:" class="fs-sm" data-toggle="modal"
                           data-target="#revoke_tpl">如何获取订单取消通知模板消息id</a>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">发货通知模板消息id</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" value="<?= $model->send_tpl ?>" name="send_tpl">
                    <div class="fs-sm text-muted">
                        <span>后台发货后向用户发送消息，</span>
                        <a href="javascript:" class="fs-sm" data-toggle="modal"
                           data-target="#send_tpl">如何获取发货通知模板消息id</a>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">退款通知模板消息id</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" value="<?= $model->refund_tpl ?>" name="refund_tpl">
                    <div class="fs-sm text-muted">
                        <span>退款订单后台处理完成后向用户发送消息，</span>
                        <a href="javascript:" class="fs-sm" data-toggle="modal"
                           data-target="#refund_tpl">如何获取退款通知模板消息id</a>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="pay_tpl" data-backdrop="static" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">如何获取支付通知模板消息id</h5>
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
                        <div>查找指定模板，点击选用</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/pay_tpl/1.png">
                        </div>
                    </li>
                    <li>
                        <div>选择下图关键词，并按下图调好顺序；点击提交</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/pay_tpl/2.png">
                        </div>
                    </li>
                    <li>
                        <div>复制模板ID</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/pay_tpl/3.png">
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
<div class="modal fade" id="revoke_tpl" data-backdrop="static" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">如何获取订单取消通知模板消息id</h5>
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
                        <div>查找指定模板，点击选用</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/revoke_tpl/1.png">
                        </div>
                    </li>
                    <li>
                        <div>选择下图关键词，并按下图调好顺序；点击提交</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/revoke_tpl/2.png">
                        </div>
                    </li>
                    <li>
                        <div>复制模板ID</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/revoke_tpl/3.png">
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
<div class="modal fade" id="send_tpl" data-backdrop="static" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">发货通知模板消息id</h5>
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
                        <div>查找指定模板，点击选用</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/send_tpl/1.png">
                        </div>
                    </li>
                    <li>
                        <div>选择下图关键词，并按下图调好顺序；点击提交</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/send_tpl/2.png">
                        </div>
                    </li>
                    <li>
                        <div>复制模板ID</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/send_tpl/3.png">
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
<div class="modal fade" id="refund_tpl" data-backdrop="static" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">如何获取退款通知模板消息id</h5>
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
                        <div>查找指定模板，点击选用</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/refund_tpl/1.png">
                        </div>
                    </li>
                    <li>
                        <div>选择下图关键词，并按下图调好顺序；点击提交</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/refund_tpl/2.png">
                        </div>
                    </li>
                    <li>
                        <div>复制模板ID</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/refund_tpl/3.png">
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