<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 9:22
 */
defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '小程序配置';
$this->params['active_nav_group'] = 1;
$statics = Yii::$app->request->baseUrl . '/statics';
?>

<link href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/index.css" rel="stylesheet">
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['mch/store/index']) ?>">我的商城</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3" id="app">
    <a class="btn btn-primary backups mb-4" href="javascript:" data-url="<?=$urlManager->createUrl(['mch/store/backups'])?>">备份当前小程序配置</a>
    <div class="bg-white" style="min-width: 1250px;">
        <div class="block p-4">
            <div class="left">
                <div class="top" v-bind:style="{backgroundColor:t_bg,color:t_color}">首页</div>
                <div class="bottom" v-bind:style="{backgroundColor:b_bg,color:b_color}">

                </div>
            </div>
        </div>
        <div class="block">
            <div>
            </div>
        </div>
    </div>
</div>
<script>
    var app = new Vue({
        el:'#app',
        data:{
            t_bg:'#fff',
            t_color:'black',
            b_bg:'#fff',
            b_color:'#666',


        }
    });
</script>
<script>
    $(document).on('click','.backups',function(){
        var a = $(this);
        $.myConfirm({
            title:'提示',
            content:'是否备份小程序配置？',
            confirm:function(){
                $.myLoading();
                $.ajax({
                    url: a.data('url'),
                    type:'post',
                    dataType:'json',
                    data:{
                        _csrf :_csrf
                    },
                    success:function(res){
                        if(res.code == 0){
                            $.myAlert({
                                content:'备份成功'
                            });
                        }
                    },
                    complete:function(){
                        $.myLoadingHide();
                    }
                });
            }
        });
    });
</script>