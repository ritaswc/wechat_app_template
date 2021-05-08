/**
 *  BusOrder Model
 *
 * */
const Sequelize = require('Sequelize');
const sequelize = require('../../db.js');

const BusOrder = sequelize.define(
    'busOrder', {
        orderId: {
            field: 'order_id',
            primaryKey: true,
            type: Sequelize.BIGINT,
            allowNull: null
        },
        ordersn: {
            field: 'order_sn',
            type: Sequelize.STRING,
            allowNull: true
        },
        busScheId: {
            field: 'bus_sche_id',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        busScheCode: {
            field: 'bus_sche_code',
            type: Sequelize.STRING,
            allowNull: true
        },
        userId: {
            field: 'user_id',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        startStation: {
            field: 'start_station',
            type: Sequelize.STRING,
            allowNull: true
        },
        endStation: {
            field: 'end_station',
            type: Sequelize.STRING,
            allowNull: true
        },
        passenger: {
            field: 'passenger',
            type: Sequelize.STRING,
            allowNull: true
        },
        passengerPhone: {
            field: 'passenger_phone',
            type: Sequelize.STRING,
            allowNull: true
        },
        isBenefit: {
            field: 'is_benefit',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        sellingPrice: {
            field: 'selling_price',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        totalPrice: {
            field: 'total_price',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        realPayment: {
            field: 'real_payment',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        ticketNum: {
            field: 'ticket_num',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        placeTime: {
            field: 'place_time',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        orderStatus: {
            field: 'order_status',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        orderRemark: {
            field: 'real_payment',
            type: Sequelize.STRING,
            allowNull: true
        },
        payType: {
            field: 'pay_type',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        payStatus: {
            field: 'pay_status',
            type: Sequelize.INTEGER,
            allowNull: true
        },
        originOrderId: {
            field: 'origin_order_id',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        newOrderId: {
            field: 'new_order_id',
            type: Sequelize.BIGINT,
            allowNull: true
        },
        orderQrcode: {
            field: 'order_qrcode',
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
        tableName: 't_bus_order',
        freezeTableName: false
    }
);

module.exports.BusOrder = BusOrder;