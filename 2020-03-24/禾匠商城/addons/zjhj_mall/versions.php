<?php

return [
    '1.0.0.0' => function () {
        hj_pdo_run(file_get_contents(__DIR__ . '/install.sql'));
    },

    '2.3.2.2' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `virtual_sales` INT ( 11 ) NULL DEFAULT 0 COMMENT '虚拟销量';");
        hj_pdo_run("UPDATE hjmall_goods SET virtual_sales = 0 WHERE ISNULL( virtual_sales );");
        hj_pdo_run("UPDATE hjmall_goods SET sort = 1000 WHERE ISNULL( sort );");
        hj_pdo_run("CREATE TABLE `hjmall_delivery` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NULL, `express_id` INT ( 11 ) NULL COMMENT '快递公司id', `customer_name` VARCHAR ( 255 ) NULL COMMENT '电子面单客户账号', `customer_pwd` VARCHAR ( 255 ) NULL COMMENT '电子面单密码', `month_code` VARCHAR ( 255 ) NULL COMMENT '月结编码', `send_site` VARCHAR ( 255 ) NULL COMMENT '网点编码', `send_name` VARCHAR ( 255 ) NULL COMMENT '网点名称', `is_delete` SMALLINT ( 2 ) NULL, `addtime` INT ( 11 ) NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_sender` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NULL, `company` VARCHAR ( 255 ) NULL, `name` VARCHAR ( 255 ) NULL, `tel` VARCHAR ( 255 ) NULL, `mobile` VARCHAR ( 255 ) NULL, `post_code` VARCHAR ( 255 ) NULL, `province` VARCHAR ( 255 ) NULL, `city` VARCHAR ( 255 ) NULL, `exp_area` VARCHAR ( 255 ) NULL, `address` VARCHAR ( 255 ) NULL, `is_delete` SMALLINT ( 2 ) NULL, `addtime` INT ( 11 ) NULL, `delivery_id` INT ( 11 ) NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_upload_config` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `storage_type` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '存储类型：留空=本地，aliyun=阿里云oss，qcloud=腾讯云cos，qiniu=七牛云存储', `aliyun` LONGTEXT COMMENT '阿里云oss配置，json格式', `qcloud` LONGTEXT COMMENT '腾讯云cos配置，json格式', `qiniu` LONGTEXT COMMENT '七牛云存储配置，json格式', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '文件上传方式配置';");
        hj_pdo_run("CREATE TABLE `hjmall_upload_file` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `file_url` LONGTEXT COMMENT '文件url', `extension` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '文件扩展名', `type` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '文件类型：', `size` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '文件大小，字节', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '用户上传的文件';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `content` LONGTEXT NULL, ADD COLUMN `is_offline` INT ( 11 ) NOT NULL DEFAULT 0 COMMENT '是否到店自提 0--否 1--是', ADD COLUMN `clerk_id` INT ( 11 ) NULL COMMENT '核销员user_id';");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD COLUMN `is_clerk` INT ( 11 ) NOT NULL DEFAULT 0 COMMENT '是否是核销员 0--不是 1--是';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `address` LONGTEXT NULL COMMENT '店铺地址';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `address_data` LONGTEXT NULL COMMENT '收货地址信息，json格式';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `cover_pic` LONGTEXT NULL COMMENT '商品缩略图';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `cat_goods_cols` INT NOT NULL DEFAULT 3 COMMENT '首页分类商品列数';");
        hj_pdo_run("CREATE TABLE `hjmall_video` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `title` VARCHAR ( 255 ) DEFAULT NULL COMMENT '标题', `url` LONGTEXT COMMENT '路径', `sort` VARCHAR ( 255 ) DEFAULT NULL COMMENT '排序  升序', `is_delete` SMALLINT ( 6 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, `store_id` INT ( 11 ) DEFAULT NULL COMMENT '商城id', `pic_url` LONGTEXT, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `over_day` INT ( 11 ) NOT NULL DEFAULT 0 COMMENT '未支付订单超时时间', ADD COLUMN `is_offline` SMALLINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否开启自提';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `is_cancel` SMALLINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否取消', ADD COLUMN `offline_qrcode` LONGTEXT NULL COMMENT '核销码', ADD COLUMN `before_update_price` DECIMAL ( 10, 2 ) NULL COMMENT '修改前的价格';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `video_url` LONGTEXT NULL COMMENT '视频';");
        hj_pdo_run("ALTER TABLE `hjmall_wechat_template_message` ADD COLUMN `pay_tpl` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '支付通知模板消息id', ADD COLUMN `send_tpl` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '发货通知模板消息id', ADD COLUMN `refund_tpl` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '退款通知模板消息id', ADD COLUMN `not_pay_tpl` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '订单未支付通知模板消息id', ADD COLUMN `revoke_tpl` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '订单取消通知模板消息id';");
        hj_pdo_run("INSERT INTO hjmall_wechat_template_message ( store_id, send_tpl ) SELECT id, order_send_tpl FROM hjmall_store;");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `unit` VARCHAR ( 255 ) NOT NULL DEFAULT '件' COMMENT '单位';");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` ADD COLUMN `total_count` TINYINT ( 11 ) NOT NULL DEFAULT - 1 COMMENT '发放总数量', ADD COLUMN `is_join` SMALLINT ( 6 ) NOT NULL DEFAULT 1 COMMENT '是否加入领券中心 1--不加入领券中心 2--加入领券中心', ADD COLUMN `sort` INT ( 11 ) NULL DEFAULT 100 COMMENT '排序按升序排列';");
        hj_pdo_run("ALTER TABLE `hjmall_user_coupon` ADD COLUMN `type` SMALLINT ( 6 ) NULL DEFAULT 0 COMMENT '领取类型 0--平台发放 1--自动发放 2--领券中心领取';");
        hj_pdo_run("ALTER TABLE `hjmall_video` ADD COLUMN `content` LONGTEXT NULL COMMENT '详情介绍';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `is_coupon` SMALLINT ( 6 ) NULL DEFAULT 0 COMMENT '是否开启优惠券';");
        hj_pdo_run("ALTER TABLE `hjmall_order` MODIFY COLUMN `name` VARCHAR ( 255 ) CHARACTER SET utf8 NULL COMMENT '收货人姓名', MODIFY COLUMN `mobile` VARCHAR ( 255 ) CHARACTER SET utf8 NULL COMMENT '收货人手机', MODIFY COLUMN `address` VARCHAR ( 1000 ) CHARACTER SET utf8 NULL COMMENT '收货地址', ADD COLUMN `shop_id` INT ( 11 ) NULL COMMENT '自提门店ID';");
        hj_pdo_run("CREATE TABLE `hjmall_shop` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NULL, `name` VARCHAR ( 255 ) NULL, `mobile` VARCHAR ( 255 ) NULL, `address` VARCHAR ( 255 ) NULL, `is_delete` SMALLINT ( 6 ) NULL, `addtime` INT ( 11 ) NULL, PRIMARY KEY ( `id` ) ) COMMENT = '门店设置';");
        hj_pdo_run("INSERT INTO hjmall_district ( `id`, `parent_id`, `citycode`, `adcode`, `name`, `lng`, `lat`, `level` ) VALUES ( '3263', '850', '320589', '320589', '高新区', '120', '30', 'district' );");
        hj_pdo_run("DELETE FROM hjmall_district WHERE id > 3263 AND citycode = 320589;");
        hj_pdo_run("CREATE TABLE `hjmall_topic` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `title` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '标题', `sub_title` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '副标题', `cover_pic` LONGTEXT COMMENT '封面图片', `content` LONGTEXT COMMENT '专题内容', `read_count` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '阅读量', `virtual_read_count` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '虚拟阅读量', `layout` SMALLINT ( 6 ) NOT NULL DEFAULT '0' COMMENT '布局方式：0=小图，1=大图模式', `sort` INT ( 11 ) NOT NULL DEFAULT '1000' COMMENT '排序：升序', `agree_count` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '点赞数', `virtual_agree_count` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '虚拟点赞数', `virtual_favorite_count` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '虚拟收藏量', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 6 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '专题';");
        hj_pdo_run("CREATE TABLE `hjmall_topic_favorite` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL DEFAULT '0', `user_id` INT ( 11 ) NOT NULL DEFAULT '0', `topic_id` INT ( 11 ) NOT NULL DEFAULT '0', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 6 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '用户收藏的专题';");
        hj_pdo_run("ALTER TABLE `hjmall_shop` DEFAULT CHARACTER SET = utf8, MODIFY COLUMN `name` VARCHAR ( 255 ) CHARACTER SET utf8 NULL DEFAULT NULL, MODIFY COLUMN `mobile` VARCHAR ( 255 ) CHARACTER SET utf8 NULL DEFAULT NULL, MODIFY COLUMN `address` VARCHAR ( 255 ) CHARACTER SET utf8 NULL DEFAULT NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` MODIFY COLUMN `total_count` INT ( 11 ) NOT NULL DEFAULT '-1' COMMENT '发放总数量';");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD COLUMN `we7_uid` INT NOT NULL DEFAULT 0 COMMENT '微擎账户id';");
        hj_pdo_run("CREATE TABLE `hjmall_admin` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `username` VARCHAR ( 255 ) NOT NULL, `password` VARCHAR ( 255 ) NOT NULL, `auth_key` VARCHAR ( 255 ) NOT NULL, `access_token` VARCHAR ( 255 ) NOT NULL, `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 6 ) NOT NULL DEFAULT '0', `app_max_count` INT ( 11 ) NOT NULL DEFAULT '0', `permission` LONGTEXT, `remark` VARCHAR ( 255 ) NOT NULL DEFAULT '', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_admin_permission` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `name` VARCHAR ( 255 ) NOT NULL DEFAULT '', `display_name` VARCHAR ( 255 ) NOT NULL, `is_delete` SMALLINT ( 6 ) NOT NULL DEFAULT '0', `sort` INT ( 11 ) NOT NULL DEFAULT '1000', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_miaosha` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `open_time` LONGTEXT COMMENT '开放时间，JSON格式', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_miaosha_goods` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `goods_id` INT ( 11 ) NOT NULL, `start_time` SMALLINT ( 6 ) NOT NULL COMMENT '开始时间：0~23', `open_date` DATE NOT NULL COMMENT '开放日期，例：2017-08-21', `attr` LONGTEXT NOT NULL COMMENT '规格秒杀价数量', `is_delete` SMALLINT ( 6 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `admin_id` INT NOT NULL DEFAULT 0, ADD COLUMN `is_delete` SMALLINT NOT NULL DEFAULT 0;");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `cat_goods_count` INT NOT NULL DEFAULT 6 COMMENT '首页分类的商品个数';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `send_type` SMALLINT NOT NULL DEFAULT 1 COMMENT '发货方式：0=快递或自提，1=仅快递，2=仅自提';");
        // hj_pdo_run("UPDATE hjmall_store SET send_type = 0 WHERE is_offline = 1;");
        // hj_pdo_run("UPDATE hjmall_store SET send_type = 1 WHERE is_offline = 0;");
        hj_pdo_run("CREATE TABLE IF NOT EXISTS `hjmall_plugin` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `data` LONGTEXT NOT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD COLUMN `shop_id` INT NULL;");
        hj_pdo_run("CREATE TABLE `hjmall_option` ( `id` BIGINT ( 20 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL DEFAULT '0', `group` VARCHAR ( 255 ) NOT NULL DEFAULT '', `name` VARCHAR ( 255 ) NOT NULL, `value` LONGTEXT NOT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `individual_share` SMALLINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否单独分销设置：0=否，1=是', ADD COLUMN `share_commission_first` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '一级分销佣金比例', ADD COLUMN `share_commission_second` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '二级分销佣金比例', ADD COLUMN `share_commission_third` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '三级分销佣金比例';");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD COLUMN `level` INT ( 11 ) NULL DEFAULT '-1' COMMENT '会员等级';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `discount` DECIMAL ( 11, 2 ) NULL COMMENT '会员折扣';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `member_content` LONGTEXT NULL COMMENT '会员等级说明';");
        hj_pdo_run("CREATE TABLE `hjmall_level` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `level` INT ( 11 ) DEFAULT NULL, `name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '等级名称', `money` DECIMAL ( 10, 1 ) DEFAULT NULL COMMENT '会员完成订单金额满足则升级', `discount` DECIMAL ( 10, 1 ) DEFAULT NULL COMMENT '折扣', `status` INT ( 11 ) DEFAULT NULL COMMENT '状态 0--禁用 1--启用', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '会员等级';");
        hj_pdo_run("ALTER TABLE `hjmall_level` MODIFY COLUMN `money` DECIMAL ( 10, 2 ) NULL DEFAULT NULL COMMENT '会员完成订单金额满足则升级';");
        hj_pdo_run("ALTER TABLE `hjmall_admin` ADD COLUMN `expire_time` INT NOT NULL DEFAULT 0 COMMENT '账户有效期至，0表示永久';");
        hj_pdo_run("ALTER TABLE `hjmall_postage_rules` ADD COLUMN `type` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT 1 COMMENT '计费方式【1=>按重计费、2=>按件计费】';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `weight` DOUBLE ( 10, 2 ) UNSIGNED NULL DEFAULT 0 COMMENT '重量';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `freight` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '运费模板ID';");
        hj_pdo_run("ALTER TABLE `hjmall_order_detail` ADD COLUMN `pic` VARCHAR ( 255 ) NOT NULL COMMENT '商品规格图片';");
        hj_pdo_run("ALTER TABLE `hjmall_shop` ADD COLUMN `longitude` LONGTEXT NULL, ADD COLUMN `latitude` LONGTEXT NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `user_coupon_id` INT NULL COMMENT '使用的优惠券ID';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `nav_count` INT ( 11 ) NULL DEFAULT 0 COMMENT '首页导航栏个数 0--4个 1--5个';");
        hj_pdo_run("CREATE TABLE `hjmall_order_message` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `order_id` INT ( 11 ) DEFAULT NULL COMMENT '类型id 系统消息时为0', `is_read` INT ( 11 ) DEFAULT NULL COMMENT '消息是否已读 0--未读 1--已读', `is_sound` INT ( 11 ) DEFAULT NULL COMMENT '是否提示 0--未提示  1--已提示', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_miaosha_goods` ADD COLUMN `buy_max` INT NOT NULL DEFAULT 0 COMMENT '限购数量，0=不限购';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `full_cut` LONGTEXT CHARACTER SET utf8 NULL COMMENT '满减';");
        hj_pdo_run("ALTER TABLE `hjmall_cat` ADD COLUMN `advert_pic` LONGTEXT CHARACTER SET utf8 NULL COMMENT '广告图片', ADD COLUMN `advert_url` LONGTEXT CHARACTER SET utf8 NULL COMMENT '广告链接', ADD COLUMN `is_show` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示【1=> 显示，2=> 隐藏】';");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD COLUMN `integral` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户当前积分', ADD COLUMN `total_integral` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户总获得积分';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `integral` TEXT CHARACTER SET utf8 NULL COMMENT '积分设置';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `integral` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 10 COMMENT '一元抵多少积分';");
        hj_pdo_run("ALTER TABLE `hjmall_user` MODIFY COLUMN `integral` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户当前积分', MODIFY COLUMN `total_integral` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户总获得积分';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `integration` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '积分使用说明';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `integral` LONGTEXT CHARACTER SET utf8 NULL COMMENT '积分使用';");
        hj_pdo_run("ALTER TABLE `hjmall_order_detail` ADD COLUMN `integral` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '单品积分获得';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `give_integral` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否发放积分【1=> 已发放 ， 0=> 未发放】';");
        hj_pdo_run("ALTER TABLE `hjmall_store` MODIFY COLUMN `integration` LONGTEXT CHARACTER SET utf8 NULL COMMENT '积分使用说明';");
        hj_pdo_run("ALTER TABLE `hjmall_video` ADD COLUMN `type` INT ( 11 ) NULL DEFAULT 0 COMMENT '视频来源 0--源地址 1--腾讯视频';");
        hj_pdo_run("ALTER TABLE `hjmall_shop` ADD COLUMN `score` INT ( 11 ) NULL DEFAULT 5 COMMENT '评分 1~5', ADD COLUMN `cover_url` LONGTEXT NULL COMMENT '门店大图', ADD COLUMN `pic_url` LONGTEXT NULL COMMENT '门店小图', ADD COLUMN `shop_time` VARCHAR ( 255 ) NULL COMMENT '营业时间', ADD COLUMN `content` LONGTEXT NULL COMMENT '门店介绍';");
        hj_pdo_run("ALTER TABLE `hjmall_attr` ADD COLUMN `is_default` SMALLINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否是默认属性';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `use_attr` SMALLINT ( 1 ) NOT NULL DEFAULT 1 COMMENT '是否使用规格：0=不使用，1=使用';");
        hj_pdo_run("CREATE TABLE `hjmall_user_card` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `user_id` INT ( 11 ) DEFAULT NULL COMMENT '用户ID', `card_id` INT ( 11 ) DEFAULT NULL COMMENT '卡券ID', `card_name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '卡券名称', `card_pic_url` LONGTEXT COMMENT '卡券图片', `card_content` LONGTEXT COMMENT '卡券描述', `is_use` INT ( 11 ) DEFAULT NULL COMMENT '是否使用 0--未使用 1--已使用', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, `clerk_id` INT ( 11 ) DEFAULT NULL COMMENT '核销人id', `shop_id` INT ( 11 ) DEFAULT NULL COMMENT '店铺ID', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_goods_card` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `goods_id` INT ( 11 ) DEFAULT NULL, `card_id` INT ( 11 ) DEFAULT NULL COMMENT '卡券id', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_card` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '卡券名称', `pic_url` LONGTEXT COMMENT '卡券图片', `content` LONGTEXT COMMENT '卡券描述', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `parent_id_1` INT ( 11 ) NULL DEFAULT 0 COMMENT '用户上二级ID', ADD COLUMN `parent_id_2` INT ( 11 ) NULL DEFAULT 0 COMMENT '用户上三级ID', ADD COLUMN `is_sale` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否超过售后时间';");
        hj_pdo_run("CREATE TABLE `hjmall_user_share_money` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `order_id` INT ( 11 ) DEFAULT NULL COMMENT '订单ID', `user_id` INT ( 11 ) DEFAULT NULL COMMENT '用户ID', `type` INT ( 11 ) DEFAULT NULL COMMENT '类型 0--佣金 1--提现', `source` INT ( 11 ) DEFAULT '1' COMMENT '佣金来源 1--一级分销 2--二级分销 3--三级分销', `money` DECIMAL ( 10, 2 ) DEFAULT NULL COMMENT '金额', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '用户佣金明细表';");
        hj_pdo_run("CREATE TABLE `hjmall_printer_setting` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `printer_id` INT ( 11 ) DEFAULT NULL COMMENT '打印机ID', `block_id` INT ( 11 ) DEFAULT NULL COMMENT '打印模板ID', `type` LONGTEXT COMMENT '打印方式 ', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '打印设置';");
        hj_pdo_run("CREATE TABLE `hjmall_printer` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '打印机名称', `printer_type` VARCHAR ( 255 ) DEFAULT NULL COMMENT '打印机类型', `printer_setting` LONGTEXT COMMENT '打印机设置', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '小票打印机';");
        hj_pdo_run("CREATE TABLE `hjmall_integral_log` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `user_id` INT ( 11 ) UNSIGNED NOT NULL COMMENT '用户id', `content` LONGTEXT NOT NULL COMMENT '描述', `integral` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '积分', `addtime` INT ( 11 ) UNSIGNED NOT NULL COMMENT '添加时间', `username` VARCHAR ( 255 ) NOT NULL COMMENT '用户名', `operator` VARCHAR ( 255 ) NOT NULL COMMENT '操作者', `store_id` INT ( 11 ) UNSIGNED NOT NULL, `operator_id` INT ( 11 ) UNSIGNED NOT NULL COMMENT '分销商id', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_banner` ADD COLUMN `type` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT 1 COMMENT '类型 【1=> 商城，2=> 拼团】';");
        hj_pdo_run("ALTER TABLE `hjmall_user_card` ADD COLUMN `clerk_time` INT NULL COMMENT ' 核销时间';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `words` LONGTEXT NULL COMMENT '商家留言';");
        hj_pdo_run("CREATE TABLE `hjmall_pt_cat` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR ( 255 ) CHARACTER SET utf8 NOT NULL COMMENT '标题名称', `store_id` INT ( 11 ) UNSIGNED NOT NULL COMMENT '商城ID', `pic_url` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '分类图片url', `sort` INT ( 11 ) NOT NULL DEFAULT '100' COMMENT '排序 升序', `addtime` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_pt_goods` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `name` VARCHAR ( 255 ) CHARACTER SET utf8 NOT NULL COMMENT '商品名称', `original_price` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL COMMENT '商品原价', `price` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL COMMENT '团购价', `detail` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '商品详情，图文', `cat_id` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品分类', `status` SMALLINT ( 6 ) UNSIGNED NOT NULL DEFAULT '2' COMMENT '上架状态【1=> 上架，2=> 下架】', `grouptime` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '拼团时间/小时', `attr` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '规格的库存及价格', `service` VARCHAR ( 2000 ) CHARACTER SET utf8 NOT NULL COMMENT '服务选项', `sort` INT ( 11 ) UNSIGNED DEFAULT '0' COMMENT '商品排序 升序', `virtual_sales` INT ( 11 ) UNSIGNED DEFAULT NULL COMMENT '虚拟销量', `cover_pic` LONGTEXT CHARACTER SET utf8 COMMENT '商品缩略图', `weight` INT ( 11 ) UNSIGNED DEFAULT '0' COMMENT '重量', `freight` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '运费模板ID', `unit` VARCHAR ( 255 ) NOT NULL DEFAULT '件' COMMENT '单位', `addtime` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间', `is_delete` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除', `group_num` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品成团数', `is_hot` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否热卖【0=>热卖1=>不是】', `limit_time` INT ( 11 ) UNSIGNED DEFAULT NULL COMMENT '拼团限时', `is_only` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否允许单独购买', `is_more` SMALLINT ( 1 ) UNSIGNED DEFAULT '1' COMMENT '是否允许多件购买', `colonel` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '团长优惠', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_pt_goods_pic` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `goods_id` INT ( 11 ) NOT NULL DEFAULT '0', `pic_url` LONGTEXT NOT NULL, `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_pt_order` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL COMMENT '用户id', `order_no` VARCHAR ( 255 ) NOT NULL COMMENT '订单号', `total_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '订单总费用(包含运费）', `pay_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '实际支付总费用(含运费）', `express_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '运费', `name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '收货人姓名', `mobile` VARCHAR ( 255 ) DEFAULT NULL COMMENT '收货人手机', `address` VARCHAR ( 1000 ) DEFAULT NULL COMMENT '收货地址', `remark` VARCHAR ( 1000 ) NOT NULL DEFAULT '' COMMENT '订单备注', `is_pay` SMALLINT ( 6 ) NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付', `pay_type` SMALLINT ( 6 ) NOT NULL DEFAULT '0' COMMENT '支付方式：1=微信支付', `pay_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '支付时间', `is_send` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '发货状态：0=未发货，1=已发货', `send_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '发货时间', `express` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '物流公司', `express_no` VARCHAR ( 255 ) NOT NULL DEFAULT '', `is_confirm` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '确认收货状态：0=未确认，1=已确认收货', `confirm_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '确认收货时间', `is_comment` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否已评价：0=未评价，1=已评价', `apply_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否申请取消订单：0=否，1=申请取消订单', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `address_data` LONGTEXT COMMENT '收货地址信息，json格式', `is_group` SMALLINT ( 1 ) UNSIGNED NOT NULL COMMENT '是否团购', `parent_id` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '团ID【0=> 团长ID】', `colonel` DECIMAL ( 10, 2 ) DEFAULT '0.00' COMMENT '团长优惠', `is_success` SMALLINT ( 1 ) UNSIGNED DEFAULT '0' COMMENT '是否成团', `success_time` INT ( 11 ) UNSIGNED DEFAULT NULL COMMENT '成团时间', `status` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '1' COMMENT '拼团状态【1=> 待付款，2= 拼团中，3=拼团成功，4=拼团失败】', `is_returnd` SMALLINT ( 1 ) UNSIGNED DEFAULT NULL COMMENT '是否退款', `is_cancel` SMALLINT ( 1 ) DEFAULT '0' COMMENT '是否取消', `limit_time` INT ( 1 ) UNSIGNED DEFAULT NULL COMMENT '拼团限时', `content` LONGTEXT CHARACTER SET utf8 COMMENT '备注', `words` LONGTEXT COMMENT '商家留言', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_pt_order_comment` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `order_id` INT ( 11 ) NOT NULL, `order_detail_id` INT ( 11 ) NOT NULL, `goods_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL, `score` DECIMAL ( 10, 1 ) NOT NULL COMMENT '评分：1=差评，2=中评，3=好', `content` VARCHAR ( 1000 ) NOT NULL DEFAULT '' COMMENT '评价内容', `pic_list` LONGTEXT COMMENT '图片', `is_hide` SMALLINT ( 6 ) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0=不隐藏，1=隐藏', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_pt_order_detail` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `order_id` INT ( 11 ) NOT NULL, `goods_id` INT ( 11 ) NOT NULL, `num` INT ( 11 ) NOT NULL DEFAULT '1' COMMENT '商品数量', `total_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '此商品的总价', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `attr` LONGTEXT NOT NULL COMMENT '商品规格', `pic` VARCHAR ( 255 ) NOT NULL COMMENT '商品规格图片', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_mail_setting` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `send_mail` LONGTEXT COMMENT '发件人邮箱', `send_pwd` VARCHAR ( 255 ) DEFAULT NULL COMMENT '授权码', `send_name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '发件人名称', `receive_mail` LONGTEXT COMMENT '收件人邮箱 多个用英文逗号隔开', `status` INT ( 11 ) DEFAULT NULL COMMENT '是否开启邮件通知 0--关闭 1--开启', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` MODIFY COLUMN `virtual_sales` INT ( 11 ) UNSIGNED NULL DEFAULT 0 COMMENT '虚拟销量';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD COLUMN `buy_limit` INT ( 11 ) UNSIGNED NULL DEFAULT 0 COMMENT '限购数量';");
        hj_pdo_run("ALTER TABLE `hjmall_setting` ADD COLUMN `price_type` INT ( 11 ) NULL DEFAULT 0 COMMENT '分销金额 0--百分比金额  1--固定金额';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `share_type` INT ( 11 ) NULL DEFAULT 0 COMMENT '佣金配比 0--百分比 1--固定金额';");
        hj_pdo_run("CREATE TABLE `hjmall_we7_user_auth` ( `id` INT ( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT, `we7_user_id` INT ( 11 ) NOT NULL, `auth` LONGTEXT, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_yy_cat` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR ( 255 ) CHARACTER SET utf8 NOT NULL COMMENT '标题名称', `store_id` INT ( 11 ) UNSIGNED NOT NULL COMMENT '商城ID', `pic_url` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '分类图片url', `sort` INT ( 11 ) NOT NULL DEFAULT '100' COMMENT '排序 升序', `addtime` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '商城预约';");
        hj_pdo_run("CREATE TABLE `hjmall_yy_form` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `goods_id` INT ( 11 ) DEFAULT NULL, `name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '字段名称', `type` VARCHAR ( 255 ) DEFAULT NULL COMMENT '字段类型', `required` INT ( 1 ) UNSIGNED DEFAULT NULL COMMENT '是否必填', `default` VARCHAR ( 255 ) DEFAULT NULL COMMENT '默认值', `tip` VARCHAR ( 255 ) DEFAULT NULL COMMENT '提示语', `sort` INT ( 11 ) DEFAULT NULL, `is_delete` INT ( 1 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, `option` LONGTEXT CHARACTER SET utf8 COMMENT '单选、复选项 值', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '预约自定义表单';");
        hj_pdo_run("CREATE TABLE `hjmall_yy_form_id` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL COMMENT '店铺id', `user_id` INT ( 11 ) NOT NULL COMMENT '用户id', `wechat_open_id` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '微信openid', `form_id` VARCHAR ( 255 ) NOT NULL DEFAULT '', `order_no` VARCHAR ( 255 ) NOT NULL DEFAULT '', `type` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '可选值：form_id或prepay_id', `send_count` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '使用次数', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_yy_goods` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR ( 255 ) CHARACTER SET utf8 NOT NULL COMMENT '商品名称', `price` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL COMMENT '预约金额', `original_price` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL COMMENT '原价', `detail` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '商品详情，图文', `cat_id` INT ( 11 ) UNSIGNED DEFAULT '0' COMMENT '商品分类', `status` SMALLINT ( 6 ) UNSIGNED NOT NULL DEFAULT '2' COMMENT '上架状态【1=> 上架，2=> 下架】', `service` VARCHAR ( 2000 ) CHARACTER SET utf8 NOT NULL COMMENT '服务选项', `sort` INT ( 11 ) UNSIGNED DEFAULT '0' COMMENT '商品排序 升序', `virtual_sales` INT ( 11 ) UNSIGNED DEFAULT NULL COMMENT '虚拟销量', `cover_pic` LONGTEXT CHARACTER SET utf8 COMMENT '商品缩略图', `addtime` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间', `is_delete` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除', `sales` INT ( 11 ) UNSIGNED NOT NULL COMMENT '实际销量', `shop_id` VARCHAR ( 11 ) CHARACTER SET utf8 DEFAULT '0' COMMENT '门店id', `store_id` INT ( 11 ) UNSIGNED NOT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_yy_goods_pic` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `goods_id` INT ( 11 ) NOT NULL DEFAULT '0', `pic_url` LONGTEXT NOT NULL, `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_yy_order` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `goods_id` INT ( 11 ) UNSIGNED NOT NULL COMMENT '商品id', `user_id` INT ( 11 ) UNSIGNED NOT NULL COMMENT '用户id', `order_no` VARCHAR ( 255 ) NOT NULL COMMENT '订单号', `total_price` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '订单总费用', `pay_price` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '实际支付总费用', `is_pay` SMALLINT ( 6 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付', `pay_type` SMALLINT ( 6 ) UNSIGNED DEFAULT '0' COMMENT '支付方式：1=微信支付', `pay_time` INT ( 11 ) UNSIGNED DEFAULT '0' COMMENT '支付时间', `is_use` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否使用', `is_comment` INT ( 1 ) UNSIGNED DEFAULT '0' COMMENT '是否评论', `apply_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否申请取消订单：0=否，1=申请取消订单', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `offline_qrcode` LONGTEXT COMMENT '核销码', `is_cancel` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否取消', `store_id` INT ( 11 ) UNSIGNED NOT NULL, `use_time` INT ( 11 ) UNSIGNED DEFAULT NULL COMMENT '核销时间', `clerk_id` INT ( 11 ) UNSIGNED DEFAULT NULL COMMENT '核销员user_id', `shop_id` INT ( 11 ) UNSIGNED DEFAULT NULL COMMENT '自提门店ID', `is_refund` SMALLINT ( 1 ) UNSIGNED DEFAULT '0' COMMENT '是否取消', `form_id` VARCHAR ( 255 ) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '表单ID', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '预约订单表';");
        hj_pdo_run("CREATE TABLE `hjmall_yy_order_comment` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `order_id` INT ( 11 ) NOT NULL, `goods_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL, `score` DECIMAL ( 10, 1 ) NOT NULL COMMENT '评分：1=差评，2=中评，3=好', `content` VARCHAR ( 1000 ) NOT NULL DEFAULT '' COMMENT '评价内容', `pic_list` LONGTEXT COMMENT '图片', `is_hide` SMALLINT ( 6 ) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0=不隐藏，1=隐藏', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_yy_order_form` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) UNSIGNED DEFAULT NULL, `goods_id` INT ( 11 ) UNSIGNED DEFAULT NULL, `user_id` INT ( 11 ) UNSIGNED DEFAULT NULL, `order_id` INT ( 11 ) UNSIGNED DEFAULT NULL, `key` VARCHAR ( 255 ) DEFAULT NULL, `value` VARCHAR ( 255 ) DEFAULT NULL, `is_delete` INT ( 11 ) UNSIGNED DEFAULT NULL, `addtime` INT ( 11 ) UNSIGNED DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_yy_setting` ( `store_id` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否显示分类', `cat` SMALLINT ( 1 ) NOT NULL COMMENT '参数', `success_notice` LONGTEXT CHARACTER SET utf8 COMMENT '预约成功模板通知', `refund_notice` LONGTEXT CHARACTER SET utf8 COMMENT '退款模板id', PRIMARY KEY ( `store_id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("INSERT INTO `hjmall_admin_permission` VALUES ( '1', 'coupon', '优惠券', '0', '1000' );");
        hj_pdo_run("INSERT INTO `hjmall_admin_permission` VALUES ( '2', 'share', '分销', '0', '1000' );");
        hj_pdo_run("INSERT INTO `hjmall_admin_permission` VALUES ( '3', 'topic', '专题', '0', '1000' );");
        hj_pdo_run("INSERT INTO `hjmall_admin_permission` VALUES ( '4', 'video', '视频专区', '0', '1000' );");
        hj_pdo_run("DELETE FROM `hjmall_option` WHERE `name` = 'user_center_menu_list' AND `group` = 'app';");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods` MODIFY COLUMN `virtual_sales` INT ( 11 ) UNSIGNED NULL DEFAULT 0 COMMENT '虚拟销量';");
        hj_pdo_run("CREATE TABLE `hjmall_form` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '名称', `type` VARCHAR ( 255 ) DEFAULT NULL COMMENT '类型', `required` INT ( 11 ) DEFAULT NULL COMMENT '必填项', `default` VARCHAR ( 255 ) DEFAULT NULL COMMENT '默认值', `tip` VARCHAR ( 255 ) DEFAULT NULL COMMENT '提示语', `sort` INT ( 11 ) DEFAULT NULL COMMENT '排序', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_order_form` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `order_id` INT ( 11 ) DEFAULT NULL, `key` VARCHAR ( 255 ) DEFAULT NULL COMMENT '表单key', `value` VARCHAR ( 255 ) DEFAULT NULL COMMENT '表单value', `is_delete` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '订单自定义表单信息';");
        hj_pdo_run("CREATE TABLE `hjmall_shop_pic` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `shop_id` INT ( 11 ) DEFAULT NULL, `store_id` INT ( 11 ) DEFAULT NULL, `pic_url` LONGTEXT, `is_delete` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods` MODIFY COLUMN `shop_id` VARCHAR ( 255 ) CHARACTER SET utf8 NULL DEFAULT '0' COMMENT '门店id';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `version` VARCHAR ( 255 ) NULL COMMENT '版本';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` MODIFY COLUMN `is_returnd` SMALLINT ( 1 ) UNSIGNED NULL DEFAULT 0 COMMENT '是否退款';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD COLUMN `type` INT ( 1 ) UNSIGNED NOT NULL DEFAULT 1 COMMENT '商品类型【1=> 送货上门，2=> 到店自提，3=> 全部支持】';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD COLUMN `shop_id` INT ( 11 ) UNSIGNED NULL DEFAULT 0 COMMENT '自提店铺', ADD COLUMN `offline` SMALLINT ( 1 ) UNSIGNED NULL DEFAULT 1 COMMENT '拿货方式';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD COLUMN `clerk_id` INT ( 11 ) UNSIGNED NULL DEFAULT 0 COMMENT '核销员ID';");
        hj_pdo_run("CREATE TABLE IF NOT EXISTS `hjmall_session` ( `id` VARCHAR ( 128 ) NOT NULL, `expire` INT ( 11 ) DEFAULT NULL, `data` LONGBLOB, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_cash` ADD COLUMN `bank` VARCHAR ( 255 ) NULL COMMENT '开户行';");
        hj_pdo_run("ALTER TABLE `hjmall_setting` ADD COLUMN `bank` TINYINT NULL COMMENT '银行卡支付  0 --不使用  1--使用';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `cut_thread` SMALLINT ( 1 ) NULL DEFAULT NULL COMMENT '分类分割线   0关闭   1开启', ADD COLUMN `dial` SMALLINT ( 1 ) NULL DEFAULT NULL COMMENT '一键拨号开关  0关闭  1开启', ADD COLUMN `dial_pic` TINYTEXT CHARACTER SET utf8 NULL COMMENT '拨号图标';");
        hj_pdo_run("ALTER TABLE `hjmall_order_message` ADD COLUMN `type` SMALLINT ( 1 ) NULL DEFAULT 0 COMMENT '订单类型  0--已下订单   1--售后订单';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD COLUMN `use_attr` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否使用规格：0=不使用，1=使用';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `express_price_1` DECIMAL ( 10, 2 ) NULL COMMENT '减免的运费';");
        hj_pdo_run("ALTER TABLE `hjmall_sms_setting` ADD COLUMN `tpl_refund` LONGTEXT NULL COMMENT '退款模板参数';");
        hj_pdo_run("CREATE TABLE `hjmall_goods_cat` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `goods_id` INT ( 11 ) DEFAULT NULL, `store_id` INT ( 11 ) DEFAULT NULL, `cat_id` INT ( 11 ) DEFAULT NULL COMMENT '分类id', `addtime` INT ( 11 ) DEFAULT NULL, `is_delete` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '商品分类';");
        hj_pdo_run("ALTER TABLE `hjmall_cash` ADD COLUMN `bank_name` VARCHAR ( 30 ) CHARACTER SET utf8 NULL DEFAULT NULL COMMENT '开户行名称';");
        hj_pdo_run("CREATE TABLE `hjmall_pt_order_refund` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL, `order_id` INT ( 11 ) NOT NULL, `order_detail_id` INT ( 11 ) NOT NULL, `order_refund_no` VARCHAR ( 255 ) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '退款单号', `type` SMALLINT ( 6 ) NOT NULL DEFAULT '1' COMMENT '售后类型：1=退货退款，2=换货', `refund_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '退款金额', `desc` VARCHAR ( 500 ) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '退款说明', `pic_list` LONGTEXT CHARACTER SET utf8 COMMENT '凭证图片列表：json格式', `status` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '状态：0=待商家处理，1=同意并已退款，2=已同意换货，3=已拒绝退换货', `refuse_desc` VARCHAR ( 500 ) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '拒绝退换货原因', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `response_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '商家处理时间', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '拼团订单售后表';");
        hj_pdo_run("INSERT INTO `hjmall_admin_permission` ( `id`, `name`, `display_name`, `is_delete`, `sort` ) VALUES ( '5', 'copyright', '版权设置', '0', '1000' );");
        hj_pdo_run("CREATE TABLE `hjmall_fxhb_hongbao` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `parent_id` INT ( 11 ) NOT NULL DEFAULT '0', `store_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL, `user_num` INT ( 11 ) NOT NULL COMMENT '拆红包所需用户数,最少2人', `coupon_total_money` DECIMAL ( 10, 2 ) NOT NULL COMMENT '红包总金额', `coupon_money` DECIMAL ( 10, 2 ) NOT NULL COMMENT '分到的红包金额', `coupon_use_minimum` DECIMAL ( 10, 2 ) NOT NULL COMMENT '红包使用最低消费金额', `coupon_expire` INT ( 11 ) NOT NULL DEFAULT '30' COMMENT '优惠券有效期，天', `distribute_type` TINYINT ( 1 ) NOT NULL COMMENT '红包分配类型：0=随机，1=平分', `is_expire` TINYINT ( 4 ) NOT NULL DEFAULT '0' COMMENT '是否已过期', `expire_time` INT ( 11 ) NOT NULL COMMENT '到期时间', `is_finish` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否已完成', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `form_id` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '小程序form_id', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_fxhb_setting` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `user_num` INT ( 11 ) NOT NULL DEFAULT '2' COMMENT '拆红包所需用户数,最少2人', `coupon_total_money` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '红包总金额', `coupon_use_minimum` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '赠送的优惠券最低消费金额', `coupon_expire` INT ( 11 ) NOT NULL DEFAULT '30' COMMENT '红包优惠券有效期', `distribute_type` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '红包分配类型：0=随机，1=平分', `tpl_msg_id` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '红包到账通知模板消息id', `game_time` INT ( 11 ) NOT NULL DEFAULT '24' COMMENT '每个红包有效期,单位：小时', `game_open` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否开启活动，0=不开启，1=开启', `rule` LONGTEXT COMMENT '规则', `share_pic` LONGTEXT, `share_title` LONGTEXT, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("INSERT INTO `hjmall_district` VALUES ( '3268', '1', '0', '0', '其他', '0', '0', 'province' );");
        hj_pdo_run("INSERT INTO `hjmall_district` VALUES ( '3269', '3268', '0', '0', '其他', '0', '0', 'city' );");
        hj_pdo_run("INSERT INTO `hjmall_district` VALUES ( '3270', '3269', '0', '0', '其他', '0', '0', 'district' );");
        hj_pdo_run("CREATE TABLE `hjmall_pt_robot` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR ( 255 ) CHARACTER SET utf8 NOT NULL COMMENT '机器人名', `pic` VARCHAR ( 255 ) CHARACTER SET utf8 NOT NULL COMMENT '头像', `is_delete` SMALLINT ( 1 ) UNSIGNED DEFAULT NULL COMMENT '是否删除', `addtime` INT ( 11 ) UNSIGNED DEFAULT NULL COMMENT '添加时间', `store_id` INT ( 11 ) UNSIGNED NOT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '拼团机器人表';");
        hj_pdo_run("ALTER TABLE `hjmall_printer_setting` ADD COLUMN `is_attr` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否打印规格 0--不打印 1--打印';");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods` ADD COLUMN `buy_limit` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '限购次数';");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods` ADD COLUMN `stock` INT ( 11 ) UNSIGNED NULL DEFAULT 0 COMMENT '库存';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD COLUMN `one_buy_limit` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '单次限购数量';");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD COLUMN `money` DECIMAL ( 10, 2 ) NULL DEFAULT 0 COMMENT '余额';");
        hj_pdo_run("CREATE TABLE `hjmall_recharge` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `pay_price` DECIMAL ( 10, 2 ) DEFAULT NULL COMMENT '支付金额', `send_price` DECIMAL ( 10, 2 ) DEFAULT '0.00' COMMENT '赠送金额', `name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '充值名称', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '充值管理';");
        hj_pdo_run("CREATE TABLE `hjmall_re_order` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `order_no` VARCHAR ( 255 ) DEFAULT NULL COMMENT '订单号', `user_id` INT ( 11 ) DEFAULT NULL COMMENT '用户', `pay_price` DECIMAL ( 10, 2 ) DEFAULT NULL COMMENT '支付金额', `send_price` DECIMAL ( 10, 2 ) DEFAULT NULL COMMENT '赠送金额', `pay_type` INT ( 11 ) DEFAULT '0' COMMENT '支付方式 1--微信支付', `is_pay` INT ( 11 ) DEFAULT '0' COMMENT '是否支付 0--未支付 1--支付', `pay_time` INT ( 11 ) DEFAULT NULL COMMENT '支付时间', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '充值记录';");
        hj_pdo_run("ALTER TABLE `hjmall_shop` ADD COLUMN `is_default` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否设为默认 0--否 1--是 （只能设置一个门店为默认门店）';");
        hj_pdo_run("ALTER TABLE `hjmall_order_comment` ADD COLUMN `reply_content` VARCHAR ( 255 ) NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `quick_purchase` SMALLINT ( 1 ) NULL DEFAULT NULL COMMENT '是否加入快速购买  0--关闭   1--开启';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `hot_cakes` SMALLINT ( 1 ) NULL DEFAULT NULL COMMENT '是否加入热销  0--关闭   1--开启';");
        hj_pdo_run("CREATE TABLE `hjmall_ms_goods` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR ( 255 ) CHARACTER SET utf8 NOT NULL COMMENT '商品名称', `original_price` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL COMMENT '原价', `detail` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '商品详情，图文', `status` SMALLINT ( 6 ) UNSIGNED NOT NULL DEFAULT '2' COMMENT '上架状态【1=> 上架，2=> 下架】', `service` VARCHAR ( 2000 ) CHARACTER SET utf8 NOT NULL COMMENT '服务选项', `sort` INT ( 11 ) UNSIGNED DEFAULT '0' COMMENT '商品排序 升序', `virtual_sales` INT ( 11 ) UNSIGNED DEFAULT '0' COMMENT '虚拟销量', `cover_pic` LONGTEXT CHARACTER SET utf8 COMMENT '商品缩略图', `addtime` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间', `is_delete` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除', `sales` INT ( 11 ) UNSIGNED NOT NULL COMMENT '实际销量', `store_id` INT ( 11 ) UNSIGNED NOT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` ADD COLUMN `video_url` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '视频', ADD COLUMN `unit` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '件' COMMENT '单位', ADD COLUMN `weight` DOUBLE ( 10, 2 ) UNSIGNED NULL DEFAULT 0.00 COMMENT '重量', ADD COLUMN `freight` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '运费模板ID', ADD COLUMN `full_cut` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '满减', ADD COLUMN `integral` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '积分设置', ADD COLUMN `use_attr` SMALLINT ( 1 ) NOT NULL DEFAULT 1 COMMENT '是否使用规格：0=不使用，1=使用';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` ADD COLUMN `attr` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '规格的库存及价格';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` MODIFY COLUMN `service` VARCHAR ( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '服务选项';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` MODIFY COLUMN `sales` INT ( 11 ) UNSIGNED NULL COMMENT '实际销量';");
        hj_pdo_run("ALTER TABLE `hjmall_miaosha_goods` ADD COLUMN `buy_limit` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '限单';");
        hj_pdo_run("CREATE TABLE `hjmall_ms_order` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL COMMENT '用户id', `order_no` VARCHAR ( 255 ) NOT NULL COMMENT '订单号', `total_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '订单总费用(包含运费）', `pay_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '实际支付总费用(含运费）', `express_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '运费', `name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '收货人姓名', `mobile` VARCHAR ( 255 ) DEFAULT NULL COMMENT '收货人手机', `address` VARCHAR ( 1000 ) DEFAULT NULL COMMENT '收货地址', `remark` VARCHAR ( 1000 ) NOT NULL DEFAULT '' COMMENT '订单备注', `is_pay` SMALLINT ( 6 ) NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付', `pay_type` SMALLINT ( 6 ) NOT NULL DEFAULT '0' COMMENT '支付方式：1=微信支付 2--货到付款', `pay_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '支付时间', `is_send` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '发货状态：0=未发货，1=已发货', `send_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '发货时间', `express` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '物流公司', `express_no` VARCHAR ( 255 ) NOT NULL DEFAULT '', `is_confirm` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '确认收货状态：0=未确认，1=已确认收货', `confirm_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '确认收货时间', `is_comment` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否已评价：0=未评价，1=已评价', `apply_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否申请取消订单：0=否，1=申请取消订单', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `is_price` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否发放佣金', `parent_id` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '用户上级ID', `first_price` DECIMAL ( 10, 2 ) NOT NULL COMMENT '一级佣金', `second_price` DECIMAL ( 10, 2 ) NOT NULL COMMENT '二级佣金', `third_price` DECIMAL ( 10, 2 ) NOT NULL COMMENT '三级佣金', `coupon_sub_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '优惠券抵消金额', `address_data` LONGTEXT COMMENT '收货地址信息，json格式', `content` LONGTEXT, `is_offline` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '是否到店自提 0--否 1--是', `clerk_id` INT ( 11 ) DEFAULT NULL COMMENT '核销员user_id', `is_cancel` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否取消', `offline_qrcode` LONGTEXT COMMENT '核销码', `before_update_price` DECIMAL ( 10, 2 ) DEFAULT NULL COMMENT '修改前的价格', `shop_id` INT ( 11 ) DEFAULT NULL COMMENT '自提门店ID', `discount` DECIMAL ( 11, 2 ) DEFAULT NULL COMMENT '会员折扣', `user_coupon_id` INT ( 11 ) DEFAULT NULL COMMENT '使用的优惠券ID', `integral` LONGTEXT CHARACTER SET utf8 COMMENT '积分使用', `give_integral` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否发放积分【1=> 已发放 ， 0=> 未发放】', `parent_id_1` INT ( 11 ) DEFAULT NULL COMMENT '用户上二级ID', `parent_id_2` INT ( 11 ) DEFAULT NULL COMMENT '用户上三级ID', `is_sale` INT ( 11 ) DEFAULT '0' COMMENT '是否超过售后时间', `words` LONGTEXT COMMENT '商家留言', `express_price_1` DECIMAL ( 10, 2 ) DEFAULT NULL COMMENT '减免的运费', `goods_id` INT ( 11 ) NOT NULL, `attr` LONGTEXT NOT NULL COMMENT '商品规格', `pic` VARCHAR ( 255 ) NOT NULL COMMENT '商品规格图片', `integral_amount` INT ( 11 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '单品积分获得', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_ms_order_refund` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL, `order_id` INT ( 11 ) NOT NULL, `order_refund_no` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '退款单号', `type` SMALLINT ( 6 ) NOT NULL DEFAULT '1' COMMENT '售后类型：1=退货退款，2=换货', `refund_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '退款金额', `desc` VARCHAR ( 500 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '退款说明', `pic_list` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '凭证图片列表：json格式', `status` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '状态：0=待商家处理，1=同意并已退款，2=已同意换货，3=已拒绝退换货', `refuse_desc` VARCHAR ( 500 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '拒绝退换货原因', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `response_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '商家处理时间', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD COLUMN `num` INT ( 11 ) NOT NULL DEFAULT 1 COMMENT '商品数量';");
        hj_pdo_run("CREATE TABLE `hjmall_ms_setting` ( `store_id` INT ( 11 ) UNSIGNED NOT NULL, `unpaid` INT ( 2 ) UNSIGNED NOT NULL DEFAULT '1' COMMENT '未付款自动取消时间', PRIMARY KEY ( `store_id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD COLUMN `limit_time` INT ( 11 ) UNSIGNED NOT NULL DEFAULT 0 COMMENT '可支付截止时间';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` ADD COLUMN `is_discount` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否支持会员折扣', ADD COLUMN `coupon` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否支持优惠劵';");
        hj_pdo_run("CREATE TABLE `hjmall_ms_order_comment` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `order_id` INT ( 11 ) NOT NULL, `goods_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL, `score` DECIMAL ( 10, 1 ) NOT NULL COMMENT '评分：1=差评，2=中评，3=好', `content` VARCHAR ( 1000 ) NOT NULL DEFAULT '' COMMENT '评价内容', `pic_list` LONGTEXT COMMENT '图片', `is_hide` SMALLINT ( 6 ) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0=不隐藏，1=隐藏', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '秒杀订单评价表';");
        hj_pdo_run("CREATE TABLE `hjmall_ms_goods_pic` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `goods_id` INT ( 11 ) NOT NULL DEFAULT '0', `pic_url` LONGTEXT NOT NULL, `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_cat` MODIFY COLUMN `pic_url` LONGTEXT NULL COMMENT '分类图片url';");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD INDEX `access_token` ( `access_token` );");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `store_id_n_user_id` ( `store_id`, `user_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD INDEX `store_id` ( `store_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_goods_pic` ADD INDEX `goods_id` ( `goods_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_upload_file` ADD INDEX `store_id` ( `store_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD INDEX `parent_id` ( `parent_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `addtime` ( `addtime` );");
        hj_pdo_run("ALTER TABLE `hjmall_home_block` ADD COLUMN `style` INT ( 11 ) NULL DEFAULT 0 COMMENT '板块样式 0--默认样式 1--样式一 2--样式二 。。。';");
        hj_pdo_run("ALTER TABLE `hjmall_banner` ADD COLUMN `open_type` VARCHAR ( 255 ) NULL;");
        hj_pdo_run("CREATE TABLE `hjmall_topic_type` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `name` VARCHAR ( 255 ) DEFAULT NULL, `sort` INT ( 11 ) DEFAULT NULL, `is_delete` SMALLINT ( 6 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_free_delivery_rules` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `price` DECIMAL ( 10, 2 ) DEFAULT NULL, `city` LONGTEXT, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_topic` ADD COLUMN `is_chosen` SMALLINT ( 6 ) NULL, ADD COLUMN `type` SMALLINT ( 6 ) NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_topic_type` ADD COLUMN `store_id` INT ( 11 ) NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` ADD COLUMN `payment` VARCHAR ( 255 ) NULL DEFAULT '' COMMENT '支付方式';");
        hj_pdo_run("ALTER TABLE `hjmall_banner` MODIFY COLUMN `page_url` LONGTEXT NULL COMMENT '页面路径';");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` ADD INDEX `store_id` ( `store_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD INDEX `cat_id` ( `cat_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_miaosha_goods` ADD INDEX `store_id` ( `store_id` ), ADD INDEX `goods_id` ( `goods_id` ), ADD INDEX `start_time` ( `start_time` ), ADD INDEX `open_date` ( `open_date` );");
        hj_pdo_run("ALTER TABLE `hjmall_cat` ADD INDEX `store_id` ( `store_id` ), ADD INDEX `parent_id` ( `parent_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_user_coupon` ADD INDEX `store_id` ( `store_id` ), ADD INDEX `user_id` ( `user_id` ), ADD INDEX `coupon_id` ( `coupon_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_goods_cat` ADD INDEX `goods_id` ( `goods_id` ), ADD INDEX `store_id` ( `store_id` ), ADD INDEX `cat_id` ( `cat_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD INDEX `store_id` ( `store_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_order_detail` ADD INDEX `order_id` ( `order_id` ), ADD INDEX `goods_id` ( `goods_id` ), ADD INDEX `addtime` ( `addtime` );");
        hj_pdo_run("ALTER TABLE `hjmall_order` DROP INDEX `store_id_n_user_id`;");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `store_id` ( `store_id` ), ADD INDEX `user_id` ( `user_id` ), ADD INDEX `order_no` ( `order_no` );");
        hj_pdo_run("ALTER TABLE `hjmall_option` ADD INDEX `store_id` ( `store_id` ), ADD INDEX `group` ( `group` ), ADD INDEX `name` ( `name` );");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD INDEX `acid` ( `acid` );");
        hj_pdo_run("ALTER TABLE `hjmall_order_comment` ADD COLUMN `reply_content` VARCHAR ( 255 ) NULL, ADD COLUMN `is_virtual` SMALLINT ( 6 ) NULL, ADD COLUMN `virtual_user` VARCHAR ( 255 ) NULL, ADD COLUMN `virtual_avatar` VARCHAR ( 255 ) NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `purchase_frame` SMALLINT ( 1 ) NULL DEFAULT 0;");
        hj_pdo_run("ALTER TABLE `hjmall_order_comment` ADD COLUMN `is_virtual` SMALLINT ( 6 ) NULL, ADD COLUMN `virtual_user` VARCHAR ( 255 ) NULL, ADD COLUMN `virtual_avatar` VARCHAR ( 255 ) NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `purchase_frame` SMALLINT ( 1 ) NULL DEFAULT 0;");
        hj_pdo_run("ALTER TABLE `hjmall_setting` ADD COLUMN `remaining_sum` TINYINT ( 1 ) NULL DEFAULT 0 COMMENT '余额提现 0=关闭 1=开启';");
        hj_pdo_run("ALTER TABLE `hjmall_cash` MODIFY COLUMN `type` SMALLINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '支付方式 0--微信支付  1--支付宝  3--银行卡  4--余额';");
        hj_pdo_run("ALTER TABLE `hjmall_cash` DROP COLUMN `bank`;");
        hj_pdo_run("ALTER TABLE `hjmall_cash` MODIFY COLUMN `type` SMALLINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '支付方式 0--微信支付  1--支付宝  2--银行卡  3--余额';");
        hj_pdo_run("ALTER TABLE `hjmall_cash` MODIFY COLUMN `status` INT ( 11 ) NOT NULL DEFAULT 0 COMMENT '申请状态 0--申请中 1--确认申请 2--已打款 3--驳回  5--余额通过';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` ADD COLUMN `individual_share` SMALLINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否单独分销设置：0=否，1=是', ADD COLUMN `share_commission_first` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '一级分销佣金比例', ADD COLUMN `share_commission_second` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '二级分销佣金比例', ADD COLUMN `share_commission_third` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '三级分销佣金比例', ADD COLUMN `share_type` INT ( 11 ) NULL DEFAULT 0 COMMENT '佣金配比 0--百分比 1--固定金额';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD COLUMN `is_sum` SMALLINT ( 1 ) NULL DEFAULT 0 COMMENT '是否计算分销 0--不计算 1--计算';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_setting` ADD COLUMN `is_share` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启分销 0--关闭 1--开启';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD COLUMN `is_price` SMALLINT ( 1 ) NULL DEFAULT 0 COMMENT '是否发放佣金';");
        hj_pdo_run("CREATE TABLE `hjmall_pt_setting` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `is_share` INT ( 11 ) DEFAULT '0' COMMENT '是否开启分销 0--关闭 1--开启', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '拼团设置';");
        hj_pdo_run("CREATE TABLE `hjmall_goods_share` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `type` INT ( 11 ) DEFAULT '0' COMMENT '商品类型 0--拼团', `goods_id` INT ( 11 ) DEFAULT NULL COMMENT '商品id', `individual_share` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否单独分销设置：0=否，1=是', `share_commission_first` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '一级分销佣金比例', `share_commission_second` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '二级分销佣金比例', `share_commission_third` DECIMAL ( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '三级分销佣金比例', `share_type` INT ( 11 ) DEFAULT '0' COMMENT '佣金配比 0--百分比 1--固定金额', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '商品分销设置；';");
        hj_pdo_run("CREATE TABLE `hjmall_order_share` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `type` INT ( 11 ) DEFAULT '0' COMMENT '类型 0--拼团 1--预约', `order_id` INT ( 11 ) DEFAULT NULL COMMENT '订单id', `parent_id_1` INT ( 11 ) DEFAULT '0' COMMENT '一级分销商id', `parent_id_2` INT ( 11 ) DEFAULT '0' COMMENT '二级分销商id', `parent_id_3` INT ( 11 ) DEFAULT '0' COMMENT '三级分销商id', `first_price` DECIMAL ( 10, 2 ) DEFAULT '0.00' COMMENT '一级分销佣金', `second_price` DECIMAL ( 10, 2 ) DEFAULT '0.00' COMMENT '二级分销佣金', `third_price` DECIMAL ( 10, 2 ) DEFAULT '0.00' COMMENT '三级分销佣金', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '订单分销数据';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `cost_price` DECIMAL ( 10, 2 ) NULL DEFAULT 0.00;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_setting` ADD COLUMN `is_share` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启分销 0--关闭 1--开启';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD COLUMN `express_price_1` DECIMAL ( 10, 2 ) NULL DEFAULT NULL COMMENT '减免的运费';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD COLUMN `payment` VARCHAR ( 255 ) NULL DEFAULT '' COMMENT '支付方式';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `is_recycle` SMALLINT ( 1 ) NULL DEFAULT 0;");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD COLUMN `contact_way` VARCHAR ( 255 ) CHARACTER SET utf8 NULL COMMENT '联系方式', ADD COLUMN `comments` VARCHAR ( 255 ) CHARACTER SET utf8 NULL COMMENT '备注';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` MODIFY COLUMN `service` VARCHAR ( 2000 ) NULL DEFAULT '' COMMENT '服务选项';");
        hj_pdo_run("ALTER TABLE `hjmall_upload_file` ADD COLUMN `group_id` INT NULL DEFAULT 0 COMMENT '分组id';");
        hj_pdo_run("CREATE TABLE `hjmall_file_group` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '名称', `is_default` INT ( 11 ) DEFAULT '0' COMMENT '是否可编辑', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '图片库分组';");
        hj_pdo_run("ALTER TABLE `hjmall_order_share` ADD COLUMN `is_delete` INT ( 11 ) NULL DEFAULT 0, ADD COLUMN `version` VARCHAR ( 255 ) NULL DEFAULT 0;");
        hj_pdo_run("ALTER TABLE `hjmall_cash` ADD COLUMN `pay_type` INT ( 11 ) NULL DEFAULT 0 COMMENT '打款方式 0--之前未统计的 1--微信自动打款 2--手动打款', ADD COLUMN `order_no` VARCHAR ( 255 ) NULL COMMENT '微信自动打款订单号';");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` ADD COLUMN `cat_id_list` VARCHAR ( 255 ) NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `member_discount` SMALLINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否参与会员折扣  0=参与   1=不参与';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD COLUMN `class_group` INT ( 11 ) NULL DEFAULT 0 COMMENT '阶级团';");
        hj_pdo_run("CREATE TABLE `hjmall_pt_goods_detail` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `goods_id` INT ( 11 ) DEFAULT NULL, `colonel` DECIMAL ( 10, 2 ) UNSIGNED DEFAULT '0.00' COMMENT '团长优惠', `group_num` INT ( 11 ) UNSIGNED DEFAULT '0' COMMENT '商品成团数', `group_time` INT ( 11 ) DEFAULT NULL COMMENT '拼团时间/小时', `attr` LONGTEXT CHARACTER SET utf8 COMMENT '规格的库存及价格', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_order_message` ADD COLUMN `order_type` INT ( 11 ) NULL DEFAULT 0 COMMENT '订单类型 0--商城订单 1--秒杀订单 2--拼团订单 3--预约订单';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_setting` ADD COLUMN `is_sms` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启短信通知', ADD COLUMN `is_print` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启订单打印', ADD COLUMN `is_mail` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启邮件通知', ADD COLUMN `is_area` TINYINT ( 1 ) NULL COMMENT '区域限制  1-开启 0-关闭';");
        hj_pdo_run("ALTER TABLE `hjmall_pt_setting` ADD COLUMN `is_sms` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启短信通知', ADD COLUMN `is_print` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启订单打印', ADD COLUMN `is_mail` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启邮件通知', ADD COLUMN `is_area` TINYINT ( 1 ) NULL DEFAULT NULL COMMENT '区域限制  0--关闭 1--开启';");
        hj_pdo_run("ALTER TABLE `hjmall_yy_setting` ADD COLUMN `is_sms` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启短信通知', ADD COLUMN `is_print` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启订单打印', ADD COLUMN `is_mail` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启邮件通知';");
        hj_pdo_run("ALTER TABLE `hjmall_setting` ADD COLUMN `rebate` DECIMAL ( 10, 2 ) NULL DEFAULT 0 COMMENT '自购返利', ADD COLUMN `is_rebate` INT ( 11 ) NULL DEFAULT 0 COMMENT '是否开启自购返利';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `rebate` DECIMAL ( 10, 2 ) NULL DEFAULT 0 COMMENT '自购返利';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` ADD COLUMN `rebate` DECIMAL ( 10, 2 ) NULL DEFAULT 0.00 COMMENT '自购返利';");
        hj_pdo_run("ALTER TABLE `hjmall_goods_share` ADD COLUMN `rebate` DECIMAL ( 10, 2 ) NULL DEFAULT 0.00 COMMENT '自购返利';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `rebate` DECIMAL ( 10, 2 ) NULL DEFAULT 0 COMMENT '自购返利', ADD COLUMN `before_update_express` DECIMAL ( 10, 2 ) NULL DEFAULT 0 COMMENT '价格修改前的运费', ADD COLUMN `seller_comments` TEXT NULL COMMENT '商家备注';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD COLUMN `rebate` DECIMAL ( 10, 2 ) NULL DEFAULT 0 COMMENT '自购返利', ADD COLUMN `before_update_express` DECIMAL ( 10, 2 ) NULL DEFAULT 0 COMMENT '价格修改前的运费';");
        hj_pdo_run("ALTER TABLE `hjmall_order_share` ADD COLUMN `rebate` DECIMAL ( 10, 2 ) NULL DEFAULT 0 COMMENT '自购返利', ADD COLUMN `user_id` INT NULL DEFAULT 0 COMMENT '下单用户id';");
        hj_pdo_run("ALTER TABLE `hjmall_user_share_money` ADD COLUMN `order_type` INT ( 11 ) NULL DEFAULT 0 COMMENT '订单类型 0--商城订单 1--秒杀订单 2--拼团订单 3--预约订单', ADD COLUMN `version` VARCHAR ( 255 ) NULL DEFAULT 0 COMMENT '版本';");
        hj_pdo_run("ALTER TABLE `hjmall_sms_setting` ADD COLUMN `tpl_code` LONGTEXT NULL COMMENT '验证码模板参数';");
        hj_pdo_run("ALTER TABLE `hjmall_share` ADD COLUMN `seller_comments` TEXT NULL COMMENT '商家备注';");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD COLUMN `binding` CHAR ( 11 ) NULL DEFAULT NULL COMMENT '授权手机号';");
        hj_pdo_run("CREATE TABLE `hjmall_territorial_limitation` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `detail` LONGTEXT NOT NULL COMMENT '规则详细', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_enable` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否启用：0=否，1=是', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `mch_id` INT NOT NULL DEFAULT 0 COMMENT '入驻商户的id', ADD COLUMN `goods_num` INT NOT NULL DEFAULT 0 COMMENT '商品总库存';");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `mch_id` INT NOT NULL DEFAULT 0 COMMENT '入驻商户id', ADD COLUMN `order_union_id` INT NOT NULL DEFAULT 0 COMMENT '合并订单的id', ADD COLUMN `is_transfer` SMALLINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否已转入商户账户：0=否，1=是';");
        hj_pdo_run("ALTER TABLE `hjmall_cart` ADD COLUMN `mch_id` INT NOT NULL DEFAULT 0 COMMENT '入驻商id';");
        hj_pdo_run("CREATE TABLE `hjmall_mch` ( `id` INT ( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL, `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` TINYINT ( 1 ) NOT NULL DEFAULT '0', `is_open` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否营业：0=否，1=是', `is_lock` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否被系统关闭：0=否，1=是', `review_status` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '审核状态：0=待审核，1=审核通过，2=审核不通过', `review_result` LONGTEXT COMMENT '审核结果', `review_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '审核时间', `realname` VARCHAR ( 255 ) NOT NULL, `tel` VARCHAR ( 255 ) NOT NULL, `name` VARCHAR ( 255 ) NOT NULL, `province_id` INT ( 11 ) NOT NULL, `city_id` INT ( 11 ) NOT NULL, `district_id` INT ( 11 ) NOT NULL, `address` VARCHAR ( 1000 ) NOT NULL, `mch_common_cat_id` INT ( 11 ) NOT NULL COMMENT '所售类目', `service_tel` VARCHAR ( 1000 ) NOT NULL COMMENT '客服电话', `logo` LONGTEXT COMMENT 'logo', `header_bg` LONGTEXT COMMENT '背景图', `transfer_rate` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '商户手续费', `account_money` DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0.00' COMMENT '商户余额', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '商户';");
        hj_pdo_run("CREATE TABLE `hjmall_mch_account_log` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `mch_id` INT ( 11 ) NOT NULL, `price` DECIMAL ( 10, 2 ) NOT NULL COMMENT '金额', `type` SMALLINT ( 6 ) NOT NULL COMMENT '类型：1=收入，2=支出', `desc` LONGTEXT, `addtime` INT ( 11 ) NOT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '入驻商户账户收支记录';");
        hj_pdo_run("CREATE TABLE `hjmall_mch_cash` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `mch_id` INT ( 11 ) NOT NULL, `money` DECIMAL ( 10, 2 ) NOT NULL, `order_no` VARCHAR ( 255 ) NOT NULL, `status` SMALLINT ( 6 ) NOT NULL DEFAULT '0' COMMENT '提现状态：0=待处理，1=已转账，2=已拒绝', `addtime` INT ( 11 ) NOT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '商户提现记录';");
        hj_pdo_run("CREATE TABLE `hjmall_mch_cat` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `parent_id` INT ( 11 ) NOT NULL DEFAULT '0', `mch_id` INT ( 11 ) NOT NULL DEFAULT '0', `name` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '分类名称', `icon` LONGTEXT COMMENT '分类图标', `sort` INT ( 11 ) NOT NULL DEFAULT '1000', `is_delete` TINYINT ( 1 ) NOT NULL DEFAULT '0', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '入驻商商品分类';");
        hj_pdo_run("CREATE TABLE `hjmall_mch_common_cat` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `name` VARCHAR ( 255 ) NOT NULL, `sort` INT ( 11 ) NOT NULL DEFAULT '1000' COMMENT '排序，升序', `is_delete` TINYINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '入驻商类目';");
        hj_pdo_run("CREATE TABLE `hjmall_mch_goods_cat` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `goods_id` INT ( 11 ) NOT NULL, `cat_id` INT ( 11 ) NOT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '入驻商商品分类关系';");
        hj_pdo_run("CREATE TABLE `hjmall_mch_postage_rules` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `mch_id` INT ( 11 ) NOT NULL, `name` VARCHAR ( 255 ) NOT NULL COMMENT '名称', `express_id` INT ( 11 ) NOT NULL COMMENT '物流公司', `detail` LONGTEXT NOT NULL COMMENT '规则详细', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_enable` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否启用：0=否，1=是', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', `express` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '快递公司', `type` SMALLINT ( 1 ) UNSIGNED NOT NULL DEFAULT '1' COMMENT '计费方式【1=>按重计费、2=>按件计费】', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '入驻商运费规则';");
        hj_pdo_run("CREATE TABLE `hjmall_mch_visit_log` ( `id` BIGINT ( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT, `user_id` INT ( 11 ) NOT NULL, `mch_id` INT ( 11 ) NOT NULL, `addtime` INT ( 11 ) NOT NULL, `visit_date` DATE NOT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '店铺浏览记录';");
        hj_pdo_run("CREATE TABLE `hjmall_order_union` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL, `user_id` INT ( 11 ) NOT NULL, `order_no` VARCHAR ( 255 ) NOT NULL COMMENT '支付单号', `order_id_list` LONGTEXT NOT NULL COMMENT '订单id列表', `price` DECIMAL ( 10, 2 ) NOT NULL COMMENT '支付金额', `is_pay` SMALLINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否已付款0=未付款，1=已付款', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', `is_delete` SMALLINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '订单合并支付';");
        hj_pdo_run("CREATE TABLE `hjmall_user_account_log` ( `id` BIGINT ( 20 ) NOT NULL AUTO_INCREMENT, `user_id` INT ( 11 ) NOT NULL, `type` SMALLINT ( 6 ) NOT NULL COMMENT '类型：1=收入，2=支出', `price` DECIMAL ( 10, 2 ) NOT NULL COMMENT '变动金额', `desc` LONGTEXT NOT NULL COMMENT '变动说明', `addtime` INT ( 11 ) NOT NULL, `order_type` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '订单类型 0--充值 1--商城订单 2--秒杀订单 3--拼团订单 4--商城订单退款 5--秒杀订单退款 6--拼团订单退款', `order_id` INT ( 11 ) DEFAULT '0' COMMENT '订单ID', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '用户账户余额变动记录';");
        hj_pdo_run("CREATE TABLE `hjmall_user_auth_login` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) NOT NULL DEFAULT '0', `user_id` INT ( 11 ) NOT NULL DEFAULT '0', `token` VARCHAR ( 225 ) NOT NULL, `is_pass` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否已确认登录：0=未扫码，1=已确认登录', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ), KEY `token` ( `token` ( 191 ) ) ) DEFAULT CHARSET = utf8 COMMENT = '用户授权登录';");
        hj_pdo_run("INSERT INTO `hjmall_district` ( `id`, `parent_id`, `citycode`, `adcode`, `name`, `lng`, `lat`, `level` ) VALUES ( '3271', '1409', '0536', '370787', '高新区', '119', '36', 'district' );");
        hj_pdo_run("ALTER TABLE `hjmall_level` ADD COLUMN `image` LONGTEXT NULL COMMENT '会员图片', ADD COLUMN `price` DECIMAL ( 10, 2 ) NULL DEFAULT 0 COMMENT '会员价格', ADD COLUMN `detail` VARCHAR ( 255 ) NULL COMMENT '会员介绍', ADD COLUMN `buy_prompt` VARCHAR ( 255 ) NULL COMMENT '购买之前提示';");
        hj_pdo_run("CREATE TABLE `hjmall_level_order` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `order_no` VARCHAR ( 255 ) DEFAULT NULL COMMENT '订单号', `user_id` INT ( 11 ) DEFAULT NULL COMMENT '用户', `pay_price` DECIMAL ( 10, 2 ) DEFAULT NULL COMMENT '支付金额', `pay_type` INT ( 11 ) DEFAULT '0' COMMENT '支付方式 1--微信支付', `is_pay` INT ( 11 ) DEFAULT '0' COMMENT '是否支付 0--未支付 1--支付', `pay_time` INT ( 11 ) DEFAULT NULL COMMENT '支付时间', `is_delete` INT ( 11 ) DEFAULT NULL, `addtime` INT ( 11 ) DEFAULT NULL, `current_level` INT ( 11 ) DEFAULT NULL, `after_level` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` ADD COLUMN `appoint_type` TINYINT ( 1 ) NULL, ADD COLUMN `goods_id_list` VARCHAR ( 255 ) NULL DEFAULT NULL;");
        hj_pdo_run("CREATE TABLE `hjmall_user_form_id` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `user_id` INT ( 11 ) NOT NULL, `form_id` VARCHAR ( 255 ) NOT NULL, `times` INT ( 11 ) NOT NULL DEFAULT '1' COMMENT '剩余使用次数', `addtime` INT ( 11 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '用户FormId记录';");
        hj_pdo_run("ALTER TABLE `hjmall_form_id` ADD INDEX `order_no` ( `order_no` );");
        hj_pdo_run("CREATE TABLE `hjmall_order_express` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `store_id` INT ( 11 ) DEFAULT NULL, `order_id` INT ( 11 ) DEFAULT NULL, `order_type` INT ( 11 ) DEFAULT NULL COMMENT '订单类型 0--商城订单 1--秒杀订单 2--拼团订单 ', `express_code` VARCHAR ( 255 ) DEFAULT NULL COMMENT '快递公司编码', `EBusinessID` VARCHAR ( 255 ) DEFAULT NULL COMMENT '快递鸟id', `order` LONGTEXT, `printTeplate` LONGTEXT, `is_delete` INT ( 11 ) DEFAULT NULL, PRIMARY KEY ( `id` ) ) DEFAULT CHARSET = utf8 COMMENT = '订单电子面单记录';");
        hj_pdo_run("ALTER TABLE `hjmall_level` MODIFY COLUMN `detail` VARCHAR ( 255 ) NULL DEFAULT '' COMMENT '会员介绍', MODIFY COLUMN `buy_prompt` VARCHAR ( 255 ) NULL DEFAULT '' COMMENT '购买之前提示';");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD INDEX `level` ( `level` );");
        hj_pdo_run("ALTER TABLE `hjmall_level` ADD INDEX `level` ( `level` );");
        hj_pdo_run("ALTER TABLE `hjmall_session` CHANGE `id` `id` VARCHAR ( 32 ) COLLATE 'utf8_general_ci' NOT NULL FIRST, CHANGE `data` `data` VARCHAR ( 10240 ) COLLATE 'utf8_general_ci' NULL AFTER `expire`, ENGINE = 'MEMORY';");
        hj_pdo_run("ALTER TABLE `hjmall_address` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_admin` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_admin_permission` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_article` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_attr` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_attr_group` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_banner` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_card` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_cart` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_cash` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_cat` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_color` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_coupon_auto_send` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_delivery` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_district` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_express` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_favorite` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_file_group` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_form` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_form_id` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_free_delivery_rules` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_fxhb_hongbao` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_fxhb_setting` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_goods` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_goods_card` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_goods_cat` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_goods_pic` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_goods_share` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_home_block` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_home_nav` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_integral_log` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_level` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_level_order` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_mail_setting` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_mch` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_mch_account_log` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_mch_cash` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_mch_cat` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_mch_common_cat` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_mch_goods_cat` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_mch_postage_rules` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_mch_visit_log` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_miaosha` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_miaosha_goods` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods_pic` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order_comment` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order_refund` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_setting` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_option` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_order` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_order_comment` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_order_detail` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_order_express` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_order_form` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_order_message` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_order_refund` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_order_share` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_order_union` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_plugin` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_postage_rules` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_printer` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_printer_setting` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_cat` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods_detail` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods_pic` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_comment` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_detail` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_refund` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_robot` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_setting` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_qrcode` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_re_order` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_recharge` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_sender` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_session` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_setting` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_share` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_shop` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_shop_pic` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_sms_record` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_sms_setting` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_store` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_territorial_limitation` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_topic` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_topic_favorite` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_topic_type` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_upload_config` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_upload_file` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_user` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_user_account_log` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_user_auth_login` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_user_card` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_user_coupon` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_user_form_id` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_user_share_money` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_video` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_we7_user_auth` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_wechat_app` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_wechat_platform` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_wechat_template_message` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_cat` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_form` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_form_id` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods_pic` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_comment` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_form` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_setting` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `user_id` ( `user_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `user_id` ( `user_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` ADD INDEX `user_id` ( `user_id` );");
        hj_pdo_run("ALTER TABLE `hjmall_order` DROP INDEX `store_id`;");
        hj_pdo_run("ALTER TABLE `hjmall_upload_file` ADD COLUMN `mch_id` INT NULL DEFAULT 0 COMMENT '商户id';");
        hj_pdo_run("ALTER TABLE `hjmall_session` ENGINE = 'InnoDB';");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `is_recommend` INT ( 10 ) NOT NULL DEFAULT 0 COMMENT '推荐商品状态 1：开启 0 ：关闭', ADD COLUMN `recommend_count` INT ( 10 ) NOT NULL DEFAULT 0 COMMENT '推荐商品数量';");
    },

    '2.3.3' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_mch` ADD COLUMN `sort` INT NOT NULL DEFAULT 1000 COMMENT '排序：升序';");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_form` ADD COLUMN `type` VARCHAR(255) NOT NULL DEFAULT '';");
        hj_pdo_run("INSERT INTO hjmall_option (`store_id`, `group`, `name`, `value`) VALUES ('0', '', 'VERSION', '版本号');");
        hj_pdo_run("ALTER TABLE `hjmall_address` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_cart` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_cash` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_favorite` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_form_id` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_fxhb_hongbao` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_integral_log` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_level_order` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch_visit_log` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order_comment` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order_refund` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_comment` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_refund` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_share` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_union` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_comment` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_refund` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_re_order` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_share` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_topic_favorite` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_account_log` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_auth_login` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_card` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_coupon` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_form_id` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_share_money` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_wechat_app` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_wechat_platform` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_form_id` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_comment` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_form` ADD INDEX `user_id` (`user_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_address` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_admin` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_admin_permission` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_article` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_attr` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_attr_group` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_banner` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_card` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_cart` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_cash` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_cat` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_color` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_coupon_auto_send` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_delivery` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_express` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_favorite` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_file_group` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_form` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_goods_card` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_goods_cat` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_goods_pic` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_home_block` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_home_nav` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_level` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_level_order` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_mail_setting` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch_cat` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch_common_cat` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch_postage_rules` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_miaosha_goods` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods_pic` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order_comment` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order_refund` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_comment` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_detail` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_express` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_form` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_message` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_refund` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_share` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_union` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_postage_rules` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_printer` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_printer_setting` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_cat` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods_pic` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_comment` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_detail` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_refund` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_robot` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_qrcode` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_re_order` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_recharge` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_sender` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_share` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_shop` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_shop_pic` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_sms_setting` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_territorial_limitation` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_topic` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_topic_favorite` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_topic_type` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_upload_config` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_upload_file` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_card` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_coupon` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_share_money` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_video` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_wechat_app` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_wechat_platform` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_cat` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_form` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods_pic` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_comment` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_form` ADD INDEX `is_delete` (`is_delete`);");
        hj_pdo_run("ALTER TABLE `hjmall_address` ADD INDEX `is_default` (`is_default`);");
        hj_pdo_run("ALTER TABLE `hjmall_attr` ADD INDEX `is_default` (`is_default`);");
        hj_pdo_run("ALTER TABLE `hjmall_cat` ADD INDEX `is_show` (`is_show`);");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` ADD INDEX `is_join` (`is_join`);");
        hj_pdo_run("ALTER TABLE `hjmall_file_group` ADD INDEX `is_default` (`is_default`);");
        hj_pdo_run("ALTER TABLE `hjmall_fxhb_hongbao` ADD INDEX `is_expire` (`is_expire`);");
        hj_pdo_run("ALTER TABLE `hjmall_fxhb_hongbao` ADD INDEX `is_finish` (`is_finish`);");
        hj_pdo_run("ALTER TABLE `hjmall_level_order` ADD INDEX `is_pay` (`is_pay`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch` ADD INDEX `is_open` (`is_open`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch` ADD INDEX `is_lock` (`is_lock`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch_postage_rules` ADD INDEX `is_enable` (`is_enable`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` ADD INDEX `is_discount` (`is_discount`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_pay` (`is_pay`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_send` (`is_send`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_confirm` (`is_confirm`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_comment` (`is_comment`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_price` (`is_price`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_offline` (`is_offline`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_cancel` (`is_cancel`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_sale` (`is_sale`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `is_sum` (`is_sum`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order_comment` ADD INDEX `is_hide` (`is_hide`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_setting` ADD INDEX `is_share` (`is_share`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_setting` ADD INDEX `is_sms` (`is_sms`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_setting` ADD INDEX `is_print` (`is_print`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_setting` ADD INDEX `is_mail` (`is_mail`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_setting` ADD INDEX `is_area` (`is_area`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_pay` (`is_pay`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_send` (`is_send`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_confirm` (`is_confirm`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_comment` (`is_comment`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_price` (`is_price`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_offline` (`is_offline`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_cancel` (`is_cancel`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_sale` (`is_sale`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_recycle` (`is_recycle`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `is_transfer` (`is_transfer`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_comment` ADD INDEX `is_hide` (`is_hide`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_comment` ADD INDEX `is_virtual` (`is_virtual`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_message` ADD INDEX `is_read` (`is_read`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_message` ADD INDEX `is_sound` (`is_sound`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_union` ADD INDEX `is_pay` (`is_pay`);");
        hj_pdo_run("ALTER TABLE `hjmall_postage_rules` ADD INDEX `is_enable` (`is_enable`);");
        hj_pdo_run("ALTER TABLE `hjmall_printer_setting` ADD INDEX `is_attr` (`is_attr`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD INDEX `is_hot` (`is_hot`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD INDEX `is_only` (`is_only`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD INDEX `is_more` (`is_more`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_pay` (`is_pay`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_send` (`is_send`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_confirm` (`is_confirm`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_comment` (`is_comment`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_group` (`is_group`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_success` (`is_success`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_returnd` (`is_returnd`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_cancel` (`is_cancel`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `is_price` (`is_price`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_comment` ADD INDEX `is_hide` (`is_hide`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_setting` ADD INDEX `is_share` (`is_share`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_setting` ADD INDEX `is_sms` (`is_sms`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_setting` ADD INDEX `is_print` (`is_print`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_setting` ADD INDEX `is_mail` (`is_mail`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_setting` ADD INDEX `is_area` (`is_area`);");
        hj_pdo_run("ALTER TABLE `hjmall_re_order` ADD INDEX `is_pay` (`is_pay`);");
        hj_pdo_run("ALTER TABLE `hjmall_setting` ADD INDEX `is_rebate` (`is_rebate`);");
        hj_pdo_run("ALTER TABLE `hjmall_shop` ADD INDEX `is_default` (`is_default`);");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD INDEX `is_offline` (`is_offline`);");
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD INDEX `is_coupon` (`is_coupon`);");
        hj_pdo_run("ALTER TABLE `hjmall_territorial_limitation` ADD INDEX `is_enable` (`is_enable`);");
        hj_pdo_run("ALTER TABLE `hjmall_topic` ADD INDEX `is_chosen` (`is_chosen`);");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD INDEX `is_distributor` (`is_distributor`);");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD INDEX `is_clerk` (`is_clerk`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_auth_login` ADD INDEX `is_pass` (`is_pass`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_card` ADD INDEX `is_use` (`is_use`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_coupon` ADD INDEX `is_expire` (`is_expire`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_coupon` ADD INDEX `is_use` (`is_use`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` ADD INDEX `is_pay` (`is_pay`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` ADD INDEX `is_use` (`is_use`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` ADD INDEX `is_comment` (`is_comment`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` ADD INDEX `is_cancel` (`is_cancel`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` ADD INDEX `is_refund` (`is_refund`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_comment` ADD INDEX `is_hide` (`is_hide`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_setting` ADD INDEX `is_share` (`is_share`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_setting` ADD INDEX `is_sms` (`is_sms`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_setting` ADD INDEX `is_print` (`is_print`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_setting` ADD INDEX `is_mail` (`is_mail`);");
        hj_pdo_run("ALTER TABLE `hjmall_address` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_article` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_attr_group` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_banner` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_card` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_cart` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_cash` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_cat` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_coupon_auto_send` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_delivery` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_favorite` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_file_group` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_form` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_form_id` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_free_delivery_rules` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_fxhb_hongbao` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_fxhb_setting` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_goods_cat` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_goods_share` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_home_block` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_home_nav` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_integral_log` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_level` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_level_order` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_mail_setting` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch_account_log` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch_cash` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_mch_common_cat` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_miaosha` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_miaosha_goods` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order_comment` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order_refund` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_setting` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_option` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_comment` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_express` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_form` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_message` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_refund` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_share` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_order_union` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_postage_rules` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_printer` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_printer_setting` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_cat` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods_detail` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_comment` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order_refund` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_robot` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_setting` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_qrcode` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_re_order` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_recharge` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_sender` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_setting` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_share` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_shop` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_shop_pic` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_sms_setting` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_territorial_limitation` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_topic` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_topic_favorite` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_topic_type` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_upload_config` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_upload_file` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_auth_login` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_card` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_coupon` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_user_share_money` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_video` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_wechat_template_message` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_cat` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_form` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_form_id` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_comment` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order_form` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_setting` ADD INDEX `store_id` (`store_id`);");
        hj_pdo_run("ALTER TABLE `hjmall_banner` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_banner` CHANGE `page_url` `page_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_card` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_cat` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_cat` CHANGE `big_pic_url` `big_pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_cat` CHANGE `advert_url` `advert_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_goods` CHANGE `video_url` `video_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_goods_pic` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_home_nav` CHANGE `url` `url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_home_nav` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods` CHANGE `video_url` `video_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_ms_goods_pic` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_cat` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods_pic` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_shop` CHANGE `cover_url` `cover_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_shop` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_shop_pic` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_store` CHANGE `copyright_pic_url` `copyright_pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_store` CHANGE `copyright_url` `copyright_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_upload_file` CHANGE `file_url` `file_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_user` CHANGE `avatar_url` `avatar_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_user_card` CHANGE `card_pic_url` `card_pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_video` CHANGE `url` `url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_video` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_cat` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods_pic` CHANGE `pic_url` `pic_url` VARCHAR(2048);");
    },

    '2.4.0' => function () {
        hj_pdo_run("CREATE TABLE `hjmall_integral_cat` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `is_delete` int(11) NOT NULL, `addtime` int(11) NOT NULL, `name` varchar(50) NOT NULL COMMENT '商品名称', `pic_url` longtext NOT NULL COMMENT '分类图片url', `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序，升序', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_integral_setting` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `store_id` INT(11) NOT NULL, `integral_shuoming` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '积分说明', `register_rule` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '签到规则', `register_integral` INT(11) NOT NULL COMMENT '每日签到获得分数', `register_continuation` INT(11) NOT NULL COMMENT '连续签到天数', `register_reward` VARCHAR(255) CHARACTER SET utf8 NOT NULL COMMENT '连续签到奖励', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_in_order_comment` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `store_id` INT(11) NOT NULL, `order_id` INT(11) NOT NULL, `order_detail_id` INT(11) NOT NULL, `goods_id` INT(11) NOT NULL, `user_id` INT(11) NOT NULL, `score` DECIMAL(10,1) NOT NULL COMMENT '评分：1=差评，2=中评，3=好', `content` VARCHAR(1000) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '评价内容', `pic_list` LONGTEXT CHARACTER SET utf8 COMMENT '图片', `is_hide` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0=不隐藏，1=隐藏', `is_delete` SMALLINT(1) NOT NULL DEFAULT '0', `addtime` INT(11) NOT NULL DEFAULT '0', `reply_content` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '0', `is_virtual` SMALLINT(6) NOT NULL DEFAULT '0', `virtual_user` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '0', `virtual_avatar` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_integral_coupon_order` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `store_id` INT(11) NOT NULL, `user_id` INT(11) NOT NULL COMMENT '用户id', `coupon_id` INT(11) NOT NULL COMMENT '优惠券ID', `order_no` VARCHAR(255) CHARACTER SET utf8 NOT NULL COMMENT '订单号', `is_pay` INT(11) NOT NULL COMMENT '是否支付  0-未支付   1-支付 纯积分兑换', `pay_time` INT(11) NOT NULL DEFAULT '0' COMMENT '支付时间', `price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额', `integral` INT(11) NOT NULL DEFAULT '0' COMMENT '积分数量', `addtime` INT(11) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_integral_goods` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `store_id` INT(11) NOT NULL, `name` VARCHAR(255) CHARACTER SET utf8 NOT NULL COMMENT '商品名称', `price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价', `original_price` DECIMAL(10,2) NOT NULL COMMENT '原价（只做显示用）', `detail` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '商品详情，图文', `cat_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品类别', `status` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '上架状态：0=下架，1=上架', `addtime` INT(11) NOT NULL DEFAULT '0', `is_delete` SMALLINT(1) NOT NULL DEFAULT '0', `attr` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '规格的库存及价格', `sort` INT(11) NOT NULL DEFAULT '100' COMMENT '排序  升序', `virtual_sales` INT(11) NOT NULL DEFAULT '0' COMMENT '虚拟销量', `cover_pic` LONGTEXT CHARACTER SET utf8 COMMENT '商品缩略图', `unit` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '件' COMMENT '单位', `weight` DOUBLE(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '重量', `freight` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '运费模板ID', `use_attr` SMALLINT(1) NOT NULL DEFAULT '1' COMMENT '是否使用规格：0=不使用，1=使用', `goods_num` INT(11) NOT NULL DEFAULT '0' COMMENT '商品总库存', `integral` INT(11) NOT NULL COMMENT '所需积分', `service` VARCHAR(2000) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '商品服务选项', `cost_price` DECIMAL(10,2) NOT NULL DEFAULT '0.00', `goods_pic_list` LONGTEXT CHARACTER SET utf8, `is_index` SMALLINT(1) NOT NULL DEFAULT '0' COMMENT '放置首页：0=不放，1=放', `user_num` INT(11) NOT NULL COMMENT '每人限制兑换数量', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_integral_order` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `store_id` INT(11) NOT NULL, `user_id` INT(11) NOT NULL COMMENT '用户id', `order_no` VARCHAR(255) CHARACTER SET utf8 NOT NULL COMMENT '订单号', `total_price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总费用(包含运费）', `pay_price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付总费用(含运费）', `express_price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费', `name` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '收货人姓名', `mobile` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '收货人手机', `address` VARCHAR(1000) CHARACTER SET utf8 NOT NULL COMMENT '收货地址', `remark` VARCHAR(1000) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '订单备注', `is_pay` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付', `pay_type` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '支付方式：0--在线支付未支付 1=支付 ', `pay_time` INT(11) NOT NULL DEFAULT '0' COMMENT '支付时间', `is_send` SMALLINT(1) NOT NULL DEFAULT '0' COMMENT '发货状态：0=未发货，1=已发货', `send_time` INT(11) NOT NULL DEFAULT '0' COMMENT '发货时间', `express` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '物流公司', `express_no` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '', `is_confirm` SMALLINT(1) NOT NULL DEFAULT '0' COMMENT '确认收货状态：0=未确认，1=已确认收货', `confirm_time` INT(11) NOT NULL DEFAULT '0' COMMENT '确认收货时间', `is_comment` SMALLINT(1) NOT NULL DEFAULT '0' COMMENT '是否已评价：0=未评价，1=已评价', `apply_delete` SMALLINT(1) NOT NULL DEFAULT '0' COMMENT '是否申请取消订单：0=否，1=申请取消订单', `addtime` INT(11) NOT NULL DEFAULT '0', `is_delete` SMALLINT(1) NOT NULL DEFAULT '0', `address_data` LONGTEXT CHARACTER SET utf8 COMMENT '收货地址信息，json格式', `is_offline` INT(11) NOT NULL DEFAULT '0' COMMENT '是否到店自提 1--否 2--是', `clerk_id` INT(11) NOT NULL DEFAULT '-1' COMMENT '核销员user_id', `is_cancel` SMALLINT(1) NOT NULL DEFAULT '0' COMMENT '是否取消', `offline_qrcode` LONGTEXT CHARACTER SET utf8 COMMENT '核销码', `shop_id` INT(11) NOT NULL DEFAULT '-1' COMMENT '自提门店ID', `is_sale` INT(11) NOT NULL DEFAULT '0' COMMENT '是否超过售后时间', `version` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '版本', `mch_id` INT(11) NOT NULL DEFAULT '0' COMMENT '入驻商户id', `integral` INT(11) NOT NULL COMMENT '花费的积分', `goods_id` INT(11) NOT NULL, `words` LONGTEXT CHARACTER SET utf8 COMMENT '商家留言', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_integral_order_detail` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `order_id` INT(11) NOT NULL, `goods_id` INT(11) NOT NULL, `num` INT(11) NOT NULL DEFAULT '1' COMMENT '商品数量', `total_price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '此商品的总价', `addtime` INT(11) NOT NULL DEFAULT '0', `is_delete` SMALLINT(1) NOT NULL DEFAULT '0', `attr` LONGTEXT CHARACTER SET utf8 NOT NULL COMMENT '商品规格', `pic` VARCHAR(255) CHARACTER SET utf8 NOT NULL COMMENT '商品规格图片', `pay_integral` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '积分花费', `goods_name` VARCHAR(255) CHARACTER SET utf8 NOT NULL, `user_id` INT(11) NOT NULL COMMENT '用户id', `store_id` INT(11) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_coupon` ADD COLUMN `is_integral` SMALLINT(6) NOT NULL DEFAULT 1 COMMENT '是否加入积分商城 1--不加入 2--加入' AFTER `appoint_type`, ADD COLUMN `integral` INT(11) NOT NULL DEFAULT 0 COMMENT '兑换需要积分数量' AFTER `is_integral`, ADD COLUMN `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT '售价' AFTER `integral`, ADD COLUMN `total_num` INT(11) NOT NULL DEFAULT 0 COMMENT '积分商城发放总数' AFTER `price`, ADD COLUMN `user_num` INT(11) NOT NULL DEFAULT 0 COMMENT '每人限制兑换数量' AFTER `total_num`;");
        hj_pdo_run("CREATE TABLE `hjmall_register` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `store_id` INT(11) NOT NULL, `user_id` INT(11) NOT NULL, `register_time` VARCHAR(25) CHARACTER SET utf8 NOT NULL, `addtime` INT(11) NOT NULL DEFAULT '0', `continuation` INT(11) NOT NULL COMMENT '连续签到天数', `integral` INT(11) NOT NULL COMMENT '获得积分', `type` TINYINT(1) NOT NULL DEFAULT '2' COMMENT '1--签到', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_register` ADD COLUMN `order_id` INT(11) NOT NULL DEFAULT '-1' AFTER `type`;");
        hj_pdo_run("ALTER TABLE `hjmall_user_coupon` ADD COLUMN `integral` INT(11) NOT NULL DEFAULT 0 COMMENT '兑换支付积分数量' AFTER `type`, ADD COLUMN `price` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '兑换支付价格' AFTER `integral`;");
        hj_pdo_run("ALTER TABLE `hjmall_integral_order` MODIFY COLUMN `address` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 0 COMMENT '收货地址' AFTER `mobile`;");
    },

    '2.4.0.1' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_order_form` ADD COLUMN `type`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '表单信息类型';");
    },

    '2.4.1' => function () {
        hj_pdo_run("UPDATE `hjmall_goods` SET sort = 1000 WHERE sort IS NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_goods` MODIFY COLUMN `sort`  int(11) NOT NULL DEFAULT 1000 COMMENT '排序  升序', MODIFY COLUMN `virtual_sales`  int(11) NOT NULL DEFAULT 0 COMMENT '虚拟销量';");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `mch_sort`  int NOT NULL DEFAULT 1000 COMMENT '多商户自己的排序';");
        hj_pdo_run("UPDATE `hjmall_goods` SET mch_sort = sort, sort = 1100 WHERE mch_id != 0 AND mch_sort = 1000 AND sort != 1000 AND sort IS NOT NULL;");
        hj_pdo_run("ALTER TABLE `hjmall_integral_log` ADD COLUMN `type`  int(11) NOT NULL DEFAULT 0 COMMENT '数据类型 0--积分修改 1--余额修改';");
    },

    '2.4.7' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `is_recycle`  smallint(1) NOT NULL DEFAULT 0 COMMENT '回收站：0=否，1=是' AFTER `is_delete`;");
        hj_pdo_run("ALTER TABLE `hjmall_admin` ADD COLUMN `mobile`  varchar(255) NOT NULL DEFAULT '' COMMENT '手机号' AFTER `expire_time`;");
    },

    '2.5.0' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_order_refund` ADD COLUMN `is_agree` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否已同意退、换货：0=待处理，1=已同意，2=已拒绝' AFTER `response_time`, ADD COLUMN `is_user_send` smallint(1) NOT NULL DEFAULT 0 COMMENT '用户已发货：0=未发货，1=已发货' AFTER `is_agree`, ADD COLUMN `user_send_express` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递公司' AFTER `is_user_send`, ADD COLUMN `user_send_express_no` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递单号' AFTER `user_send_express`;");
    },

    '2.5.2' => function () {
        hj_pdo_run("CREATE TABLE `hjmall_auth_role` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `name` varchar(64) NOT NULL, `description` text, `created_at` int(11) DEFAULT NULL, `updated_at` int(11) DEFAULT NULL, PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_auth_role_permission` ( `id` int(11) NOT NULL AUTO_INCREMENT, `role_id` int(11) NOT NULL, `permission_name` varchar(64) NOT NULL, PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_auth_role_user` ( `role_id` int(11) NOT NULL, `user_id` int(11) NOT NULL ) DEFAULT CHARSET=utf8;");
    },

    '2.5.5' => function () {
        hj_pdo_run('ALTER TABLE `hjmall_integral_log` ADD COLUMN `pic_url` varchar(255) NOT NULL DEFAULT \'\', ADD COLUMN `explain` varchar(255) NOT NULL DEFAULT \'\';ALTER TABLE `hjmall_integral_log` ADD COLUMN `pic_url` varchar(255) NOT NULL DEFAULT \'\', ADD COLUMN `explain` varchar(255) NOT NULL DEFAULT \'\';');
        hj_pdo_run(' ALTER TABLE `hjmall_mch_cash` ADD COLUMN `type` smallint(6) NOT NULL DEFAULT 0 COMMENT \'提现类型 0--微信自动打款 1--微信线下打款 2--支付宝线下打款 3--银行卡线下打款 4--充值到余额\', ADD COLUMN `type_data` varchar(255) NOT NULL DEFAULT \'\' COMMENT \'不同提现类型，提交的数据\', ADD COLUMN `virtual_type` smallint(6) NOT NULL DEFAULT 0 COMMENT \'实际上打款方式\';ALTER TABLE `hjmall_mch_cash` ADD COLUMN `type` smallint(6) NOT NULL DEFAULT 0 COMMENT \'提现类型 0--微信自动打款 1--微信线下打款 2--支付宝线下打款 3--银行卡线下打款 4--充值到余额\', ADD COLUMN `type_data` varchar(255) NOT NULL DEFAULT \'\' COMMENT \'不同提现类型，提交的数据\', ADD COLUMN `virtual_type` smallint(6) NOT NULL DEFAULT 0 COMMENT \'实际上打款方式\';');
    },

    '2.5.8' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_store` add status tinyint(4) NOT NULL DEFAULT '0' COMMENT '禁用状态：0.未禁用|1.禁用';");
        hj_pdo_run(" ALTER TABLE `hjmall_user_card` ADD COLUMN `order_id` int(11) NOT NULL DEFAULT 0 COMMENT '发放卡券的订单id', ADD COLUMN `goods_id` int(11) NOT NULL DEFAULT 0 COMMENT '商品ID';");
        hj_pdo_run(" ALTER TABLE `hjmall_user` ADD COLUMN `wechat_platform_open_id` varchar(64) NOT NULL DEFAULT '' COMMENT '微信公众号openid' AFTER `binding`;");
        hj_pdo_run(" CREATE TABLE `hjmall_action_log` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `title` varchar(45) NOT NULL DEFAULT '' COMMENT '操作记录描述', `addtime` int(11) NOT NULL COMMENT '记录时间', `admin_name` varchar(45) NOT NULL DEFAULT '' COMMENT '操作人姓名', `admin_id` int(11) NOT NULL COMMENT '操作人ID', `admin_ip` varchar(255) DEFAULT NULL COMMENT '操作人IP地址', `route` varchar(255) NOT NULL DEFAULT '' COMMENT '操作路由', `action_type` varchar(45) NOT NULL DEFAULT '' COMMENT '操作类型', `obj_id` int(11) NOT NULL COMMENT '操作数据ID', `result` text COMMENT '操作结果', `store_id` int(11) NOT NULL, `is_delete` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run(" CREATE TABLE `hjmall_pond` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `type` int(11) NOT NULL DEFAULT '0' COMMENT '1.红包2.优惠卷3.积分4.实物.5.无', `num` int(11) DEFAULT '0' COMMENT '积分数量', `price` decimal(10,2) DEFAULT '0.00' COMMENT '红包价格', `image_url` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '图片', `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存', `orderby` int(11) DEFAULT '0' COMMENT '排序', `coupon_id` int(11) DEFAULT '0' COMMENT '优惠卷', `gift_id` int(11) DEFAULT '0' COMMENT '赠品', `create_time` int(11) NOT NULL DEFAULT '0', `update_time` int(11) NOT NULL DEFAULT '0', `attr` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '规格', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run(" CREATE TABLE `hjmall_pond_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `pond_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0', `status` int(11) NOT NULL DEFAULT '0' COMMENT ' 0未领取1 已领取', `create_time` int(11) NOT NULL DEFAULT '0', `raffle_time` int(11) DEFAULT '0' COMMENT '领取时间', `type` int(11) NOT NULL DEFAULT '0', `num` int(11) DEFAULT '0', `gift_id` int(11) DEFAULT '0', `coupon_id` int(11) DEFAULT '0', `price` int(11) DEFAULT '0', `attr` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '规格', `order_id` int(11) DEFAULT '0', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run(" CREATE TABLE `hjmall_pond_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `probability` int(11) NOT NULL DEFAULT '0' COMMENT '概率', `oppty` int(11) NOT NULL DEFAULT '0' COMMENT '抽奖次数', `type` int(11) NOT NULL DEFAULT '0' COMMENT '1.天 2 用户', `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间', `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run(" ALTER TABLE `hjmall_order` ADD COLUMN `type` int(11) NULL DEFAULT 0 COMMENT '0:普通订单 1大转盘订单' AFTER `is_transfer`;");
        hj_pdo_run(" CREATE TABLE `hjmall_admin_register` ( `id` int(11) NOT NULL AUTO_INCREMENT, `username` varchar(32) NOT NULL COMMENT '帐户名', `password` varchar(255) NOT NULL COMMENT '密码', `mobile` varchar(15) NOT NULL COMMENT '手机号', `name` varchar(255) NOT NULL COMMENT '姓名/企业名', `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '申请原因', `addtime` int(11) NOT NULL, `status` smallint(1) NOT NULL DEFAULT 0 COMMENT '审核状态：0=待审核，1=通过，2=不通过', `is_delete` smallint(1) NOT NULL DEFAULT 0, PRIMARY KEY (`id`) USING BTREE ) DEFAULT CHARSET=utf8;");
    },

    '2.6.0' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_pond_log` MODIFY COLUMN `price`  decimal(10,2) NULL DEFAULT 0 AFTER `coupon_id`, MODIFY COLUMN `attr`  varchar(255) NULL DEFAULT '' AFTER `price`;");
    },

    '2.6.2' => function () {
    },

    '2.6.3' => function () {
    },

    '2.6.5' => function () {
    },

    '2.6.6' => function () {
    },

    '2.6.7' => function () {
    },

    '2.6.8' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_pond_setting` ADD COLUMN `title`  varchar(255) NULL DEFAULT '' COMMENT '小程序标题' AFTER `end_time`, ADD COLUMN `rule`  varchar(1000) NULL DEFAULT '' COMMENT '规则' AFTER `title`;");
        hj_pdo_run("ALTER TABLE `hjmall_mch` ADD COLUMN `wechat_name`  varchar(255) NULL DEFAULT '' COMMENT '微信号' AFTER `sort`;");
        hj_pdo_run("CREATE TABLE `hjmall_scratch` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `type` int(11) NOT NULL DEFAULT '0' COMMENT '1.红包2.优惠卷3.积分4.实物', `num` int(11) DEFAULT '0' COMMENT '积分数量', `price` decimal(10,2) DEFAULT '0.00' COMMENT '红包价格', `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存', `coupon_id` int(11) DEFAULT '0' COMMENT '优惠卷', `gift_id` int(11) DEFAULT '0' COMMENT '赠品', `create_time` int(11) NOT NULL DEFAULT '0', `update_time` int(11) NOT NULL DEFAULT '0', `attr` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '规格', `status` int(11) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启', `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '1删除', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_scratch_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `pond_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0', `status` int(11) NOT NULL DEFAULT '0' COMMENT ' 0预领取 1 未领取 2 已领取', `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', `raffle_time` int(11) DEFAULT '0' COMMENT '领取时间', `type` int(11) NOT NULL DEFAULT '0', `num` int(11) DEFAULT '0', `gift_id` int(11) DEFAULT '0', `coupon_id` int(11) DEFAULT '0', `price` decimal(10,2) DEFAULT '0.00', `attr` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '规格', `order_id` int(11) DEFAULT '0' COMMENT '订单号', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_scratch_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `probability` int(11) NOT NULL DEFAULT '0' COMMENT '概率', `oppty` int(11) NOT NULL DEFAULT '0' COMMENT '抽奖次数', `type` int(11) NOT NULL DEFAULT '0' COMMENT '1.天 2 用户', `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间', `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间', `title` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '小程序标题', `rule` varchar(1000) CHARACTER SET utf8 DEFAULT '' COMMENT '规则说明', `deplete_register` int(11) DEFAULT '0' COMMENT '消耗积分', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
    },

    '2.6.9' => function () {
    },

    '2.7.0' => function () {
        hj_pdo_run("CREATE TABLE `hjmall_user_log`( `id` INT(11) NOT NULL AUTO_INCREMENT, `store_id` INT(11) NOT NULL DEFAULT '0', `user_id` INT(11) NOT NULL DEFAULT '0', `type` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '改变的字段', `before_change` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '改变前字段的值', `after_change` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '改变后字段的值', `is_delete` SMALLINT(6) NOT NULL DEFAULT '0', `addtime` INT(11) NOT NULL DEFAULT '0', PRIMARY KEY(`id`) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD `confine_count` INT(11) NOT NULL DEFAULT '0' COMMENT '购买限制:0.不限制|大于0等于限购数量';");
        hj_pdo_run("ALTER TABLE `hjmall_mch` ADD `is_recommend` INT(11) NOT NULL DEFAULT '1' COMMENT '好店推荐：0.不推荐|1.推荐';");
        hj_pdo_run("CREATE TABLE `hjmall_wx_title`( `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, `url` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '小程序页面路径', `store_id` INT(11) DEFAULT NULL COMMENT '商城ID', `title` VARCHAR(45) NOT NULL DEFAULT '默认标题' COMMENT '小程序页面标题', `addtime` VARCHAR(255) NOT NULL DEFAULT '', PRIMARY KEY(`id`) ) DEFAULT CHARSET = utf8;");
        hj_pdo_run("alter table `hjmall_auth_role` add `creator_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建者ID';");
    },

    '2.7.1' => function () {
    },

    '2.7.2' => function () {
    },

    '2.7.3' => function () {
    },

    '2.7.4' => function () {
    },

    '2.7.5' => function () {
    },

    '2.7.6' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_pond_setting` ADD COLUMN `deplete_register` int(11) NULL DEFAULT 0 COMMENT '消耗积分' AFTER `rule`;");
        hj_pdo_run(" ALTER TABLE `hjmall_pt_order_comment` ADD COLUMN `is_virtual` smallint(6) NULL DEFAULT 0 AFTER `addtime`, ADD COLUMN `virtual_user` varchar(255) NULL DEFAULT '' AFTER `is_virtual`, ADD COLUMN `virtual_avatar` varchar(255) NULL DEFAULT '' AFTER `virtual_user`;");
        hj_pdo_run(" ALTER TABLE `hjmall_order_refund` ADD COLUMN `address_id` int(11) NULL DEFAULT 0 COMMENT '退货 换货地址id' AFTER `user_send_express_no`;");
        hj_pdo_run(" ALTER TABLE `hjmall_yy_order` ADD COLUMN `refund_time` int(11) NULL DEFAULT 0 COMMENT '取消时间' AFTER `form_id`;");
        hj_pdo_run(" CREATE TABLE `hjmall_refund_address` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '收件人名称', `address` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '收件人地址', `mobile` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '收件人电话', `is_delete` smallint(6) DEFAULT '0', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run(" ALTER TABLE `hjmall_order` ADD COLUMN `share_price` decimal(11,2) NULL DEFAULT 0 COMMENT '发放佣金的金额', ADD COLUMN `is_show` smallint(1) NOT NULL DEFAULT 1 COMMENT '是否显示 0--不显示 1--显示';");
        hj_pdo_run(" ALTER TABLE `hjmall_ms_order` ADD COLUMN `is_recycle` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否在回收站 0--不在回收站 1--在回收站', ADD COLUMN `is_show` smallint(1) NOT NULL DEFAULT 1 COMMENT '是否显示 0--不显示 1--显示（软删除用）';");
        hj_pdo_run(" ALTER TABLE `hjmall_pt_order` ADD COLUMN `is_recycle` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否加入回收站 0--不加入 1--加入', ADD COLUMN `is_show` smallint(1) NOT NULL DEFAULT 1 COMMENT '是否显示 0--不显示 1--显示（软删除）';");
        hj_pdo_run(" ALTER TABLE `hjmall_yy_order` ADD COLUMN `is_recycle` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否加入回收站 0--不加入 1--加入', ADD COLUMN `is_show` smallint(1) NOT NULL DEFAULT 1 COMMENT '是否显示 0--不显示 1--显示（软删除）';");
        hj_pdo_run(" ALTER TABLE `hjmall_integral_order` ADD COLUMN `is_recycle` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否加入回收站 0--不加入 1--加入', ADD COLUMN `is_show` smallint(1) NOT NULL DEFAULT 1 COMMENT '是否显示 0--不显示 1--显示（软删除）';");
        hj_pdo_run(" CREATE TABLE `hjmall_mch_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mch_id` int(11) NOT NULL DEFAULT '0', `store_id` int(11) NOT NULL DEFAULT '0', `is_share` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否开启分销 0--不开启 1--开启', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 COMMENT='商户设置';");
        hj_pdo_run(" CREATE TABLE `hjmall_mch_plugin` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mch_id` int(11) NOT NULL DEFAULT '0', `store_id` int(11) NOT NULL DEFAULT '0', `is_share` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否开启分销 0--不开启 1--开启', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 COMMENT='商户权限表';");
    },

    '2.7.7' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_goods` ADD COLUMN `is_level` smallint(1) NOT NULL DEFAULT 1 COMMENT '是否享受会员折扣 0-不享受 1--享受', ADD COLUMN `type` smallint(1) NOT NULL DEFAULT 0 COMMENT '类型 0--商城或多商户 2--砍价商品';");
        hj_pdo_run(" CREATE TABLE `hjmall_bargain_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0', `order_no` varchar(255) NOT NULL DEFAULT '0', `goods_id` int(11) NOT NULL DEFAULT '0', `original_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品售价', `min_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品最低价', `time` int(11) NOT NULL DEFAULT '0' COMMENT '砍价时间', `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态 0--进行中 1--成功 2--失败', `is_delete` int(11) NOT NULL DEFAULT '0', `addtime` int(11) NOT NULL DEFAULT '0', `attr` varchar(255) NOT NULL DEFAULT '', `status_data` varchar(255) NOT NULL DEFAULT '' COMMENT '砍价方式数据', PRIMARY KEY (`id`), KEY `store_id` (`store_id`) USING BTREE, KEY `user_id` (`user_id`) USING BTREE ) DEFAULT CHARSET=utf8 COMMENT='发起砍价订单表';");
        hj_pdo_run(" CREATE TABLE `hjmall_bargain_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `is_print` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否打印 0--否 1--是', `is_share` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否参与分销 0--不参与 1--参与', `is_sms` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否发送短信 0--否 1--是', `is_mail` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否发送邮件 0--否 1--是', `content` varchar(255) NOT NULL DEFAULT '' COMMENT '活动规则', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run(" CREATE TABLE `hjmall_bargain_user_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `order_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0', `price` decimal(10,2) NOT NULL DEFAULT '0.00', `is_delete` int(11) NOT NULL DEFAULT '0', `addtime` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), KEY `store_id` (`store_id`) USING BTREE, KEY `user_id` (`user_id`) USING BTREE, KEY `order_id` (`order_id`) USING BTREE ) DEFAULT CHARSET=utf8 COMMENT='砍价插件用户砍价情况；';");
        hj_pdo_run(" CREATE TABLE `hjmall_bargain_goods` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `goods_id` int(11) NOT NULL DEFAULT '0', `min_price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '最低价', `begin_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间', `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间', `time` int(11) NOT NULL DEFAULT '0' COMMENT '砍价小时数', `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '砍价方式 0--按人数 1--按价格', `status_data` varchar(255) NOT NULL DEFAULT '' COMMENT '砍价方式数据', `is_delete` smallint(6) NOT NULL DEFAULT '0', `addtime` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), KEY `goods_id` (`goods_id`) USING BTREE, KEY `store_id` (`store_id`) USING BTREE ) DEFAULT CHARSET=utf8;");
        hj_pdo_run(" ALTER TABLE `hjmall_order_detail` ADD COLUMN `is_level` smallint(6) NOT NULL DEFAULT 1 COMMENT '是否使用会员折扣 0--不适用 1--使用';");
    },

    '2.7.8' => function () {
    },

    '2.7.9' => function () {
    },

    '2.8.0' => function () {
    },

    '2.8.1' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_user` ADD COLUMN `platform` tinyint NOT NULL DEFAULT '0' COMMENT '小程序平台 0 => 微信, 1 => 支付宝';");
        hj_pdo_run(" CREATE TABLE `hjmall_template_msg` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `tpl_name` varchar(32) NOT NULL COMMENT '模版名称', `tpl_id` varchar(64) NOT NULL COMMENT '模版ID', PRIMARY KEY (`id`), UNIQUE KEY `store_id_tpl_name` (`store_id`,`tpl_name`) ) DEFAULT CHARSET=utf8;");
    },

    '2.8.3' => function () {
    },

    '2.8.4' => function () {
    },

    '2.8.5' => function () {
    },

    '2.8.6' => function () {
    },

    '2.8.7' => function () {
    },

    '2.8.8' => function () {
        hj_pdo_run("ALTER TABLE hjmall_store ADD is_comment tinyint(4) NOT NULL DEFAULT '1' COMMENT '商城评价开关：0.关闭 1.开启';");
        hj_pdo_run("ALTER TABLE `hjmall_ms_order` ADD COLUMN `seller_comments` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商家备注' AFTER `is_show`;");
        hj_pdo_run("ALTER TABLE `hjmall_pt_order` ADD COLUMN `seller_comments` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商家备注' AFTER `is_show`;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_order` ADD COLUMN `seller_comments` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商家备注' AFTER `is_show`, ADD COLUMN `attr` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '规格' AFTER `seller_comments`;");
        hj_pdo_run("ALTER TABLE `hjmall_yy_goods` ADD COLUMN `attr` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '规格' AFTER `stock`, ADD COLUMN `use_attr` smallint(1) NULL DEFAULT 0 COMMENT '是否启用规格' AFTER `attr`;");
    },

    '2.8.9' => function () {
        hj_pdo_run("CREATE TABLE `hjmall_mch_option` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `mch_id` int(11) NOT NULL DEFAULT '0', `group` varchar(255) NOT NULL DEFAULT '', `name` varchar(255) NOT NULL DEFAULT '', `value` longtext NOT NULL, PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("ALTER TABLE `hjmall_mch` ADD COLUMN `longitude` varchar(255) NOT NULL DEFAULT 0 COMMENT '经度', ADD COLUMN `latitude` varchar(255) NOT NULL DEFAULT 0 COMMENT '纬度';");
    },

    '2.9.0' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_cash` ADD COLUMN `service_charge` decimal(11,2) NOT NULL DEFAULT 0 COMMENT '提现手续费';");
        hj_pdo_run(" ALTER TABLE hjmall_store ADD is_sales tinyint(4) NOT NULL DEFAULT '1' COMMENT '商城商品销量开关：0.关闭 1.开启';");
        hj_pdo_run(" ALTER TABLE hjmall_pt_order_refund ADD is_agree smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已同意退、换货：0=待处理，1=已同意，2=已拒绝';");
        hj_pdo_run(" ALTER TABLE hjmall_pt_order_refund ADD is_user_send smallint(1) NOT NULL DEFAULT '0' COMMENT '用户已发货：0=未发货，1=已发货';");
        hj_pdo_run(" ALTER TABLE hjmall_pt_order_refund ADD user_send_express varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递公司';");
        hj_pdo_run(" ALTER TABLE hjmall_pt_order_refund ADD user_send_express_no varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递单号';");
        hj_pdo_run(" ALTER TABLE hjmall_pt_order_refund ADD address_id int(11) DEFAULT '0' COMMENT '退货 换货地址id';");
        hj_pdo_run(" ALTER TABLE hjmall_ms_order_refund ADD is_agree smallint(1) NOT NULL DEFAULT '0' COMMENT '是否已同意退、换货：0=待处理，1=已同意，2=已拒绝';");
        hj_pdo_run(" ALTER TABLE hjmall_ms_order_refund ADD is_user_send smallint(1) NOT NULL DEFAULT '0' COMMENT '用户已发货：0=未发货，1=已发货';");
        hj_pdo_run(" ALTER TABLE hjmall_ms_order_refund ADD user_send_express varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递公司';");
        hj_pdo_run(" ALTER TABLE hjmall_ms_order_refund ADD user_send_express_no varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货快递单号';");
        hj_pdo_run(" ALTER TABLE hjmall_ms_order_refund ADD address_id int(11) DEFAULT '0' COMMENT '退货 换货地址id';");
        hj_pdo_run(" ALTER TABLE `hjmall_coupon` ADD COLUMN `rule` varchar(1000) NULL DEFAULT '' COMMENT '使用说明' AFTER `user_num`;");
        hj_pdo_run(" ALTER TABLE `hjmall_home_nav` ADD COLUMN `is_hide` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否隐藏 0 显示 1隐藏 ' AFTER `is_delete`;");
    },

    '2.9.1' => function () {
    },

    '2.9.2' => function () {
    },

    '2.9.3' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_setting` ADD share_good_status tinyint(4) NOT NULL DEFAULT '0' COMMENT '购买商品自动成为分销商：0.关闭 1.任意商品 2.指定商品';");
        hj_pdo_run(" ALTER TABLE `hjmall_setting` ADD share_good_id int(11) NOT NULL DEFAULT '0' COMMENT '购买指定分销商品Id';");
        hj_pdo_run(" ALTER TABLE `hjmall_goods` ADD COLUMN `is_negotiable` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否面议 0 不面议 1面议' AFTER `type`;");
    },

    '2.9.5' => function () {
    },

    '2.9.6' => function () {
    },

    '2.9.7' => function () {
    },

    '2.9.18' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD COLUMN `good_negotiable` varchar(255) NULL DEFAULT '' COMMENT '1 电话 2 客服';");
        hj_pdo_run(" ALTER TABLE `hjmall_store` ADD COLUMN `buy_member` smallint(1) NULL DEFAULT 0 COMMENT '是否购买会员 0不支持 1支持';");
        hj_pdo_run(" ALTER TABLE hjmall_goods ADD attr_setting_type tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销设置类型 0.普通设置|1.详细设置';");
        hj_pdo_run(" ALTER TABLE hjmall_ms_goods ADD attr_setting_type tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销设置类型 0.普通设置|1.详细设置';");
        hj_pdo_run(" ALTER TABLE hjmall_goods_share ADD attr_setting_type tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销设置类型 0.普通设置|1.详细设置';");
        hj_pdo_run(" ALTER TABLE `hjmall_goods_share` ADD relation_id int(11) NOT NULL DEFAULT '0' COMMENT '关联临时分销佣金ID，秒杀商品设置|拼团阶梯团';");
        hj_pdo_run(" ALTER TABLE `hjmall_store` ADD logo varchar(255) COMMENT '商城logo';");
        hj_pdo_run(" ALTER TABLE `hjmall_user` ADD blacklist tinyint(1) NOT NULL DEFAULT '0' COMMENT '黑名单 0.否 | 1.是';");
        hj_pdo_run(" CREATE TABLE `hjmall_lottery_goods` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id', `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间', `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间', `stock` int(11) NOT NULL DEFAULT '0' COMMENT '数量', `attr` longtext NOT NULL COMMENT '规格', `is_delete` smallint(1) NOT NULL DEFAULT '0', `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序', `status` int(11) NOT NULL DEFAULT '0' COMMENT '是否关闭', `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间', `type` int(11) NOT NULL DEFAULT '0' COMMENT '0未完成 1已完成', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run(" CREATE TABLE `hjmall_lottery_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID', `lottery_id` int(11) NOT NULL DEFAULT '0', `addtime` int(11) NOT NULL DEFAULT '0', `status` int(11) NOT NULL DEFAULT '0' COMMENT '0待开奖 1未中奖 2中奖3已领取', `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id', `attr` longtext NOT NULL, `raffle_time` int(11) NOT NULL DEFAULT '0' COMMENT '领取时间', `order_id` int(11) NOT NULL DEFAULT '0', `obtain_time` int(11) DEFAULT '0', `form_id` varchar(255) NOT NULL DEFAULT '', PRIMARY KEY (`id`), KEY `lottery_id` (`lottery_id`) USING BTREE, KEY `status` (`status`) USING BTREE ) DEFAULT CHARSET=utf8;");
        hj_pdo_run(" CREATE TABLE `hjmall_lottery_reserve` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL, `lottery_id` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
    },

    '2.9.19' => function () {
        hj_pdo_run("ALTER TABLE hjmall_store ADD is_member_price tinyint(1) NOT NULL DEFAULT '1' COMMENT '会员价是否显示 0.不显示|1.显示';");
        hj_pdo_run(" ALTER TABLE hjmall_store ADD is_share_price tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销价是否显示 0.不显示|1.显示';");
        hj_pdo_run(" ALTER TABLE `hjmall_store` ADD COLUMN `is_member` smallint(1) NULL DEFAULT 0 COMMENT '是否购买会员 0不支持 1支持';");
        hj_pdo_run(" ALTER TABLE `hjmall_level` ADD COLUMN `synopsis` longtext NULL COMMENT '会员权益(禁用)';");
        hj_pdo_run(" ALTER TABLE `hjmall_topic` ADD COLUMN `qrcode_pic` longtext NULL COMMENT '海报分享图';");
    },

    '2.9.20' => function () {
    },

    '2.9.21' => function () {
    },

    '2.9.22' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_pt_goods` ADD is_level tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否享受会员折扣 0-不享受 1--享受';");
        hj_pdo_run(" ALTER TABLE `hjmall_pt_goods_detail` ADD is_level tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否享受会员折扣 0-不享受 1--享受';");
        hj_pdo_run(" ALTER TABLE `hjmall_yy_goods` ADD is_level tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否享受会员折扣 0-不享受 1--享受';");
        hj_pdo_run(" ALTER TABLE `hjmall_miaosha_goods` ADD is_level tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否享受会员折扣 0-不享受 1--享受';");
    },

    '2.9.24' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_store` ADD is_official_account tinyint(1) NOT NULL DEFAULT '0' COMMENT '公众号关注组件 0.否 | 1.是';");
        hj_pdo_run(" AlTER TABLE `hjmall_user` ADD parent_user_id int(11) NOT NULL DEFAULT '0' COMMENT '可能成为上级的ID';");
        hj_pdo_run(" ALTER TABLE `hjmall_mch` ADD COLUMN `main_content` varchar(255) NOT NULL DEFAULT '' COMMENT '主营范围', ADD COLUMN `summary` varchar(255) NULL DEFAULT '';");
        hj_pdo_run(" ALTER TABLE `hjmall_lottery_log` ADD COLUMN `child_id` int(11) NOT NULL DEFAULT 0 COMMENT '下级用户', ADD COLUMN `lucky_code` varchar(255) NOT NULL DEFAULT '' COMMENT '幸运码';");
        hj_pdo_run(" CREATE TABLE `hjmall_lottery_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `rule` varchar(2000) NOT NULL DEFAULT '' COMMENT '规则', `title` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序标题', `type` int(10) NULL DEFAULT 0 COMMENT '0不强制 1强制', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
    },

    '2.9.25' => function () {
    },

    '2.9.26' => function () {
    },

    '2.9.27' => function () {
    },

    '2.9.29' => function () {
    },

    '2.9.30' => function () {
    },

    '2.9.31' => function () {
    },

    '2.9.32' => function () {
    },

    '2.9.33' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_bargain_setting` ADD COLUMN `share_title` longtext NULL;");
        hj_pdo_run(" ALTER TABLE `hjmall_pond` ADD COLUMN `name` varchar(255) NULL DEFAULT '' COMMENT '别名' AFTER `attr`;");
    },

    '2.9.34' => function () {
    },

    '2.9.35' => function () {
    },

    '2.9.36' => function () {
        hj_pdo_run("AlTER TABLE hjmall_yy_goods ADD video_url varchar(255) DEFAULT NULL COMMENT '视频url';");
        hj_pdo_run(" AlTER TABLE hjmall_pt_goods ADD video_url varchar(255) DEFAULT NULL COMMENT '视频url';");
        hj_pdo_run(" CREATE TABLE `hjmall_task` ( `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, `token` varchar(128) NOT NULL, `delay_seconds` int(11) NOT NULL COMMENT '多少秒后执行', `is_executed` int(1) NOT NULL DEFAULT 0 COMMENT '是否已执行：0=未执行，1=已执行', `class` varchar(255) NOT NULL, `params` longtext NULL, `addtime` int(11) NOT NULL DEFAULT 0, `is_delete` int(1) NOT NULL DEFAULT 0, PRIMARY KEY (`id`) USING BTREE ) DEFAULT CHARSET=utf8 COMMENT = '定时任务' ROW_FORMAT = Dynamic;");
    },

    '2.9.37' => function () {
    },

    '2.9.38' => function () {
    },

    '2.9.39' => function () {
    },

    '2.10.0' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_order` ADD COLUMN `currency` decimal(10,2) NOT NULL DEFAULT 0 COMMENT '货币：活力币' AFTER `is_show`;");
        hj_pdo_run("CREATE TABLE `hjmall_step_activity` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `name` varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题', `currency` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '奖金池', `bail_currency` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '保证金', `status` int(11) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启', `step_num` int(11) NOT NULL DEFAULT '0' COMMENT '挑战布数', `open_date` date NOT NULL COMMENT '开放日期，例：2017-08-21', `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除', `type` int(11) NOT NULL DEFAULT '0' COMMENT '0进行中 1 已完成', `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_step_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `step_id` int(11) NOT NULL DEFAULT '0' COMMENT 'stepID', `num` int(11) NOT NULL DEFAULT '0' COMMENT '兑换布数', `status` int(11) NOT NULL DEFAULT '0' COMMENT '1收入 2支出 ', `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 布数兑换 1商品兑换 2 布数挑战', `step_currency` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '活力币', `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单/活动', `raffle_time` int(11) NOT NULL DEFAULT '0' COMMENT '领取时间', `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', PRIMARY KEY (`id`), KEY `step_currency` (`step_currency`) USING BTREE, KEY `num` (`num`) USING BTREE, KEY `type` (`type`) USING BTREE, KEY `status` (`status`) USING BTREE ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_step_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `rule` varchar(2000) DEFAULT '' COMMENT '规则', `title` varchar(255) DEFAULT '' COMMENT '小程序标题', `convert_max` int(11) DEFAULT '0' COMMENT '最大步数兑换限制', `convert_ratio` int(11) DEFAULT '0' COMMENT '兑换比率', `invite_ratio` int(11) DEFAULT '0' COMMENT '邀请比率', `remind_time` varchar(255) DEFAULT '8' COMMENT '0-24', `activity_rule` varchar(2000) DEFAULT '' COMMENT '活动规则', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_step_user` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `step_currency` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '活力币 ', `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID', `ratio` int(11) NOT NULL DEFAULT '0' COMMENT '概率加成', `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '邀请ID', `invite_ratio` int(11) NOT NULL DEFAULT '0' COMMENT '邀请好友加成', `is_delete` int(11) NOT NULL DEFAULT '0', `remind` int(11) NOT NULL DEFAULT '0' COMMENT '0提醒 1开启', PRIMARY KEY (`id`), KEY `step_currency` (`step_currency`) USING BTREE ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_ad` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `is_delete` int(11) NOT NULL DEFAULT '0', `status` int(11) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启', `unit_id` varchar(255) NOT NULL DEFAULT '' COMMENT '广告id', `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', `type` int(11) NOT NULL DEFAULT '0' COMMENT '1抽奖首页', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_pic` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 步数海报', `type` int(11) NOT NULL, `pic_url` varchar(2048) DEFAULT '', `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_step_remind` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `date` date NOT NULL DEFAULT '0000-00-00' COMMENT '日期', `user_id` int(11) NOT NULL, PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
    },

    '2.10.2' => function () {
    },

    '2.10.3' => function () {
    },

    '2.10.4' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_step_setting` ADD COLUMN `share_title` varchar(2000) NULL DEFAULT '' COMMENT '转发标题';");
    },

    '2.10.5' => function () {
        hj_pdo_run("CREATE TABLE `hjmall_step_remind` ( `id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL DEFAULT '0', `date` date NOT NULL DEFAULT '0000-00-00' COMMENT '日期', `user_id` int(11) NOT NULL, PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
    },

    '2.10.6' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_step_setting` ADD COLUMN `ranking_num` int(11) NULL DEFAULT 0 COMMENT '全国排行限制', ADD COLUMN `qrcode_title` varchar(255) NULL DEFAULT '' COMMENT '海报文字';");
    },

    '2.10.7' => function () {
    },

    '2.10.8' => function () {
    },

    '2.10.9' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_task` ADD COLUMN `content` longtext NOT NULL COMMENT '任务说明';");
        hj_pdo_run(" ALTER TABLE hjmall_action_log ADD type tinyint(4) NOT NULL DEFAULT '0' COMMENT '日志类型:1.操作日志 2.定时任务日志';");
    },

    '2.10.10' => function () {
    },

    '2.10.11' => function () {
    },

    '2.10.12' => function () {
    },

    '2.10.13' => function () {
    },

    '2.10.14' => function () {
    },

    '3.0.0' => function () {
        hj_pdo_run("CREATE TABLE `hjmall_diy_page` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `store_id` int(11) DEFAULT NULL, `title` varchar(45) NOT NULL DEFAULT '' COMMENT '页面标题', `template_id` int(11) NOT NULL COMMENT '模板ID', `is_delete` int(11) NOT NULL DEFAULT '0', `addtime` int(11) NOT NULL, `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0--禁用 1--启用', `is_index` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否覆盖首页 0--不覆盖 1--覆盖', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_diy_template` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `name` varchar(45) NOT NULL DEFAULT '' COMMENT '模板名称', `template` longtext NOT NULL COMMENT '模板数据', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `addtime` int(11) NOT NULL, PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
    },

    '3.0.2' => function () {
    },

    '3.0.3' => function () {
    },

    '3.0.4' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_plugin` ADD COLUMN `name` varchar(255) NOT NULL DEFAULT '' AFTER `data`, ADD COLUMN `display_name` varchar(255) NOT NULL DEFAULT '' AFTER `name`, ADD COLUMN `route` varchar(255) NOT NULL DEFAULT '' AFTER `display_name`, ADD COLUMN `addtime` int(0) NOT NULL DEFAULT 0 AFTER `route`;");
    },

    '3.1.0' => function () {
    },

    '3.1.2' => function () {
    },

    '3.1.3' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_plugin` ADD COLUMN `name` varchar(255) NOT NULL DEFAULT '' AFTER `data`, ADD COLUMN `display_name` varchar(255) NOT NULL DEFAULT '' AFTER `name`, ADD COLUMN `route` varchar(255) NOT NULL DEFAULT '' AFTER `display_name`, ADD COLUMN `addtime` int(0) NOT NULL DEFAULT 0 AFTER `route`;");
    },

    '3.1.4' => function () {
    },

    '3.1.5' => function () {
    },

    '3.1.6' => function () {
    },

    '3.1.7' => function () {
    },

    '3.1.8' => function () {
    },

    '3.1.9' => function () {
        hj_pdo_run("UPDATE `hjmall_action_log` SET type = 1 where title='定时任务' AND type = 0;");
    },

    '3.1.10' => function () {
    },

    '3.1.11' => function () {
        hj_pdo_run("CREATE TABLE `hjmall_gwd_buy_list` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `order_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `is_delete` tinyint(4) NOT NULL DEFAULT '0', `addtime` varchar(255) NOT NULL DEFAULT '', `type` tinyint(1) NOT NULL COMMENT '0.商城|1.秒杀|2.拼团', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_gwd_like_list` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `good_id` int(11) NOT NULL, `is_delete` tinyint(4) NOT NULL DEFAULT '0', `addtime` varchar(255) NOT NULL DEFAULT '', `type` tinyint(1) NOT NULL COMMENT '0.商城|1.秒杀|2.拼团', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_gwd_like_user` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `like_id` int(11) NOT NULL, `is_delete` tinyint(4) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_gwd_setting` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `status` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
    },

    '3.1.14' => function () {
        hj_pdo_run("CREATE TABLE `hjmall_gwd_buy_list` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `order_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `is_delete` tinyint(4) NOT NULL DEFAULT '0', `addtime` varchar(255) NOT NULL DEFAULT '', `type` tinyint(1) NOT NULL COMMENT '0.商城|1.秒杀|2.拼团', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_gwd_like_list` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `good_id` int(11) NOT NULL, `is_delete` tinyint(4) NOT NULL DEFAULT '0', `addtime` varchar(255) NOT NULL DEFAULT '', `type` tinyint(1) NOT NULL COMMENT '0.商城|1.秒杀|2.拼团', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_gwd_like_user` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `like_id` int(11) NOT NULL, `is_delete` tinyint(4) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
        hj_pdo_run("CREATE TABLE `hjmall_gwd_setting` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `status` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8;");
    },

    '3.1.15' => function () {
    },

    '3.1.16' => function () {
    },

    '3.1.17' => function () {
    },

    '3.1.18' => function () {
    },

    '3.1.19' => function () {
    },

    '3.1.20' => function () {
    },

    '3.1.21' => function () {
        hj_pdo_run("alter table `hjmall_action_log` modify column result LONGTEXT;");
    },

    '3.1.22' => function () {
    },

    '3.1.23' => function () {
    },

    '3.1.24' => function () {
    },

    '3.1.25' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_form_id` ADD is_usable tinyint(1) NOT NULL DEFAULT '0' COMMENT '用于判断 prepay_id 是否有效';");
    },

    '3.1.26' => function () {
    },

    '3.1.27' => function () {
    },

    '3.1.28' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_printer_setting` ADD COLUMN `big`  int(11) NULL DEFAULT 1 COMMENT '发大倍数';");
    },

    '3.1.29' => function () {
    },

    '3.1.31' => function () {
    },

    '3.1.32' => function () {
    },

    '3.1.33' => function () {
    },

    '3.1.34' => function () {
    },

    '3.1.35' => function () {
    },

    '3.1.36' => function () {
    },

    '3.1.37' => function () {
    },

    '3.1.38' => function () {
    },

    '3.1.39' => function () {
        hj_pdo_run("ALTER TABLE `hjmall_delivery` ADD COLUMN `template_size` varchar(255) NULL COMMENT '快递鸟电子面单模板规格';");
    },

    '3.1.40' => function () {
    },

    '3.1.41' => function () {
    },

];
