const constant = {
    // host: 'http://172.31.16.209:8080',
    host: 'https://api.jiyiguan.nowui.com',
    platform: 'WX',
    version: '1.0.0',
    color: '#c81623',
    duration: 2000,
    category_list: [{
        category_id: '',
        category_name: '所有商品',
        category_color: '#fd666b',
        category_image: '/image/apps.png'
    }, {
        category_id: '146474b15ba545d9b9717cf8b5a6c3f5',
        category_name: '肠内营养',
        category_color: '#73b4ef',
        category_image: '/image/discover.png'
    }, {
        category_id: '9ed6cb3551fb4bfaabfeee89cc63f9b4',
        category_name: '快康系列',
        category_color: '#e78ab0',
        category_image: '/image/form.png'
    }, {
        category_id: '34fb354194e0409e8a80a4382a7fa18d',
        category_name: '特殊奶粉',
        category_color: '#7acfa6',
        category_image: '/image/present.png'
    }, {
        category_id: '26ef74aa1bb242479df5305478f31b08',
        category_name: '理疗辅助',
        category_color: '#ffcb63',
        category_image: '/image/punch.png'
    }, {
        category_id: '45ac41e5c3334439a6ac45abdea31a30',
        category_name: '补血系列',
        category_color: '#9f8bea',
        category_image: '/image/shop.png'
    }],
    order_status_list: [{
        order_status_value: '',
        order_status_name: '全部订单',
        order_status_image: ''
    }, {
        order_status_value: 'WAIT_PAY',
        order_status_name: '代付款',
        order_status_image: '/image/pay.png'
    }, {
        order_status_value: 'WAIT_SEND',
        order_status_name: '代发货',
        order_status_image: '/image/deliver.png'
    }, {
        order_status_value: 'WAIT_RECEIVE',
        order_status_name: '代收货',
        order_status_image: '/image/deliver.png'
    }, {
        order_status_value: 'FINISH',
        order_status_name: '已完成',
        order_status_image: '/image/comment.png'
    }]
}

module.exports = constant;