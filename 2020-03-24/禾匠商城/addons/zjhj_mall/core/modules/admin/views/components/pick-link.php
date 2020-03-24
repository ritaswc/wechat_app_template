<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/13
 * Time: 9:44
 */

?>
<style>
    .label-help {
        display: inline-block;
        font-size: .65rem;
        background: #555;
        color: #fff;
        border-radius: 999px;
        width: 1rem;
        height: 1rem;
        line-height: 1rem;
        text-align: center;
        text-decoration: none;
        margin-left: .25rem;
    }

    .label-help:hover,
    .label-help:visited {
        color: #fff;
        text-decoration: none;
    }
</style>
<div id="pick_link_modal">
    <div class="modal fade pick-link-modal" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">选择链接</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span class="input-group-addon">可选链接</span>
                        <select class="form-control link-list-select">
                            <option value="">点击选择链接</option>
                            <option v-for="(link,i) in link_list"
                                    v-if="in_array(link.open_type,open_type) != -1"
                                    v-bind:value="i">
                                {{link.name}}
                            </option>
                        </select>
                    </div>
                    <template v-if="selected_link">
                        <template v-if="selected_link.params && selected_link.params.length>0">
                            <div class="form-group row" v-for="(param,i) in selected_link.params">
                                <label class="col-sm-2 text-right col-form-label">{{param.key}}</label>
                                <div class="col-sm-10">
                                    <input class="form-control" v-model="param.value">
                                    <div class="fs-sm text-muted" v-if="param.desc">{{param.desc}}</div>
                                </div>
                            </div>
                        </template>
                        <div v-else class="text-center text-muted">此页面无需配置参数</div>
                    </template>
                    <div v-else class="text-center text-muted">请选择链接</div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary pick-link-confirm" href="javascript:">确定</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    String.prototype._trim = function (char, type) {
        if (char) {
            if (type == 'left') {
                return this.replace(new RegExp('^\\' + char + '+', 'g'), '');
            } else if (type == 'right') {
                return this.replace(new RegExp('\\' + char + '+$', 'g'), '');
            }
            return this.replace(new RegExp('^\\' + char + '+|\\' + char + '+$', 'g'), '');
        }
        return this.replace(/^\s+|\s+$/g, '');
    };

    var pick_link_modal;
    $(document).ready(function () {
        var pick_link_btn;

        pick_link_modal = new Vue({
            el: "#pick_link_modal",
            data: {
                in_array: function (val, arr) {
                    return $.inArray(val, arr);
                },
                open_type: [],
                selected_link: null,
                link_list: [
                    {
                        name: "商城首页",
                        link: "/pages/index/index",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "分类",
                        link: "/pages/cat/cat",
                        open_type: "navigate",
                        params: [
                            {
                                key: "cat_id",
                                value: "",
                                desc: "cat_id请填写在商品分类中相关分类的ID"
                            }
                        ]
                    },
                    {
                        name: "购物车",
                        link: "/pages/cart/cart",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "用户中心",
                        link: "/pages/user/user",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "商品列表",
                        link: "/pages/list/list",
                        open_type: "navigate",
                        params: [
                            {
                                key: "cat_id",
                                value: "",
                                desc: "cat_id请填写在商品分类中相关分类的ID"
                            }
                        ]
                    },
                    {
                        name: "商品详情",
                        link: "/pages/goods/goods",
                        open_type: "navigate",
                        params: [
                            {
                                key: "id",
                                value: "",
                                desc: "id请填写在商品列表中相关商品的ID"
                            }
                        ]
                    },
                    {
                        name: "所有订单",
                        link: "/pages/order/order?status=-1",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "待付款订单",
                        link: "/pages/order/order?status=0",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "待发货订单",
                        link: "/pages/order/order?status=1",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "待收货订单",
                        link: "/pages/order/order?status=2",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "已完成订单",
                        link: "/pages/order/order?status=3",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "售后订单",
                        link: "/pages/order/order?status=4",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "分销中心",
                        link: "/pages/share/index",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "我的优惠券",
                        link: "/pages/coupon/coupon",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "我的收藏",
                        link: "/pages/favorite/favorite",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "关于我们",
                        link: "/pages/article-detail/article-detail?id=about_us",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "服务中心",
                        link: "/pages/article-list/article-list?id=2",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "视频专区",
                        link: "/pages/video/video-list",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "专题列表",
                        link: "/pages/topic-list/topic-list",
                        open_type: "navigate",
                        params: [
                            {
                                key: "type",
                                value: "",
                                desc: "type请填写在专题分类中的ID"
                            }
                        ]
                    },
                    {
                        name: "专题详情",
                        link: "/pages/topic/topic",
                        open_type: "navigate",
                        params: [
                            {
                                key: "id",
                                value: "",
                                desc: "id请填写在专题列表中相关专题的ID"
                            }
                        ]
                    },
                    {
                        name: "领券中心",
                        link: "/pages/coupon-list/coupon-list",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "小程序（同一公众号下关联的小程序）",
                        link: "/",
                        open_type: "wxapp",
                        params: [
                            {
                                key: "appId",
                                value: "",
                                desc: "要打开的小程序 appId"
                            },
                            {
                                key: "path",
                                value: "",
                                desc: "打开的页面路径，如pages/index/index，开头请勿加“/”"
                            },
                        ]
                    },
                    {
                        name: "整点秒杀（需先安装插件）",
                        link: "/pages/miaosha/miaosha",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "网页链接",
                        link: "/pages/web/web",
                        open_type: "navigate",
                        params: [
                            {
                                key: "url",
                                value: "",
                                desc: "打开的网页链接（注：域名必须已在微信官方小程序平台设置业务域名）",
                            }
                        ],
                    },
                    {
                        name: "门店列表",
                        link: "/pages/shop/shop",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "拼团",
                        link: "/pages/pt/index/index",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "拼团详情",
                        link: "/pages/pt/details/details",
                        open_type: "navigate",
                        params: [
                            {
                                key:"gid",
                                value:"",
                                desc:"gid请填写拼团商品列表的商品ID"
                            }
                        ],
                    },
                    {
                        name: "预约",
                        link: "/pages/book/index/index",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "预约详情",
                        link: "/pages/book/details/details",
                        open_type: "navigate",
                        params: [
                            {
                                key:"id",
                                value:"",
                                desc:"ID请填写预约商品列表的商品ID"
                            }
                        ],
                    },
                    {
                        name: "快速购买",
                        link: "/pages/quick-purchase/index/index",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "裂变拆红包",
                        link: "/pages/fxhb/open/open",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "充值",
                        link: "/pages/recharge/recharge",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "好店推荐",
                        link: "/mch/shop-list/shop-list",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "入驻商",
                        link: "/mch/m/myshop/myshop",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "积分商城",
                        link: "/pages/integral-mall/index/index",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "签到",
                        link: "/pages/integral-mall/register/index",
                        open_type: "navigate",
                        params: []
                    },
                ]
            }
        });

        $(document).on("change", ".link-list-select", function () {
            var i = $(this).val();
            if (i == "") {
                pick_link_modal.selected_link = null;
                return;
            }
            pick_link_modal.selected_link = pick_link_modal.link_list[i];
        });

        $(document).on("click", ".pick-link-btn", function () {
            pick_link_btn = $(this);
            var open_type = $(this).attr("open-type");
            if (open_type && open_type != "") {
                open_type = open_type.split(",");
            } else {
                open_type = ["navigate", "switchTab", "wxapp"];
            }
            pick_link_modal.open_type = open_type;
            $(".pick-link-modal").modal("show");
        });

        $(document).on("click", ".pick-link-confirm", function () {
            var selected_link = pick_link_modal.selected_link;
            if (!selected_link) {
                $(".pick-link-modal").modal("hide");
                return;
            }
            var link_input = pick_link_btn.parents(".page-link-input").find(".link-input");
            var open_type_input = pick_link_btn.parents(".page-link-input").find(".link-open-type");
            var params = "";
            if (selected_link.params && selected_link.params.length > 0) {
                for (var i in selected_link.params) {
                    params += selected_link.params[i].key + "=" + encodeURIComponent(selected_link.params[i].value) + "&";
                }
            }
            var link = selected_link.link;
            link += "?" + params;
            link = link._trim("&");
            link = link._trim("?");
            link_input.val(link).change();
            open_type_input.val(selected_link.open_type).change();
            $(".pick-link-modal").modal("hide");


        });

    });
</script>