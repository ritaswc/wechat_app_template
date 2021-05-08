<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '上传测试';
$this->params['active_nav_group'] = 1;
?>
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
    <form class="form auto-submit-form" method="post" autocomplete="off"
          data-return="<?= $urlManager->createUrl(['mch/store/upload']) ?>">
        <div class="form-title"><?= $this->title ?></div>
        <div class="form-body">
            <div class="pic" v-for="(item,i) in pic_list">shangchuan-{{i}}</div>
            <a class="add-pic">ADD</a>

            <div class="image"></div>

            <?= \app\widgets\ImageUpload::widget([
                'name' => 'test',
                'value' => '',
            ]) ?>
            <?= \app\widgets\ImageUpload::widget([
                'name' => 'test',
                'value' => '',
            ]) ?>
        </div>

    </form>

</div>
<script>


    var app = new Vue({
        el: "#app",
        data: {
            pic_list: [{}]
        },
    });

    initPickFile();
    $(".add-pic").on("click", function () {
        app.pic_list.push({});
        setTimeout(function () {
            initPickFile();
        }, 0)
    });

    function initPickFile() {
        $(".pic").pickImage({
            success: function (res, $this, _this) {

                console.log(res);
            }
        });
    }

</script>