<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 13:41
 */
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;
use \app\models\Option;

/* @var \app\models\Printer $model */

$urlManager = Yii::$app->urlManager;
$this->title = '打印机编辑';
$this->params['active_nav_group'] = 13;
$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl) {
    $returnUrl = $urlManager->createUrl(['mch/printer/board']);
}
?>
<style>
    .module-list {
        width: 20rem;
        min-height: 32rem;
        border: 1px solid #e3e3e3;
        padding: .5rem;
        margin-right: 1rem;
        position: relative;
        list-style: disc;
        overflow-x: hidden;
        display: inline-block;
        vertical-align: top;
    }

    .block-title {
        float: left;
        width: 20rem;
        margin-right: 1rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 1rem;
    }

    .module-item {
        width: 100%;
        min-height: 50px;
        box-shadow: 0 0 10px 2px #ddd;
        cursor: pointer;
        margin-bottom:10px;
        padding:5px;
    }
    .board-list{

    }
    .board{
        display: inline-block;
        margin-right:10px;
        margin-bottom:10px;
        padding:5px;
        border-radius: 5px;
        background-color: #e3e3e3;
        cursor: pointer;
    }
</style>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['mch/store/index']) ?>">我的商城</a>
            <a class="breadcrumb-item" href="<?= $returnUrl ?>">打印机模板库</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>
<div class="main-body p-3" id="app">
    <form class="form auto-submit-form" method="post" autocomplete="off" data-return="<?= $returnUrl ?>">
        <div class="form-title"><?= $this->title ?></div>
        <div class="form-body">

            <div class="clearfix">
                <div class="block-title">模板设置</div>
                <div class="block-title">可选组件</div>
                <div class="block-title">小票预览</div>
            </div>
            <div class="clearfix">
                <div class="module-list" style="max-height: 540px;overflow-y: scroll;">
                    <div class="module-item board-add" :data-index="i" v-for="(item,i) in edit_board">
                        <div v-if="item.name=='head'" :style="{fontSize:item.is_font,textAlign:item.is_center}">
                            {{item.value}}
                        </div>
                        <div v-if="item.name == 'goods'" flex="dir:left box:mean">
                            <div class="flex-grow-1">名称</div>
                            <div class="flex-grow-1">单价</div>
                            <div class="flex-grow-1">数量</div>
                            <div class="flex-grow-1">金额</div>
                        </div>
                        <div v-if="item.name_1 == 'block'">
                            {{item.value}}:订单总金额
                        </div>
                    </div>
                </div>
                <div class="module-list" style="max-height: 540px;overflow-y: scroll;">
                    <div :hidden="type!=1?true:false" class="board-list flex-row">
                        <div class="board flex-grow-1"
                             v-for="(item,i) in board_list" :data-index="i">
                            {{item.default}}
                        </div>
                    </div>
                    <div :hidden="type!=2?true:false" class="board-list flex-row">
                        <div class="board-add text-center" data-index="-1"><a href="javascript:">[+返回选择]</a></div>
                        <div v-if="edit.name=='head'">
                            <div class="form-group row">
                                <div class="col-4 text-right">
                                    <label class=" col-form-label">是否放大</label>
                                </div>
                                <div class="col-8">
                                    <div class="pt-1">
                                        <label class="custom-control custom-radio">
                                            <input id="radio1"
                                                   value="32px" :checked="edit.is_font == '32px'?true:false" v-model="edit.is_font"
                                                   name="is_font" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">是</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input id="radio2"
                                                   value="16px" :checked="edit.is_font=='32px'?false:true" v-model="edit.is_font"
                                                   name="is_font" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">否</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-4 text-right">
                                    <label class=" col-form-label">是否居中</label>
                                </div>
                                <div class="col-8">
                                    <div class="pt-1">
                                        <label class="custom-control custom-radio">
                                            <input id="radio1"
                                                   value="center" :checked="edit.is_center=='center'?true:false" v-model="edit.is_center"
                                                   name="is_center" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">是</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input id="radio2"
                                                   value="left" :checked="edit.is_center=='center'?false:true" v-model="edit.is_center"
                                                   name="is_center" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">否</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="module-list" style="max-height: 540px;overflow-y: scroll;">

                </div>
            </div>


            <div class="form-group row">
                <div class="col-9 offset-sm-3">
                    <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                    <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                    <a class="btn btn-primary submit-btn" href="javascript:">保存</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    var app = new Vue({
        el: "#app",
        data: {
            type: 1,
            board_list:<?=$board_list?>,
            edit_board:[],
            edit:"",
            index:0
        }
    });
</script>
<script>
    $(document).on('click','.board-add',function(){
        var index = $(this).data('index');
        if(index != -1){
            app.index = index;
            app.edit = app.edit_board[app.index];
            app.type = 2;
        }else{
            app.type = 1;
        }
    });
    $(document).on('click','.board',function(){
        var index = $(this).data('index');
        app.edit_board.push(app.board_list[index])
    });
</script>
