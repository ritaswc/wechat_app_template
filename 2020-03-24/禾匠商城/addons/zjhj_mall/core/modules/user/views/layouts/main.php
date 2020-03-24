<?php
defined('YII_ENV') or exit('Access Denied');

use app\models\AdminPermission;

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 * @var \yii\web\View $this
 * @var \app\models\Admin $admin
 */
$urlManager = Yii::$app->urlManager;

$admin = null;
$admin_permission_list = [];
$current_url = Yii::$app->request->absoluteUrl;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title><?= $this->title ?></title>
    <link href="//at.alicdn.com/t/font_353057_prsw3pcf1in.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/user/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/user/css/jquery.datetimepicker.min.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/css/flex.css?version=<?= $version ?>" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/css/common.css?version=<?= $version ?>" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/user/css/common.v2.css?version=<?= $version ?>"
          rel="stylesheet">

    <script>var _csrf = "<?=Yii::$app->request->csrfToken?>";</script>
    <script>var _upload_url = "<?=Yii::$app->urlManager->createUrl(['upload/file','mch_id'=>Yii::$app->user->id])?>";</script>
    <script>var _district_data_url = "<?=Yii::$app->urlManager->createUrl(['api/default/district', 'store_id' => $this->context->store->id])?>";</script>

    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/jquery.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/vue.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/tether.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/bootstrap.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/plupload.full.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/jquery.datetimepicker.full.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/js/common.js?version=<?= $version ?>"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/common.v2.js?version=<?= $version ?>"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/clipboard.js"></script>
</head>
<body>
<!-- 文件选择模态框 Modal -->
<div class="modal fade" id="file_select_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="panel">
            <div class="panel-header">
                <span>选择文件</span>
                <a href="javascript:" class="panel-close" data-dismiss="modal">&times;</a>
            </div>
            <div class="panel-body">
                <div class="file-list"></div>
                <div class="file-loading text-center" style="display: none">
                    <img style="height: 1.14286rem;width: 1.14286rem"
                         src="<?= Yii::$app->request->baseUrl ?>/statics/images/loading-2.svg">
                </div>
                <div class="text-center">
                    <a style="display: none" href="javascript:" class="file-more">加载更多</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 地区选择模态框 -->
<div class="modal fade" id="district_pick_modal">
    <div class="modal-dialog">
        <div class="panel">
            <div class="panel-header">
                <span>选择地区</span>
                <a href="javascript:" class="panel-close" data-dismiss="modal">&times;</a>
            </div>
            <div class="panel-body">
                <table class="w-100">
                    <colgroup>
                        <col style="width: 33.333333%">
                        <col style="width: 33.333333%">
                        <col style="width: 33.333333%">
                    </colgroup>
                    <tr>
                        <td>省</td>
                        <td>市</td>
                        <td>县/区</td>
                    </tr>
                    <tr>
                        <td>
                            <select v-model="province_id" v-on:change="provinceChange" class="form-control">
                                <option :value="item.id" v-for="(item,index) in province_list">{{item.name}}</option>
                            </select>
                        </td>
                        <td>
                            <select v-model="city_id" v-on:change="cityChange" class="form-control">
                                <option :value="item.id" v-for="(item,index) in city_list">{{item.name}}</option>
                            </select>
                        </td>
                        <td>
                            <select v-model="district_id" v-on:change="districtChange" class="form-control">
                                <option :value="item.id" v-for="(item,index) in district_list">{{item.name}}</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="panel-footer">
                <a class="btn btn-primary district-confirm-btn" href="javascript:">确定</a>
            </div>
        </div>
    </div>
</div>

<?php
$menu_list = $this->context->getMenuList();
$route = Yii::$app->requestedRoute;
$current_menu = getCurrentMenu($menu_list, $route);
function activeMenu($item, $route)
{
    if (isset($item['route']) && ($item['route'] == $route || (is_array($item['sub']) && in_array($route, $item['sub'])))) {
        return 'active';
    }
    if (isset($item['list']) && is_array($item['list'])) {
        foreach ($item['list'] as $sub_item) {
            $active = activeMenu($sub_item, $route);
            if ($active != '') {
                return $active;
            }
        }
    }
    return '';
}

function getCurrentMenu($menu_list, $route)
{
    foreach ($menu_list as $item) {
        if (isset($item['route']) && ($item['route'] == $route || (is_array($item['sub']) && in_array($route, $item['sub'])))) {
            return $item;
        }
        if (isset($item['list']) && is_array($item['list'])) {
            foreach ($item['list'] as $sub_item) {
                if (isset($sub_item['route']) && ($sub_item['route'] == $route || (is_array($sub_item['sub']) && in_array($route, $sub_item['sub'])))) {
                    return $item;
                }
                if (isset($sub_item['list']) && is_array($sub_item['list'])) {
                    foreach ($sub_item['list'] as $sub_sub_item) {
                        if (isset($sub_sub_item['route']) && ($sub_sub_item['route'] == $route || (is_array($sub_sub_item['sub']) && in_array($route, $sub_sub_item['sub'])))) {
                            return $item;
                        }
                    }
                }
            }
        }
    }
    return null;
}

?>
<div class="sidebar <?= $current_menu && count($current_menu['list']) ? 'sidebar-sub' : null ?>">
    <div class="sidebar-1">
        <div class="logo">
            <a class="home-link"
               href="<?= $urlManager->createUrl(['user']) ?>"><?= $this->context->store->name ?></a>
        </div>
        <div>
            <?php foreach ($menu_list as $item) : ?>
                <a class="nav-item <?= activeMenu($item, $route) ?>"
                   href="<?= $urlManager->createUrl($item['route']) ?>">
                    <span class="nav-icon iconfont <?= $item['icon'] ?>"></span>
                    <span><?= $item['name'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if ($current_menu && count($current_menu['list'])) : ?>
        <div class="sidebar-2">
            <div class="current-menu-name"><?= $current_menu['name'] ?></div>
            <div class="sidebar-content">
                <?php foreach ($current_menu['list'] as $item) : ?>
                    <?php if (isset($item['list']) && is_array($item['list']) && count($item['list']) > 0) : ?>
                        <a class="nav-item <?= activeMenu($item, $route) ?>"
                           href="javascript:">
                            <span class="nav-pointer iconfont icon-play_fill"></span>
                            <span><?= $item['name'] ?></span>
                        </a>
                        <div class="sub-item-list">
                            <?php foreach ($item['list'] as $sub_item) : ?>
                                <a class="nav-item <?= activeMenu($sub_item, $route) ?>"
                                   href="<?= $urlManager->createUrl($sub_item['route']) ?>">
                                    <span><?= $sub_item['name'] ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <a class="nav-item <?= activeMenu($item, $route) ?>"
                           href="<?= $urlManager->createUrl($item['route']) ?>">
                            <span><?= $item['name'] ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="main">
    <div class="main-header">
        <?php if (is_array($this->params['page_navs'])) : ?>
            <div class="btn-group">
                <?php foreach ($this->params['page_navs'] as $page_nav) : ?>
                    <a href="<?= $page_nav['url'] ?>"
                       class="btn btn-secondary <?= $page_nav['active'] ? 'active' : null ?>"><?= $page_nav['name'] ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="float-right">

            <div class="btn-group float-left">
                <a href="javascript:" class="btn btn-secondary dropdown-toggle"
                   data-toggle="dropdown"><?= Yii::$app->user->identity->nickname ?></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="<?= $urlManager->createUrl(['user/passport/logout']) ?>">注销</a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-body">
        <?= $content ?>
    </div>
</div>
<script>
    /*
     * 获取浏览器竖向滚动条宽度
     * 首先创建一个用户不可见、无滚动条的DIV，获取DIV宽度后，
     * 再将DIV的Y轴滚动条设置为永远可见，再获取此时的DIV宽度
     * 删除DIV后返回前后宽度的差值
     *
     * @return    Integer     竖向滚动条宽度
     **/
    function getScrollWidth() {
        var noScroll, scroll, oDiv = document.createElement("DIV");
        oDiv.style.cssText = "position:absolute; top:-1000px; width:100px; height:100px; overflow:hidden;";
        noScroll = document.body.appendChild(oDiv).clientWidth;
        oDiv.style.overflowY = "scroll";
        scroll = oDiv.clientWidth;
        document.body.removeChild(oDiv);
        return noScroll - scroll;
    }

    if ($('.sidebar-content')) {
        $('.sidebar-content').css('width', ($('.sidebar-content').width() + getScrollWidth()) + 'px');
    }


    $(document).on("click", "body > .sidebar .sidebar-2 .nav-item", function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip({
            animation: false,
        })
    });

    $(document).on("click", ".input-hide .tip-block", function () {
        $(this).hide();
    });


    $(document).on("click", ".input-group .dropdown-item", function () {
        var val = $.trim($(this).text());
        $(this).parents(".input-group").find(".form-control").val(val);
    });
</script>
</body>
</html>