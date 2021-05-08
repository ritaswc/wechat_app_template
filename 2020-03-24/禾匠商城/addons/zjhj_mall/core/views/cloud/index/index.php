<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/23 14:12
 */
$this->title = '站点授权信息';
?>
<style>
.container {
    max-width: 800px;
    margin: 20px auto;
}

.card-header {
    font-weight: bold;
}

.info-row {
    margin: 15px 0;
}

.row-label {
    display: inline-block;
    min-width: 80px;
}

.ip-item {
    display: inline-block;
    margin-right: 20px;
}

.my-table {
    width: 100%;
}

.my-table tr td {
    padding: 12px 0;
    vertical-align: top;
}

.my-table tr td:first-child {
    width: 100px;
}

</style>
<div id="app">
    <div class="container">
        <el-card shadow="never">
            <div slot="header" class="clearfix">
                <span class="card-header"><?= $this->title ?></span>
            </div>
            <div>
                <table class="my-table">
                    <?php if ($code == 0) : ?>
                        <tr>
                            <td>授权状态</td>
                            <td><span style="color: #67C23A">已授权</span></td>
                        </tr>
                        <tr>
                            <td>站点名称</td>
                            <td><?= $data['host']['name'] ?></td>
                        </tr>
                        <tr>
                            <td>站点域名</td>
                            <td><?= $data['host']['protocol'] . $data['host']['domain'] ?></td>
                        </tr>
                        <tr>
                            <td>授权IP</td>
                            <td>
                                <?php foreach ($data['host']['host_ips'] as $item) : ?>
                                    <span class="ip-item"><?= $item['ip'] ?></span>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <?php if ($localDomain !== $data['host']['domain']) : ?>
                            <tr>
                                <td>授权错误</td>
                                <td style="color: #F56C6C">授权的域名与当前访问的域名不一致，将导致云服务无法正常使用，请立即
                                    <el-button type="text" style="margin: -12px 0"
                                               @click="setAuthInfoDialogVisible = !setAuthInfoDialogVisible">设置站点域名
                                    </el-button>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>服务状态</td>
                            <td>
                                <template v-if="loading">检测中...</template>
                                <template v-if="siteTestResult !== null">
                                    <span v-if="siteTestResult" style="color: #67C23A">云服务正常</span>
                                    <span v-else style="color: #F56C6C">{{siteTestContent}}</span>
                                </template>
                            </td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td>授权状态</td>
                            <td><span style="color: #F56C6C">未授权</span></td>
                        </tr>
                        <tr>
                            <td>详细信息</td>
                            <td><?= $msg ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </el-card>
    </div>

    <el-dialog title="设置站点域名" :visible.sync="setAuthInfoDialogVisible">
        <el-form :model="form" :rules="rules" ref="form" onsubmit="return false">
            <el-form-item label="站点域名" prop="domain">
                <el-input v-model="form.domain"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button :loading="submitLoading" type="primary" @click="submit('form')">保存</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>

</div>
<script>
const app = new Vue({
    el: '#app',
    data() {
        return {
            setAuthInfoDialogVisible: false,
            loading: false,
            siteTestResult: null,
            siteTestDetail: null,
            siteTestContent: null,
            submitLoading: false,
            form: {
                domain: '',
            },
            rules: {
                domain: [
                    {required: true, message: '请填写域名。'}
                ]
            }
        };
    },
    created() {
        this.testSite();
    },
    methods: {
        testSite() {
            this.loading = true;
            this.siteTestResult = null;
            this.siteTestDetail = null;
            request({
                url: '<?=$remoteTestSiteUrl?>',
                method: 'post',
                data: {
                    url: '<?=$localTestSiteUrl?>',
                }
            }).then(e => {
                this.loading = false;
                if (e.data.code === 0) {
                    this.siteTestResult = true;
                } else if (e.data.code === 1) {
                    this.siteTestResult = false;
                    this.siteTestDetail = e.data.data;
                    this.setSiteTestDetail();
                } else {
                    this.siteTestResult = false;
                    this.siteTestDetail = e;
                    this.setSiteTestDetail();
                }
            });
        },
        setSiteTestDetail() {
            let content = '';
            if (this.siteTestDetail.desc) {
                content += `${this.siteTestDetail.desc}\n`;
                content += `预期返回: ${this.siteTestDetail.expect}\n`;
                content += `实际返回: ${this.siteTestDetail.body}\n`;
            } else if (this.siteTestDetail) {
                content += `${this.siteTestDetail}\n`;
            } else {
                content = '连接云服务器出错。';
            }
            this.siteTestContent = content;
        },
        submit(formName) {
            this.$refs[formName].validate(valid => {
                if (valid) {
                    this.submitLoading = true;
                    request({
                        params: {
                            r: 'cloud/index/update-auth-info'
                        },
                        method: 'post',
                        data: this.form,
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.setAuthInfoDialogVisible = false;
                            this.$alert(e.data.msg, '提示', {
                                confirmButtonText: '确定',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        } else {
                            this.submitLoading = false;
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                    });
                } else {
                }
            });
        }
    },
});
</script>
