/**
 *  BusInfo Model
 *
 * */

const Sequelize = require('Sequelize');
const sequelize = require('../../db.js');

const BusInfo = sequelize.define(
    'busInfo', {
        bus_id: {
            field: 'bus_id',
            primaryKey: true,
            type: Sequelize.BIGINT,
            allowNull: null
        },
        busCityId: {
            field: 'bus_city_id',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        busCode: {
            field: 'bus_code',
            type: Sequelize.STRING,
            allowNull: true
        },
        departTime: {
            field: 'depart_time',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        arriveTime: {
            field: 'arrive_time',
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
        tableName: 't_bus_info',
        freezeTableName: false
    }
);

module.exports.BusInfo = BusInfo;

