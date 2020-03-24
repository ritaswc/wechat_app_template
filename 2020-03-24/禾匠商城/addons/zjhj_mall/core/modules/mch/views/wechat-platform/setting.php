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
defined('YII_ENV') or exit('Access Denied');
$this->title = '参数配置';
?>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <b>公众号配置:</b>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">公众号AppId</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="app_id" value="<?= $data['app_id'] ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">公众号AppSecret</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="app_secret" value="<?= $data['app_secret'] ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <b>模板消息ID配置:</b>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">新订单消息</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="tpl_id_list[new_order]"
                           value="<?= $data['tpl_id_list']['new_order'] ?>">
                    <div class="fs-sm text-muted">用户购买商品后管理员收到通知，<a
                                class="show-tpl-modal"
                                data-name="新订单通知"
                                data-pic="<?= Yii::$app->request->baseUrl ?>/statics/images/wechatplatform/1.png"
                                href="javascript:">模板示例</a></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">分销商入驻申请通知</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="tpl_id_list[fxs_apply]"
                           value="<?= $data['tpl_id_list']['fxs_apply'] ?>">
                    <div class="fs-sm text-muted">用户申请成为分销商时管理员收到通知，<a
                                class="show-tpl-modal"
                                data-name="入驻申请提醒"
                                data-pic="<?= Yii::$app->request->baseUrl ?>/statics/images/wechatplatform/2.png"
                                href="javascript:">模板示例</a></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">分销商提现通知</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="tpl_id_list[fxs_cash]"
                           value="<?= $data['tpl_id_list']['fxs_cash'] ?>">
                    <div class="fs-sm text-muted">分销商提交提现申请管理员收到通知，<a
                                class="show-tpl-modal"
                                data-name="提现申请通知"
                                data-pic="<?= Yii::$app->request->baseUrl ?>/statics/images/wechatplatform/3.png"
                                href="javascript:">模板示例</a></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">多商户入驻申请通知</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="tpl_id_list[mch_apply]"
                           value="<?= $data['tpl_id_list']['mch_apply'] ?>">
                    <div class="fs-sm text-muted">用户申请入驻商管理员收到通知，<a
                                class="show-tpl-modal"
                                data-name="入驻申请提醒"
                                data-pic="<?= Yii::$app->request->baseUrl ?>/statics/images/wechatplatform/2.png"
                                href="javascript:">模板示例</a></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">入驻商商品上架申请通知</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control"
                           name="tpl_id_list[mch_upload_goods]" value="<?= $data['tpl_id_list']['mch_upload_goods'] ?>">
                    <div class="fs-sm text-muted">入驻商商品申请上架时管理员收到通知，<a
                                class="show-tpl-modal"
                                data-name="服务申请通知"
                                data-pic="<?= Yii::$app->request->baseUrl ?>/statics/images/wechatplatform/4.png"
                                href="javascript:">模板示例</a></div>
                </div>
            </div>

            <div class="form-group row" style="display: none">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">入驻商商品上架申请结果通知</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control"
                           name="tpl_id_list[mch_upload_goods_result]"
                           value="<?= $data['tpl_id_list']['mch_upload_goods_result'] ?>">
                    <div class="fs-sm text-muted">管理员审核入驻商上架商品时入驻商收到通知，<a
                                class="show-tpl-modal"
                                data-name="新订单通知"
                                data-pic="<?= Yii::$app->request->baseUrl ?>/statics/images/wechatplatform/1.png"
                                href="javascript:">模板示例</a></div>
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <b>其它配置:</b>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">管理员公众号OpenId</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="admin_open_id"
                           value="<?= $data['admin_open_id'] ?>">
                    <div class="fs-sm text-muted">管理员必须关注公众号才能收到模板消息。<a href="javascript:" data-toggle="modal"
                                                                        data-target="#getOpenIdModal">如何获取OpenId</a>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right"></div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>

        </form>


        <!-- Modal -->
        <div class="modal fade" id="getOpenIdModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">如何获取OpenId</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ol>
                            <li>关注公众号，在公众号中回复任意消息。</li>
                            <li>登录微信公众号平台，进入消息管理，点击刚刚回复的消息<br><img
                                        src="<?= Yii::$app->request->baseUrl ?>/statics/images/getopenid/1.png"
                                        style="max-width: 100%"></li>
                            <li>在浏览器地址栏即可找到用户的Openid（tofakeid）<br><img
                                        src="<?= Yii::$app->request->baseUrl ?>/statics/images/getopenid/2.png"
                                        style="max-width: 100%"></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="getTplModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">模板消息示例</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ol>
                            <li class="my-1">登录微信公众号平台，打开-模板消息-模板库。</li>
                            <li class="my-1">搜索模板“{{name}}”并添加。</li>
                            <li class="my-1">模板格式如图：<br><img style="max-width: 100%" :src="pic"></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            name: '',
            pic: '',
        },
    });
    $(document).on('click', '.show-tpl-modal', function () {
        app.name = $(this).attr('data-name');
        app.pic = $(this).attr('data-pic');
        $('#getTplModal').modal('show');
    });
</script>
