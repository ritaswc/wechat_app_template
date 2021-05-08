// /**
//  * Created by Administrator on 2017/3/7.
//  */
// var webpack = require('webpack');
// var path = require('path');
// //用了打包的
// module.exports = {
//     //页面入口文件配置
//     entry: {
//         index: './webapp/script/js/index.js'
//         , activity: './webapp/script/js/activity.js'
//         , beauty: './webapp/script/js/beauty.js'
//         , center: './webapp/script/js/center.js'
//         , comment: './webapp/script/js/comment.js'
//         , express: './webapp/script/js/express.js'
//         , gift: './webapp/script/js/gift.js'
//         , goods: './webapp/script/js/goods.js'
//         , goodsOrder: './webapp/script/js/goodsOrder.js'
//         , integral: './webapp/script/js/integral.js'
//         , integralDetail: './webapp/script/js/integralDetail.js'
//         , myAddress: './webapp/script/js/myAddress.js'
//         , myBooking: './webapp/script/js/myBooking.js'
//         , myCoupon: './webapp/script/js/myCoupon.js'
//         , myOrder: './webapp/script/js/myOrder.js'
//         , nearby: './webapp/script/js/nearby.js'
//         , order: './webapp/script/js/order.js'
//         , product: './webapp/script/js/product.js'
//         , sales: './webapp/script/js/sales.js'
//         , shop: './webapp/script/js/shop.js'
//         , storeDetail: './webapp/script/js/storeDetail.js'
//     },
//     //入口文件输出配置
//     output: {
//         path: path.resolve(__dirname, 'webapp/js'),
//         filename: '[name].js'
//     },
//     module: {
//         //加载器配置
//         loaders: [
//             //{ test: /\.css$/, loader: 'style-loader!css-loader' },
//             {test: /\.js$/, loader: 'jsx-loader?harmony'}
//             //{ test: /\.scss$/, loader: 'style!css!sass?sourceMap'},
//             //{ test: /\.(png|jpg)$/, loader: 'url-loader?limit=8192'}
//         ]
//     },
//     //其它解决方案配置
//     resolve: {
//         //root: __dirname + '/src', //绝对路径
//         extensions: ['.js', '.json', '.scss']
//         //alias: {
//         //    AppStore : 'js/stores/AppStores.js',
//         //    ActionType : 'js/actions/ActionType.js',
//         //    AppAction : 'js/actions/AppAction.js'
//         //}
//     }
// };