<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/18
 * Time: 11:41
 */
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '模板编辑';
?>
<link href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/diy.css?v=<?= rand(1, 999) ?>" rel="stylesheet">
<link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/themes.css">
<div class="panel mb-3" id="app" style="min-width: 1275px;">
    <div class="panel-header">
        <span><?= $this->title ?></span>
    </div>
    <div class="panel-body" style="padding: 0;">
        <form class="auto-form-diy">
            <input style="display: none" class="template_id" value="<?= $_GET['id'] ?>">
            <div class="d-flex flex-row" style="height: 57rem">
                <div class="temp-1 p-4">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-4" style="padding-right: 0;">
                            <label class="col-form-label required">模板名称</label>
                        </div>
                        <div class="col-sm-6" style="padding: 0;">
                            <input class="form-control" v-model="detail.name">
                        </div>
                    </div>
                    <?php if ($max_diy >= 0):?>
                    <div class="form-group row text-danger">
                        <div class="form-group-label col-sm-4" style="padding-right: 0;">
                            <label class="col-form-label">温馨提示：</label>
                        </div>
                        <div class="col-sm-6" style="padding: 0;">
                            <label class="col-form-label">最多添加<?= $max_diy ? $max_diy : 20 ?>个组件</label>
                        </div>
                    </div>
                    <?php endif;?>
                    <div class="pb-2" style="width: 22.6rem;">
                        <template v-for="(item,index) in modules_list">
                            <div class="modules-name">{{item.name}}</div>
                            <div class="d-flex flex-row flex-wrap"
                                 style="border-top: 1px solid #eee;border-left: 1px solid #eee;width: 100%;">
                                <template v-for="(value,key) in item.sub">
                                    <div class="modules" :data-type="value.type">
                                        <div>
                                            <img :src="icon + value.type + '.png'"
                                                 style="width: 2.85rem; height: 2.85rem;margin-bottom: 1rem;">
                                        </div>
                                        <div>{{value.name}}</div>
                                        <div v-if="value.type == 'ad' || value.type== 'float'"
                                             class="fs-sm text-danger">只能添加一个
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="temp-2 pt-4 pb-4 col">
                    <div style="height: 100%;">
                        <div class="d-flex justify-content-center" style="width: 100%;height: 100%;">
                            <div style="width:375px">
                                <div class="block">
                                    <div class="block-header">
                                        <img src="<?= $statics ?>/mch/images/phone-header.png">
                                    </div>
                                </div>
                                <div id="sortList">
                                    <template v-if="temp_list.length == 0">
                                        <div class="d-flex justify-content-center align-items-center block-empty">
                                            <div style="text-align: center">
                                                <img src="<?= $statics ?>/mch/images/empty.png">
                                                <div class="mt-4" style="color:#bbb">模板空空如也<br>快添加组件吧</div>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <template v-for="(item,index) in temp_list">
                                            <div :class="(index == temp_index && item.type != 'modal' && item.type != 'float') ? 'block active' : 'block'">
                                                <?= $this->render('temp.php'); ?>
                                                <template v-if="index == temp_index && item.type != 'modal' && item.type != 'float'">
                                                    <div class="block-handle block-handle-left">
                                                        <div class="handle delete" title="删除组件">
                                                            <img src="<?= $statics ?>/mch/images/x.png">
                                                        </div>
                                                        <div class="handle copy" title="复制组件" v-if="item.type != 'ad'">
                                                            <img src="<?= $statics ?>/mch/images/copy.png">
                                                        </div>
                                                    </div>
                                                    <div class="block-handle block-handle-right">
                                                        <div class="handle up" v-if="index > 0" title="上移组件">
                                                            <img src="<?= $statics ?>/mch/images/up.png">
                                                        </div>
                                                        <div class="handle down" v-if="index < temp_list.length-1"
                                                             title="下移组件">
                                                            <img src="<?= $statics ?>/mch/images/up.png">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="temp-3 pt-4">
                    <div class="pr-2 pl-2 pb-4" style="width: 100%;">
                        <template v-for="(item,index) in temp_list">
                            <template v-if="temp_index == index">
                                <template v-if="item.type == 'search'">
                                    <?= $this->render('param/search.php'); ?>
                                </template>
                                <template v-if="item.type == 'banner'">
                                    <?= $this->render('param/banner.php'); ?>
                                </template>
                                <template v-if="item.type == 'nav'">
                                    <?= $this->render('param/nav.php'); ?>
                                </template>
                                <template v-if="item.type == 'notice'">
                                    <?= $this->render('param/notice.php'); ?>
                                </template>
                                <template v-if="item.type == 'topic'">
                                    <?= $this->render('param/topic.php'); ?>
                                </template>
                                <template v-if="item.type == 'link'">
                                    <?= $this->render('param/link.php'); ?>
                                </template>
                                <template v-if="item.type == 'line'">
                                    <?= $this->render('param/line.php'); ?>
                                </template>
                                <template v-if="item.type == 'ad'">
                                    <?= $this->render('param/ad.php'); ?>
                                </template>
                                <template v-if="item.type == 'rubik'">
                                    <?= $this->render('param/rubik.php'); ?>
                                </template>
                                <template v-if="item.type == 'video'">
                                    <?= $this->render('param/video.php'); ?>
                                </template>
                                <template v-if="item.type == 'coupon'">
                                    <?= $this->render('param/coupon.php'); ?>
                                </template>
                                <template v-if="item.type == 'goods'">
                                    <?= $this->render('param/goods.php'); ?>
                                </template>
                                <template v-if="item.type == 'time'">
                                    <?= $this->render('param/time.php'); ?>
                                </template>
                                <template v-if="item.type == 'miaosha'">
                                    <?= $this->render('param/miaosha.php'); ?>
                                </template>
                                <template v-if="item.type == 'pintuan'">
                                    <?= $this->render('param/pintuan.php'); ?>
                                </template>
                                <template v-if="item.type == 'bargain'">
                                    <?= $this->render('param/bargain.php'); ?>
                                </template>
                                <template v-if="item.type == 'book'">
                                    <?= $this->render('param/book.php'); ?>
                                </template>
                                <template v-if="item.type == 'shop'">
                                    <?= $this->render('param/shop.php'); ?>
                                </template>
                                <template v-if="item.type == 'lottery'">
                                    <?= $this->render('param/lottery.php'); ?>
                                </template>
                                <template v-if="item.type == 'mch'">
                                    <?= $this->render('param/mch.php'); ?>
                                </template>
                                <template v-if="item.type == 'integral'">
                                    <?= $this->render('param/integral.php'); ?>
                                </template>
                                <template v-if="item.type == 'float'">
                                    <?= $this->render('param/float.php'); ?>
                                </template>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
            <div class="publish-bar">
                <a href="javascript:" class="btn btn-primary auto-form-btn-diy" data-type="0">保存</a>
                <a href="javascript:" class="btn btn-primary auto-form-btn-diy" data-type="1">另存为</a>
            </div>
        </form>
        <?= $this->render('tempfile/modal.php'); ?>
    </div>
</div>
<script>
    var CATURL = "<?= $urlManager->createUrl(['mch/diy/diy/get-cat'])?>";
    var GOODSURL = "<?= $urlManager->createUrl(['mch/diy/diy/get-goods'])?>";
    var RUBIKURL = "<?= $urlManager->createUrl(['mch/diy/diy/get-rubik'])?>";
    var SUBMITURL = "<?= $urlManager->createUrl(['mch/diy/diy/edit'])?>";
    var GETURL = "<?= $urlManager->createUrl(['mch/diy/diy/get-nav-banner'])?>";
    var Url = "<?=\Yii::$app->request->hostInfo . $statics?>";
    var MAX_DIY = <?= $max_diy ? $max_diy : 20 ?>;
</script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/Sortable.min.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/colorpicker.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/diy.js?v=<?= rand(100, 999) ?>"></script>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=OV7BZ-ZT3HP-6W3DE-LKHM3-RSYRV-ULFZV"></script>
