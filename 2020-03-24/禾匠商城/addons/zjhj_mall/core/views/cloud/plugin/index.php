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

.plugin-list {
    margin: 0 auto;
    overflow-x: hidden;
}

.plugin-item {
    background: #fff;
    padding: 15px;
    margin-bottom: 10px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 0 1px rgba(0, 0, 0, .15);
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
</style>
<div id="app">
    <div class="plugin-list">
        <el-row style="" :gutter="10">
            <el-col v-for="(item, index) in list" :key="index" :xs="24" :sm="12" :md="8" :lg="6">
                <div class="plugin-item">
                    <div class="plugin-tag" v-if="item.tag">{{item.tag}}</div>
                    <div flex="box:first">
                        <div>
                            <div class="plugin-icon" :style="'background-image: url(' + item.pic_url + ')'"></div>
                        </div>
                        <div>
                            <div class="plugin-name">{{item.display_name}}</div>
                            <div class="plugin-price">￥{{item.price}}</div>
                            <div flex>
                                <div>
                                    <el-button size="small" type="primary" plain
                                               @click="showDetail(item)">查看详情
                                    </el-button>
                                </div>
                                <div v-if="item.installed" flex="cross:center" style="color: #909399;margin-left: 15px">
                                    已安装
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</div>
<script>
new Vue({
    el: '#app',
    data() {
        return {
            list: null,
            pagination: null,
        };
    },
    created() {
        request({
            params: {
                r: 'cloud/plugin/index'
            }
        }).then(e => {
            if (e.data.code === 0) {
                this.list = e.data.data.list;
                this.pagination = e.data.data.pagination;
            } else {
                this.$alert(e.data.msg, '提示', {
                    type: 'error'
                });
            }
        }).catch(e => {
        });
    },
    methods: {
        showDetail(item) {
            this.$navigate({r: 'cloud/plugin/detail', id: item.id});
        },
    },
});
</script>