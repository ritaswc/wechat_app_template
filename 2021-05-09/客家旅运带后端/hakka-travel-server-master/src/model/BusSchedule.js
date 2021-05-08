/**
 *  BusOrder Model
 *
 * */
const Sequelize = require('Sequelize');
const sequelize = require('../../db.js');

const BusSchedule = sequelize.define(
    'busSchedule', {
        busScheId: {
            field: 'bus_sche_id',
            primaryKey: true,
            type: Sequelize.BIGINT,
            allowNull: null
        },
        busId: {
            field: 'bus_id',
            type: Sequelize.STRING,
            allowNull: true
        },
        busScheCode: {
            field: 'bus_sche_code',
            type: Sequelize.STRING,
            allowNull: true
        },
        scheduleDate: {
            field: 'schedule_date',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        startSell: {
            field: 'start_sell',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        endSell: {
            field: 'end_sell',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        price: {
            field: 'price',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        benefitPrice: {
            field: 'benefit_price',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        totalTicket: {
            field: 'total_ticket',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        totalLeavings: {
            field: 'total_leavings',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        normalTicket: {
            field: 'normal_ticket',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        normalLeavings: {
            field: 'normal_leavings',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        benefitTicket: {
            field: 'benefit_ticket',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        benefitLeavings: {
            field: 'benefit_leavings',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        isDelete: {
            field: 'is_delete',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        guidePhone: {
            field: 'guide_phone',
            type: Sequelize.STRING,
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
        tableName: 't_bus_schedule',
        freezeTableName: false
    }
);

module.exports.BusSchedule = BusSchedule;

