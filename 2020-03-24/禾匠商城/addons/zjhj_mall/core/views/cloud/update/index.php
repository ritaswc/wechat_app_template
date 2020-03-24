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

.version-item {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px dashed #ebeef5;
}

.update-content p {
    margin: 0;
}
</style>
<div id="app">
    <div v-loading="loading">
        <template v-if="data">
            <el-card shadow="never" style="margin-bottom: 15px">当前系统版本: {{data.host_version}}</el-card>
            <el-card shadow="never" style="margin-bottom: 15px" v-if="data.next_version">
                <div style="margin-bottom: 15px">下一版本: {{data.next_version.version}}</div>
                <div style="margin-bottom: 15px">发布时间: {{data.next_version.add_time}}</div>
                <div style="margin-bottom: 15px" class="update-content" v-html="data.next_version.content"></div>
                <div>
                    <el-button style="margin-bottom: 5px" size="small" plain type="primary" :loading="updating"
                               @click="updateConfirmDialogVisible = true">{{updating?'更新中':'立即更新'}}
                    </el-button>
                    <div style="color: #E6A23C">更新过程请勿关闭或刷新页面！</div>
                </div>
            </el-card>
            <el-card shadow="never">
                <div slot="header">更新记录</div>
                <template v-if="data.list && data.list.length">
                    <div class="version-item" v-for="(item, index) in data.list">
                        <div style="margin-bottom: 15px">版本号: {{item.version}}</div>
                        <div style="margin-bottom: 15px">发布时间: {{item.add_time}}</div>
                        <div class="update-content" v-html="item.content"></div>
                    </div>
                </template>
                <div v-else style="color: #909399">暂无更新记录</div>
            </el-card>

            <el-dialog
                    v-if="data.next_version"
                    title="提示"
                    :visible.sync="updateConfirmDialogVisible"
                    width="600px">
                <div style="margin-bottom: 15px">确认升级到版本: {{data.next_version.version}}</div>
                <div>
                    <div style="margin-bottom: 15px">更新协议:</div>
                    <el-checkbox v-model="updateAgreement.backup">已经做好了相关文件和数据库的备份工作，并自愿承担更新所存在的风险</el-checkbox>
                </div>
                <div slot="footer">
                    <el-button @click="updateConfirmDialogVisible = false">取 消</el-button>
                    <el-button type="primary" @click="updateNow">确 定</el-button>
                </div>
            </el-dialog>
        </template>
    </div>
</div>
<script>
new Vue({
    el: '#app',
    data() {
        return {
            loading: false,
            updateConfirmDialogVisible: false,
            updateAgreement: {
                backup: false,
            },
            updating: false,
            data: null,
        };
    },
    created() {
        this.loading = true;
        request({
            params: {
                r: 'cloud/update/index'
            }
        }).then(e => {
            this.loading = false;
            if (e.data.code === 0) {
                this.data = e.data.data;
            } else {
                this.$alert(e.data.msg, '提示', {
                    type: 'error'
                });
            }
        }).catch(e => {
        });
    },
    methods: {
        updateNow() {
            if (!this.updateAgreement.backup) {
                this.$message.error('请先确认更新协议');
                return;
            }
            this.updateConfirmDialogVisible = false;
            this.update();
        },
        update() {
            this.updating = true;
            let stopContent = '系统更新中，请勿关闭或刷新页面！';
            window.onbeforeunload = function (event) {
                event.returnValue = stopContent;
            };
            if (parent && parent.stopPageClose) {
                parent.stopPageClose(true, stopContent);
            }
            request({
                params: {
                    r: 'cloud/update/update',
                },
                method: 'post',
                data: this.data.next_version,
            }).then(e => {
                window.onbeforeunload = null;
                if (parent && parent.stopPageClose) {
                    parent.stopPageClose(false);
                }
                if (e.data.code === 0) {
                    this.$alert('更新成功！', '提示').then(e => {
                        location.reload();
                    });
                } else {
                    this.$alert(e.data.msg, '提示').then(e => {
                        this.updating = false;
                    });
                }
            }).catch(e => {
            });
        },
    },
});
</script>