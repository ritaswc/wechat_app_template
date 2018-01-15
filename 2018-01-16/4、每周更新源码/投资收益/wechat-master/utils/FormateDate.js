 // 时间戳格式化
function FormateDate(timestamp ,format){			
    var _date, _year, _month, _day, _hour, _minute, _second;
    _date = new Date(timestamp * 1000);//时间戳要乘1000
    _year = _date.getFullYear();
    _month = (_date.getMonth() + 1 < 10) ? ('0' + (_date.getMonth() + 1)) : (_date.getMonth() + 1);
    _day = (_date.getDate() < 10) ? ('0' + _date.getDate()) : (_date.getDate());
    _hour = (_date.getHours() < 10) ? ('0' + _date.getHours()) : (_date.getHours());
    _minute = (_date.getMinutes() < 10) ? ('0' + _date.getMinutes()) : (_date.getMinutes());
    _second = (_date.getSeconds() < 10) ? ('0' + _date.getSeconds()) : (_date.getSeconds());
    if (format == 'Y-m-d h:m:s') {
        return (_year + '-' + _month + '-' + _day + ' ' + _hour + ':' + _minute + ':' + _second);
    } else if (format == 'Y-m-d') {
        return (_year + '-' + _month + '-' + _day);
    } else if (format == 'm-d') {
        return (_month + '-' + _day);
    } else if (format == 'm-d h:m:s') {
        return (_month + '-' + _day + ' ' + _hour + ':' + _minute + ':' + _second);
    } else if (format == 'm-d h:m') {
        return (_month + '-' + _day + ' ' + _hour + ':' + _minute);
    } else if(format == 'h:m:s'){
            return ( _hour + ':' + _minute + ':' + _second);
    } else if(format == 'Y-m-d h:m'){
        return (_year + '-' + _month + '-' + _day + ' ' + _hour + ':' + _minute);
    } else if(format == 'h:m'){
        return ( _hour + ':' + _minute);
    }
    else {
        return 0;
    }
}
module.exports = {
  FormateDate: FormateDate
}
