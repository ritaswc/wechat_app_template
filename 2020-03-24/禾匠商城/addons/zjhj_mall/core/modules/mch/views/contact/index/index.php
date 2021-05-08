<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/4/4
 * Time: 13:00
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
$this->title = "客服回调模板消息";
$urlManager = Yii::$app->urlManager;
?>
<div class="panel mb-3" id="app">
    <div class="panel-header">
        <div>客服回调模板消息<span class="point">(可复制相应模板编号到微信小程序后台搜索)</span></div>
        <div style="color: #0275d8;">温馨提示：该设置用于客服发送给离线访客消息后，可以通过小程序模版消息对该访客进行通知提醒</div>
    </div>
    <div class="panel-body">
        <div class="form-group row">
            <div class="form-group-label col-sm-3 text-right">
                <label class="col-form-label">客服回调链接</label>
            </div>
            <div class="col-sm-3">
                <div>
                    <input class="form-control" style="max-width: 300px" disabled readonly
                           :value="url">
                </div>
                <div class="text-danger">注：请不要泄露链接，以免造成不必要的麻烦</div>
            </div>
            <div class="col-sm-3">
                <a class="btn btn-sm btn-primary copy"
                   :data-clipboard-text="url"
                   href="javascript:">复制链接</a>
                <a class="btn btn-sm btn-secondary" href="javascript:" @click="reset">重置链接</a>
            </div>
        </div>
        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-3 text-right">
                    <label class="col-form-label">客服回复通知<br>(模板编号: AT1633 )</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="contact_tpl" placeholder="请输入模版 ID ..."
                           v-model="contact_tpl">
                    <div class="text-muted fs-sm">客服向用户发送模板消息，
                        <a data-toggle="modal" data-target="#tip_contact_tpl"
                           href="javascript:">查看模板消息格式</a></div>
                </div>
            </div>

            <div class="form-group row">
                <div style="margin-left: 7px;" class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
        <div class="modal fade" id="tip_contact_tpl">
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
                             src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/contact_tpl.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var clipboard = new Clipboard('.copy');
        clipboard.on('success', function (e) {
            $.myAlert({
                title: '提示',
                content: '复制成功'
            });
        });
        clipboard.on('error', function (e) {
            $.myAlert({
                title: '提示',
                content: '复制失败，请手动复制。链接为：' + e.text
            });
        });
    });
    var app = new Vue({
        el: '#app',
        data: {
            contact_tpl: '',
            url: ''
        },
        created() {
            var self = this;
            $.ajax({
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        self.contact_tpl = res.data.contact_tpl;
                        self.url = res.data.url;
                    } else {
                        $.myAlert({
                            content: res.msg
                        })
                    }
                }
            });
        },
        methods: {
            reset: function () {
                var self = this;
                var url = "<?= $urlManager->createUrl(['mch/contact/index/reset-token'])?>";
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            self.url = res.data.url;
                            $.myAlert({
                                content: '重置成功'
                            })
                        } else {
                            $.myAlert({
                                content: res.msg
                            })
                        }
                    }
                });
            }
        }
    });
</script>
