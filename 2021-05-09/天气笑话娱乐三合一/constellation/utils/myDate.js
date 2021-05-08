function buildDay(flag) {
    var a = newDate(flag);
    var month = a.getMonth()+1;
    var day = a.getDate();
    return month+"/"+day;
}

//添减天
function newDate(flag) {
    var a = new Date();
    var long = a.valueOf();
    long = long + flag * 24 * 60 * 60 * 1000;
    a = new Date(long);
    return a;
}

module.exports = {
  buildDay: buildDay
}