<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 10:49
 */

$urlManager = Yii::$app->urlManager;
$this->title = '秒杀商品编辑';
$returnUrl = Yii::$app->request->referrer;
?>

<style>
    form .form-group .col-3 {
        -webkit-box-flex: 0;
        -webkit-flex: 0 0 160px;
        -ms-flex: 0 0 160px;
        flex: 0 0 160px;
        max-width: 160px;
        width: 160px;
    }
</style>


<div id="one_menu_bar">
    <div id="tab_bar">
        <ul>
            <li class="tab_bar_item" id="tab2" onclick="myclick(2)" style="background-color: #eeeeee">
                分销价设置
            </li>
            <li class="tab_bar_item" <?= get_plugin_type() == 2 ? 'hidden' : '' ?> id="tab3" onclick="myclick(3)">
                会员价设置
            </li>
        </ul>
    </div>
    <div id="page">
        <form class="auto-form" method="post" return="<?= $returnUrl ?>">

            <!--多规格分销价-->
            <div class="tab_css" id="tab2_content" style="display: block">
                <div>
                    <?= $this->render('/layouts/attrs/attr_share_price', [
                        'goods' => $goods_share,
                        'attr' => $goods['attr']
                    ]) ?>
                </div>
            </div>

            <!--多规格会员价-->
            <div class="tab_css" id="tab3_content">
                <div>
                    <?= $this->render('/layouts/attrs/attr_member_price', [
                        'levelList' => $levelList,
                        'goods' => $goods_share,
                        'attr' => $goods['attr']
                    ]) ?>
                </div>
            </div>

            <div style="margin-left: 0;" class="form-group row text-center">
                <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                <input type="button" class="btn btn-default ml-4"
                       name="Submit" onclick="javascript:history.back(-1);" value="返回">
            </div>
        </form>
    </div>
</div>

<?= $this->render('/layouts/attrs/common', [
    'page_type' => 'MIAOSHA',
    'goods' => $goods,
    'level_list' => $levelList,
]) ?>
