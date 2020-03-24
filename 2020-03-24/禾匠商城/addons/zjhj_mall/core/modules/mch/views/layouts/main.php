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
$this->params['active_nav_group'] = isset($this->params['active_nav_group']) ? $this->params['active_nav_group'] : 0;
$version = hj_core_version();

$admin = null;
$admin_permission_list = [];
if (!Yii::$app->admin->isGuest) {
    $admin = Yii::$app->admin->identity;
    $admin_permission_list = json_decode($admin->permission, true);
    if (!$admin_permission_list)
        $admin_permission_list = [];
} else {
    $admin = true;
    $admin_permission_list = $this->context->we7_user_auth;
}
try {
    $plugin_list = \app\hejiang\cloud\CloudPlugin::getInstallPluginList();
} catch (Exception $e) {
    $plugin_list = [];
}

$current_url = Yii::$app->request->absoluteUrl;
$key = 'addons/';
$we7_url = mb_substr($current_url, 0, stripos($current_url, $key));
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title><?= $this->title ?></title>
    <link href="//at.alicdn.com/t/font_353057_uuvgc82rsqo.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/jquery.datetimepicker.min.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/css/flex.css?version=<?= $version ?>" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/css/common.css?version=<?= $version ?>" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/common.v2.css?version=<?= $version ?>"
          rel="stylesheet">

    <script>var _csrf = "<?=Yii::$app->request->csrfToken?>";</script>
    <script>var _upload_url = "<?=Yii::$app->urlManager->createUrl(['upload/file'])?>";</script>
    <script>var _upload_file_list_url = "<?=Yii::$app->urlManager->createUrl(['mch/store/upload-file-list'])?>";</script>
    <script>var _district_data_url = "<?=Yii::$app->urlManager->createUrl(['api/default/district', 'store_id' => $this->context->store->id])?>";</script>
    <script>var CLODOP_URL = "<?= Yii::$app->request->baseUrl ?>/statics/mch/js/Lodop.js"</script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/jquery.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/jquery.nicescroll.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/vue.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/tether.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/bootstrap.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/plupload.full.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/jquery.datetimepicker.full.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/datetime.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/js/common.js?version=<?= $version ?>"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/common.v2.js?version=<?= $version ?>"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/clipboard.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/vendor/layer/layer.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/vendor/laydate/laydate.js"></script>
</head>
<style>
    html {
        /*隐藏滚动条，当IE下溢出，仍然可以滚动*/
        -ms-overflow-style:none;
    }
</style>
<body>
<?php $this->beginBody() ?>
<?= $this->render('/components/pick-link.php') ?>
<?= $this->render('/components/pick-file.php') ?>
<!-- 文件选择模态框 Modal -->
<div class="modal fade" id="file_select_modal_1" data-backdrop="static">
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
                                <option :value="item.id" v-for="(item,index) in province_list">{{item.name}}
                                </option>
                            </select>
                        </td>
                        <td>
                            <select v-model="city_id" v-on:change="cityChange" class="form-control">
                                <option :value="item.id" v-for="(item,index) in city_list">{{item.name}}</option>
                            </select>
                        </td>
                        <td>
                            <select v-model="district_id" v-on:change="districtChange" class="form-control">
                                <option :value="item.id" v-for="(item,index) in district_list">{{item.name}}
                                </option>
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
    if (isset($item['route']) && ($item['route'] == $route)) {
        return 'active';
    }
    if (isset($item['sub'])) {
        foreach ($item['sub'] as $i) {
            if (isset($i['route']) && $route == $i['route']) {
                return 'active';
            }
        }
    }
    if (isset($item['children']) && is_array($item['children'])) {
        foreach ($item['children'] as $sub_item) {
            $active = activeMenu($sub_item, $route);
            if ($active != '')
                return $active;
        }
    }
    return '';
}

function getCurrentMenu($menu_list, $route, $return = [], $level = 0)
{
    foreach ($menu_list as $item) {
        if ($level == 0) {
            $return = $item;
        }
        if (isset($item['route'])) {
            if ($item['route'] == $route) {
                return $return;
            }

            if (isset($item['sub'])) {
                foreach ($item['sub'] as $k => $i) {
                    if ($i['route'] == $route) {
                        return $return;
                    }
                }
            }
        }
        if (isset($item['children']) && is_array($item['children'])) {
            $aa = getCurrentMenu($item['children'], $route, $return, $level + 1);
            if ($aa) {
                return $return;
            }
        }
    }
    return null;
}

?>
<div class="sidebar <?= $current_menu && count($current_menu['children']) ? 'sidebar-sub' : null ?>">
    <div class="sidebar-1">
        <div class="logo">
            <a class="home-link"
               href="<?= $urlManager->createUrl(['mch/default/index']) ?>"><?= $this->context->store->name ?></a>
        </div>
        <div>
            <?php foreach ($menu_list as $item): ?>
                <a class="nav-item <?= activeMenu($item, $route) ?>"
                   href="<?= $urlManager->createUrl($item['route']) ?>">
                    <span class="nav-icon iconfont <?= $item['icon'] ?>"></span>
                    <span><?= $item['name'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if ($current_menu && count($current_menu['children'])): ?>
        <div class="sidebar-2">
            <div class="current-menu-name"><?= $current_menu['name'] ?></div>
            <div class="sidebar-content">
                <?php foreach ($current_menu['children'] as $item): ?>
                    <?php if (isset($item['children']) && is_array($item['children']) && count($item['children']) > 0): ?>
                        <a class="nav-item <?= activeMenu($item, $route) ?>"
                           href="javascript:">
                            <span class="nav-pointer iconfont icon-play_fill"></span>
                            <span><?= $item['name'] ?></span>
                        </a>
                        <div class="sub-item-list">
                            <?php foreach ($item['children'] as $sub_item): ?>
                                <a class="nav-item <?= activeMenu($sub_item, $route) ?>"
                                   href="<?= $urlManager->createUrl($sub_item['route']) ?>">
                                    <span><?= $sub_item['name'] ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
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
        <?php if (is_array($this->params['page_navs'])): ?>
            <div class="btn-group">
                <?php foreach ($this->params['page_navs'] as $page_nav): ?>
                    <a href="<?= $page_nav['url'] ?>"
                       class="btn btn-secondary <?= $page_nav['active'] ? 'active' : null ?>"><?= $page_nav['name'] ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="float-right">

            <div class="btn-group float-left message">
                <a href="javascript:" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                   style="position: relative">
                    <span>消息提醒</span>
                    <div class="text-center ml-2 totalNum"
                         hidden
                         style="width: 18px;height: 18px;line-height: 18px;border-radius:999px;display: inline-block;background-color: #ff4544;color:#fff;">
                        5
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right message-list" hidden>
                </div>
            </div>

            <div class="btn-group float-left">
                <a href="javascript:" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <?= $this->context->store->name ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <?php if (Yii::$app->user->isGuest == false): ?>
                        <p class="dropdown-item">管理员：<?= Yii::$app->user->identity->nickname ?></p>
                    <?php elseif (Yii::$app->admin->isGuest == false): ?>
                        <p class="dropdown-item">管理员:<?= Yii::$app->admin->identity->username ?></p>
                    <?php else: ?>
                        <p class="dropdown-item">操作员:<?= Yii::$app->mchRoleAdmin->identity->nickname ?></p>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->isGuest == false): ?>
                        <a class="dropdown-item"
                           href="<?= Yii::$app->urlManager->createUrl(['mch/passport/logout']) ?>">返回系统</a>
                    <?php elseif (Yii::$app->admin->isGuest == false): ?>
                        <a class="dropdown-item"
                           href="<?= Yii::$app->urlManager->createUrl(['mch/passport/logout']) ?>">返回系统</a>
                    <?php else: ?>
                        <a class="dropdown-item"
                           href="<?= Yii::$app->urlManager->createUrl(['mch/permission/passport/logout']) ?>">退出登录</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            var checkUrl = "<?=Yii::$app->urlManager->createUrl(['mch/get-data/order'])?>";


            var sound = "<?=Yii::$app->request->baseUrl . '/statics/'?>/5611.wav";

            function playSound(id) {
                var borswer = window.navigator.userAgent.toLowerCase();
                if (borswer.indexOf("ie") >= 0) {
                    //IE内核浏览器
                    var strEmbed = '<embed name="embedPlay" src="' + sound + '" autostart="true" hidden="true" loop="false"></embed>';
                    if ($("body").find("embed").length <= 0)
                        $("body").append(strEmbed);
                    var embed = document.embedPlay;

                    //浏览器不支持 audion，则使用 embed 播放
                    embed.volume = 100;
                } else {
                    //非IE内核浏览器
                    var strAudio = "<audio id='audioPlay' src='" + sound + "' hidden='true'>";
                    if ($("body").find("audio").length <= 0)
                        $("body").append(strAudio);
                    var audio = document.getElementById("audioPlay");

                    //浏览器支持 audion
                    audio.play();
                }
            }

            function is_index(indexOf, list) {
                for (var i = 0; i < list.length; i++) {
                    if (indexOf == list[i]) {
                        return true;
                    }
                }
                return false;
            }

            // 订单消息
            function checkmessage() {
                $.ajax({
                    url: checkUrl,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        var sound_list = JSON.parse(localStorage.getItem('sound_list'));
                        if (!sound_list) {
                            sound_list = [];
                        }
                        if (res.code == 0) {
                            var count = res.data.length;
                            if (count == 0) {
                                return;
                            }
                            $('.message-list').empty();

                            for (var i = 0; i < count; i++) {
                                $('.message-list').prop('hidden', false);
                                $('.totalNum').prop('hidden', false).html(count);
                                var type = res.data[i].type;
                                var order_type = res.data[i].order_type;
                                if (order_type == 4) {
                                    var html = "<a target='_blank' class='dropdown-item' data-index='" + res.data[i].id + "' href='" + res.data[i].url + "'>" + res.data[i].name + "申请商品上架</a>";
                                } else {
                                    if (type == 0) {
                                        var html = "<a target='_blank' class='dropdown-item' data-index='" + res.data[i].id + "' href='" + res.data[i].url + "'>" + res.data[i].name + "下了一个订单</a>";
                                    } else {
                                        var html = "<a target='_blank' class='dropdown-item' data-index='" + res.data[i].id + "' href='" + res.data[i].url + "'>" + res.data[i].name + "一个售后订单</a>";
                                    }
                                }

                                $('.message-list').append(html);

                                if (res.data[i].is_sound == 0 && !is_index(res.data[i].id, sound_list)) {
                                    sound_list.push(res.data[i].id);
                                    playSound(res.data[i].id);
                                }

                            }
                            localStorage.setItem('sound_list', JSON.stringify(sound_list));
                            $('.message-list').append("<a class='dropdown-item' style='text-align:center' href='<?=Yii::$app->urlManager->createUrl(['mch/store/order-message', 'status' => 1])?>'>全部消息</a>");
                        }
                    }
                });
            }

            $(document).ready(function () {
                $('.message').hover(function () {
                    $('.message-list').show();
                }, function () {
                    $('.message-list').hide();
                });
                $('.message-list').on('click', 'a', function () {
                    var num = $('.totalNum');
                    num.text(num.text() - 1);
                    if (num.text() == 0) {
                        num.prop('hidden', true);
                        $('.message-list').prop('hidden', true)
                    }
                    $.ajax({
                        url: '<?=Yii::$app->urlManager->createUrl(['mch/get-data/message-del'])?>',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            'id': $(this).data('index')
                        },
                        success: function (res) {
                            if (res.code == 0) {
                                window.location.href = $(this).data('url');
                            }
                        }
                    });
                    $(this).remove();
                });
                setInterval(function () {
                    checkmessage();
                }, 30000);
            });
        </script>
    </div>
    <div class="main-body">
        <?= $content ?>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".sidebar-1").niceScroll();
    })

</script>

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

    //图片库vue对象
    var file_app;
</script>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
