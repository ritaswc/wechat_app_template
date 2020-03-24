<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/7/9
 * Time: 15:58
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
$this->title = $title = '商城导出';
$urlManager = Yii::$app->urlManager;
?>
<style>
    .export-content div {
        width: 160px;
        margin: 20px 0 0 20px;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix" id="app">
            <div style="background-color: #fce9e6;width: 100%;border-color: #edd7d4;color: #e55640;border-radius: 2px;padding: 15px;margin-bottom: 20px;">
                注：为了服务器性能和用户体验考虑，请在用户少的时候进行导出数据操作！！！
            </div>
            <div style="margin-bottom: 24px;">导出内容</div>
            <div class="card" v-for="item in list" style="margin-bottom: 16px;">
                <div class="card-header" style="height: 50px;background-color: #f3f5f6">
                    {{item.name}}
                </div>
                <div class="card-body export-content" flex="dir:left" style="flex-wrap: wrap;padding-bottom: 20px;">
                    <div v-for="value in item.content">{{value.name}}</div>
                </div>
            </div>
            <div class="col-sm-12">
                <div>
                    <label class="form-control-label">商城数据码</label>
                    <input class="form-control" style="max-width: 600px" disabled readonly
                           :value="code">
                </div>
                <div class="text-danger">注：请不要泄露链接，以免造成不必要的麻烦</div>
            </div>
            <div class="col-sm-12">
                <a class="btn btn-sm btn-primary copy"
                   :data-clipboard-text="code"
                   href="javascript:">复制链接</a>
                <a class="btn btn-sm btn-secondary" href="javascript:" @click="reset">重置链接</a>
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
    const app = new Vue({
        el: '#app',
        data() {
            return {
                code: '',
                list: [
                    {
                        name: '基本数据',
                        content: [
                            {
                                name: '微信基本配置',
                            },
                            {
                                name: '轮播图',
                            },
                            {
                                name: '导航图标',
                            },
                            {
                                name: '图片魔方',
                            },
                            {
                                name: '导航栏',
                            },
                            {
                                name: '首页布局',
                            },
                            {
                                name: '用户中心',
                            },
                            {
                                name: '版权设置',
                            },
                            {
                                name: '商城设置-基本设置',
                            },
                            {
                                name: '商城设置-图标设置',
                            },
                        ]
                    },
                    {
                        name: '用户数据',
                        content: [
                            {
                                name: '会员等级'
                            },
                            {
                                name: '用户管理'
                            },
                            {
                                name: '分销商数据'
                            },
                        ]
                    },
                    {
                        name: '商品信息',
                        content: [
                            {
                                name: '商城商品分类'
                            },
                            {
                                name: '商城商品'
                            },
                            {
                                name: '秒杀商品'
                            },
                            {
                                name: '拼团商品'
                            },
                            {
                                name: '拼团商品分类'
                            },
                            {
                                name: '预约商品分类'
                            },
                            {
                                name: '预约商品'
                            },
                            {
                                name: '积分商城商品'
                            },
                            {
                                name: '积分商城商品分类'
                            },
                        ]
                    }
                ]
            }
        },
        created() {
            this.loadData();
        },
        methods: {
            loadData: function () {
                var self = this;
                var url = "<?= $urlManager->createUrl(['mch/system/get-code'])?>";
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            self.code = res.data.code;
                        } else {
                            $.myAlert({
                                content: res.msg
                            })
                        }
                    }
                });
            },
            reset: function () {
                var self = this;
                var url = "<?= $urlManager->createUrl(['mch/system/get-code'])?>";
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        refresh: 1
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            self.code = res.data.code;
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
            },
        }
    });
</script>
