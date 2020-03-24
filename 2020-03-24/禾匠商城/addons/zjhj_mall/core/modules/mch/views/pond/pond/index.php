<?php
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '九宫格抽奖设置';
?>
 <style>
    .mobile-box {
        width: 250px;
        height: 500px;
        background-image: url("<?=Yii::$app->request->baseUrl?>/statics/images/pond.png");
        background-size: cover;
        position: relative;
        font-size: .85rem;
        float: left;
        background-size: 100% 100%;
        -moz-background-size: 100% 100%;
        -webkit-background-size: 100% 100%
    }

    .mobile-box .mobile-screen {
        position: absolute;
        top: 257px;
        left: 26px;
        right: 13px;
        bottom: 50px;
        overflow-y: hidden;
    }

    .mobile-box .mobile-a {
        position: absolute;
        top: 0px;
        left: 0px;
        width:62px;
        height:56px;
        overflow-y: auto;
        text-align: center;
    }
    .mobile-box .mobile-b {
        position: absolute;
        top: 0px;
        left: 68px;
        width:62px;
        height:56px;
        overflow-y: auto;
        text-align: center;
    }
    .mobile-box .mobile-c {
        position: absolute;
        top: 0px;
        left: 135px;
        width:62px;
        height:56px;
        overflow-y: auto;
        text-align: center;
    }
    .mobile-box .mobile-d {
        position: absolute;
        top: 66px;
        left: 135px;
        width:62px;
        height:56px;
        overflow-y: auto;
        text-align: center;
    }
    .mobile-box .mobile-e {
        position: absolute;
        top: 132px;
        left: 135px;
        width:62px;
        height:56px;
        overflow-y: auto;
        text-align: center;
    }
    .mobile-box .mobile-f {
        position: absolute;
        top: 132px;
        left: 68px;
        width:62px;
        height:56px;
        overflow-y: auto;
        text-align: center;
    }
    .mobile-box .mobile-g {
        position: absolute;
        top: 132px;
        left: 0px;
        width:62px;
        height:56px;
        overflow-y: auto;
        text-align: center;
    }
    .mobile-box .mobile-h {
        position: absolute;
        top: 66px;
        left: 0px;
        width:62px;
        height:56px;
        overflow-y: auto;
        text-align: center;
    }

    .mobile-box .mobile-img {
        width:50px;
        height:50px;
    }
    body.dragging, body.dragging * {
        cursor: move !important;
    }

    .dragged {
        position: absolute;
        z-index: 2000;
        box-shadow: 2px 2px 1px rgba(0, 0, 0, .05);
    }

    .home-block {
        position: relative;
    }

    .home-block .block-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .home-block .block-name {
        color: #fff;
        padding: 6px;
        text-align: center;
        background: rgba(0, 0, 0, .2);
        opacity: .9;
    }

    .home-block:hover .block-name {
        background: rgba(0, 0, 0, .7);
        opacity: 1;
    }

    .home-block .block-img {
        width: 100%;
        height: auto;
    }

    .sortable-ghost {
        opacity: .3;
    }


    .edit-box .module-item {
        background: #fff;
        cursor: move;
        border: none;
        margin-bottom: 1px;
    }

    .edit-box .module-item {
    }

    .right-box {
        float: left;
        width: 80%;
    }

    .all-module-list {
        border: 1px solid #eee;
        border-radius: 5px;
    }

    .all-module-list .module-item {
        display: inline-block;
        border: 1px solid #eee;
        background: #fff;
        width: 80px;
        height: 80px;
        overflow: hidden;
        float: left;
        margin-right: 1rem;
        margin-bottom: 1rem;
    }

    .all-module-list .module-item:hover {
        border-color: #b8dcff;
    }

    .all-module-list .module-name {
        height: 50px;
        text-align: center;
        padding: 5px 0;
    }

    .all-module-list .module-option {
        text-align: center;
        border-top: 1px dashed #eee;
        height: 30px;
        line-height: 30px;
        display: block;
    }

    .all-module-list .edit {
        float: left;
        width: 50%;
    }
</style>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="clearfix" style="width:1340px">
            <div class="mobile-box">
                <div class="mobile-screen">
                    <div class="mobile-content">
                      <div class="mobile-a"><image class="mobile-img" src="<?=$list[0]['image_url']?>" alt=""></div> 
                      <div class="mobile-b"><image class="mobile-img" src="<?=$list[1]['image_url']?>" alt=""></div> 
                      <div class="mobile-c"><image class="mobile-img" src="<?=$list[2]['image_url']?>" alt=""></div> 
                      <div class="mobile-d"><image class="mobile-img" src="<?=$list[3]['image_url']?>" alt=""></div> 
                      <div class="mobile-e"><image class="mobile-img" src="<?=$list[4]['image_url']?>" alt=""></div> 
                      <div class="mobile-f"><image class="mobile-img" src="<?=$list[5]['image_url']?>" alt=""></div> 
                      <div class="mobile-g"><image class="mobile-img" src="<?=$list[6]['image_url']?>" alt=""></div>
                      <div class="mobile-h"><image class="mobile-img" src="<?=$list[7]['image_url']?>" alt=""></div> 
                    </div>
                </div>
            </div>

            <div class="right-box ml-2">
                <div class="panel mb-3 all-module-list">
                    <div class="panel-header">抽奖配置</div>
                    <div class="fs-sm text-muted">说明：抽奖链接可以放到导航图标、轮播图、魔方。</div>
                    <div class="panel-body">

                        <form class="form auto-form" method="post" autocomplete="off">
                            <div class="form-group row">
                                <div class="form-group-label col-sm-2 text-right">
                                    <label class="col-form-label required">中奖概率</label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">万分之</span>
                                        <input type="number" min="0" max="10000" step="1" class="form-control" name="probability" value="<?= $setting->probability ?>">
                                    </div>
                                    <div class="text-muted fs-sm">注：奖品设置的奖品数量越多，则该奖品中奖率越高</div>
                                    <div class="text-muted fs-sm">例如：总中奖率50%，奖品一1个，奖品二2个，奖品三3个，奖品四4个，....,奖品一中奖概率为50%*1/(1+2+3+4+...)</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-group-label col-3 text-right">
                                    <label class="col-form-label required">开始日期</label>
                                </div>
                                <div class="col-sm-5"> 
                                    <div class="input-group">
                                        <input class="form-control"
                                               id="start_time"
                                               name="start_time"
                                               value="<?= $setting->start_time ? date('Y-m-d H:i', $setting->start_time) : date('Y-m-d H:i') ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-group-label col-3 text-right">
                                    <label class="col-form-label required">结束日期</label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input class="form-control"
                                               id="end_time"
                                               name="end_time"
                                               value="<?= $setting->end_time ? date('Y-m-d H:i', $setting->end_time) : date('Y-m-d H:i') ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-group-label col-sm-2 text-right">
                                    <label class="col-form-label required">抽奖规则</label>
                                </div>
                                <div class="col-sm-10">
                                    <label class="radio-label">
                                        <input name="type" <?=$setting->type == 1 ? "checked" : ""?> value="1" type="radio" class="custom-control-input">
                                        <span class="label-icon"></span>
                                        <span class="label-text">一天<?= $setting->oppty ?>次</span>
                                    </label>

                                    <label class="radio-label">
                                        <input name="type" <?=$setting->type == 2 ? "checked" : ""?> value="2" type="radio" class="custom-control-input">
                                        <span class="label-icon"></span>
                                        <span class="label-text">一人<?= $setting->oppty ?>次</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-group-label col-sm-2 text-right">
                                    <label class="col-form-label required">抽奖次数</label>
                                </div>
                                <div class="col-sm-5">
                                    <input class="form-control" type="number" name="oppty" 
                                           value="<?= $setting->oppty ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-group-label col-sm-2 text-right">
                                    <label class="col-form-label">小程序标题</label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input class="form-control" name="title" 
                                           value="<?= $setting->title ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-group-label col-sm-2 text-right">
                                    <label class=" col-form-label">消耗积分</label>
                                </div>
                                <div class="col-sm-5">
                                    <input class="form-control" type="number" name="deplete_register" value="<?= $setting->deplete_register?>">
                                     <div class="text-muted fs-sm">每次抽奖消耗积分数</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-group-label col-sm-2 text-right">
                                    <label class=" col-form-label required">规则说明</label>
                                </div>
                                <div class="col-sm-5">
                                    <textarea class="form-control" rows="5" id="content"
                                        name="rule"><?= $setting->rule?></textarea>
                                </div>
                            </div>

                           <div class="form-group row">
                                <div class="form-group-label col-3 text-right">
                                    <label class="col-form-label">奖品设置</label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <a class="input-group-addon" href="<?= $urlManager->createUrl(['mch/pond/pond/edit']) ?>" style="background: #fff;color:#33aaff;">编辑奖品</a>
                                    </div>
                                </div>
                            </div> 

                            <div class="form-group row">
                                <div class="form-group-label col-3 text-right">
                                </div>
                                <div class="col-9">
                                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        $.datetimepicker.setLocale('zh');
        $('#start_time').datetimepicker({
            format: 'Y-m-d H:i',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $('#end_time').val() ? $('#end_time').val() : false
                })
            },
            timepicker: true,
        });
        $('#end_time').datetimepicker({
            format: 'Y-m-d H:i',
            onShow: function (ct) {
                this.setOptions({
                    minDate: $('#begin_time').val() ? $('#begin_time').val() : false
                })
            },
            timepicker: true,
        });
    })();
</script>

