const stringUtil = require('../util/stringUtil.js');
const Sequelize = require('Sequelize');
const sequelize = require('../../db.js');
const BusCity = require("../model/BusCity.js");

// query list
const list = function(condition) {
    let param = stringUtil.fromCamel(Object.keys(condition)[0]);
    let value = condition[Object.keys(condition)[0]];

    let sql = '';
    switch (param) {
        case 'start_city':
            sql += 'select * from t_bus_city where is_delete=0 and start_city = "' + value + '"';
            break;
        case 'end_city':
            sql += 'select * from t_bus_city where is_delete=0 and end_city = "' + value + '"';
            break;
        case 'all_start':
            sql += 'select distinct start_city, bus_city_id from t_bus_city where is_delete=0';
            break;
        case 'all_end':
            sql += 'select distinct end_city, bus_city_id from t_bus_city where is_delete=0';
            break;
        default:
            break;
    }
    return sequelize.query(sql, {type: Sequelize.QueryTypes.SELECT});
};

module.exports = {
    list: list
};