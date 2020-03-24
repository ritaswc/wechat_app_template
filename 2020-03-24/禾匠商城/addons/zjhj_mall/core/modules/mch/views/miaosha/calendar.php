<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '秒杀';
$this->params['active_nav_group'] = 10;
?>
<link href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/jquery.datetimepicker.2.5.12.min.css" rel="stylesheet">
<style>
    .miaosha-calendar-box {
        display: inline-block;
        position: relative;
    }

    .miaosha-calendar-box .xdsoft_datepicker {
        width: auto;
    }

    .miaosha-calendar-box .xdsoft_datetimepicker {
        position: static !important;
        z-index: inherit;
        left: 0;
    }

    .miaosha-calendar-box .xdsoft_datetimepicker .xdsoft_month {
        width: auto;
    }

    .miaosha-calendar-box .xdsoft_today_button {
        display: none;
    }

    .miaosha-calendar-box .xdsoft_datetimepicker .xdsoft_calendar td {
        padding: 0;
    }

    .miaosha-calendar-box .xdsoft_datetimepicker .xdsoft_calendar td > div {
        padding: 0;
        text-align: center;
        width: 100px;
        height: 80px;
    }

    .miaosha-calendar-box .xdsoft_monthpicker > * {
        float: left;
    }

    .miaosha-calendar-box .xdsoft_datetimepicker .xdsoft_label {
        z-index: 1;
    }

    .miaosha-calendar-box #miaosha_calendar {
        display: block !important;
        position: absolute;
        background: rgba(255, 255, 255, 0.4);
        z-index: 2;
        left: 0;
        top: 0;
        width: 100%;
        bottom: 6px;
        visibility: hidden;
        opacity: 0;
        transition: 100ms;
    }

    .miaosha-calendar-box #miaosha_calendar .loading-img {
        width: 100px;
        margin: 50px auto;
        display: block;
    }

    .miaosha-calendar-box #miaosha_calendar.loading {
        visibility: visible;
        opacity: 1;
    }

    .miaosha-calendar-box .xdsoft_date > div {
        opacity: 0;
    }

    .miaosha-calendar-box .xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_other_month,
    .miaosha-calendar-box .xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_disabled,
    .miaosha-calendar-box .xdsoft_datetimepicker .xdsoft_time_box > div > div.xdsoft_disabled {
        opacity: 1;
    }

    .miaosha-calendar-box .xdsoft_date .miaosha-box {
        opacity: 1;
        display:;
    }

    .miaosha-calendar-box .miaosha-date {
        font-size: 18px;
        padding: 5px 0;
    }

    .miaosha-calendar-box .no-miaosha,
    .miaosha-calendar-box .has-miaosha {
        display: inline-block;
        padding: 2px 4px;
        background: rgba(0, 0, 0, 0.05);
        color: rgba(0, 0, 0, 0.25);
        border-radius: 2px;
    }

    .miaosha-calendar-box .has-miaosha {
        background: rgba(0, 102, 212, 0.22);
        color: rgba(27, 41, 60, 0.8);
    }

    .miaosha-calendar-box .xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_current,
    .miaosha-calendar-box .xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_current:hover {
        background: #f5f5f5 !important;
        color: #1e7bdd !important;
        box-shadow: none;
    }

    .miaosha-calendar-box .miaosha-box.pointer {
        cursor: pointer;
    }
</style>

<div class="panel mb-3">
    <div class="panel-header">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/miaosha/goods']) ?>">按商品查看</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?= $urlManager->createUrl(['mch/miaosha/calendar']) ?>">按日历查看</a>
            </li>
        </ul>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/miaosha/goods-edit']) ?>">添加秒杀商品</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <div>
            <div class="miaosha-calendar-box">
                <div id="miaosha_calendar">
                    <img class="loading-img" src="<?= Yii::$app->request->baseUrl ?>/statics/images/loading2.svg">
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal miaosha-deteil" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">秒杀安排表</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-loading text-center p-3">
                    <img src="<?= Yii::$app->request->baseUrl ?>/statics/images/loading2.svg" style="width: 80px">
                </div>
                <div class="my-modal-content"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.bootcss.com/jquery-datetimepicker/2.5.12/jquery.datetimepicker.full.min.js"></script>
<script>
    var miaosha_data = null;
    var setDisableDate = function (picker, current_time) {
        if (!current_time)
            current_time = new Date();
        var minDate = new Date(current_time);
        var maxDate = new Date(current_time);
        minDate.setDate(1);
        maxDate.setMonth(maxDate.getMonth() + 1);
        maxDate.setDate(1);
        maxDate.setDate(maxDate.getDate() - 1);
        picker.setOptions({
            minDate: minDate,
            maxDate: maxDate,
        });
    };

    var loadMiaoshaData = function (date) {
        if (miaosha_data) {
            setData(miaosha_data);
        } else {
            var ct = new Date();
            var month = (date.getFullYear() - ct.getFullYear()) * 12 + (date.getMonth() - ct.getMonth());
            $('#miaosha_calendar').addClass('loading');
            $.ajax({
                data: {
                    month: month,
                },
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        miaosha_data = res.data.list;
                        setData(res.data.list);
                    }
                }
            });
        }

        function setData(list) {
            $('.miaosha-calendar-box td.xdsoft_date').each(function () {
                if ($(this).hasClass('xdsoft_other_month'))
                    return;
                $(this).addClass('xdsoft_disabled');
                var y = parseInt($(this).attr('data-year'));
                var m = parseInt($(this).attr('data-month')) + 1;
                var d = parseInt($(this).attr('data-date'));
                m = m < 10 ? ("0" + m) : ("" + m);
                d = d < 10 ? ("0" + d) : ("" + d);
                var date = y + "" + m + "" + d;
                var miaosha = list[date];
                var html;
                if (miaosha) {
                    html = '<div class="miaosha-box pointer" onclick="miaoshaBoxClick(this)" data-date=' + miaosha.open_date + '>' +
                        '<div class="miaosha-date">' + parseInt(d) + '</div>' +
                        '<div class="has-miaosha">' + miaosha.miaosha_count + '条秒杀安排</div>' +
                        '</div>';
                } else {
                    html = '<div class="miaosha-box">' +
                        '<div class="miaosha-date">' + parseInt(d) + '</div>' +
                        '<div class="no-miaosha">暂无秒杀</div>' +
                        '</div>';
                }
                $(this).html(html);
            });
            $('#miaosha_calendar').removeClass('loading');
        }

    };

    jQuery.datetimepicker.setLocale('zh');
    jQuery('#miaosha_calendar').datetimepicker({
        format: 'd-m-Y',
        inline: true,
        todayButton: false,
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        dayOfWeekStart: 1,
        onShow: function (current_time) {
            setDisableDate(this, current_time);
            loadMiaoshaData(current_time);
        },
        onChangeMonth: function (current_time) {
            miaosha_data = null;
            setDisableDate(this, current_time);
            loadMiaoshaData(current_time);
        },
        onGenerate: function (current_time) {
            loadMiaoshaData(current_time);
        },
    });
    $('#miaosha_calendar').datetimepicker('show');

    function miaoshaBoxClick(e) {
        $('.miaosha-deteil .modal-title').html('秒杀安排表');
        $('.miaosha-deteil .modal-loading').show();
        $('.miaosha-deteil .my-modal-content').hide();
        $('.miaosha-deteil').modal('show');
        $.ajax({
            url: '<?= $urlManager->createUrl(["mch/miaosha/date"])?>',
            data: {
                date: e.dataset.date,
            },
            dataType: "json",
            success: function (res) {
                $('.miaosha-deteil .modal-loading').hide();
                $('.miaosha-deteil .modal-title').html(res.data.title);
                $('.miaosha-deteil .my-modal-content').html(res.data.content).show();
            }
        });
    }
</script>
