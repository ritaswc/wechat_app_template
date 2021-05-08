const stringUtil = require('../util/stringUtil.js');
const Sequelize = require('Sequelize');
const sequelize = require('../../db.js');
const BusStationList = require('../model/BusStationList.js');

const list = function(condition) {

    let stationType = condition["stationType"];
    let busCityId = condition["busCityId"];

    let sql = 'select * from t_bus_station_list where is_delete=0 and station_type=' + stationType + ' and bus_city_id = ' + busCityId +
        ' order by station_order asc';

    return sequelize.query(sql, {type: Sequelize.SELECT})
};

module.exports = {
    list: list
};