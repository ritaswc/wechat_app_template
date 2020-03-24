<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/3/29
 * Time: 15:16
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
$title = '超限设置';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix" id="app">
            <div style="background-color: #fce9e6;width: 100%;border-color: #edd7d4;color: #e55640;border-radius: 2px;padding: 15px;margin-bottom: 20px;">
                注：为了服务器性能和用户体验考虑做了如下限制，请谨慎考虑！！！
            </div>
            <form class="auto-form" method="post" v-if="loading">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">上传图片限制</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input class="form-control" name="max_picture" v-model="max_picture" :disabled="over_picture == 1">
                            <span class="input-group-addon">M</span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label class="checkbox-label">
                            <input :checked="over_picture == 1" name="over_picture" type="checkbox" @change="changePicture"
                                   class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">不限制</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">diy组件限制</label>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control" name="max_diy" v-model="max_diy" :disabled="over_diy == 1">
                    </div>
                    <div class="col-sm-2">
                        <label class="checkbox-label">
                            <input :checked="over_diy == 1" name="over_diy" type="checkbox" @change="changeDiy"
                                   class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">不限制</span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-primary submit" href="javascript:" @click="submit">保存</a>
                        <a class="btn btn-secondary reset" href="javascript:" @click="reset">默认设置</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            max_picture: 1,
            max_diy: 20,
            over_picture: false,
            over_diy: 0,
            loading: 0
        },
        created: function() {
            this.loadData();
        },
        methods: {
            loadData: function () {
                var self = this;
                $.ajax({
                    method: 'get',
                    success: function (res) {
                        if (res.code == 0) {
                            self.max_picture = res.data.max_picture;
                            self.max_diy = res.data.max_diy;
                            self.over_picture = res.data.over_picture;
                            self.over_diy = res.data.over_diy;
                            self.loading = true
                        }
                    }
                });
            },
            changeDiy: function () {
                if (this.over_diy == 0) {
                    this.over_diy = 1;
                } else {
                    this.over_diy = 0;
                }
            },
            changePicture: function () {
                if (this.over_picture == 0) {
                    this.over_picture = 1;
                } else {
                    this.over_picture = 0;
                }
            },
            submit: function () {
                var self = this;
                $('.submit').btnLoading('保存中');
                $.ajax({
                    method: 'post',
                    data: {
                        max_picture: self.max_picture,
                        max_diy: self.max_diy,
                        over_picture: self.over_picture,
                        over_diy: self.over_diy,
                        _csrf: _csrf
                    },
                    success: function (res) {
                        $.myAlert({
                            content: res.msg
                        });
                    },
                    complete: function (res) {
                        $('.submit').btnReset();
                    }
                });
            },
            reset: function () {
                var self = this;
                $('.reset').btnLoading('保存中');
                $.ajax({
                    method: 'post',
                    data: {
                        _csrf: _csrf
                    },
                    success: function (res) {
                        $.myAlert({
                            content: res.msg
                        });
                    },
                    complete: function (res) {
                        $('.reset').btnReset();
                    }
                });
            },
        }
    });
</script>
