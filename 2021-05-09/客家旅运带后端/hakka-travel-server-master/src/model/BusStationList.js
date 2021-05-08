/**
 *  BusStationList Model
 *
 * */

const Sequelize = require('Sequelize');
const sequelize = require('../../db.js');

const BusStationList = sequelize.define(
    'busStationList', {
        busStationId: {
            field: 'bus_station_id',
            primaryKey: true,
            type: Sequelize.BIGINT,
            allowNull: null
        },
        busCityId: {
            field: 'bus_city_id',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        stationName: {
            field: 'station_name',
            type: Sequelize.STRING,
            allowNull: true
        },
        isDelete: {
            field: 'is_delete',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        stationType: {
            field: 'station_type',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        stationOrder: {
            field: 'station_order',
            type: Sequelize.INTEGER,
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
        tableName: 't_bus_station_list',
        freezeTableName: false
    }
);

module.exports.BusStationList = BusStationList;


