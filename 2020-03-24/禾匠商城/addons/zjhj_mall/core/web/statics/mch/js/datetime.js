/**
 * Created by Administrator on 2018/7/12.
 */
$(document).ready(function () {
    jQuery.datetimepicker.setLocale('zh');
    jQuery('#date_start').datetimepicker({
        datepicker: true,
        timepicker: true,
        format: 'Y-m-d H:i:s',
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
        timepicker: true,
        format: 'Y-m-d H:i:s',
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
        jQuery('#date_start').datetimepicker('setOptions', {value: mydate});
        jQuery('#date_end').datetimepicker('setOptions', {value: myDate});
    });

    $(document).on('click', '.kj-status', function () {
        var child = $(this).data('children');
        var val = $(this).children("input[type='radio']:checked").val();
        var children = $('.' + child);
        if(val == 0){
            $(children[0]).show();
            $(children[1]).hide();
        }else{
            $(children[0]).hide();
            $(children[1]).show();
        }
    });
});