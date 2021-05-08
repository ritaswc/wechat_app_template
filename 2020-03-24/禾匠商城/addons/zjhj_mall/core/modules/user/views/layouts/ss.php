<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/9
 * Time: 17:41
 */

$urlManager = Yii::$app->urlManager;
$commonDistrict = new \app\models\common\CommonDistrict();
$district = Yii::$app->serializer->encode($commonDistrict->search());
?>
<style>
    .form-group > div:first-child {
        padding-right: 4px;
        text-align: right;
    }

    .form-group > div:last-child {
        padding-left: 4px;
    }

    .middle-center {
        /*line-height: 34px;*/
    }

    .w-20 {
        width: 20rem;
    }

    .w-12 {
        width: 12rem;
    }

    .status .nav-item,.bg-shaixuan {
        background-color: #f8f8f8;
    }

    .status .nav-item .nav-link {
        color: #464a4c;
        border: 1px solid #ddd;
        border-radius: 0;
    }

    .status .nav-item .nav-link.active {
        border-color: #ddd #ddd #fff;
        background-color: #fff;
    }
    .new-day{
        cursor: pointer;
    }
</style>


<!--修改地址-->
<div class="modal fade" id="editAddress">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">修改收货地址</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="form-group row">
                    <div style="margin-right: 10px;" class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">收件人</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control name" value="">
                    </div>
                </div>

                <div class="form-group row">
                    <div style="margin-right: 10px;" class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">电话</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control mobile" value="">
                    </div>
                </div>

                <div class="form-group row">
                    <div style="margin-right: 10px;" class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">发件人地区</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <select class="form-control province" style="float: left;"
                                    name="model[province]">
                                <option v-for="(item,index) in province"
                                        :value="item.name" :data-index="index">{{item.name}}
                                </option>
                            </select>
                            <select class="form-control city" style="float: left;" name="model[city]">
                                <option v-for="(item,index) in city"
                                        :value="item.name" :data-index="index">{{item.name}}
                                </option>
                            </select>
                            <select class="form-control area" style="float: left;" name="model[exp_area]">
                                <option v-for="(item,index) in area"
                                        :value="item.name" :data-index="index">{{item.name}}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div style="margin-right: 10px;" class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">详细地址</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control address" value="">
                    </div>
                </div>

                <input style="display: none;" class="order-id" name="orderId" value="">
                <input style="display: none;" class="order-type" name="orderType" value="">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-primary update-address" href="javascript:">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery.datetimepicker.setLocale('zh');
    jQuery('#date_start').datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'Y-m-d',
        dayOfWeekStart: 1,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onShow: function (ct) {
            this.setOptions({
                maxDate: jQuery('#date_end').val() ? jQuery('#date_end').val() : false
            })
        }
    });
    $(document).on('click', '#show_date_start', function () {
        $('#date_start').datetimepicker('show');
    });
    jQuery('#date_end').datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'Y-m-d',
        dayOfWeekStart: 1,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onShow: function (ct) {
            this.setOptions({
                minDate: jQuery('#date_start').val() ? jQuery('#date_start').val() : false
            })
        }
    });
    $(document).on('click', '#show_date_end', function () {
        $('#date_end').datetimepicker('show');
    });
    $(document).on('click', '.new-day', function () {
        var index = $(this).data('index');
        var myDate = new Date();
        var mydate = new Date(myDate.getTime() - index * 24 * 60 * 60 * 1000);
        jQuery('#date_start').datetimepicker('setOptions', {value: mydate});
        jQuery('#date_end').datetimepicker('setOptions', {value: myDate});
    });
</script>

<!--更新地址相关-->
<script>
    var app = new Vue({
        el: '#editAddress',
        data: {
            province:<?=$district?>,
            city: [],
            area: [],
            sender_province: "<?=$sender->province?>",
            sender_city: "<?=$sender->city?>",
            sender_area: "<?=$sender->exp_area?>",
            orderList: <?= Yii::$app->serializer->encode($list) ?>
        }
    });

    // 弹框
    $(document).on("click", ".edit-address", function () {
        var orderType = $(this).data('orderType');
        var index = $(this).data('index');
        var orderId = app.orderList[index].id;
        var name = app.orderList[index].name;
        var mobile = app.orderList[index].mobile;

        $('.name').val(name);
        $('.mobile').val(mobile);
        $('.order-id').val(orderId);
        $('.order-type').val(orderType);
        $('.address').val(app.orderList[index].address_data.detail)
        app.sender_province = app.orderList[index].address_data.province
        app.sender_city = app.orderList[index].address_data.city
        app.sender_area = app.orderList[index].address_data.district

        $('.province').find('option').each(function (i) {
            if ($(this).val() == app.sender_province) {
                $(this).prop('selected', 'selected');
                return true;
            }
        });
        $('.city').find('option').each(function (i) {
            if ($(this).val() == app.sender_city) {
                $(this).prop('selected', 'selected');
                return true;
            }
        });
        $('.area').find('option').each(function (i) {
            if ($(this).val() == app.sender_area) {
                $(this).prop('selected', 'selected');
                return true;
            }
        });

        app.city = app.province[0].list;
        app.area = app.city[0].list;
        $(app.province).each(function (i) {
            if (app.province[i].name == app.sender_province) {
                app.city = app.province[i].list;
                return true;
            }
        });
        $(app.city).each(function (i) {
            if (app.city[i].name == app.sender_city) {
                app.area = app.city[i].list;
                return true;
            }
        });

        $('#editAddress').modal('show');
    });

    $(document).on('change', '.province', function () {
        var index = $(this).find('option:selected').data('index');
        app.city = app.province[index].list;
        app.area = app.city[0].list;
    });
    $(document).on('change', '.city', function () {
        var index = $(this).find('option:selected').data('index');
        app.area = app.city[index].list;
    });

    // 提交更新
    $(document).on('click', '.update-address', function () {
        $('.update-address').btnLoading('更新中');
        var href = '<?= $urlManager->createUrl(['user/mch/order/update-order-address']) ?>';
        $.ajax({
            url: href,
            type: "post",
            data: {
                orderId: $('.order-id').val(),
                orderType: $('.order-type').val(),
                name: $('.name').val(),
                mobile: $('.mobile').val(),
                province: $('.province').val(),
                city: $('.city').val(),
                district: $('.area').val(),
                address: $('.address').val(),
                _csrf: _csrf
            },
            dataType: "json",
            success: function (res) {
                $('.update-address').btnReset();
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        if (res.code == 0) {
                            location.reload();
                        }
                    }
                })
            }
        });
        return false;
    });
</script>

