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
</style>
<div id="app">
    <div class="container">
        <el-card shadow="never">
            <div slot="header" class="clearfix">
                <span class="card-header"><?= $this->title ?></span>
            </div>
            <div>
                <?php if ($code == 0): ?>
                    <div class="info-row">
                        <span class="row-label">授权状态</span>
                        <span style="color: #67C23A">已授权</span>
                    </div>
                    <div class="info-row">
                        <span class="row-label">站点名称</span>
                        <span><?= $data['host']['name'] ?></span>
                    </div>
                    <div class="info-row">
                        <span class="row-label">授权站点</span>
                        <span>
                            <?= $data['host']['protocol'] . $data['host']['domain'] ?>
                            <el-button type="text" @click="testSite" :loading="loading">云服务检测</el-button>
                            <template v-if="siteTestResult !== null">
                                <span v-if="siteTestResult" style="color: #67C23A">云服务正常</span>
                                <span v-else style="color: #F56C6C">云服务出错，<a href="javascript:"
                                                                             @click="showSiteTestDetail">查看详情</a></span>
                            </template>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="row-label">授权IP(<?= count($data['host']['host_ips']) ?>)</span>
                        <span>
                    <?php foreach ($data['host']['host_ips'] as $item): ?>
                        <span class="ip-item"><?= $item['ip'] ?></span>
                    <?php endforeach; ?>
                </span>
                    </div>
                <?php else: ?>
                    <div class="info-row">
                    <div class="info-row">
                        <span class="row-label">授权状态</span>
                        <span style="color: #F56C6C">未授权</span>
                    </div>
                        <span class="row-label">授权状态</span>
                        <span><?= $msg ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </el-card>
    </div>
</div>
<script>
const app = new Vue({
    el: '#app',
    data() {
        return {
            loading: false,
            siteTestResult: null,
            siteTestDetail: null,
        };
    },
    created() {
    },
    methods: {
        testSite() {
            this.loading = true;
            this.siteTestResult = null;
            this.siteTestDetail = null;
            client({
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
                } else {
                    this.siteTestResult = false;
                    this.siteTestDetail = e;
                }
            });
        },
        showSiteTestDetail() {
            let content = '';
            if (this.siteTestDetail.desc) {
                content += `<div>${this.siteTestDetail.desc}</div>`;
                content += `<div>预期返回: ${this.siteTestDetail.expect}</div>`;
                content += `<div>实际返回: ${this.siteTestDetail.body}</div>`;
            } else if (this.siteTestDetail) {
                content += `<div>${this.siteTestDetail}</div>`;
            } else {
                content = '连接云服务器出错。';
            }
            this.$alert(content, '云服务检测结果:', {
                dangerouslyUseHTMLString: true
            });
        },
    },
});
</script>
