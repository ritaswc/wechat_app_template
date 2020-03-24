<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/27 15:44
 */
?>
<style>
#app {
    padding: 15px;
}

.plugin-item {
    background: #fff;
    padding: 15px;
    box-shadow: 0 0 1px rgba(0, 0, 0, .15);
    margin: 0 auto;
}

.plugin-icon {
    width: 100px;
    height: 100px;
    background-size: cover;
    background-position: center;
    margin-right: 15px;
}

.plugin-name {
    font-size: 17px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 12px;
    line-height: 1.5;
}

.plugin-price {
    color: #F56C6C;
    margin-bottom: 12px;
    font-weight: bold;
}

.plugin-desc {
    font-size: 12px;
    color: #909399;

    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.plugin-tag {
    position: absolute;
    right: calc(-50% + 10px);
    top: -5px;
    background: #ff4544;
    color: #fff;
    padding: 0 0;
    width: 100%;
    height: 35px;
    line-height: 45px;
    text-align: center;
    transform: rotate(45deg);
    font-size: 10px;
    box-shadow: 0 0 3px rgba(0, 0, 0, .15);
}

.plugin-content {
    border-top: 1px solid #e3e3e3;
    padding-top: 15px;
    line-height: 1.5;
}

.order-table td {
    padding: 5px 0;
}
</style>
<div id="app">
    <div v-loading="loading" class="plugin-item">
        <template v-if="item">
            <div flex="box:first" style="margin-bottom: 15px">
                <div>
                    <div class="plugin-icon" :style="'background-image: url(' + item.pic_url + ')'"></div>
                </div>
                <div>
                    <div class="plugin-name">{{item.display_name}}</div>
                    <div class="plugin-price">￥{{item.price}}</div>
                    <template v-if="item.installed">
                        <el-button size="small" type="primary" plain disabled>已安装</el-button>
                    </template>
                    <template v-else>
                        <template v-if="item.order">
                            <template v-if="item.order.is_pay == 1">
                                <el-button size="small" type="primary" plain @click="install" :loading="installLoading">
                                    安装
                                </el-button>
                            </template>
                            <template v-else>
                                <el-button size="small" type="primary" plain
                                           @click="payDialogVisible = !payDialogVisible">付款
                                </el-button>
                            </template>
                        </template>
                        <template v-else>
                            <el-button size="small" type="primary" plain @click="buy" :loading="buyLoading">购买
                            </el-button>
                        </template>
                    </template>
                </div>
            </div>
            <div v-if="item.desc" style="color: #909399;margin-bottom: 15px">{{item.desc}}</div>
            <div class="plugin-content" v-html="item.content"></div>

            <el-dialog
                    title="付款"
                    :visible.sync="payDialogVisible"
                    @closed="payHandleClose">
                <div>
                    <table v-if="item.order" class="order-table">
                        <colgroup>
                            <col style="width: 80px">
                            <col style="width: auto">
                        </colgroup>
                        <tr>
                            <td>订单编号</td>
                            <td style="font-weight: bold">{{item.order.order_no}}</td>
                        </tr>
                        <tr>
                            <td>订单金额</td>
                            <td style="text-decoration: line-through; color: #909399">￥{{item.order.price}}</td>
                        </tr>
                        <tr>
                            <td>支付金额</td>
                            <td style="color: #ff4544;font-weight: bold">￥{{item.order.pay_price}}</td>
                        </tr>
                        <tr>
                            <td>支付状态</td>
                            <td>
                                <template v-if="item.order.is_pay == 1">
                                    <span style="color: #67C23A">已支付</span>
                                </template>
                                <template v-else>
                                    <span style="color: #909399">未支付</span>
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td>付款方式</td>
                            <td>请联系管理员完成付款。</td>
                        </tr>
                    </table>
                    <div v-else>暂无订单信息</div>
                </div>
                <span slot="footer">
                    <el-button type="primary" @click="payDialogVisible = false">确定</el-button>
                  </span>
            </el-dialog>
        </template>
    </div>
</div>
<script>
new Vue({
    el: '#app',
    data() {
        return {
            loading: true,
            buyLoading: false,
            payDialogVisible: false,
            installLoading: false,
            item: null,
        };
    },
    created() {
        request({
            params: {
                r: 'cloud/plugin/detail',
                id: getQuery('id'),
            }
        }).then(e => {
            this.loading = false;
            if (e.data.code === 0) {
                this.item = e.data.data;
            } else {
                this.$alert(e.data.msg, '提示', {
                    type: 'error'
                });
            }
        }).catch(e => {
        });
    },
    methods: {
        buy() {
            this.$confirm('确认购买该插件？', '提示').then(e => {
                this.createOrder();
            }).catch(e => {
            });
        },
        createOrder() {
            this.buyLoading = true;
            request({
                params: {
                    r: 'cloud/plugin/create-order',
                },
                method: 'post',
                data: {
                    id: this.item.id,
                }
            }).then(e => {
                if (e.data.code === 0) {
                    this.$alert('订单提交成功。', '提示').then(e => {
                        location.reload();
                    });
                } else {
                    this.buyLoading = false;
                    this.$message.error(e.data.msg);
                }
            }).catch(e => {
            });
        },
        payHandleClose() {
            location.reload();
        },
        install() {
            this.$confirm('确认安装插件？', '提示').then(e => {
                this.doInstall();
            }).catch(e => {
            });
        },
        doInstall() {
            this.installLoading = true;
            let stopContent = '插件安装中，请勿关闭或刷新页面！';
            window.onbeforeunload = function (event) {
                event.returnValue = stopContent;
            };
            if (parent && parent.stopPageClose) {
                parent.stopPageClose(true, stopContent);
            }
            request({
                params: {
                    r: 'cloud/plugin/install',
                    id: this.item.id,
                }
            }).then(e => {
                window.onbeforeunload = null;
                if (parent && parent.stopPageClose) {
                    parent.stopPageClose(false);
                }
                if (e.data.code === 0) {
                    this.$alert(e.data.msg, '提示').then(e => {
                        location.reload();
                    });
                } else {
                    this.installLoading = false;
                    this.$message.error(e.data.msg);
                }
            });
        }
    },
});
</script>