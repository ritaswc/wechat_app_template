<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/10/11
 * Time: 11:53
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<div class="modal fade bd-example-modal-lg" id="catModal" aria-labelledby="myLargeModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="panel" v-if="temp_index != -1">
            <div class="panel-header">
                <span v-if="temp_list[temp_index].type == 'mch'">选择商户</span>
                <span v-else>选择分类</span>
                <a href="javascript:" class="panel-close" data-dismiss="modal">&times;</a>
            </div>
            <div class="panel-body" style="max-height: 700px;overflow-y: auto">
                <form class="cat-form" :action="modal_list.page_url">
                    <div class="input-group mb-2">
                        <input class="form-control" name="keyword" placeholder="根据名称搜索">
                        <a class="btn input-group-addon cat-form-btn">搜索</a>
                    </div>
                </form>
                <table class="table table-hover">
                    <tr>
                        <td>
                            <label class="checkbox-label checkbox-block" style="width: calc(100% - 2rem)">
                                <input type="checkbox" class="checkbox-all">
                                <span class="label-icon"></span>
                                <span class="label-text" v-if="temp_list[temp_index].type == 'mch'">商户名称</span>
                                <span class="label-text" v-else>分类名称</span>
                            </label>
                        </td>
                        <td>数量</td>
                    </tr>
                    <template v-for="(item,index) in modal_list.cat_list">
                        <tr>
                            <td>
                                <label class="checkbox-label checkbox-block" style="width: calc(100% - 2rem)">
                                    <input class="checkbox-one" type="checkbox" :data-index="index">
                                    <span class="label-icon"></span>
                                    <span class="label-text">{{item.name}}</span>
                                </label>
                            </td>
                            <td>{{item.goods_count}}</td>
                        </tr>
                    </template>
                </table>
                <?= $this->render('nav.php'); ?>
                <div class="text-center mt-2">
                    <a class="btn btn-primary cat-btn" href="javascript:">确定</a>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="goodsModal" aria-labelledby="myLargeModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="panel" v-if="temp_index != -1">
            <div class="panel-header">
                <span v-if="temp_list[temp_index].type == 'shop'">选择门店</span>
                <span v-else-if="temp_list[temp_index].type == 'topic'">选择专题</span>
                <span v-else>选择商品</span>
                <a href="javascript:" class="panel-close" data-dismiss="modal">&times;</a>
            </div>
            <div class="panel-body" style="max-height: 700px;overflow-y: auto">
                <form class="goods-form" :action="modal_list.page_url">
                    <div class="input-group mb-2">
                        <input class="form-control" name="keyword" placeholder="根据名称搜索">
                        <a class="btn input-group-addon goods-form-btn">搜索</a>
                    </div>
                </form>
                <table class="table table-hover" v-if="modal_list.goods_list">
                    <tr>
                        <td>
                            <label class="checkbox-label checkbox-block" style="width: calc(100% - 2rem)">
                                <input type="checkbox" class="checkbox-all">
                                <span class="label-icon"></span>
                                <span class="label-text" v-if="temp_list[temp_index].type == 'shop'">门店名称</span>
                                <span class="label-text" v-else>名称</span>
                            </label>
                        </td>
                        <td v-if="temp_list[temp_index].type == 'miaosha'">
                            <span class="label-text">开始时间</span>
                        </td>
                        <td v-if="temp_list[temp_index].type == 'bargain'">
                            <span class="label-text">活动时间</span>
                        </td>
                    </tr>
                    <template v-for="(item,index) in modal_list.goods_list">
                        <tr>
                            <td>
                                <label class="checkbox-label checkbox-block" style="width: calc(100% - 2rem)">
                                    <input class="checkbox-one" type="checkbox" :data-index="index">
                                    <span class="label-icon"></span>
                                    <span class="label-text text-more" style="width: 400px;">{{item.name}}</span>
                                </label>
                            </td>
                            <td v-if="temp_list[temp_index].type == 'miaosha'">
                                <span class="label-text">{{item.open_date + ' ' + item.start_time + ':00'}}</span>
                            </td>
                            <td v-if="temp_list[temp_index].type == 'bargain'">
                                <span class="label-text">{{item.begin_time_text}}~{{item.end_time_text}}</span>
                            </td>
                        </tr>
                    </template>
                </table>
                <?= $this->render('nav.php'); ?>
                <div class="text-center mt-2">
                    <a class="btn btn-primary goods-btn" href="javascript:">确定</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="adModal" aria-labelledby="myLargeModalLabel" data-backdrop="static"
     style="z-index: 1040;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="panel">
            <div class="panel-header">
                <span>弹窗广告设置</span>
                <a href="javascript:" class="panel-close" data-dismiss="modal">&times;</a>
            </div>
            <div class="panel-body" style="max-height: 700px;overflow-y: auto;padding: 24px 0 24px 48px;">
                <div class="d-flex" v-for="(item,index) in temp_list"
                     v-if="index == temp_index && item.type == 'modal'">
                    <div class="ad-modal-block d-flex align-items-center justify-content-center">
                        <div class="ad-modal-img"
                             :style="{'backgroundColor':(item.param.list.length ? item.param.list[0].pic_url:'#fff')}">
                            <div
                                :style="{'backgroundImage':'url('+(item.param.list.length ? item.param.list[0].pic_url : '')+')'}">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex p-2">
                            <div class="d-flex col-4 align-items-center"><span>是否开启</span></div>
                            <div class="input-block col-6">
                                <template v-if="item.param.show == 1">
                                    <div class="switch d-flex justify-content-center" data-param="show" data-value="1">
                                        <div style="width: 50%;text-align: center"></div>
                                        <div style="width: 50%;text-align: center">开</div>
                                        <div class="switch-one"></div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="switch d-flex justify-content-center"
                                         style="background-color: #eeeeee;color:#353535"
                                         data-param="show" data-value="0">
                                        <div style="width: 50%;text-align: center">关</div>
                                        <div style="width: 50%;text-align: center"></div>
                                        <div class="switch-one" style="left: 24px;"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="p-2">图片最大高度700px;图片最大宽度650px;</div>
                        <template v-for="(value, key) in item.param.list">
                            <div class="modal-handle" style="position: relative;padding-right: 40px;">
                                <div class="d-flex p-2 param-key" :data-key="key">
                                    <div class="notice-block">
                                        <div class="img d-flex justify-content-center align-items-center">
                                            <div class="upload-group">
                                                <input class="form-control file-input model-param"
                                                       style="display: none;"
                                                       data-param="pic_url" v-model="value.pic_url">
                                                <template v-if="value.pic_url">
                                                    <div class="upload-preview select-file text-center upload-preview">
                                                        <span class="upload-preview-tip" hidden>650&times;700</span>
                                                        <img class="upload-preview-img" :src="value.pic_url">
                                                    </div>
                                                </template>
                                                <template v-else>
                                                    <div class="select-file" style="color: #5CB3FD;cursor: pointer">
                                                        +添加图片
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="d-flex p-2">
                                            <div class=""><span>链接页面</span></div>
                                            <div class="input-block col-9">
                                                <div class="page-link-input">
                                                    <input class="form-control link-name model-param pick-link-btn"
                                                           data-param="page_name"
                                                           readonly v-model="value.page_name" style="cursor: pointer">
                                                    <input class="form-control link-input model-param pick-link-btn"
                                                           data-param="url" type="hidden"
                                                           readonly v-model="value.url" style="cursor: pointer">
                                                    <input class="link-open-type model-param" data-param="open_type"
                                                           v-model="value.open_type" type="hidden">
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="block-handle" :data-key="key" style="display: none;right:0;">
                                    <div class="handle modal-delete">
                                        <img src="<?= $statics ?>/mch/images/x.png">
                                    </div>
                                    <div class="handle list-up" v-if="key > 0">
                                        <img src="<?= $statics ?>/mch/images/up.png">
                                    </div>
                                    <div class="handle list-down" style="transform: rotate(180deg);"
                                         v-if="key < item.param.list.length-1">
                                        <img src="<?= $statics ?>/mch/images/up.png">
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-if="item.param.list.length < 3">
                            <div class="modal-add list-add d-flex justify-content-center">+添加图片</div>
                        </template>


                        <div class="d-flex p-2">
                            <div class="d-flex col-3 align-items-center"><span>状态</span></div>
                            <label class="radio-label radio-block"
                                   data-param="status" data-item="0">
                                <input type="radio" name="status" :checked="item.param.status == 0 ? true : ''">
                                <span class="label-icon"></span>
                                <span class="label-text">仅首次</span>
                            </label>
                            <label class="radio-label radio-block"
                                   data-param="status" data-item="1">
                                <input type="radio" name="status" :checked="item.param.status == 1 ? true : ''">
                                <span class="label-icon"></span>
                                <span class="label-text">每次</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <a class="btn btn-primary ad-modal-btn" href="javascript:">确定</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="nbModal" aria-labelledby="myLargeModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="panel">
            <div class="panel-header">
                <span>{{modal_list.title}}</span>
                <a href="javascript:" class="panel-close" data-dismiss="modal">&times;</a>
            </div>
            <div class="panel-body" style="max-height: 700px;overflow-y: auto">
                <table class="table table-hover">
                    <tr>
                        <td>
                            <label class="checkbox-label checkbox-block" style="width: calc(100% - 2rem)">
                                <input type="checkbox" class="checkbox-all">
                                <span class="label-icon"></span>
                                <span class="label-text">ID</span>
                            </label>
                        </td>
                        <td>名称</td>
                        <td>页面链接</td>
                    </tr>
                    <template v-for="(item,index) in modal_list.list">
                        <tr>
                            <td>
                                <label class="checkbox-label checkbox-block" style="width: calc(100% - 2rem)">
                                    <input class="checkbox-one" type="checkbox" :data-index="index">
                                    <span class="label-icon"></span>
                                    <span class="label-text">{{item.id}}</span>
                                </label>
                            </td>
                            <td>{{item.name}}</td>
                            <td>{{item.url}}</td>
                        </tr>
                    </template>
                </table>
                <?= $this->render('nav.php'); ?>
                <div class="text-center mt-2">
                    <a class="btn btn-primary nav-banner-btn" :data-key="modal_list.type" href="javascript:">确定</a>
                </div>
            </div>
        </div>
    </div>
</div>