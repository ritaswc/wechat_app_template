<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/24
 * Time: 10:18
 */

use yii\widgets\LinkPager;

/* @var \app\models\Coupon $model */

$urlManager = Yii::$app->urlManager;
$this->title = '优惠券编辑';
$this->params['active_nav_group'] = 7;
?>
<style>

    .cat-list .cat-item {
        text-align: center;
        width: 120px;
        border: 1px solid #e3e3e3;
        height: 110px;
        cursor: pointer;
        display: inline-block;
        vertical-align: top;
        margin: 1rem 1rem;
        border-radius: .15rem;
    }

    .cat-list .cat-item:hover {
        background: rgba(238, 238, 238, 0.54);
    }

    .cat-list .cat-item img {
        width: 4rem;
        height: 4rem;
        border-radius: 999px;
        margin-bottom: 3px;
        margin-top: 1rem;
    }

    .cat-list .cat-item.active {
        background: rgba(2, 117, 216, 0.69);
        color: #fff;
    }
</style>
<div id="panel-body">
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off" return="<?= $urlManager->createUrl(['mch/coupon/edit','id' => $model->id]) ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">优惠券名称</label>
                    </div>
                    <div class="col-9">
                        <input class="form-control" name="name" value="<?= $model->name ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">优惠券类型</label>
                    </div>
                    <div class="col-9 pt-1">
                        <!--
                    <label class="custom-control custom-radio">
                        <input name="discount_type" value="1"
                            <?= $model->discount_type == null || $model->discount_type == 1 ? 'checked' : null ?>
                               type="radio" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">折扣券</span>
                    </label>
                        <label class="custom-control custom-radio">
                            <input name="discount_type" value="2" type="radio" class="custom-control-input"
                                <?= $model->discount_type == null || $model->discount_type == 2 ? 'checked' : null ?>>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">满减券</span>
                        </label>
                    -->
                        <label class="radio-label" hidden>
                            <input name="discount_type" value="1"
                                <?= $model->discount_type == null || $model->discount_type == 1 ? 'checked' : null ?>
                                   type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">折扣券</span>
                        </label>
                        <label class="radio-label">
                            <input name="discount_type" value="2" type="radio" class="custom-control-input"
                                <?= $model->discount_type == null || $model->discount_type == 2 ? 'checked' : null ?>>
                            <span class="label-icon"></span>
                            <span class="label-text">满减券</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">最低消费金额（元）</label>
                    </div>
                    <div class="col-9">
                        <input class="form-control" type="number" step="0.01" min="0" name="min_price"
                               value="<?= $model->min_price ? $model->min_price : 0 ?>">
                        <div class="fs-sm text-muted">购物金额（不含运费）达到最低消费金额才可使用优惠券，无门槛优惠券请填0</div>
                    </div>
                </div>
                <div class="form-group row discount-type discount-type-1"
                     style="<?= $model->discount_type != 1 ? 'display:none' : null ?>">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">折扣率</label>
                    </div>
                    <div class="col-9">
                        <input class="form-control" type="number" step="0.1" min="0.1" max="10" name="discount"
                               value="<?= $model->discount ? $model->discount : 10 ?>">
                        <div class="fs-sm text-muted">如打8.5折请填写8.5，如不打折请填写10</div>
                        <div class="fs-sm text-muted">支持折扣率0.1-10</div>
                    </div>
                </div>
                <div class="form-group row discount-type discount-type-2"
                     style="<?= $model->discount_type != null && $model->discount_type != 2 ? 'display:none' : null ?>">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">优惠金额（元）</label>
                    </div>
                    <div class="col-9">
                        <input class="form-control" type="number" step="0.01" min="0" name="sub_price"
                               value="<?= $model->sub_price ? $model->sub_price : 0 ?>">
                        <div class="text-danger text-muted">注：优惠券只能抵消商品金额，不能抵消运费，商品金额最多优惠到0.01元</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">优惠券有效期</label>
                    </div>
                    <div class="col-9 pt-1">
                        <!--
                        <label class="custom-control custom-radio">
                            <input name="expire_type" value="1"
                                <?= $model->expire_type == null || $model->expire_type == 1 ? 'checked' : null ?>
                                   type="radio"
                                   class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">领取后N天内有效</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input name="expire_type"
                                <?= $model->expire_type == 2 ? 'checked' : null ?>
                                   value="2" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">时间段</span>
                        </label>-->
                        <label class="radio-label">
                            <input name="expire_type" value="1"
                                <?= $model->expire_type == null || $model->expire_type == 1 ? 'checked' : null ?>
                                   type="radio"
                                   class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">领取后N天内有效</span>
                        </label>
                        <label class="radio-label">
                            <input name="expire_type"
                                <?= $model->expire_type == 2 ? 'checked' : null ?>
                                   value="2" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">时间段</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row expire-type expire-type-1"
                     style="<?= $model->expire_type != null && $model->expire_type != 1 ? 'display:none' : null ?>">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">有效天数</label>
                    </div>
                    <div class="col-9">
                        <input class="form-control" type="number" step="1" min="1" name="expire_day"
                               value="<?= $model->expire_day ? $model->expire_day : 1 ?>">
                    </div>
                </div>
                <div class="form-group row expire-type expire-type-2"
                     style="<?= $model->expire_type != 2 ? 'display:none' : null ?>">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">有效期范围</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group">
                            <span class="input-group-addon">开始日期：</span>
                            <input class="form-control"
                                   id="begin_time"
                                   name="begin_time"
                                   value="<?= $model->begin_time ? date('Y-m-d', $model->begin_time) : date('Y-m-d') ?>">
                            <span class="input-group-addon">结束日期：</span>
                            <input class="form-control"
                                   id="end_time"
                                   name="end_time"
                                   value="<?= $model->end_time ? date('Y-m-d', $model->end_time) : date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">加入领券中心</label>
                    </div>
                    <div class="col-9 pt-1">
                        <!--
                        <label class="custom-control custom-radio">
                            <input name="is_join" value="1"
                                <?= $model->is_join == null || $model->is_join == 1 ? 'checked' : null ?>
                                   type="radio"
                                   class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">不加入</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input name="is_join"
                                <?= $model->is_join == 2 ? 'checked' : null ?>
                                   value="2" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">加入</span>
                        </label>-->
                        <label class="radio-label">
                            <input name="is_join" value="1"
                                <?= $model->is_join == null || $model->is_join == 1 ? 'checked' : null ?>
                                   type="radio"
                                   class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">不加入</span>
                        </label>
                        <label class="radio-label">
                            <input name="is_join"
                                <?= $model->is_join == 2 ? 'checked' : null ?>
                                   value="2" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">加入</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row total-count"
                     style="<?= $model->is_join == null || $model->is_join == 1 ? 'display:none' : null ?>">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">发放总数</label>
                    </div>
                    <div class="col-9">
                        <input class="form-control" type="number" step="1" min="1" name="total_count"
                               value="<?= $model->total_count ? $model->total_count : -1 ?>">
                        <div class="text-danger text-muted">注：优惠券总数量，没有不能领取或发放,-1为不限制张数</div>
                        <div class="text-danger text-muted">注：优惠券总数量只限制领券中心领取的优惠券数量</div>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">排序</label>
                    </div>
                    <div class="col-9">
                        <input class="form-control" type="number" step="1" min="1" name="sort"
                               value="<?= $model->sort ? $model->sort : 100 ?>">
                        <div class="text-danger text-muted">注：排序按升序排列</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">指定商品类别或商品</label>
                    </div>
                    <div class="col-9 pt-1">
                        <label class="radio-label">
                            <input name="appoint_type" value="1"
                                <?= $model->appoint_type == null || $model->appoint_type == 1 ? 'checked' : null ?>
                                   type="radio"
                                   class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">指定商品类别</span>
                        </label>
                        <label class="radio-label">
                            <input name="appoint_type"
                                <?= $model->appoint_type == 2 ? 'checked' : null ?>
                                   value="2" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">指定商品</span>
                        </label>
                    </div>
                </div>

                <div class="coupon-type coupon-type-1" style="<?= $model->appoint_type != null && $model->appoint_type != 1 ? 'display:none' : null ?>">
                    <div class="form-group row">
                        <div class="form-group-label col-3 text-right">
                            <label class="">已指定商品类别</label>
                        </div>
                        <div class="col-9">
                            <div class="cat-list">
                                <?php foreach ($cat as $item) : ?>
                                    <label class="cat-item">
                                        <img src="<?= $item->pic_url ?>">
                                        <div style="position:absolute; top:1px;width:20px;height:20px;border:1px solid #E3E3E3" onclick="delete_cat(<?= $item->id ?>,<?= $model->id ?>)">X</div>

                                        <input type="hidden" name="cat_id[]" value="<?= $item->id ?>">
                                        <div style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden">
                                            <?= $item->name ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-3 text-right">
                            <label class="">添加商品类别</label>
                            <label style="color:red;">不添加表示全场通用</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group mb-3" style="max-width: 250px">
                                <input class="form-control search-cat-keyword" placeholder="商品类别"
                                       onkeydown="if(event.keyCode==13) {search_cat();return false;}">
                                <span class="input-group-btn">
                                    <a class="btn btn-secondary search-cat-btn" onclick="search_cat()"
                                       href="javascript:">查找商品类别</a>
                                </span>
                            </div>
                            <div class="cat-list">
                                <div v-if="cat_list">
                                    <label class="cat-item" v-for="(cat,index) in cat_list">
                                        <img v-bind:src="cat.pic_url">
                                        <input v-bind:value="cat.id" type="checkbox" name="cat_id_list[]"
                                               style="display: none">
                                        <div style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden">
                                            {{cat.name}}
                                        </div>
                                    </label>
                                </div>
                                <div v-else style="color: #ddd;">请输入商品类别</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="coupon-type coupon-type-2" style="<?= $model->appoint_type != 2 ? 'display:none' : null ?>">
                    <div class="form-group row">
                        <div class="form-group-label col-3 text-right">
                            <label class="">已指定商品</label>
                        </div>
                        <div class="col-9">
                            <div class="cat-list">
                                <?php foreach ($goods as $item) : ?>
                                    <label class="cat-item">
                                        <div style="position:absolute;width:20px;height:20px;border:1px solid #E3E3E3" onclick="delete_goods(<?= $item->id ?>,<?= $model->id ?>)">X</div>
                                        <img src="<?= $item->cover_pic ?>">
                                        <input type="hidden" name="cat_id[]" value="<?= $item->id ?>">
                                        <div style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden">
                                            <?= $item->name ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-3 text-right">
                            <label class="">添加商品</label>
                            <label style="color:red;">不添加表示全场通用</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group mb-3" style="max-width: 250px">
                                <input class="form-control search-goods-keyword" placeholder="商品名称"
                                       onkeydown="if(event.keyCode==13) {search_cat();return false;}">
                                <span class="input-group-btn">
                                    <a class="btn btn-secondary search-goods-btn" onclick="search_goods()"
                                       href="javascript:">查找商品</a>
                                </span>
                            </div>
                            <div class="cat-list">
                                <div v-if="goods_list">
                                    <label class="cat-item" v-for="(goods,index) in goods_list">
                                        <img v-bind:src="goods.cover_pic">
                                        <input v-bind:value="goods.id" type="checkbox" name="goods_id_list[]"
                                               style="display: none">
                                        <div style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden">
                                            {{goods.name}}
                                        </div>
                                    </label>
<!--                                    <div class="text-center">-->
<!--                                        <div>-->
<!--                                            <span>上一页</span>-->
<!--                                            <span>下一页</span>-->
<!--                                        </div>-->
<!--                                        <div class="text-muted">{{row_count}}条数据</div>-->
<!--                                    </div>-->
                                </div>
                                <div v-else style="color: #ddd;">请输入商品</div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">使用说明</label>
                    </div>
                    <div class="col-sm-5">
                        <textarea class="form-control" rows="5" id="rule"
                            name="rule"><?= $model->rule ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                    </div>
                    <div class="col-9">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                        <input type="button" class="btn btn-default ml-4" 
                               name="Submit" onclick="javascript:history.back(-1);" value="返回">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
<!--<script src="https://cdn.bootcss.com/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>-->
<script>
    var app = new Vue({
        el: "#panel-body",
        data: {
            cat_list: false,
            goods_list: false,
        }
    });
    $(document).on("change", "input[name='cat_id_list[]']", function () {
        console.log($(this).parents("label"));
        if ($(this).prop("checked")) {
            $(this).parents("label").addClass("active");
        } else {
            $(this).parents("label").removeClass("active");
        }
    });

    $(document).on("change", "input[name=expire_type]", function () {
        $(".expire-type").hide();
        $(".expire-type-" + this.value).show();
    });

    $(document).on("change", "input[name=appoint_type]", function () {
        $(".coupon-type").hide();
        $(".coupon-type-" + this.value).show();
    });

    $(document).on("change", "input[name='goods_id_list[]']", function () {
        console.log($(this).parents("label"));
        if ($(this).prop("checked")) {
            $(this).parents("label").addClass("active");
        } else {
            $(this).parents("label").removeClass("active");
        }
    });

    $(document).on("change", "input[name=discount_type]", function () {
        $(".discount-type").hide();
        $(".discount-type-" + this.value).show();
    });

    $(document).on("change", "input[name=is_join]", function () {
        $(".total-count").hide();
        if (this.value == 2) {
            $(".total-count").show();
        }
    });

    (function () {
        $.datetimepicker.setLocale('zh');
        $('#begin_time').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $('#end_time').val() ? $('#end_time').val() : false
                })
            },
            timepicker: false,
        });
        $('#end_time').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    minDate: $('#begin_time').val() ? $('#begin_time').val() : false
                })
            },
            timepicker: false,
        });
    })();
    function search_cat() {
        var btn = $(".search-cat-btn");
        var keyword = $(".search-cat-keyword").val();
        btn.btnLoading("正在查找");
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/coupon/search-cat'])?>",
            dataType: "json",
            data: {
                keyword: keyword,
            },
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.cat_list = res.data.list;
                }
            }
        });
    }

    function search_goods() {
        var btn = $(".search-goods-btn");
        var keyword = $(".search-goods-keyword").val();
        btn.btnLoading("正在查找");
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/coupon/search-goods'])?>",
            dataType: "json",
            data: {
                keyword: keyword,
            },
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.goods_list = res.data.list;
                }
            }
        });
    }


    function delete_cat(cat_id,coupon_id){
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/coupon/delete-cat'])?>",
            dataType: "json",
            data: {
                cat_id: cat_id,
                coupon_id:coupon_id,
            },
            success: function (res) {
                if (res.code == 0) {
                    location.reload();
                }
            }
        });
    }
    function delete_goods(goods_id,coupon_id){
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/coupon/delete-goods'])?>",
            dataType: "json",
            data: {
                goods_id: goods_id,
                coupon_id:coupon_id,
            },
            success: function (res) {
                if (res.code == 0) {
                    location.reload();
                }
            }
        });
    }


</script>