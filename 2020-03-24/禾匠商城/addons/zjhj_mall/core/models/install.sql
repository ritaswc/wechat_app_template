-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `hjmall_action_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL DEFAULT '' COMMENT '操作记录描述',
  `addtime` int(11) NOT NULL COMMENT '记录时间',
  `admin_name` varchar(45) NOT NULL DEFAULT '' COMMENT '操作人姓名',
  `admin_id` int(11) NOT NULL COMMENT '操作人ID',
  `admin_ip` varchar(255) DEFAULT NULL COMMENT '操作人IP地址',
  `route` varchar(255) NOT NULL DEFAULT '' COMMENT '操作路由',
  `action_type` varchar(45) NOT NULL DEFAULT '' COMMENT '操作类型',
  `obj_id` int(11) NOT NULL COMMENT '操作数据ID',
  `result` longtext,
  `store_id` int(11) NOT NULL,
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '日志类型:1.操作日志 2.定时任务日志',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启',
  `unit_id` varchar(255) NOT NULL DEFAULT '' COMMENT '广告id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1抽奖首页',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(255) NOT NULL COMMENT '手机号',
  `province_id` int(11) NOT NULL DEFAULT '0',
  `province` varchar(255) NOT NULL COMMENT '省份名称',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `city` varchar(255) NOT NULL COMMENT '城市名称',
  `district_id` int(11) NOT NULL DEFAULT '0',
  `district` varchar(255) NOT NULL COMMENT '县区名称',
  `detail` varchar(1000) NOT NULL COMMENT '详细地址',
  `is_default` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否是默认地址：0=否，1=是',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_default` (`is_default`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收货地址';


CREATE TABLE `hjmall_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `auth_key` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `app_max_count` int(11) NOT NULL DEFAULT '0',
  `permission` longtext,
  `remark` varchar(255) NOT NULL DEFAULT '',
  `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '账户有效期至，0表示永久',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `hjmall_admin_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_name` varchar(255) NOT NULL,
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '1000',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `hjmall_admin_permission` (`id`, `name`, `display_name`, `is_delete`, `sort`) VALUES
(1,	'coupon',	'优惠券',	0,	1000),
(2,	'share',	'分销',	0,	1000),
(3,	'topic',	'专题',	0,	1000),
(4,	'video',	'视频专区',	0,	1000),
(5,	'copyright',	'版权设置',	0,	1000)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `name` = VALUES(`name`), `display_name` = VALUES(`display_name`), `is_delete` = VALUES(`is_delete`), `sort` = VALUES(`sort`);

CREATE TABLE `hjmall_admin_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL COMMENT '帐户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `mobile` varchar(15) NOT NULL COMMENT '手机号',
  `name` varchar(255) NOT NULL COMMENT '姓名/企业名',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '申请原因',
  `addtime` int(11) NOT NULL,
  `status` smallint(1) NOT NULL DEFAULT '0' COMMENT '审核状态：0=待审核，1=通过，2=不通过',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `article_cat_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id：1=关于我们，2=服务中心',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext COMMENT '内容',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序：升序',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统文章';


CREATE TABLE `hjmall_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attr_group_id` int(11) NOT NULL,
  `attr_name` varchar(255) NOT NULL,
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `is_default` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否是默认属性',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_default` (`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品规格';


CREATE TABLE `hjmall_attr_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `attr_group_name` varchar(255) NOT NULL,
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品规格组';


CREATE TABLE `hjmall_auth_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建者ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_auth_role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_auth_role_user` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL COMMENT '商城id',
  `pic_url` varchar(2048) DEFAULT NULL,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `page_url` varchar(2048) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序，升序',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_delete` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否删除：0=未删除，1=已删除',
  `type` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型 【1=> 商城，2=> 拼团】',
  `open_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商店幻灯片';


CREATE TABLE `hjmall_bargain_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `min_price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '最低价',
  `begin_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `time` int(11) NOT NULL DEFAULT '0' COMMENT '砍价小时数',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '砍价方式 0--按人数 1--按价格',
  `status_data` varchar(255) NOT NULL DEFAULT '' COMMENT '砍价方式数据',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_bargain_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_no` varchar(255) NOT NULL DEFAULT '0',
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品售价',
  `min_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品最低价',
  `time` int(11) NOT NULL DEFAULT '0' COMMENT '砍价时间',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态 0--进行中 1--成功 2--失败',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `attr` varchar(255) NOT NULL DEFAULT '',
  `status_data` varchar(255) NOT NULL DEFAULT '' COMMENT '砍价方式数据',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发起砍价订单表';


CREATE TABLE `hjmall_bargain_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `is_print` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否打印 0--否 1--是',
  `is_share` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否参与分销 0--不参与 1--参与',
  `is_sms` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否发送短信 0--否 1--是',
  `is_mail` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否发送邮件 0--否 1--是',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '活动规则',
  `share_title` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_bargain_user_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='砍价插件用户砍价情况；';


CREATE TABLE `hjmall_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '卡券名称',
  `pic_url` varchar(2048) DEFAULT NULL,
  `content` longtext COMMENT '卡券描述',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `attr` longtext NOT NULL COMMENT '规格',
  `mch_id` int(11) NOT NULL DEFAULT '0' COMMENT '入驻商id',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';


CREATE TABLE `hjmall_cash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '申请状态 0--申请中 1--确认申请 2--已打款 3--驳回  5--余额通过',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `pay_time` int(11) NOT NULL COMMENT '付款',
  `type` smallint(1) NOT NULL DEFAULT '0' COMMENT '支付方式 0--微信支付  1--支付宝  2--银行卡  3--余额',
  `mobile` varchar(255) DEFAULT NULL COMMENT '支付宝账号',
  `name` varchar(255) DEFAULT NULL COMMENT '支付宝姓名',
  `bank_name` varchar(30) DEFAULT NULL COMMENT '开户行名称',
  `pay_type` int(11) DEFAULT '0' COMMENT '打款方式 0--之前未统计的 1--微信自动打款 2--手动打款',
  `order_no` varchar(255) DEFAULT NULL COMMENT '微信自动打款订单号',
  `service_charge` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现表';


CREATE TABLE `hjmall_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL COMMENT '商城id',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类id',
  `name` varchar(255) NOT NULL COMMENT '分类名称',
  `pic_url` varchar(2048) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序，升序',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `big_pic_url` varchar(2048) DEFAULT NULL,
  `advert_pic` longtext COMMENT '广告图片',
  `advert_url` varchar(2048) DEFAULT NULL,
  `is_show` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示【1=> 显示，2=> 隐藏】',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `parent_id` (`parent_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_show` (`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类';


CREATE TABLE `hjmall_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rgb` varchar(255) NOT NULL COMMENT 'rgb颜色码 例如："0，0，0"',
  `color` varchar(255) NOT NULL COMMENT '16进制颜色码例如：#ffffff',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='颜色库';

INSERT INTO `hjmall_color` (`id`, `rgb`, `color`, `is_delete`, `addtime`) VALUES
(1,	'{\"r\":\"51\",\"g\":\"51\",\"b\":\"51\"}',	'#333333',	0,	0),
(2,	'{\"r\":\"255\",\"g\":\"69\",\"b\":\"68\"}',	'#ff4544',	0,	0),
(3,	'{\"r\":\"255\",\"g\":\"255\",\"b\":\"255\"}',	'#ffffff',	0,	0),
(4,	'{\"r\":\"239\",\"g\":\"174\",\"b\":\"57\"}',	'#EFAE39',	0,	0),
(6,	'{\"r\":\"88\",\"g\":\"228\",\"b\":\"88\"}',	'#58E458',	0,	0),
(7,	'{\"r\":\"9\",\"g\":\"122\",\"b\":\"220\"}',	'#097ADC',	0,	0),
(8,	'{\"r\":\"164\",\"g\":\"62\",\"b\":\"228\"}',	'#A43EE4',	0,	0)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `rgb` = VALUES(`rgb`), `color` = VALUES(`color`), `is_delete` = VALUES(`is_delete`), `addtime` = VALUES(`addtime`);

CREATE TABLE `hjmall_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '优惠券名称',
  `desc` varchar(2000) NOT NULL DEFAULT '',
  `pic_url` varchar(2048) DEFAULT NULL,
  `discount_type` smallint(6) NOT NULL DEFAULT '1' COMMENT '优惠券类型：1=折扣，2=满减',
  `min_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最低消费金额',
  `sub_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `discount` decimal(3,1) NOT NULL DEFAULT '10.0' COMMENT '折扣率',
  `expire_type` smallint(1) NOT NULL DEFAULT '1' COMMENT '到期类型：1=领取后N天过期，2=指定有效期',
  `expire_day` int(11) NOT NULL DEFAULT '0' COMMENT '有效天数，expire_type=1时',
  `begin_time` int(11) NOT NULL DEFAULT '0' COMMENT '有效期开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '有效期结束时间',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `total_count` int(11) NOT NULL DEFAULT '-1' COMMENT '发放总数量',
  `is_join` smallint(6) NOT NULL DEFAULT '1' COMMENT '是否加入领券中心 1--不加入领券中心 2--加入领券中心',
  `sort` int(11) DEFAULT '100' COMMENT '排序按升序排列',
  `cat_id_list` varchar(255) DEFAULT NULL,
  `appoint_type` tinyint(1) DEFAULT NULL,
  `is_integral` smallint(6) NOT NULL DEFAULT '1' COMMENT '是否加入积分商城 1--不加入 2--加入',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '兑换需要积分数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `total_num` int(11) NOT NULL DEFAULT '0' COMMENT '积分商城发放总数',
  `user_num` int(11) NOT NULL DEFAULT '0' COMMENT '每人限制兑换数量',
  `rule` varchar(1000) DEFAULT '' COMMENT '使用说明',
  `goods_id_list` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_join` (`is_join`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券';


CREATE TABLE `hjmall_coupon_auto_send` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `event` int(11) NOT NULL DEFAULT '1' COMMENT '触发事件：1=分享，2=购买并付款',
  `send_times` int(11) NOT NULL DEFAULT '1' COMMENT '最多发放次数，0表示不限制',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券自动发放';


CREATE TABLE `hjmall_delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `express_id` int(11) DEFAULT NULL COMMENT '快递公司id',
  `customer_name` varchar(255) DEFAULT NULL COMMENT '电子面单客户账号',
  `customer_pwd` varchar(255) DEFAULT NULL COMMENT '电子面单密码',
  `month_code` varchar(255) DEFAULT NULL COMMENT '月结编码',
  `send_site` varchar(255) DEFAULT NULL COMMENT '网点编码',
  `send_name` varchar(255) DEFAULT NULL COMMENT '网点名称',
  `is_delete` smallint(2) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `template_size` varchar(255) DEFAULT NULL COMMENT '快递鸟电子面单模板规格',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_diy_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `title` varchar(45) NOT NULL DEFAULT '' COMMENT '页面标题',
  `template_id` int(11) NOT NULL COMMENT '模板ID',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0--禁用 1--启用',
  `is_index` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否覆盖首页 0--不覆盖 1--覆盖',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_diy_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL DEFAULT '' COMMENT '模板名称',
  `template` longtext NOT NULL COMMENT '模板数据',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '100',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '数据类型：kdniao=快递鸟',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='快递公司';

INSERT INTO `hjmall_express` (`id`, `name`, `code`, `sort`, `type`, `is_delete`) VALUES
(1,	'顺丰快递',	'SF',	1,	'kdniao',	0),
(2,	'申通快递',	'STO',	1,	'kdniao',	0),
(3,	'韵达快递',	'YD',	1,	'kdniao',	0),
(4,	'圆通速递',	'YTO',	1,	'kdniao',	0),
(5,	'中通速递',	'ZTO',	1,	'kdniao',	0),
(6,	'百世快递',	'HTKY',	1,	'kdniao',	0),
(7,	'EMS',	'EMS',	2,	'kdniao',	0),
(8,	'天天快递',	'HHTT',	2,	'kdniao',	0),
(9,	'邮政平邮/小包',	'YZPY',	2,	'kdniao',	0),
(10,	'宅急送',	'ZJS',	2,	'kdniao',	0),
(11,	'国通快递',	'GTO',	5,	'kdniao',	0),
(12,	'全峰快递',	'QFKD',	5,	'kdniao',	0),
(13,	'优速快递',	'UC',	5,	'kdniao',	0),
(14,	'中铁快运',	'ZTKY',	5,	'kdniao',	0),
(15,	'中铁物流',	'ZTWL',	5,	'kdniao',	0),
(16,	'亚马逊物流',	'AMAZON',	5,	'kdniao',	0),
(17,	'城际快递',	'CJKD',	5,	'kdniao',	0),
(18,	'德邦',	'DBL',	5,	'kdniao',	0),
(19,	'汇丰物流',	'HFWL',	5,	'kdniao',	0),
(20,	'百世快运',	'BTWL',	100,	'kdniao',	0),
(21,	'安捷快递',	'AJ',	100,	'kdniao',	0),
(22,	'安能物流',	'ANE',	100,	'kdniao',	0),
(23,	'安信达快递',	'AXD',	100,	'kdniao',	0),
(24,	'北青小红帽',	'BQXHM',	100,	'kdniao',	0),
(25,	'百福东方',	'BFDF',	100,	'kdniao',	0),
(26,	'CCES快递',	'CCES',	100,	'kdniao',	0),
(27,	'城市100',	'CITY100',	100,	'kdniao',	0),
(28,	'COE东方快递',	'COE',	100,	'kdniao',	0),
(29,	'长沙创一',	'CSCY',	100,	'kdniao',	0),
(30,	'成都善途速运',	'CDSTKY',	100,	'kdniao',	0),
(31,	'D速物流',	'DSWL',	100,	'kdniao',	0),
(32,	'大田物流',	'DTWL',	100,	'kdniao',	0),
(33,	'快捷速递',	'FAST',	100,	'kdniao',	0),
(34,	'FEDEX联邦(国内件）',	'FEDEX',	100,	'kdniao',	0),
(35,	'FEDEX联邦(国际件）',	'FEDEX_GJ',	100,	'kdniao',	0),
(36,	'飞康达',	'FKD',	100,	'kdniao',	0),
(37,	'广东邮政',	'GDEMS',	100,	'kdniao',	0),
(38,	'共速达',	'GSD',	100,	'kdniao',	0),
(39,	'高铁速递',	'GTSD',	100,	'kdniao',	0),
(40,	'恒路物流',	'HLWL',	100,	'kdniao',	0),
(41,	'天地华宇',	'HOAU',	100,	'kdniao',	0),
(42,	'华强物流',	'hq568',	100,	'kdniao',	0),
(43,	'华夏龙物流',	'HXLWL',	100,	'kdniao',	0),
(44,	'好来运快递',	'HYLSD',	100,	'kdniao',	0),
(45,	'京广速递',	'JGSD',	100,	'kdniao',	0),
(46,	'九曳供应链',	'JIUYE',	100,	'kdniao',	0),
(47,	'佳吉快运',	'JJKY',	100,	'kdniao',	0),
(48,	'嘉里物流',	'JLDT',	100,	'kdniao',	0),
(49,	'捷特快递',	'JTKD',	100,	'kdniao',	0),
(50,	'急先达',	'JXD',	100,	'kdniao',	0),
(51,	'晋越快递',	'JYKD',	100,	'kdniao',	0),
(52,	'加运美',	'JYM',	100,	'kdniao',	0),
(53,	'佳怡物流',	'JYWL',	100,	'kdniao',	0),
(54,	'跨越物流',	'KYWL',	100,	'kdniao',	0),
(55,	'龙邦快递',	'LB',	100,	'kdniao',	0),
(56,	'联昊通速递',	'LHT',	100,	'kdniao',	0),
(57,	'民航快递',	'MHKD',	100,	'kdniao',	0),
(58,	'明亮物流',	'MLWL',	100,	'kdniao',	0),
(59,	'能达速递',	'NEDA',	100,	'kdniao',	0),
(60,	'平安达腾飞快递',	'PADTF',	100,	'kdniao',	0),
(61,	'全晨快递',	'QCKD',	100,	'kdniao',	0),
(62,	'全日通快递',	'QRT',	100,	'kdniao',	0),
(63,	'如风达',	'RFD',	100,	'kdniao',	0),
(64,	'赛澳递',	'SAD',	100,	'kdniao',	0),
(65,	'圣安物流',	'SAWL',	100,	'kdniao',	0),
(66,	'盛邦物流',	'SBWL',	100,	'kdniao',	0),
(67,	'上大物流',	'SDWL',	100,	'kdniao',	0),
(68,	'盛丰物流',	'SFWL',	100,	'kdniao',	0),
(69,	'盛辉物流',	'SHWL',	100,	'kdniao',	0),
(70,	'速通物流',	'ST',	100,	'kdniao',	0),
(71,	'速腾快递',	'STWL',	100,	'kdniao',	0),
(72,	'速尔快递',	'SURE',	100,	'kdniao',	0),
(73,	'唐山申通',	'TSSTO',	100,	'kdniao',	0),
(74,	'全一快递',	'UAPEX',	100,	'kdniao',	0),
(75,	'万家物流',	'WJWL',	100,	'kdniao',	0),
(76,	'万象物流',	'WXWL',	100,	'kdniao',	0),
(77,	'新邦物流',	'XBWL',	100,	'kdniao',	0),
(78,	'信丰快递',	'XFEX',	100,	'kdniao',	0),
(79,	'希优特',	'XYT',	100,	'kdniao',	0),
(80,	'新杰物流',	'XJ',	100,	'kdniao',	0),
(81,	'源安达快递',	'YADEX',	100,	'kdniao',	0),
(82,	'远成物流',	'YCWL',	100,	'kdniao',	0),
(83,	'义达国际物流',	'YDH',	100,	'kdniao',	0),
(84,	'越丰物流',	'YFEX',	100,	'kdniao',	0),
(85,	'原飞航物流',	'YFHEX',	100,	'kdniao',	0),
(86,	'亚风快递',	'YFSD',	100,	'kdniao',	0),
(87,	'运通快递',	'YTKD',	100,	'kdniao',	0),
(88,	'亿翔快递',	'YXKD',	100,	'kdniao',	0),
(89,	'增益快递',	'ZENY',	100,	'kdniao',	0),
(90,	'汇强快递',	'ZHQKD',	100,	'kdniao',	0),
(91,	'众通快递',	'ZTE',	100,	'kdniao',	0),
(92,	'中邮物流',	'ZYWL',	100,	'kdniao',	0),
(93,	'速必达物流',	'SUBIDA',	100,	'kdniao',	0),
(94,	'瑞丰速递',	'RFEX',	100,	'kdniao',	0),
(95,	'快客快递',	'QUICK',	100,	'kdniao',	0),
(96,	'CNPEX中邮快递',	'CNPEX',	100,	'kdniao',	0),
(97,	'鸿桥供应链',	'HOTSCM',	100,	'kdniao',	0),
(98,	'海派通物流公司',	'HPTEX',	100,	'kdniao',	0),
(99,	'澳邮专线',	'AYCA',	100,	'kdniao',	0),
(100,	'泛捷快递',	'PANEX',	100,	'kdniao',	0),
(101,	'PCA Express',	'PCA',	100,	'kdniao',	0),
(102,	'UEQ Express',	'UEQ',	100,	'kdniao',	0)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `name` = VALUES(`name`), `code` = VALUES(`code`), `sort` = VALUES(`sort`), `type` = VALUES(`type`), `is_delete` = VALUES(`is_delete`);

CREATE TABLE `hjmall_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='我喜欢的商品';


CREATE TABLE `hjmall_file_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `is_default` int(11) DEFAULT '0' COMMENT '是否可编辑',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_default` (`is_default`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片库分组';


CREATE TABLE `hjmall_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `type` varchar(255) DEFAULT NULL COMMENT '类型',
  `required` int(11) DEFAULT NULL COMMENT '必填项',
  `default` varchar(255) DEFAULT NULL COMMENT '默认值',
  `tip` varchar(255) DEFAULT NULL COMMENT '提示语',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_form_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL COMMENT '店铺id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `wechat_open_id` varchar(255) NOT NULL DEFAULT '' COMMENT '微信openid',
  `form_id` varchar(255) NOT NULL DEFAULT '',
  `order_no` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '可选值：form_id或prepay_id',
  `send_count` int(11) NOT NULL DEFAULT '0' COMMENT '使用次数',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_usable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用于判断 prepay_id 是否有效',
  PRIMARY KEY (`id`),
  KEY `order_no` (`order_no`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小程序form_id和prepay_id记录';


CREATE TABLE `hjmall_free_delivery_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `city` longtext,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_fxhb_hongbao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_num` int(11) NOT NULL COMMENT '拆红包所需用户数,最少2人',
  `coupon_total_money` decimal(10,2) NOT NULL COMMENT '红包总金额',
  `coupon_money` decimal(10,2) NOT NULL COMMENT '分到的红包金额',
  `coupon_use_minimum` decimal(10,2) NOT NULL COMMENT '红包使用最低消费金额',
  `coupon_expire` int(11) NOT NULL DEFAULT '30' COMMENT '优惠券有效期，天',
  `distribute_type` tinyint(1) NOT NULL COMMENT '红包分配类型：0=随机，1=平分',
  `is_expire` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已过期',
  `expire_time` int(11) NOT NULL COMMENT '到期时间',
  `is_finish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已完成',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `form_id` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序form_id',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_expire` (`is_expire`),
  KEY `is_finish` (`is_finish`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_fxhb_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_num` int(11) NOT NULL DEFAULT '2' COMMENT '拆红包所需用户数,最少2人',
  `coupon_total_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包总金额',
  `coupon_use_minimum` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '赠送的优惠券最低消费金额',
  `coupon_expire` int(11) NOT NULL DEFAULT '30' COMMENT '红包优惠券有效期',
  `distribute_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '红包分配类型：0=随机，1=平分',
  `tpl_msg_id` varchar(255) NOT NULL DEFAULT '' COMMENT '红包到账通知模板消息id',
  `game_time` int(11) NOT NULL DEFAULT '24' COMMENT '每个红包有效期,单位：小时',
  `game_open` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启活动，0=不开启，1=开启',
  `rule` longtext COMMENT '规则',
  `share_pic` longtext,
  `share_title` longtext,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价（只做显示用）',
  `detail` longtext NOT NULL COMMENT '商品详情，图文',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品类别',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '上架状态：0=下架，1=上架',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `attr` longtext NOT NULL COMMENT '规格的库存及价格',
  `service` varchar(2000) NOT NULL DEFAULT '' COMMENT '商品服务选项',
  `sort` int(11) NOT NULL DEFAULT '1000' COMMENT '排序  升序',
  `virtual_sales` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟销量',
  `cover_pic` longtext COMMENT '商品缩略图',
  `video_url` varchar(2048) DEFAULT NULL,
  `unit` varchar(255) NOT NULL DEFAULT '件' COMMENT '单位',
  `individual_share` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否单独分销设置：0=否，1=是',
  `share_commission_first` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级分销佣金比例',
  `share_commission_second` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级分销佣金比例',
  `share_commission_third` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '三级分销佣金比例',
  `weight` double(10,2) unsigned DEFAULT '0.00' COMMENT '重量',
  `freight` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '运费模板ID',
  `full_cut` longtext COMMENT '满减',
  `integral` text COMMENT '积分设置',
  `use_attr` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否使用规格：0=不使用，1=使用',
  `share_type` int(11) DEFAULT '0' COMMENT '佣金配比 0--百分比 1--固定金额',
  `quick_purchase` smallint(1) DEFAULT NULL COMMENT '是否加入快速购买  0--关闭   1--开启',
  `hot_cakes` smallint(1) DEFAULT NULL COMMENT '是否加入热销  0--关闭   1--开启',
  `cost_price` decimal(10,2) DEFAULT '0.00',
  `member_discount` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否参与会员折扣  0=参与   1=不参与',
  `rebate` decimal(10,2) DEFAULT '0.00' COMMENT '自购返利',
  `mch_id` int(11) NOT NULL DEFAULT '0' COMMENT '入驻商户的id',
  `goods_num` int(11) NOT NULL DEFAULT '0' COMMENT '商品总库存',
  `mch_sort` int(11) NOT NULL DEFAULT '1000' COMMENT '多商户自己的排序',
  `confine_count` int(11) NOT NULL DEFAULT '0' COMMENT '购买限制:0.不限制|大于0等于限购数量',
  `is_level` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否享受会员折扣 0-不享受 1--享受',
  `type` smallint(1) NOT NULL DEFAULT '0' COMMENT '类型 0--商城或多商户 2--砍价商品',
  `is_negotiable` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否面议 0 不面议 1面议',
  `attr_setting_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销设置类型 0.普通设置|1.详细设置',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `cat_id` (`cat_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品';


CREATE TABLE `hjmall_goods_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL COMMENT '卡券id',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_goods_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL COMMENT '分类id',
  `addtime` int(11) DEFAULT NULL,
  `is_delete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `store_id` (`store_id`),
  KEY `cat_id` (`cat_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类';


CREATE TABLE `hjmall_goods_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `pic_url` varchar(2048) DEFAULT NULL,
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_goods_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT '0' COMMENT '商品类型 0--拼团',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `individual_share` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否单独分销设置：0=否，1=是',
  `share_commission_first` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级分销佣金比例',
  `share_commission_second` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级分销佣金比例',
  `share_commission_third` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '三级分销佣金比例',
  `share_type` int(11) DEFAULT '0' COMMENT '佣金配比 0--百分比 1--固定金额',
  `rebate` decimal(10,2) DEFAULT '0.00' COMMENT '自购返利',
  `attr_setting_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销设置类型 0.普通设置|1.详细设置',
  `relation_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联临时分销佣金ID，秒杀商品设置|拼团阶梯团',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分销设置；';


CREATE TABLE `hjmall_gwd_buy_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  `addtime` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL COMMENT '0.商城|1.秒杀|2.拼团',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_gwd_like_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `good_id` int(11) NOT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  `addtime` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL COMMENT '0.商城|1.秒杀|2.拼团',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_gwd_like_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `like_id` int(11) NOT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_gwd_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_home_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `data` longtext,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `style` int(11) DEFAULT '0' COMMENT '板块样式 0--默认样式 1--样式一 2--样式二 。。。',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首页自定义版块';


CREATE TABLE `hjmall_home_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '图标名称',
  `url` varchar(2048) DEFAULT NULL,
  `open_type` varchar(255) NOT NULL DEFAULT '' COMMENT '打开方式',
  `pic_url` varchar(2048) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序，升序',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `is_hide` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏 0 显示 1隐藏 ',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首页导航图标';


CREATE TABLE `hjmall_integral_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `is_delete` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '商品名称',
  `pic_url` longtext NOT NULL COMMENT '分类图片url',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序，升序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_integral_coupon_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `coupon_id` int(11) NOT NULL COMMENT '优惠券ID',
  `order_no` varchar(255) NOT NULL COMMENT '订单号',
  `is_pay` int(11) NOT NULL COMMENT '是否支付  0-未支付   1-支付 纯积分兑换',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分数量',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_integral_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `original_price` decimal(10,2) NOT NULL COMMENT '原价（只做显示用）',
  `detail` longtext NOT NULL COMMENT '商品详情，图文',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品类别',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '上架状态：0=下架，1=上架',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `attr` longtext NOT NULL COMMENT '规格的库存及价格',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序  升序',
  `virtual_sales` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟销量',
  `cover_pic` longtext COMMENT '商品缩略图',
  `unit` varchar(255) NOT NULL DEFAULT '件' COMMENT '单位',
  `weight` double(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '重量',
  `freight` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '运费模板ID',
  `use_attr` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否使用规格：0=不使用，1=使用',
  `goods_num` int(11) NOT NULL DEFAULT '0' COMMENT '商品总库存',
  `integral` int(11) NOT NULL COMMENT '所需积分',
  `service` varchar(2000) NOT NULL DEFAULT '' COMMENT '商品服务选项',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_pic_list` longtext,
  `is_index` smallint(1) NOT NULL DEFAULT '0' COMMENT '放置首页：0=不放，1=放',
  `user_num` int(11) NOT NULL COMMENT '每人限制兑换数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_integral_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `content` longtext NOT NULL COMMENT '描述',
  `integral` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `addtime` int(11) unsigned NOT NULL COMMENT '添加时间',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `operator` varchar(255) NOT NULL COMMENT '操作者',
  `store_id` int(11) unsigned NOT NULL,
  `operator_id` int(11) unsigned NOT NULL COMMENT '分销商id',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '数据类型 0--积分修改 1--余额修改',
  `pic_url` varchar(255) NOT NULL DEFAULT '',
  `explain` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_integral_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `order_no` varchar(255) NOT NULL COMMENT '订单号',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总费用(包含运费）',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付总费用(含运费）',
  `express_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `name` varchar(255) NOT NULL DEFAULT '0' COMMENT '收货人姓名',
  `mobile` varchar(255) NOT NULL DEFAULT '0' COMMENT '收货人手机',
  `address` varchar(1000) NOT NULL DEFAULT '0' COMMENT '收货地址',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '订单备注',
  `is_pay` smallint(6) NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付',
  `pay_type` smallint(6) NOT NULL DEFAULT '0' COMMENT '支付方式：0--在线支付未支付 1=支付 ',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `is_send` smallint(1) NOT NULL DEFAULT '0' COMMENT '发货状态：0=未发货，1=已发货',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `express` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司',
  `express_no` varchar(255) NOT NULL DEFAULT '',
  `is_confirm` smallint(1) NOT NULL DEFAULT '0' COMMENT '确认收货状态：0=未确认，1=已确认收货',
  `confirm_time` int(11) NOT NULL DEFAULT '0' COMMENT '确认收货时间',
  `is_comment` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已评价：0=未评价，1=已评价',
  `apply_delete` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否申请取消订单：0=否，1=申请取消订单',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `address_data` longtext COMMENT '收货地址信息，json格式',
  `is_offline` int(11) NOT NULL DEFAULT '0' COMMENT '是否到店自提 1--否 2--是',
  `clerk_id` int(11) NOT NULL DEFAULT '-1' COMMENT '核销员user_id',
  `is_cancel` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否取消',
  `offline_qrcode` longtext COMMENT '核销码',
  `shop_id` int(11) NOT NULL DEFAULT '-1' COMMENT '自提门店ID',
  `is_sale` int(11) NOT NULL DEFAULT '0' COMMENT '是否超过售后时间',
  `version` varchar(255) NOT NULL DEFAULT '0' COMMENT '版本',
  `mch_id` int(11) NOT NULL DEFAULT '0' COMMENT '入驻商户id',
  `integral` int(11) NOT NULL COMMENT '花费的积分',
  `goods_id` int(11) NOT NULL,
  `words` longtext COMMENT '商家留言',
  `is_recycle` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否加入回收站 0--不加入 1--加入',
  `is_show` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0--不显示 1--显示（软删除）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_integral_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '此商品的总价',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `attr` longtext NOT NULL COMMENT '商品规格',
  `pic` varchar(255) NOT NULL COMMENT '商品规格图片',
  `pay_integral` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分花费',
  `goods_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_integral_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `integral_shuoming` longtext NOT NULL COMMENT '积分说明',
  `register_rule` longtext NOT NULL COMMENT '签到规则',
  `register_integral` int(11) NOT NULL COMMENT '每日签到获得分数',
  `register_continuation` int(11) NOT NULL COMMENT '连续签到天数',
  `register_reward` varchar(255) NOT NULL COMMENT '连续签到奖励',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_in_order_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` decimal(10,1) NOT NULL COMMENT '评分：1=差评，2=中评，3=好',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '评价内容',
  `pic_list` longtext COMMENT '图片',
  `is_hide` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0=不隐藏，1=隐藏',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `reply_content` varchar(255) NOT NULL DEFAULT '0',
  `is_virtual` smallint(6) NOT NULL DEFAULT '0',
  `virtual_user` varchar(255) NOT NULL DEFAULT '0',
  `virtual_avatar` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '等级名称',
  `money` decimal(10,2) DEFAULT NULL COMMENT '会员完成订单金额满足则升级',
  `discount` decimal(10,1) DEFAULT NULL COMMENT '折扣',
  `status` int(11) DEFAULT NULL COMMENT '状态 0--禁用 1--启用',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `image` longtext COMMENT '会员图片',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '会员价格',
  `detail` varchar(255) DEFAULT '' COMMENT '会员介绍',
  `buy_prompt` varchar(255) DEFAULT '' COMMENT '购买之前提示',
  `synopsis` longtext COMMENT '会员权益(禁用)',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员等级';


CREATE TABLE `hjmall_level_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `order_no` varchar(255) DEFAULT NULL COMMENT '订单号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户',
  `pay_price` decimal(10,2) DEFAULT NULL COMMENT '支付金额',
  `pay_type` int(11) DEFAULT '0' COMMENT '支付方式 1--微信支付',
  `is_pay` int(11) DEFAULT '0' COMMENT '是否支付 0--未支付 1--支付',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `current_level` int(11) DEFAULT NULL,
  `after_level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_pay` (`is_pay`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_lottery_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `attr` longtext NOT NULL COMMENT '规格',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '是否关闭',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0未完成 1已完成',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_lottery_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `lottery_id` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0待开奖 1未中奖 2中奖3已领取',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `attr` longtext NOT NULL,
  `raffle_time` int(11) NOT NULL DEFAULT '0' COMMENT '领取时间',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `obtain_time` int(11) DEFAULT '0',
  `form_id` varchar(255) NOT NULL DEFAULT '',
  `child_id` int(11) NOT NULL DEFAULT '0' COMMENT '下级用户',
  `lucky_code` varchar(255) NOT NULL DEFAULT '' COMMENT '幸运码',
  PRIMARY KEY (`id`),
  KEY `lottery_id` (`lottery_id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_lottery_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `lottery_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_lottery_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `rule` varchar(2000) NOT NULL DEFAULT '' COMMENT '规则',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序标题',
  `type` int(10) DEFAULT '0' COMMENT '0不强制 1强制',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_mail_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `send_mail` longtext COMMENT '发件人邮箱',
  `send_pwd` varchar(255) DEFAULT NULL COMMENT '授权码',
  `send_name` varchar(255) DEFAULT NULL COMMENT '发件人名称',
  `receive_mail` longtext COMMENT '收件人邮箱 多个用英文逗号隔开',
  `status` int(11) DEFAULT NULL COMMENT '是否开启邮件通知 0--关闭 1--开启',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_mch` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_open` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否营业：0=否，1=是',
  `is_lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被系统关闭：0=否，1=是',
  `review_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态：0=待审核，1=审核通过，2=审核不通过',
  `review_result` longtext COMMENT '审核结果',
  `review_time` int(11) NOT NULL DEFAULT '0' COMMENT '审核时间',
  `realname` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `province_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `address` varchar(1000) NOT NULL,
  `mch_common_cat_id` int(11) NOT NULL COMMENT '所售类目',
  `service_tel` varchar(1000) NOT NULL COMMENT '客服电话',
  `logo` longtext COMMENT 'logo',
  `header_bg` longtext COMMENT '背景图',
  `transfer_rate` int(11) NOT NULL DEFAULT '0' COMMENT '商户手续费',
  `account_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商户余额',
  `sort` int(11) NOT NULL DEFAULT '1000' COMMENT '排序：升序',
  `wechat_name` varchar(255) DEFAULT '' COMMENT '微信号',
  `is_recommend` int(11) NOT NULL DEFAULT '1' COMMENT '好店推荐：0.不推荐|1.推荐',
  `longitude` varchar(255) NOT NULL DEFAULT '0' COMMENT '经度',
  `latitude` varchar(255) NOT NULL DEFAULT '0' COMMENT '纬度',
  `main_content` varchar(255) NOT NULL DEFAULT '' COMMENT '主营范围',
  `summary` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_open` (`is_open`),
  KEY `is_lock` (`is_lock`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户';


CREATE TABLE `hjmall_mch_account_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `mch_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT '金额',
  `type` smallint(6) NOT NULL COMMENT '类型：1=收入，2=支出',
  `desc` longtext,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='入驻商户账户收支记录';


CREATE TABLE `hjmall_mch_cash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `mch_id` int(11) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `order_no` varchar(255) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '提现状态：0=待处理，1=已转账，2=已拒绝',
  `addtime` int(11) NOT NULL,
  `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '提现类型 0--微信自动打款 1--微信线下打款 2--支付宝线下打款 3--银行卡线下打款 4--充值到余额',
  `type_data` varchar(255) NOT NULL DEFAULT '' COMMENT '不同提现类型，提交的数据',
  `virtual_type` smallint(6) NOT NULL DEFAULT '0' COMMENT '实际上打款方式',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户提现记录';


CREATE TABLE `hjmall_mch_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `mch_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `icon` longtext COMMENT '分类图标',
  `sort` int(11) NOT NULL DEFAULT '1000',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='入驻商商品分类';


CREATE TABLE `hjmall_mch_common_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '1000' COMMENT '排序，升序',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='入驻商类目';


CREATE TABLE `hjmall_mch_goods_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='入驻商商品分类关系';


CREATE TABLE `hjmall_mch_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `mch_id` int(11) NOT NULL DEFAULT '0',
  `group` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_mch_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mch_id` int(11) NOT NULL DEFAULT '0',
  `store_id` int(11) NOT NULL DEFAULT '0',
  `is_share` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否开启分销 0--不开启 1--开启',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户权限表';


CREATE TABLE `hjmall_mch_postage_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mch_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `express_id` int(11) NOT NULL COMMENT '物流公司',
  `detail` longtext NOT NULL COMMENT '规则详细',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_enable` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否启用：0=否，1=是',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `express` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司',
  `type` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '计费方式【1=>按重计费、2=>按件计费】',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='入驻商运费规则';


CREATE TABLE `hjmall_mch_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mch_id` int(11) NOT NULL DEFAULT '0',
  `store_id` int(11) NOT NULL DEFAULT '0',
  `is_share` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否开启分销 0--不开启 1--开启',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户设置';


CREATE TABLE `hjmall_mch_visit_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `mch_id` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺浏览记录';


CREATE TABLE `hjmall_miaosha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `open_time` longtext COMMENT '开放时间，JSON格式',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_miaosha_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `start_time` smallint(6) NOT NULL COMMENT '开始时间：0~23',
  `open_date` date NOT NULL COMMENT '开放日期，例：2017-08-21',
  `attr` longtext NOT NULL COMMENT '规格秒杀价数量',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `buy_max` int(11) NOT NULL DEFAULT '0' COMMENT '限购数量，0=不限购',
  `buy_limit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '限单',
  `is_level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否享受会员折扣 0-不享受 1--享受',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `goods_id` (`goods_id`),
  KEY `start_time` (`start_time`),
  KEY `open_date` (`open_date`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_ms_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `original_price` decimal(10,2) unsigned NOT NULL COMMENT '原价',
  `detail` longtext NOT NULL COMMENT '商品详情，图文',
  `status` smallint(6) unsigned NOT NULL DEFAULT '2' COMMENT '上架状态【1=> 上架，2=> 下架】',
  `service` varchar(2000) DEFAULT NULL COMMENT '服务选项',
  `sort` int(11) unsigned DEFAULT '0' COMMENT '商品排序 升序',
  `virtual_sales` int(11) unsigned DEFAULT '0' COMMENT '虚拟销量',
  `cover_pic` longtext COMMENT '商品缩略图',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_delete` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `sales` int(11) unsigned DEFAULT NULL COMMENT '实际销量',
  `store_id` int(11) unsigned NOT NULL,
  `video_url` varchar(2048) DEFAULT NULL,
  `unit` varchar(255) NOT NULL DEFAULT '件' COMMENT '单位',
  `weight` double(10,2) unsigned DEFAULT '0.00' COMMENT '重量',
  `freight` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '运费模板ID',
  `full_cut` longtext COMMENT '满减',
  `integral` text COMMENT '积分设置',
  `use_attr` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否使用规格：0=不使用，1=使用',
  `attr` longtext NOT NULL COMMENT '规格的库存及价格',
  `is_discount` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否支持会员折扣',
  `coupon` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否支持优惠劵',
  `payment` varchar(255) DEFAULT '' COMMENT '支付方式',
  `individual_share` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否单独分销设置：0=否，1=是',
  `share_commission_first` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级分销佣金比例',
  `share_commission_second` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级分销佣金比例',
  `share_commission_third` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '三级分销佣金比例',
  `share_type` int(11) DEFAULT '0' COMMENT '佣金配比 0--百分比 1--固定金额',
  `rebate` decimal(10,2) DEFAULT '0.00' COMMENT '自购返利',
  `attr_setting_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销设置类型 0.普通设置|1.详细设置',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_discount` (`is_discount`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_ms_goods_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `pic_url` varchar(2048) DEFAULT NULL,
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_ms_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `order_no` varchar(255) NOT NULL COMMENT '订单号',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总费用(包含运费）',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付总费用(含运费）',
  `express_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `name` varchar(255) DEFAULT NULL COMMENT '收货人姓名',
  `mobile` varchar(255) DEFAULT NULL COMMENT '收货人手机',
  `address` varchar(1000) DEFAULT NULL COMMENT '收货地址',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '订单备注',
  `is_pay` smallint(6) NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付',
  `pay_type` smallint(6) NOT NULL DEFAULT '0' COMMENT '支付方式：1=微信支付 2--货到付款',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `is_send` smallint(1) NOT NULL DEFAULT '0' COMMENT '发货状态：0=未发货，1=已发货',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `express` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司',
  `express_no` varchar(255) NOT NULL DEFAULT '',
  `is_confirm` smallint(1) NOT NULL DEFAULT '0' COMMENT '确认收货状态：0=未确认，1=已确认收货',
  `confirm_time` int(11) NOT NULL DEFAULT '0' COMMENT '确认收货时间',
  `is_comment` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已评价：0=未评价，1=已评价',
  `apply_delete` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否申请取消订单：0=否，1=申请取消订单',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `is_price` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否发放佣金',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户上级ID',
  `first_price` decimal(10,2) NOT NULL COMMENT '一级佣金',
  `second_price` decimal(10,2) NOT NULL COMMENT '二级佣金',
  `third_price` decimal(10,2) NOT NULL COMMENT '三级佣金',
  `coupon_sub_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠券抵消金额',
  `address_data` longtext COMMENT '收货地址信息，json格式',
  `content` longtext,
  `is_offline` int(11) NOT NULL DEFAULT '0' COMMENT '是否到店自提 0--否 1--是',
  `clerk_id` int(11) DEFAULT NULL COMMENT '核销员user_id',
  `is_cancel` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否取消',
  `offline_qrcode` longtext COMMENT '核销码',
  `before_update_price` decimal(10,2) DEFAULT NULL COMMENT '修改前的价格',
  `shop_id` int(11) DEFAULT NULL COMMENT '自提门店ID',
  `discount` decimal(11,2) DEFAULT NULL COMMENT '会员折扣',
  `user_coupon_id` int(11) DEFAULT NULL COMMENT '使用的优惠券ID',
  `integral` longtext COMMENT '积分使用',
  `give_integral` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否发放积分【1=> 已发放 ， 0=> 未发放】',
  `parent_id_1` int(11) DEFAULT NULL COMMENT '用户上二级ID',
  `parent_id_2` int(11) DEFAULT NULL COMMENT '用户上三级ID',
  `is_sale` int(11) DEFAULT '0' COMMENT '是否超过售后时间',
  `words` longtext COMMENT '商家留言',
  `express_price_1` decimal(10,2) DEFAULT NULL COMMENT '减免的运费',
  `goods_id` int(11) NOT NULL,
  `attr` longtext NOT NULL COMMENT '商品规格',
  `pic` varchar(255) NOT NULL COMMENT '商品规格图片',
  `integral_amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单品积分获得',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `limit_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '可支付截止时间',
  `is_sum` smallint(1) DEFAULT '0' COMMENT '是否计算分销 0--不计算 1--计算',
  `rebate` decimal(10,2) DEFAULT '0.00' COMMENT '自购返利',
  `before_update_express` decimal(10,2) DEFAULT '0.00' COMMENT '价格修改前的运费',
  `is_recycle` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否在回收站 0--不在回收站 1--在回收站',
  `is_show` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0--不显示 1--显示（软删除用）',
  `seller_comments` mediumtext COMMENT '商家备注',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_pay` (`is_pay`),
  KEY `is_send` (`is_send`),
  KEY `is_confirm` (`is_confirm`),
  KEY `is_comment` (`is_comment`),
  KEY `is_price` (`is_price`),
  KEY `is_offline` (`is_offline`),
  KEY `is_cancel` (`is_cancel`),
  KEY `is_sale` (`is_sale`),
  KEY `is_sum` (`is_sum`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_ms_order_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` decimal(10,1) NOT NULL COMMENT '评分：1=差评，2=中评，3=好',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '评价内容',
  `pic_list` longtext COMMENT '图片',
  `is_hide` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0=不隐藏，1=隐藏',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_hide` (`is_hide`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='秒杀订单评价表';


CREATE TABLE `hjmall_ms_order_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_refund_no` varchar(255) NOT NULL DEFAULT '' COMMENT '退款单号',
  `type` smallint(6) NOT NULL DEFAULT '1' COMMENT '售后类型：1=退货退款，2=换货',
  `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '退款说明',
  `pic_list` longtext COMMENT '凭证图片列表：json格式',
  `status` smallint(1) NOT NULL DEFAULT '0' COMMENT '状态：0=待商家处理，1=同意并已退款，2=已同意换货，3=已拒绝退换货',
  `refuse_desc` varchar(500) NOT NULL DEFAULT '' COMMENT '拒绝退换货原因',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `response_time` int(11) NOT NULL DEFAULT '0' COMMENT '商家处理时间',
  `is_agree` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已同意退、换货：0=待处理，1=已同意，2=已拒绝',
  `is_user_send` smallint(1) NOT NULL DEFAULT '0' COMMENT '用户已发货：0=未发货，1=已发货',
  `user_send_express` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递公司',
  `user_send_express_no` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递单号',
  `address_id` int(11) DEFAULT '0' COMMENT '退货 换货地址id',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_ms_setting` (
  `store_id` int(11) unsigned NOT NULL,
  `unpaid` int(2) unsigned NOT NULL DEFAULT '1' COMMENT '未付款自动取消时间',
  `is_share` int(11) DEFAULT '0' COMMENT '是否开启分销 0--关闭 1--开启',
  `is_sms` int(11) DEFAULT '0' COMMENT '是否开启短信通知',
  `is_print` int(11) DEFAULT '0' COMMENT '是否开启订单打印',
  `is_mail` int(11) DEFAULT '0' COMMENT '是否开启邮件通知',
  `is_area` tinyint(1) DEFAULT NULL COMMENT '区域限制  1-开启 0-关闭',
  PRIMARY KEY (`store_id`),
  KEY `is_share` (`is_share`),
  KEY `is_sms` (`is_sms`),
  KEY `is_print` (`is_print`),
  KEY `is_mail` (`is_mail`),
  KEY `is_area` (`is_area`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_option` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `group` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `group` (`group`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `hjmall_option` (`id`, `store_id`, `group`, `name`, `value`) VALUES
(1,	0,	'',	'VERSION',	'版本号')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `store_id` = VALUES(`store_id`), `group` = VALUES(`group`), `name` = VALUES(`name`), `value` = VALUES(`value`);

CREATE TABLE `hjmall_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `order_no` varchar(255) NOT NULL COMMENT '订单号',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总费用(包含运费）',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付总费用(含运费）',
  `express_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `name` varchar(255) DEFAULT NULL COMMENT '收货人姓名',
  `mobile` varchar(255) DEFAULT NULL COMMENT '收货人手机',
  `address` varchar(1000) DEFAULT NULL COMMENT '收货地址',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '订单备注',
  `is_pay` smallint(6) NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付',
  `pay_type` smallint(6) NOT NULL DEFAULT '0' COMMENT '支付方式：1=微信支付',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `is_send` smallint(1) NOT NULL DEFAULT '0' COMMENT '发货状态：0=未发货，1=已发货',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `express` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司',
  `express_no` varchar(255) NOT NULL DEFAULT '',
  `is_confirm` smallint(1) NOT NULL DEFAULT '0' COMMENT '确认收货状态：0=未确认，1=已确认收货',
  `confirm_time` int(11) NOT NULL DEFAULT '0' COMMENT '确认收货时间',
  `is_comment` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已评价：0=未评价，1=已评价',
  `apply_delete` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否申请取消订单：0=否，1=申请取消订单',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `is_price` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否发放佣金',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户上级ID',
  `first_price` decimal(10,2) NOT NULL COMMENT '一级佣金',
  `second_price` decimal(10,2) NOT NULL COMMENT '二级佣金',
  `third_price` decimal(10,2) NOT NULL COMMENT '三级佣金',
  `coupon_sub_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠券抵消金额',
  `content` longtext,
  `is_offline` int(11) NOT NULL DEFAULT '0' COMMENT '是否到店自提 0--否 1--是',
  `clerk_id` int(11) DEFAULT NULL COMMENT '核销员user_id',
  `address_data` longtext COMMENT '收货地址信息，json格式',
  `is_cancel` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否取消',
  `offline_qrcode` longtext COMMENT '核销码',
  `before_update_price` decimal(10,2) DEFAULT NULL COMMENT '修改前的价格',
  `shop_id` int(11) DEFAULT NULL COMMENT '自提门店ID',
  `discount` decimal(11,2) DEFAULT NULL COMMENT '会员折扣',
  `user_coupon_id` int(11) DEFAULT NULL COMMENT '使用的优惠券ID',
  `integral` longtext COMMENT '积分使用',
  `give_integral` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否发放积分【1=> 已发放 ， 0=> 未发放】',
  `parent_id_1` int(11) DEFAULT '0' COMMENT '用户上二级ID',
  `parent_id_2` int(11) DEFAULT '0' COMMENT '用户上三级ID',
  `is_sale` int(11) DEFAULT '0' COMMENT '是否超过售后时间',
  `words` longtext COMMENT '商家留言',
  `version` varchar(255) DEFAULT NULL COMMENT '版本',
  `express_price_1` decimal(10,2) DEFAULT NULL COMMENT '减免的运费',
  `is_recycle` smallint(1) DEFAULT '0',
  `rebate` decimal(10,2) DEFAULT '0.00' COMMENT '自购返利',
  `before_update_express` decimal(10,2) DEFAULT '0.00' COMMENT '价格修改前的运费',
  `seller_comments` text COMMENT '商家备注',
  `mch_id` int(11) NOT NULL DEFAULT '0' COMMENT '入驻商户id',
  `order_union_id` int(11) NOT NULL DEFAULT '0' COMMENT '合并订单的id',
  `is_transfer` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已转入商户账户：0=否，1=是',
  `type` int(11) DEFAULT '0' COMMENT '0:普通订单 1大转盘订单',
  `share_price` decimal(11,2) DEFAULT '0.00' COMMENT '发放佣金的金额',
  `is_show` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0--不显示 1--显示',
  `currency` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '货币：活力币',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`),
  KEY `user_id` (`user_id`),
  KEY `order_no` (`order_no`),
  KEY `is_delete` (`is_delete`),
  KEY `is_pay` (`is_pay`),
  KEY `is_send` (`is_send`),
  KEY `is_confirm` (`is_confirm`),
  KEY `is_comment` (`is_comment`),
  KEY `is_price` (`is_price`),
  KEY `is_offline` (`is_offline`),
  KEY `is_cancel` (`is_cancel`),
  KEY `is_sale` (`is_sale`),
  KEY `is_recycle` (`is_recycle`),
  KEY `is_transfer` (`is_transfer`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';


CREATE TABLE `hjmall_order_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` decimal(10,1) NOT NULL COMMENT '评分：1=差评，2=中评，3=好',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '评价内容',
  `pic_list` longtext COMMENT '图片',
  `is_hide` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0=不隐藏，1=隐藏',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `reply_content` varchar(255) DEFAULT NULL,
  `is_virtual` smallint(6) DEFAULT NULL,
  `virtual_user` varchar(255) DEFAULT NULL,
  `virtual_avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_hide` (`is_hide`),
  KEY `is_virtual` (`is_virtual`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单评价';


CREATE TABLE `hjmall_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '此商品的总价',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `attr` longtext NOT NULL COMMENT '商品规格',
  `pic` varchar(255) NOT NULL COMMENT '商品规格图片',
  `integral` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单品积分获得',
  `is_level` smallint(6) NOT NULL DEFAULT '1' COMMENT '是否使用会员折扣 0--不适用 1--使用',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`),
  KEY `addtime` (`addtime`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单明细';


CREATE TABLE `hjmall_order_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_type` int(11) DEFAULT NULL COMMENT '订单类型 0--商城订单 1--秒杀订单 2--拼团订单 ',
  `express_code` varchar(255) DEFAULT NULL COMMENT '快递公司编码',
  `EBusinessID` varchar(255) DEFAULT NULL COMMENT '快递鸟id',
  `order` longtext,
  `printTeplate` longtext,
  `is_delete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单电子面单记录';


CREATE TABLE `hjmall_order_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL COMMENT '表单key',
  `value` varchar(255) DEFAULT NULL COMMENT '表单value',
  `is_delete` int(11) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '表单信息类型',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单自定义表单信息';


CREATE TABLE `hjmall_order_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT '类型id 系统消息时为0',
  `is_read` int(11) DEFAULT NULL COMMENT '消息是否已读 0--未读 1--已读',
  `is_sound` int(11) DEFAULT NULL COMMENT '是否提示 0--未提示  1--已提示',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `type` smallint(1) DEFAULT '0' COMMENT '订单类型  0--已下订单   1--售后订单',
  `order_type` int(11) DEFAULT '0' COMMENT '订单类型 0--商城订单 1--秒杀订单 2--拼团订单 3--预约订单',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_read` (`is_read`),
  KEY `is_sound` (`is_sound`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_order_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  `order_refund_no` varchar(255) NOT NULL DEFAULT '' COMMENT '退款单号',
  `type` smallint(6) NOT NULL DEFAULT '1' COMMENT '售后类型：1=退货退款，2=换货',
  `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '退款说明',
  `pic_list` longtext COMMENT '凭证图片列表：json格式',
  `status` smallint(1) NOT NULL DEFAULT '0' COMMENT '状态：0=待商家处理，1=同意并已退款，2=已同意换货，3=已拒绝退换货',
  `refuse_desc` varchar(500) NOT NULL DEFAULT '' COMMENT '拒绝退换货原因',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `response_time` int(11) NOT NULL DEFAULT '0' COMMENT '商家处理时间',
  `is_agree` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已同意退、换货：0=待处理，1=已同意，2=已拒绝',
  `is_user_send` smallint(1) NOT NULL DEFAULT '0' COMMENT '用户已发货：0=未发货，1=已发货',
  `user_send_express` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递公司',
  `user_send_express_no` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递单号',
  `address_id` int(11) DEFAULT '0' COMMENT '退货 换货地址id',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='售后订单';


CREATE TABLE `hjmall_order_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT '0' COMMENT '类型 0--拼团 1--预约',
  `order_id` int(11) DEFAULT NULL COMMENT '订单id',
  `parent_id_1` int(11) DEFAULT '0' COMMENT '一级分销商id',
  `parent_id_2` int(11) DEFAULT '0' COMMENT '二级分销商id',
  `parent_id_3` int(11) DEFAULT '0' COMMENT '三级分销商id',
  `first_price` decimal(10,2) DEFAULT '0.00' COMMENT '一级分销佣金',
  `second_price` decimal(10,2) DEFAULT '0.00' COMMENT '二级分销佣金',
  `third_price` decimal(10,2) DEFAULT '0.00' COMMENT '三级分销佣金',
  `is_delete` int(11) DEFAULT '0',
  `version` varchar(255) DEFAULT '0',
  `rebate` decimal(10,2) DEFAULT '0.00' COMMENT '自购返利',
  `user_id` int(11) DEFAULT '0' COMMENT '下单用户id',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单分销数据';


CREATE TABLE `hjmall_order_union` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_no` varchar(255) NOT NULL COMMENT '支付单号',
  `order_id_list` longtext NOT NULL COMMENT '订单id列表',
  `price` decimal(10,2) NOT NULL COMMENT '支付金额',
  `is_pay` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已付款0=未付款，1=已付款',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_pay` (`is_pay`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单合并支付';


CREATE TABLE `hjmall_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 步数海报',
  `type` int(11) NOT NULL,
  `pic_url` varchar(2048) DEFAULT '',
  `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_name` varchar(255) NOT NULL DEFAULT '',
  `route` varchar(255) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pond` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1.红包2.优惠卷3.积分4.实物.5.无',
  `num` int(11) DEFAULT '0' COMMENT '积分数量',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '红包价格',
  `image_url` varchar(255) DEFAULT '' COMMENT '图片',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `orderby` int(11) DEFAULT '0' COMMENT '排序',
  `coupon_id` int(11) DEFAULT '0' COMMENT '优惠卷',
  `gift_id` int(11) DEFAULT '0' COMMENT '赠品',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `attr` varchar(255) DEFAULT '' COMMENT '规格',
  `name` varchar(255) DEFAULT '' COMMENT '别名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pond_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `pond_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT ' 0未领取1 已领取',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `raffle_time` int(11) DEFAULT '0' COMMENT '领取时间',
  `type` int(11) NOT NULL DEFAULT '0',
  `num` int(11) DEFAULT '0',
  `gift_id` int(11) DEFAULT '0',
  `coupon_id` int(11) DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `attr` varchar(255) DEFAULT '',
  `order_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pond_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `probability` int(11) NOT NULL DEFAULT '0' COMMENT '概率',
  `oppty` int(11) NOT NULL DEFAULT '0' COMMENT '抽奖次数',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1.天 2 用户',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `title` varchar(255) DEFAULT '' COMMENT '小程序标题',
  `rule` varchar(1000) DEFAULT '' COMMENT '规则',
  `deplete_register` int(11) DEFAULT '0' COMMENT '消耗积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_postage_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `express_id` int(11) NOT NULL COMMENT '物流公司',
  `detail` longtext NOT NULL COMMENT '规则详细',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_enable` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否启用：0=否，1=是',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `express` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司',
  `type` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '计费方式【1=>按重计费、2=>按件计费】',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_enable` (`is_enable`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运费规则';


CREATE TABLE `hjmall_printer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '打印机名称',
  `printer_type` varchar(255) DEFAULT NULL COMMENT '打印机类型',
  `printer_setting` longtext COMMENT '打印机设置',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小票打印机';


CREATE TABLE `hjmall_printer_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `printer_id` int(11) DEFAULT NULL COMMENT '打印机ID',
  `block_id` int(11) DEFAULT NULL COMMENT '打印模板ID',
  `type` longtext COMMENT '打印方式 ',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `is_attr` int(11) DEFAULT '0' COMMENT '是否打印规格 0--不打印 1--打印',
  `big` int(11) DEFAULT '1' COMMENT '发大倍数',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_attr` (`is_attr`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='打印设置';


CREATE TABLE `hjmall_pt_cat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '标题名称',
  `store_id` int(11) unsigned NOT NULL COMMENT '商城ID',
  `pic_url` varchar(2048) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序 升序',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `is_delete` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pt_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `original_price` decimal(10,2) unsigned NOT NULL COMMENT '商品原价',
  `price` decimal(10,2) unsigned NOT NULL COMMENT '团购价',
  `detail` longtext NOT NULL COMMENT '商品详情，图文',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品分类',
  `status` smallint(6) unsigned NOT NULL DEFAULT '2' COMMENT '上架状态【1=> 上架，2=> 下架】',
  `grouptime` int(11) NOT NULL DEFAULT '0' COMMENT '拼团时间/小时',
  `attr` longtext NOT NULL COMMENT '规格的库存及价格',
  `service` varchar(2000) DEFAULT '' COMMENT '服务选项',
  `sort` int(11) unsigned DEFAULT '0' COMMENT '商品排序 升序',
  `virtual_sales` int(11) unsigned DEFAULT '0' COMMENT '虚拟销量',
  `cover_pic` longtext COMMENT '商品缩略图',
  `weight` int(11) unsigned DEFAULT '0' COMMENT '重量',
  `freight` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '运费模板ID',
  `unit` varchar(255) NOT NULL DEFAULT '件' COMMENT '单位',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_delete` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `group_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品成团数',
  `is_hot` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否热卖【0=>热卖1=>不是】',
  `limit_time` int(11) unsigned DEFAULT NULL COMMENT '拼团限时',
  `is_only` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许单独购买',
  `is_more` smallint(1) unsigned DEFAULT '1' COMMENT '是否允许多件购买',
  `colonel` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '团长优惠',
  `buy_limit` int(11) unsigned DEFAULT '0' COMMENT '限购数量',
  `type` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '商品类型【1=> 送货上门，2=> 到店自提，3=> 全部支持】',
  `use_attr` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否使用规格：0=不使用，1=使用',
  `one_buy_limit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单次限购数量',
  `payment` varchar(255) DEFAULT '' COMMENT '支付方式',
  `is_level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否享受会员折扣 0-不享受 1--享受',
  `video_url` varchar(255) DEFAULT NULL COMMENT '视频url',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_hot` (`is_hot`),
  KEY `is_only` (`is_only`),
  KEY `is_more` (`is_more`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pt_goods_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `colonel` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '团长优惠',
  `group_num` int(11) unsigned DEFAULT '0' COMMENT '商品成团数',
  `group_time` int(11) DEFAULT NULL COMMENT '拼团时间/小时',
  `attr` longtext COMMENT '规格的库存及价格',
  `is_level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否享受会员折扣 0-不享受 1--享受',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pt_goods_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `pic_url` varchar(2048) DEFAULT NULL,
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pt_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `order_no` varchar(255) NOT NULL COMMENT '订单号',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总费用(包含运费）',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付总费用(含运费）',
  `express_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `name` varchar(255) DEFAULT NULL COMMENT '收货人姓名',
  `mobile` varchar(255) DEFAULT NULL COMMENT '收货人手机',
  `address` varchar(1000) DEFAULT NULL COMMENT '收货地址',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '订单备注',
  `is_pay` smallint(6) NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付',
  `pay_type` smallint(6) NOT NULL DEFAULT '0' COMMENT '支付方式：1=微信支付',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `is_send` smallint(1) NOT NULL DEFAULT '0' COMMENT '发货状态：0=未发货，1=已发货',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `express` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司',
  `express_no` varchar(255) NOT NULL DEFAULT '',
  `is_confirm` smallint(1) NOT NULL DEFAULT '0' COMMENT '确认收货状态：0=未确认，1=已确认收货',
  `confirm_time` int(11) NOT NULL DEFAULT '0' COMMENT '确认收货时间',
  `is_comment` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已评价：0=未评价，1=已评价',
  `apply_delete` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否申请取消订单：0=否，1=申请取消订单',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `address_data` longtext COMMENT '收货地址信息，json格式',
  `is_group` smallint(1) unsigned NOT NULL COMMENT '是否团购',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '团ID【0=> 团长ID】',
  `colonel` decimal(10,2) DEFAULT '0.00' COMMENT '团长优惠',
  `is_success` smallint(1) unsigned DEFAULT '0' COMMENT '是否成团',
  `success_time` int(11) unsigned DEFAULT NULL COMMENT '成团时间',
  `status` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '拼团状态【1=> 待付款，2= 拼团中，3=拼团成功，4=拼团失败】',
  `is_returnd` smallint(1) unsigned DEFAULT '0' COMMENT '是否退款',
  `is_cancel` smallint(1) DEFAULT '0' COMMENT '是否取消',
  `limit_time` int(1) unsigned DEFAULT NULL COMMENT '拼团限时',
  `content` longtext COMMENT '备注',
  `words` longtext COMMENT '商家留言',
  `shop_id` int(11) unsigned DEFAULT '0' COMMENT '自提店铺',
  `offline` smallint(1) unsigned DEFAULT '1' COMMENT '拿货方式',
  `clerk_id` int(11) unsigned DEFAULT '0' COMMENT '核销员ID',
  `is_price` smallint(1) DEFAULT '0' COMMENT '是否发放佣金',
  `express_price_1` decimal(10,2) DEFAULT NULL COMMENT '减免的运费',
  `class_group` int(11) DEFAULT '0' COMMENT '阶级团',
  `is_recycle` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否加入回收站 0--不加入 1--加入',
  `is_show` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0--不显示 1--显示（软删除）',
  `seller_comments` mediumtext COMMENT '商家备注',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_pay` (`is_pay`),
  KEY `is_send` (`is_send`),
  KEY `is_confirm` (`is_confirm`),
  KEY `is_comment` (`is_comment`),
  KEY `is_group` (`is_group`),
  KEY `is_success` (`is_success`),
  KEY `is_returnd` (`is_returnd`),
  KEY `is_cancel` (`is_cancel`),
  KEY `is_price` (`is_price`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pt_order_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` decimal(10,1) NOT NULL COMMENT '评分：1=差评，2=中评，3=好',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '评价内容',
  `pic_list` longtext COMMENT '图片',
  `is_hide` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0=不隐藏，1=隐藏',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_virtual` smallint(6) DEFAULT '0',
  `virtual_user` varchar(255) DEFAULT '',
  `virtual_avatar` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_hide` (`is_hide`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pt_order_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '此商品的总价',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `attr` longtext NOT NULL COMMENT '商品规格',
  `pic` varchar(255) NOT NULL COMMENT '商品规格图片',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_pt_order_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  `order_refund_no` varchar(255) NOT NULL DEFAULT '' COMMENT '退款单号',
  `type` smallint(6) NOT NULL DEFAULT '1' COMMENT '售后类型：1=退货退款，2=换货',
  `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '退款说明',
  `pic_list` longtext COMMENT '凭证图片列表：json格式',
  `status` smallint(1) NOT NULL DEFAULT '0' COMMENT '状态：0=待商家处理，1=同意并已退款，2=已同意换货，3=已拒绝退换货',
  `refuse_desc` varchar(500) NOT NULL DEFAULT '' COMMENT '拒绝退换货原因',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `response_time` int(11) NOT NULL DEFAULT '0' COMMENT '商家处理时间',
  `is_agree` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已同意退、换货：0=待处理，1=已同意，2=已拒绝',
  `is_user_send` smallint(1) NOT NULL DEFAULT '0' COMMENT '用户已发货：0=未发货，1=已发货',
  `user_send_express` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递公司',
  `user_send_express_no` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递单号',
  `address_id` int(11) DEFAULT '0' COMMENT '退货 换货地址id',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团订单售后表';


CREATE TABLE `hjmall_pt_robot` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '机器人名',
  `pic` varchar(255) NOT NULL COMMENT '头像',
  `is_delete` smallint(1) unsigned DEFAULT NULL COMMENT '是否删除',
  `addtime` int(11) unsigned DEFAULT NULL COMMENT '添加时间',
  `store_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团机器人表';


CREATE TABLE `hjmall_pt_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `is_share` int(11) DEFAULT '0' COMMENT '是否开启分销 0--关闭 1--开启',
  `is_sms` int(11) DEFAULT '0' COMMENT '是否开启短信通知',
  `is_print` int(11) DEFAULT '0' COMMENT '是否开启订单打印',
  `is_mail` int(11) DEFAULT '0' COMMENT '是否开启邮件通知',
  `is_area` tinyint(1) DEFAULT NULL COMMENT '区域限制  0--关闭 1--开启',
  PRIMARY KEY (`id`),
  KEY `is_share` (`is_share`),
  KEY `is_sms` (`is_sms`),
  KEY `is_print` (`is_print`),
  KEY `is_mail` (`is_mail`),
  KEY `is_area` (`is_area`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团设置';


CREATE TABLE `hjmall_qrcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `qrcode_bg` longtext NOT NULL COMMENT '背景图片',
  `avatar_size` varchar(255) NOT NULL COMMENT '头像大小{"w":"","h":""}',
  `avatar_position` varchar(255) NOT NULL COMMENT '头像坐标{"x":"","y":""}',
  `qrcode_size` varchar(255) NOT NULL COMMENT '二维码宽度',
  `qrcode_position` varchar(255) NOT NULL COMMENT '二维码坐标{"x":"","y":""}',
  `font_position` varchar(255) NOT NULL COMMENT '字体坐标{"x":"","y":""}',
  `font` longtext NOT NULL COMMENT '字体设置\r\n{\r\n  "size":大小,\r\n  "color":"r,g,b"\r\n}',
  `preview` longtext NOT NULL COMMENT '预览图',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海报图的设置';


CREATE TABLE `hjmall_recharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `pay_price` decimal(10,2) DEFAULT NULL COMMENT '支付金额',
  `send_price` decimal(10,2) DEFAULT '0.00' COMMENT '赠送金额',
  `name` varchar(255) DEFAULT NULL COMMENT '充值名称',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值管理';


CREATE TABLE `hjmall_refund_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '收件人名称',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '收件人地址',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '收件人电话',
  `is_delete` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `register_time` varchar(25) NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `continuation` int(11) NOT NULL COMMENT '连续签到天数',
  `integral` int(11) NOT NULL COMMENT '获得积分',
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1--签到',
  `order_id` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_re_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `order_no` varchar(255) DEFAULT NULL COMMENT '订单号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户',
  `pay_price` decimal(10,2) DEFAULT NULL COMMENT '支付金额',
  `send_price` decimal(10,2) DEFAULT NULL COMMENT '赠送金额',
  `pay_type` int(11) DEFAULT '0' COMMENT '支付方式 1--微信支付',
  `is_pay` int(11) DEFAULT '0' COMMENT '是否支付 0--未支付 1--支付',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_pay` (`is_pay`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值记录';


CREATE TABLE `hjmall_scratch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1.红包2.优惠卷3.积分4.实物',
  `num` int(11) DEFAULT '0' COMMENT '积分数量',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '红包价格',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `coupon_id` int(11) DEFAULT '0' COMMENT '优惠卷',
  `gift_id` int(11) DEFAULT '0' COMMENT '赠品',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `attr` varchar(255) DEFAULT '' COMMENT '规格',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启',
  `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_scratch_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `pond_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT ' 0预领取 1 未领取 2 已领取',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `raffle_time` int(11) DEFAULT '0' COMMENT '领取时间',
  `type` int(11) NOT NULL DEFAULT '0',
  `num` int(11) DEFAULT '0',
  `gift_id` int(11) DEFAULT '0',
  `coupon_id` int(11) DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `attr` varchar(255) DEFAULT '' COMMENT '规格',
  `order_id` int(11) DEFAULT '0' COMMENT '订单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_scratch_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `probability` int(11) NOT NULL DEFAULT '0' COMMENT '概率',
  `oppty` int(11) NOT NULL DEFAULT '0' COMMENT '抽奖次数',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1.天 2 用户',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `title` varchar(255) DEFAULT '' COMMENT '小程序标题',
  `rule` varchar(1000) DEFAULT '' COMMENT '规则说明',
  `deplete_register` int(11) DEFAULT '0' COMMENT '消耗积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_sender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `post_code` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `exp_area` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_delete` smallint(2) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_session` (
  `id` varchar(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` varchar(10240) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first` decimal(10,2) NOT NULL DEFAULT '0.00',
  `second` decimal(10,2) NOT NULL DEFAULT '0.00',
  `third` decimal(10,2) NOT NULL DEFAULT '0.00',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商城id',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '分销层级 0--不开启 1--一级分销 2--二级分销 3--三级分销',
  `condition` int(11) NOT NULL DEFAULT '0' COMMENT '成为下线条件 0--首次点击分享链接 1--首次下单 2--首次付款',
  `share_condition` int(11) NOT NULL DEFAULT '0' COMMENT '成为分销商的条件 \r\n0--无条件\r\n1--申请',
  `content` longtext COMMENT '分销佣金 的 用户须知',
  `pay_type` smallint(1) NOT NULL DEFAULT '0' COMMENT '提现方式 0--支付宝转账  1--微信企业支付',
  `min_money` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '最小提现额度',
  `agree` longtext COMMENT '分销协议',
  `first_name` varchar(255) DEFAULT NULL,
  `second_name` varchar(255) DEFAULT NULL,
  `third_name` varchar(255) DEFAULT NULL,
  `pic_url_1` longtext,
  `pic_url_2` longtext,
  `price_type` int(11) DEFAULT '0' COMMENT '分销金额 0--百分比金额  1--固定金额',
  `bank` tinyint(4) DEFAULT NULL COMMENT '银行卡支付  0 --不使用  1--使用',
  `remaining_sum` tinyint(1) DEFAULT '0' COMMENT '余额提现 0=关闭 1=开启',
  `rebate` decimal(10,2) DEFAULT '0.00' COMMENT '自购返利',
  `is_rebate` int(11) DEFAULT '0' COMMENT '是否开启自购返利',
  `share_good_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '购买商品自动成为分销商：0.关闭 1.任意商品 2.指定商品',
  `share_good_id` int(11) NOT NULL DEFAULT '0' COMMENT '购买指定分销商品Id',
  PRIMARY KEY (`id`),
  KEY `is_rebate` (`is_rebate`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分销商佣金设置';


CREATE TABLE `hjmall_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '审核状态 0--未审核 1--审核通过 2--审核不通过',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `store_id` int(11) NOT NULL,
  `seller_comments` text COMMENT '商家备注',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分销商';


CREATE TABLE `hjmall_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_delete` smallint(6) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `longitude` longtext,
  `latitude` longtext,
  `score` int(11) DEFAULT '5' COMMENT '评分 1~5',
  `cover_url` varchar(2048) DEFAULT NULL,
  `pic_url` varchar(2048) DEFAULT NULL,
  `shop_time` varchar(255) DEFAULT NULL COMMENT '营业时间',
  `content` longtext COMMENT '门店介绍',
  `is_default` int(11) DEFAULT '0' COMMENT '是否设为默认 0--否 1--是 （只能设置一个门店为默认门店）',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_default` (`is_default`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='门店设置';


CREATE TABLE `hjmall_shop_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `pic_url` varchar(2048) DEFAULT NULL,
  `is_delete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_sms_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(255) DEFAULT NULL,
  `tpl` varchar(255) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信发送记录';


CREATE TABLE `hjmall_sms_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `AccessKeyId` varchar(255) DEFAULT NULL COMMENT '阿里云AccessKeyId',
  `AccessKeySecret` varchar(255) DEFAULT NULL COMMENT '阿里云AccessKeySecret',
  `name` varchar(255) DEFAULT NULL COMMENT '短信模板名称',
  `sign` varchar(255) DEFAULT NULL COMMENT '短信模板签名',
  `tpl` varchar(255) DEFAULT NULL COMMENT '短信模板code',
  `msg` varchar(255) DEFAULT NULL COMMENT '短信模板参数',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '开启状态 0--关闭 1--开启',
  `mobile` varchar(255) DEFAULT NULL,
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `tpl_refund` longtext COMMENT '退款模板参数',
  `tpl_code` longtext COMMENT '验证码模板参数',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信设置';


CREATE TABLE `hjmall_step_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题',
  `currency` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '奖金池',
  `bail_currency` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '保证金',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启',
  `step_num` int(11) NOT NULL DEFAULT '0' COMMENT '挑战布数',
  `open_date` date NOT NULL COMMENT '开放日期，例：2017-08-21',
  `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0进行中 1 已完成',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_step_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `step_id` int(11) NOT NULL DEFAULT '0' COMMENT 'stepID',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '兑换布数',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '1收入 2支出 ',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 布数兑换 1商品兑换 2 布数挑战',
  `step_currency` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '活力币',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单/活动',
  `raffle_time` int(11) NOT NULL DEFAULT '0' COMMENT '领取时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `step_currency` (`step_currency`) USING BTREE,
  KEY `num` (`num`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_step_remind` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00' COMMENT '日期',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_step_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `rule` varchar(2000) DEFAULT '' COMMENT '规则',
  `title` varchar(255) DEFAULT '' COMMENT '小程序标题',
  `convert_max` int(11) DEFAULT '0' COMMENT '最大步数兑换限制',
  `convert_ratio` int(11) DEFAULT '0' COMMENT '兑换比率',
  `invite_ratio` int(11) DEFAULT '0' COMMENT '邀请比率',
  `remind_time` varchar(255) DEFAULT '8' COMMENT '0-24',
  `activity_rule` varchar(2000) DEFAULT '' COMMENT '活动规则',
  `share_title` varchar(2000) DEFAULT '' COMMENT '转发标题',
  `ranking_num` int(11) DEFAULT '0' COMMENT '全国排行限制',
  `qrcode_title` varchar(255) DEFAULT '' COMMENT '海报文字',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_step_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `step_currency` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '活力币 ',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `ratio` int(11) NOT NULL DEFAULT '0' COMMENT '概率加成',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '邀请ID',
  `invite_ratio` int(11) NOT NULL DEFAULT '0' COMMENT '邀请好友加成',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `remind` int(11) NOT NULL DEFAULT '0' COMMENT '0提醒 1开启',
  PRIMARY KEY (`id`),
  KEY `step_currency` (`step_currency`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `is_recycle` smallint(1) NOT NULL DEFAULT '0' COMMENT '回收站：0=否，1=是',
  `acid` int(11) NOT NULL DEFAULT '0' COMMENT '微擎公众号id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `wechat_platform_id` int(11) NOT NULL DEFAULT '0' COMMENT '微信公众号id',
  `wechat_app_id` int(11) NOT NULL DEFAULT '0' COMMENT '微信小程序id',
  `name` varchar(255) NOT NULL COMMENT '店铺名称',
  `order_send_tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '发货通知模板消息id',
  `contact_tel` varchar(255) NOT NULL DEFAULT '' COMMENT '联系电话',
  `show_customer_service` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否显示在线客服：0=否，1=是',
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `copyright_pic_url` varchar(2048) DEFAULT NULL,
  `copyright_url` varchar(2048) DEFAULT NULL,
  `delivery_time` int(11) NOT NULL DEFAULT '10' COMMENT '收货时间',
  `after_sale_time` int(11) NOT NULL DEFAULT '7' COMMENT '售后时间',
  `use_wechat_platform_pay` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否使用公众号支付：0=否，1=是',
  `kdniao_mch_id` varchar(255) NOT NULL DEFAULT '' COMMENT '快递鸟商户号',
  `kdniao_api_key` varchar(255) NOT NULL DEFAULT '' COMMENT '快递鸟api key',
  `cat_style` smallint(6) NOT NULL DEFAULT '1' COMMENT '分类页面样式：1=无侧栏，2=有侧栏',
  `home_page_module` longtext COMMENT '首页模块布局',
  `address` longtext COMMENT '店铺地址',
  `cat_goods_cols` int(11) NOT NULL DEFAULT '3' COMMENT '首页分类商品列数',
  `over_day` int(11) NOT NULL DEFAULT '0' COMMENT '未支付订单超时时间',
  `is_offline` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否开启自提',
  `is_coupon` smallint(6) DEFAULT '0' COMMENT '是否开启优惠券',
  `cat_goods_count` int(11) NOT NULL DEFAULT '6' COMMENT '首页分类的商品个数',
  `send_type` smallint(6) NOT NULL DEFAULT '1' COMMENT '发货方式：0=快递或自提，1=仅快递，2=仅自提',
  `member_content` longtext COMMENT '会员等级说明',
  `nav_count` int(11) DEFAULT '0' COMMENT '首页导航栏个数 0--4个 1--5个',
  `integral` int(11) unsigned NOT NULL DEFAULT '10' COMMENT '一元抵多少积分',
  `integration` longtext COMMENT '积分使用说明',
  `cut_thread` smallint(1) DEFAULT NULL COMMENT '分类分割线   0关闭   1开启',
  `dial` smallint(1) DEFAULT NULL COMMENT '一键拨号开关  0关闭  1开启',
  `dial_pic` tinytext COMMENT '拨号图标',
  `purchase_frame` smallint(1) DEFAULT '0',
  `is_recommend` int(10) NOT NULL DEFAULT '0' COMMENT '推荐商品状态 1：开启 0 ：关闭',
  `recommend_count` int(10) NOT NULL DEFAULT '0' COMMENT '推荐商品数量',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '禁用状态：0.未禁用|1.禁用',
  `is_comment` tinyint(4) NOT NULL DEFAULT '1' COMMENT '商城评价开关：0.关闭 1.开启',
  `is_sales` tinyint(4) NOT NULL DEFAULT '1' COMMENT '商城商品销量开关：0.关闭 1.开启',
  `good_negotiable` varchar(255) DEFAULT '' COMMENT '1 电话 2 客服',
  `buy_member` smallint(1) DEFAULT '0' COMMENT '是否购买会员 0不支持 1支持',
  `logo` varchar(255) DEFAULT NULL COMMENT '商城logo',
  `is_member_price` tinyint(1) NOT NULL DEFAULT '1' COMMENT '会员价是否显示 0.不显示|1.显示',
  `is_share_price` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销价是否显示 0.不显示|1.显示',
  `is_member` smallint(1) DEFAULT '0' COMMENT '是否购买会员 0不支持 1支持',
  `is_official_account` tinyint(1) NOT NULL DEFAULT '0' COMMENT '公众号关注组件 0.否 | 1.是',
  PRIMARY KEY (`id`),
  KEY `acid` (`acid`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_offline` (`is_offline`),
  KEY `is_coupon` (`is_coupon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商城';


CREATE TABLE `hjmall_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(128) NOT NULL,
  `delay_seconds` int(11) NOT NULL COMMENT '多少秒后执行',
  `is_executed` int(1) NOT NULL DEFAULT '0' COMMENT '是否已执行：0=未执行，1=已执行',
  `class` varchar(255) NOT NULL,
  `params` longtext,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` int(1) NOT NULL DEFAULT '0',
  `content` longtext NOT NULL COMMENT '任务说明',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='定时任务';


CREATE TABLE `hjmall_template_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `tpl_name` varchar(32) NOT NULL COMMENT '模版名称',
  `tpl_id` varchar(64) NOT NULL COMMENT '模版ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_id_tpl_name` (`store_id`,`tpl_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_territorial_limitation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `detail` longtext NOT NULL COMMENT '规则详细',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_enable` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否启用：0=否，1=是',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_enable` (`is_enable`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `sub_title` varchar(255) NOT NULL DEFAULT '' COMMENT '副标题',
  `cover_pic` longtext COMMENT '封面图片',
  `content` longtext COMMENT '专题内容',
  `read_count` int(11) NOT NULL DEFAULT '0' COMMENT '阅读量',
  `virtual_read_count` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟阅读量',
  `layout` smallint(6) NOT NULL DEFAULT '0' COMMENT '布局方式：0=小图，1=大图模式',
  `sort` int(11) NOT NULL DEFAULT '1000' COMMENT '排序：升序',
  `agree_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `virtual_agree_count` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟点赞数',
  `virtual_favorite_count` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟收藏量',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `is_chosen` smallint(6) DEFAULT NULL,
  `type` smallint(6) DEFAULT NULL,
  `qrcode_pic` longtext COMMENT '海报分享图',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_chosen` (`is_chosen`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专题';


CREATE TABLE `hjmall_topic_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `topic_id` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收藏的专题';


CREATE TABLE `hjmall_topic_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `is_delete` smallint(6) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_upload_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `storage_type` varchar(255) NOT NULL DEFAULT '' COMMENT '存储类型：留空=本地，aliyun=阿里云oss，qcloud=腾讯云cos，qiniu=七牛云存储',
  `aliyun` longtext COMMENT '阿里云oss配置，json格式',
  `qcloud` longtext COMMENT '腾讯云cos配置，json格式',
  `qiniu` longtext COMMENT '七牛云存储配置，json格式',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文件上传方式配置';


CREATE TABLE `hjmall_upload_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `file_url` varchar(2048) DEFAULT NULL,
  `extension` varchar(255) NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '文件类型：',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小，字节',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `group_id` int(11) DEFAULT '0' COMMENT '分组id',
  `mch_id` int(11) DEFAULT '0' COMMENT '商户id',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户上传的文件';


CREATE TABLE `hjmall_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '用户类型：0=管理员，1=普通用户',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `auth_key` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `wechat_open_id` varchar(255) NOT NULL DEFAULT '' COMMENT '微信openid',
  `wechat_union_id` varchar(255) NOT NULL DEFAULT '' COMMENT '微信用户union id',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar_url` varchar(2048) DEFAULT NULL,
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商城id',
  `is_distributor` int(11) NOT NULL DEFAULT '0' COMMENT '是否是分销商 0--不是 1--是 2--申请中',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `time` int(11) NOT NULL DEFAULT '0' COMMENT '成为分销商的时间',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '累计佣金',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '可提现佣金',
  `is_clerk` int(11) NOT NULL DEFAULT '0' COMMENT '是否是核销员 0--不是 1--是',
  `we7_uid` int(11) NOT NULL DEFAULT '0' COMMENT '微擎账户id',
  `shop_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT '-1' COMMENT '会员等级',
  `integral` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户当前积分',
  `total_integral` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户总获得积分',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '余额',
  `contact_way` varchar(255) DEFAULT NULL COMMENT '联系方式',
  `comments` varchar(255) DEFAULT NULL COMMENT '备注',
  `binding` char(11) DEFAULT NULL COMMENT '授权手机号',
  `wechat_platform_open_id` varchar(64) NOT NULL DEFAULT '' COMMENT '微信公众号openid',
  `platform` tinyint(4) NOT NULL DEFAULT '0' COMMENT '小程序平台 0 => 微信, 1 => 支付宝',
  `blacklist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '黑名单 0.否 | 1.是',
  `parent_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '可能成为上级的ID',
  PRIMARY KEY (`id`),
  KEY `access_token` (`access_token`),
  KEY `parent_id` (`parent_id`),
  KEY `store_id` (`store_id`),
  KEY `level` (`level`),
  KEY `is_delete` (`is_delete`),
  KEY `is_distributor` (`is_distributor`),
  KEY `is_clerk` (`is_clerk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';


CREATE TABLE `hjmall_user_account_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` smallint(6) NOT NULL COMMENT '类型：1=收入，2=支出',
  `price` decimal(10,2) NOT NULL COMMENT '变动金额',
  `desc` longtext NOT NULL COMMENT '变动说明',
  `addtime` int(11) NOT NULL,
  `order_type` int(11) NOT NULL DEFAULT '0' COMMENT '订单类型 0--充值 1--商城订单 2--秒杀订单 3--拼团订单 4--商城订单退款 5--秒杀订单退款 6--拼团订单退款',
  `order_id` int(11) DEFAULT '0' COMMENT '订单ID',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户账户余额变动记录';


CREATE TABLE `hjmall_user_auth_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `token` varchar(225) NOT NULL,
  `is_pass` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已确认登录：0=未扫码，1=已确认登录',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`(191)),
  KEY `user_id` (`user_id`),
  KEY `is_pass` (`is_pass`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户授权登录';


CREATE TABLE `hjmall_user_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `card_id` int(11) DEFAULT NULL COMMENT '卡券ID',
  `card_name` varchar(255) DEFAULT NULL COMMENT '卡券名称',
  `card_pic_url` varchar(2048) DEFAULT NULL,
  `card_content` longtext COMMENT '卡券描述',
  `is_use` int(11) DEFAULT NULL COMMENT '是否使用 0--未使用 1--已使用',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `clerk_id` int(11) DEFAULT NULL COMMENT '核销人id',
  `shop_id` int(11) DEFAULT NULL COMMENT '店铺ID',
  `clerk_time` int(11) DEFAULT NULL COMMENT ' 核销时间',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '发放卡券的订单id',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_use` (`is_use`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_user_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `coupon_auto_send_id` int(11) NOT NULL DEFAULT '0' COMMENT '自动发放id',
  `begin_time` int(11) NOT NULL DEFAULT '0' COMMENT '有效期开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '有效期结束时间',
  `is_expire` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已过期：0=未过期，1=已过期',
  `is_use` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已使用：0=未使用，1=已使用',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `type` smallint(6) DEFAULT '0' COMMENT '领取类型 0--平台发放 1--自动发放 2--领券中心领取',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '兑换支付积分数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '兑换支付价格',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `user_id` (`user_id`),
  KEY `coupon_id` (`coupon_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_expire` (`is_expire`),
  KEY `is_use` (`is_use`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户-优惠券关系';


CREATE TABLE `hjmall_user_form_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `form_id` varchar(255) NOT NULL,
  `times` int(11) NOT NULL DEFAULT '1' COMMENT '剩余使用次数',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户FormId记录';


CREATE TABLE `hjmall_user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '改变的字段',
  `before_change` varchar(255) NOT NULL DEFAULT '' COMMENT '改变前字段的值',
  `after_change` varchar(255) NOT NULL DEFAULT '' COMMENT '改变后字段的值',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_user_share_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `type` int(11) DEFAULT NULL COMMENT '类型 0--佣金 1--提现',
  `source` int(11) DEFAULT '1' COMMENT '佣金来源 1--一级分销 2--二级分销 3--三级分销',
  `money` decimal(10,2) DEFAULT NULL COMMENT '金额',
  `is_delete` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `order_type` int(11) DEFAULT '0' COMMENT '订单类型 0--商城订单 1--秒杀订单 2--拼团订单 3--预约订单',
  `version` varchar(255) DEFAULT '0' COMMENT '版本',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户佣金明细表';


CREATE TABLE `hjmall_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `url` varchar(2048) DEFAULT NULL,
  `sort` varchar(255) DEFAULT NULL COMMENT '排序  升序',
  `is_delete` smallint(6) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL COMMENT '商城id',
  `pic_url` varchar(2048) DEFAULT NULL,
  `content` longtext COMMENT '详情介绍',
  `type` int(11) DEFAULT '0' COMMENT '视频来源 0--源地址 1--腾讯视频',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_we7_user_auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `we7_user_id` int(11) NOT NULL,
  `auth` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_wechat_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL DEFAULT '0' COMMENT '微擎公众号id',
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `app_id` varchar(255) NOT NULL,
  `app_secret` varchar(255) NOT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `mch_id` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `cert_pem` longtext,
  `key_pem` longtext,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信小程序';


CREATE TABLE `hjmall_wechat_platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL DEFAULT '0' COMMENT '微擎公众号id',
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '公众号名称',
  `app_id` varchar(255) NOT NULL COMMENT '公众号appid',
  `app_secret` varchar(255) NOT NULL COMMENT '公众号appsecret',
  `desc` varchar(255) DEFAULT NULL COMMENT '公共号说明、备注',
  `mch_id` varchar(255) DEFAULT NULL COMMENT '微信支付商户号',
  `key` varchar(255) DEFAULT NULL COMMENT '微信支付key',
  `cert_pem` longtext COMMENT '微信支付cert文件内容',
  `key_pem` longtext COMMENT '微信支付key文件内容',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信公众号';


CREATE TABLE `hjmall_wechat_template_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `pay_tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '支付通知模板消息id',
  `send_tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '发货通知模板消息id',
  `refund_tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '退款通知模板消息id',
  `not_pay_tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '订单未支付通知模板消息id',
  `revoke_tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '订单取消通知模板消息id',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_wx_title` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(45) NOT NULL DEFAULT '' COMMENT '小程序页面路径',
  `store_id` int(11) DEFAULT NULL COMMENT '商城ID',
  `title` varchar(45) NOT NULL DEFAULT '默认标题' COMMENT '小程序页面标题',
  `addtime` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_yy_cat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '标题名称',
  `store_id` int(11) unsigned NOT NULL COMMENT '商城ID',
  `pic_url` varchar(2048) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序 升序',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `is_delete` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商城预约';


CREATE TABLE `hjmall_yy_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '字段名称',
  `type` varchar(255) DEFAULT NULL COMMENT '字段类型',
  `required` int(1) unsigned DEFAULT NULL COMMENT '是否必填',
  `default` varchar(255) DEFAULT NULL COMMENT '默认值',
  `tip` varchar(255) DEFAULT NULL COMMENT '提示语',
  `sort` int(11) DEFAULT NULL,
  `is_delete` int(1) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `option` longtext COMMENT '单选、复选项 值',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预约自定义表单';


CREATE TABLE `hjmall_yy_form_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL COMMENT '店铺id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `wechat_open_id` varchar(255) NOT NULL DEFAULT '' COMMENT '微信openid',
  `form_id` varchar(255) NOT NULL DEFAULT '',
  `order_no` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '可选值：form_id或prepay_id',
  `send_count` int(11) NOT NULL DEFAULT '0' COMMENT '使用次数',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_yy_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) unsigned NOT NULL COMMENT '预约金额',
  `original_price` decimal(10,2) unsigned NOT NULL COMMENT '原价',
  `detail` longtext NOT NULL COMMENT '商品详情，图文',
  `cat_id` int(11) unsigned DEFAULT '0' COMMENT '商品分类',
  `status` smallint(6) unsigned NOT NULL DEFAULT '2' COMMENT '上架状态【1=> 上架，2=> 下架】',
  `service` varchar(2000) NOT NULL COMMENT '服务选项',
  `sort` int(11) unsigned DEFAULT '0' COMMENT '商品排序 升序',
  `virtual_sales` int(11) unsigned DEFAULT '0' COMMENT '虚拟销量',
  `cover_pic` longtext COMMENT '商品缩略图',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_delete` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `sales` int(11) unsigned NOT NULL COMMENT '实际销量',
  `shop_id` varchar(255) DEFAULT '0' COMMENT '门店id',
  `store_id` int(11) unsigned NOT NULL,
  `buy_limit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '限购次数',
  `stock` int(11) unsigned DEFAULT '0' COMMENT '库存',
  `attr` longtext COMMENT '规格',
  `use_attr` smallint(1) DEFAULT '0' COMMENT '是否启用规格',
  `is_level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否享受会员折扣 0-不享受 1--享受',
  `video_url` varchar(255) DEFAULT NULL COMMENT '视频url',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_yy_goods_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `pic_url` varchar(2048) DEFAULT NULL,
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_yy_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL COMMENT '商品id',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `order_no` varchar(255) NOT NULL COMMENT '订单号',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单总费用',
  `pay_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际支付总费用',
  `is_pay` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付',
  `pay_type` smallint(6) unsigned DEFAULT '0' COMMENT '支付方式：1=微信支付',
  `pay_time` int(11) unsigned DEFAULT '0' COMMENT '支付时间',
  `is_use` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否使用',
  `is_comment` int(1) unsigned DEFAULT '0' COMMENT '是否评论',
  `apply_delete` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否申请取消订单：0=否，1=申请取消订单',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `offline_qrcode` longtext COMMENT '核销码',
  `is_cancel` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否取消',
  `store_id` int(11) unsigned NOT NULL,
  `use_time` int(11) unsigned DEFAULT NULL COMMENT '核销时间',
  `clerk_id` int(11) unsigned DEFAULT NULL COMMENT '核销员user_id',
  `shop_id` int(11) unsigned DEFAULT NULL COMMENT '自提门店ID',
  `is_refund` smallint(1) unsigned DEFAULT '0' COMMENT '是否取消',
  `form_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '表单ID',
  `refund_time` int(11) DEFAULT '0' COMMENT '取消时间',
  `is_recycle` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否加入回收站 0--不加入 1--加入',
  `is_show` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0--不显示 1--显示（软删除）',
  `seller_comments` mediumtext COMMENT '商家备注',
  `attr` longtext COMMENT '规格',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_pay` (`is_pay`),
  KEY `is_use` (`is_use`),
  KEY `is_comment` (`is_comment`),
  KEY `is_cancel` (`is_cancel`),
  KEY `is_refund` (`is_refund`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预约订单表';


CREATE TABLE `hjmall_yy_order_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` decimal(10,1) NOT NULL COMMENT '评分：1=差评，2=中评，3=好',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '评价内容',
  `pic_list` longtext COMMENT '图片',
  `is_hide` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0=不隐藏，1=隐藏',
  `is_delete` smallint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `is_hide` (`is_hide`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_yy_order_form` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) unsigned DEFAULT NULL,
  `goods_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `order_id` int(11) unsigned DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `is_delete` int(11) unsigned DEFAULT NULL,
  `addtime` int(11) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete` (`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hjmall_yy_setting` (
  `store_id` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示分类',
  `cat` smallint(1) NOT NULL COMMENT '参数',
  `success_notice` longtext COMMENT '预约成功模板通知',
  `refund_notice` longtext COMMENT '退款模板id',
  `is_share` int(11) DEFAULT '0' COMMENT '是否开启分销 0--关闭 1--开启',
  `is_sms` int(11) DEFAULT '0' COMMENT '是否开启短信通知',
  `is_print` int(11) DEFAULT '0' COMMENT '是否开启订单打印',
  `is_mail` int(11) DEFAULT '0' COMMENT '是否开启邮件通知',
  PRIMARY KEY (`store_id`),
  KEY `is_share` (`is_share`),
  KEY `is_sms` (`is_sms`),
  KEY `is_print` (`is_print`),
  KEY `is_mail` (`is_mail`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2019-04-12 02:27:38 v3.1.40
