<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;
$urlManager = Yii::$app->urlManager;
$this->title = '抽奖详情';
$this->params['active_nav_group'] = 7;

?>
<style>

    .user-list .user-item {
        text-align: center;
        width: 120px;
        border: 1px solid #e3e3e3;
        padding: 1rem 0;
        cursor: pointer;
        display: inline-block;
        vertical-align: top;
        margin: 0 1rem 1rem 0;
        border-radius: .15rem;
    }

    .user-list .user-item:hover {
        background: rgba(238, 238, 238, 0.54);
    }

    .user-list .user-item img {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 999px;
        margin-bottom: 1rem;
    }

    .user-list .user-item.active {
        background: rgba(2, 117, 216, 0.69);
        color: #fff;
    }
    .user-list .user-xx {
        position:absolute;
        top:1px;
        width:20px;
        height:20px;
        border:1px solid #E3E3E3;
    }
</style>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
    <form class="form auto-form" method="post" autocomplete="off" data-timeout="5" >
        <div class="form-body">

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label>商品名称</label>
                </div>
                <div class="col-9">
                    <?= $goods_list->goods->name ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="">规格</label>
                </div>
                <div class="col-9">
                    <?php $attr_list = json_decode($goods_list->attr); ?>
                    <?php if (is_array($attr_list)) :
                        foreach ($attr_list as $attr) : ?>
                            <span class="mr-3"><?= $attr->attr_group_name ?>
                                :<?= $attr->attr_name ?></span>
                        <?php endforeach;;
                    endif; ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="">当前参与人数</label>
                </div>
                <div class="col-9">
                    <?= $num?>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="">中奖数量</label>
                </div>
                <div class="col-9">
                    <?= $goods_list->stock?>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="">时间范围</label>
                </div>
                <div class="col-9">
                    <span class="text-danger"><?= date('Y-m-d H:i', $goods_list->start_time) ?></span> 至 <span
                          class="text-danger"><?= date('Y-m-d H:i', $goods_list->end_time) ?></span>
       
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="">预获奖名单</label>
                </div>
                <div class="col-9">
                    <div class="user-list">
                        <?php foreach ($user_list as $item) : ?>
                            <label class="user-item">
                                <img src="<?= $item['avatar_url'] ?>">
                                <div class="user-xx" onclick="delete_user(<?= $goods_list->id ?>,<?= $item['id'] ?>)">X</div>
                                <div style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden">
                                    <?= $item['nickname'] ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- 搜索 -->
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="">预定中奖用户</label>
                </div>
                <div class="col-9">
                    <div class="input-group mb-3" style="max-width: 250px">
                        <input class="form-control search-user-keyword" placeholder="昵称/ID"
                               onkeydown="if(event.keyCode==13) {search_user();return false;}">
                        <span class="input-group-btn">
                            <a class="btn btn-secondary search-user-btn" onclick="search_user()"
                               href="javascript:">查找用户</a>
                        </span>
                    </div>
                    <div class="user-list">
                        <div v-if="user_list">
                            <label class="user-item" v-for="(user,index) in user_list">
                                <img v-bind:src="user.avatar_url">
                                <input v-bind:value="user.id" type="checkbox" name="user_id_list[]"
                                       style="display: none">
                                <div style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden">
                                    {{user.nickname}}
                                </div>
                            </label>
                        </div>
                        <div v-else style="color: #ddd;">请输入昵称/ID查找用户</div>
                    </div>
                    <div class="fs-sm text-danger">
                        搜索结果为 已参与用户
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
        </div>

    </form>
</div>
</div>
<script>

    var app = new Vue({
        el: "#app",
        data: {
            user_list: false,
        }
    });

    $(document).on("change", "input[name='user_id_list[]']", function () {
        console.log($(this).parents("label"));
        if ($(this).prop("checked")) {
            $(this).parents("label").addClass("active");
        } else {
            $(this).parents("label").removeClass("active");
        }
    });

    function search_user() {
        var btn = $(".search-user-btn");
        var keyword = $(".search-user-keyword").val();
        var lottery_id = <?= $goods_list->id ?>;

        btn.btnLoading("正在查找");
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/lottery/default/search-user'])?>",
            dataType: "json",
            data: {
                keyword: keyword,
                lottery_id: lottery_id,
            },
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.user_list = res.data.list;
                }
            }
        }); 
    }

    function delete_user(lottery_id,log_id){
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/lottery/default/delete-log'])?>",
            dataType: "json",
            data: {
                lottery_id: lottery_id,
                log_id:log_id,
            },
            success: function (res) {
                if (res.code == 0) {
                    location.reload();
                }
            }
        });
    }


</script>