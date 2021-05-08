/**
 *  BusStationInfo Model
 *
 * */

const Sequelize = require('Sequelize');
const sequelize = require('../../db.js');

const BusStationInfo = sequelize.define(
    'busStationInfo', {
        id: {
            field: 'id',
            primaryKey: true,
            type: Sequelize.BIGINT,
            allowNull: null
        },
        busId: {
            field: 'bus_id',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        stationName: {
            field: 'station_name',
            type: Sequelize.STRING,
            allowNull: true
        },
        stationDepartTime: {
            field: 'station_depart_time',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        dbUpdateTime: {
            field: 'db_update_time',
            type: Sequelize.STRING,
            allowNull: true
        },
        dbCreateTime: {
            field: 'db_create_time',
            type: Sequelize.STRING,
            allowNull: true
        }
    },
    {
        tableName: 't_bus_station_info',
        freezeTableName: false
    }
);

module.exports.BusStationInfo = BusStationInfo;

