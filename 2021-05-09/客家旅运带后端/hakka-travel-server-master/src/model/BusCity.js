/**
 *  BusCity Model
 *
 * */

const Sequelize = require('Sequelize');
const sequelize = require('../../db.js');

const BusCity = sequelize.define(
    'busCity', {
        busCityId: {
            field: 'bus_city_id',
            primaryKey: true,
            type: Sequelize.BIGINT,
            allowNull: null
        },
        startCity: {
            field: 'start_city',
            type: Sequelize.STRING,
            allowNull: true
        },
        endCity: {
            field: 'end_city',
            type: Sequelize.STRING,
            allowNull: true
        },
        isDelete: {
            field: 'is_delete',
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
        tableName: 't_bus_city',
        freezeTableName: false
    }
);

module.exports.BusCity = BusCity;
