<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

define('REGULAR_EMAIL', '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i');
define('REGULAR_MOBILE', '/^\d{6,15}$/');
define('REGULAR_USERNAME', '/^[\x{4e00}-\x{9fa5}a-z\d_\.]{3,30}$/iu');

define('TEMPLATE_DISPLAY', 0);
define('TEMPLATE_FETCH', 1);
define('TEMPLATE_INCLUDEPATH', 2);

define('ACCOUNT_SUBSCRIPTION', 1);
define('ACCOUNT_SUBSCRIPTION_VERIFY', 3);
define('ACCOUNT_SERVICE', 2);
define('ACCOUNT_SERVICE_VERIFY', 4);
define('ACCOUNT_TYPE_OFFCIAL_NORMAL', 1);
define('ACCOUNT_TYPE_OFFCIAL_AUTH', 3);
define('ACCOUNT_TYPE_APP_NORMAL', 4);
define('ACCOUNT_TYPE_WEBAPP_NORMAL', 5);
define('ACCOUNT_TYPE_PHONEAPP_NORMAL', 6);
define('ACCOUNT_TYPE_APP_AUTH', 7);
define('ACCOUNT_TYPE_WXAPP_WORK', 8);
define('ACCOUNT_TYPE_XZAPP_NORMAL', 9);
define('ACCOUNT_TYPE_XZAPP_AUTH', 10);
define('ACCOUNT_TYPE_ALIAPP_NORMAL', 11);
define('ACCOUNT_TYPE_BAIDUAPP_NORMAL', 12);
define('ACCOUNT_TYPE_TOUTIAOAPP_NORMAL', 13);

define('ACCOUNT_TYPE_SIGN', 'account');
define('WXAPP_TYPE_SIGN', 'wxapp');
define('WEBAPP_TYPE_SIGN', 'webapp');
define('PHONEAPP_TYPE_SIGN', 'phoneapp');
define('WELCOMESYSTEM_TYPE_SIGN', 'welcome');
define('XZAPP_TYPE_SIGN', 'xzapp');
define('ALIAPP_TYPE_SIGN', 'aliapp');
define('BAIDUAPP_TYPE_SIGN', 'baiduapp');
define('TOUTIAOAPP_TYPE_SIGN', 'toutiaoapp');

define('ACCOUNT_OAUTH_LOGIN', 3);
define('ACCOUNT_NORMAL_LOGIN', 1);

define('WEIXIN_ROOT', 'https://mp.weixin.qq.com');

define('ACCOUNT_OPERATE_ONLINE', 1);
define('ACCOUNT_OPERATE_MANAGER', 2);
define('ACCOUNT_OPERATE_CLERK', 3);

define('ACCOUNT_MANAGE_NAME_CLERK', 'clerk');
define('ACCOUNT_MANAGE_TYPE_OPERATOR', 1);
define('ACCOUNT_MANAGE_NAME_OPERATOR', 'operator');
define('ACCOUNT_MANAGE_TYPE_MANAGER', 2);
define('ACCOUNT_MANAGE_NAME_MANAGER', 'manager');
define('ACCOUNT_MANAGE_TYPE_OWNER', 3);
define('ACCOUNT_MANAGE_NAME_OWNER', 'owner');
define('ACCOUNT_MANAGE_NAME_FOUNDER', 'founder');
define('ACCOUNT_MANAGE_GROUP_FOUNDER', 1);
define('ACCOUNT_MANAGE_TYPE_VICE_FOUNDER', 4);
define('ACCOUNT_MANAGE_NAME_VICE_FOUNDER', 'vice_founder');
define('ACCOUNT_MANAGE_GROUP_VICE_FOUNDER', 2);
define('ACCOUNT_MANAGE_GROUP_GENERAL', 0);
define('ACCOUNT_MANAGE_NAME_UNBIND_USER', 'unbind_user');
define('ACCOUNT_NO_OWNER_UID', 0);
define('ACCOUNT_MANAGE_NAME_EXPIRED', 'expired');

define('SYSTEM_COUPON', 1);
define('WECHAT_COUPON', 2);
define('COUPON_TYPE_DISCOUNT', '1'); define('COUPON_TYPE_CASH', '2'); define('COUPON_TYPE_GROUPON', '3'); define('COUPON_TYPE_GIFT', '4'); define('COUPON_TYPE_GENERAL', '5'); define('COUPON_TYPE_MEMBER', '6'); define('COUPON_TYPE_SCENIC', '7'); define('COUPON_TYPE_MOVIE', '8'); define('COUPON_TYPE_BOARDINGPASS', '9'); define('COUPON_TYPE_MEETING', '10'); define('COUPON_TYPE_BUS', '11'); 
define('ATTACH_FTP', 1); define('ATTACH_OSS', 2); define('ATTACH_QINIU', 3); define('ATTACH_COS', 4); 
define('ATTACH_TYPE_IMAGE', 1);
define('ATTACH_TYPE_VOICE', 2);
define('ATTACH_TYPE_VEDIO', 3);
define('ATTACH_TYPE_NEWS', 4);

define('ATTACHMENT_IMAGE', 'image');

define('ATTACH_SAVE_TYPE_FIXED', 1);
define('ATTACH_SAVE_TYPE_TEMP', 2);

define('STATUS_OFF', 0); define('STATUS_ON', 1); define('STATUS_SUCCESS', 0); 
define('CACHE_EXPIRE_SHORT', 60);
define('CACHE_EXPIRE_MIDDLE', 300);
define('CACHE_EXPIRE_LONG', 3600);
define('CACHE_KEY_LENGTH', 100); 
define('MODULE_SYSTEM', 1);
define('MODULE_NONSYSTEM', 0);
define('MODULE_SUPPORT_WXAPP', 2);
define('MODULE_NONSUPPORT_WXAPP', 1);
define('MODULE_SUPPORT_ACCOUNT', 2);
define('MODULE_NONSUPPORT_ACCOUNT', 1);
define('MODULE_NOSUPPORT_WEBAPP', 1);
define('MODULE_SUPPORT_WEBAPP', 2);
define('MODULE_NOSUPPORT_PHONEAPP', 1);
define('MODULE_SUPPORT_PHONEAPP', 2);
define('MODULE_SUPPORT_SYSTEMWELCOME', 2);
define('MODULE_NONSUPPORT_SYSTEMWELCOME', 1);
define('MODULE_NOSUPPORT_ANDROID', 1);
define('MODULE_SUPPORT_ANDROID', 2);
define('MODULE_NOSUPPORT_IOS', 1);
define('MODULE_SUPPORT_IOS', 2);
define('MODULE_SUPPORT_XZAPP', 2);
define('MODULE_NOSUPPORT_XZAPP', 1);
define('MODULE_SUPPORT_ALIAPP', 2);
define('MODULE_NOSUPPORT_ALIAPP', 1);
define('MODULE_SUPPORT_BAIDUAPP', 2);
define('MODULE_NOSUPPORT_BAIDUAPP', 1);
define('MODULE_SUPPORT_TOUTIAOAPP', 2);
define('MODULE_NOSUPPORT_TOUTIAOAPP', 1);

define('MODULE_SUPPORT_WXAPP_NAME', 'wxapp_support');
define('MODULE_SUPPORT_ACCOUNT_NAME', 'account_support');
define('MODULE_SUPPORT_WEBAPP_NAME', 'webapp_support');
define('MODULE_SUPPORT_PHONEAPP_NAME', 'phoneapp_support');
define('MODULE_SUPPORT_SYSTEMWELCOME_NAME', 'welcome_support');
define('MODULE_SUPPORT_XZAPP_NAME', 'xzapp_support');
define('MODULE_SUPPORT_ALIAPP_NAME', 'aliapp_support');
define('MODULE_SUPPORT_BAIDUAPP_NAME', 'baiduapp_support');
define('MODULE_SUPPORT_TOUTIAOAPP_NAME', 'toutiaoapp_support');

define('MODULE_LOCAL_INSTALL', '1');
define('MODULE_LOCAL_UNINSTALL', '2');
define('MODULE_CLOUD_INSTALL', '3');
define('MODULE_CLOUD_UNINSTALL', '4');
define('MODULE_CLOUD_UNINSTALL_NORMAL', '1');
define('MODULE_CLOUD_UNINSTALL_CHECKED', '2');
define('MODULE_CLOUD_UNINSTALL_IGNORE', '3');
define('MODULE_RECYCLE_INSTALL_DISABLED', '1');
define('MODULE_RECYCLE_UNINSTALL_IGNORE', '2');

define('PERMISSION_ACCOUNT', 'system');
define('PERMISSION_WXAPP', 'wxapp');
define('PERMISSION_WEBAPP', 'webapp');
define('PERMISSION_PHONEAPP', 'phoneapp');
define('PERMISSION_XZAPP', 'xzapp');
define('PERMISSION_ALIAPP', 'aliapp');
define('PERMISSION_BAIDUAPP', 'baiduapp');
define('PERMISSION_TOUTIAOAPP', 'toutiaoapp');
define('PERMISSION_SYSTEM', 'site');
define('PERMISSION_MODULES', 'modules');

define('PAYMENT_WECHAT_TYPE_NORMAL', 1); define('PAYMENT_WECHAT_TYPE_BORROW', 2); define('PAYMENT_WECHAT_TYPE_SERVICE', 3); define('PAYMENT_WECHAT_TYPE_CLOSE', 4);

define('FANS_CHATS_FROM_SYSTEM', 1);

define('WXAPP_STATISTICS_DAILYVISITTREND', 2);
define('WXAPP_DIY', 1);
define('WXAPP_TEMPLATE', 2);
define('WXAPP_MODULE', 3);
define('WXAPP_CREATE_MODULE', 1);
define('WXAPP_CREATE_MUTI_MODULE', 2);
define('WXAPP_CREATE_DEFAULT', 0);

define('MATERIAL_LOCAL', 'local'); define('MATERIAL_WEXIN', 'perm'); 
define('MENU_CURRENTSELF', 1);
define('MENU_HISTORY', 2);
define('MENU_CONDITIONAL', 3);

define('USER_STATUS_CHECK', 1);
define('USER_STATUS_NORMAL', 2);
define('USER_STATUS_BAN', 3);

define('USER_TYPE_COMMON', 1);
define('USER_TYPE_CLERK', 3);

define('PERSONAL_BASE_TYPE', 1);
define('PERSONAL_AUTH_TYPE', 2);
define('PERSONAL_LIST_TYPE', 3);
define('PERSONAL_BIND_TYPE', 4);
define('PERSONAL_LIMIT_TYPE', 5);

define('STORE_TYPE_ACCOUNT', 2);
define('STORE_TYPE_WXAPP', 3);
define('STORE_TYPE_WEBAPP', 17);
define('STORE_TYPE_PHONEAPP', 18);
define('STORE_TYPE_XZAPP', 19);
define('STORE_TYPE_ALIAPP', 20);
define('STORE_TYPE_BAIDUAPP', 21);
define('STORE_TYPE_TOUTIAOAPP', 22);

define('STORE_TYPE_ACCOUNT_RENEW', 7);
define('STORE_TYPE_WXAPP_RENEW', 8);
define('STORE_TYPE_WEBAPP_RENEW', 23);
define('STORE_TYPE_PHONEAPP_RENEW', 24);
define('STORE_TYPE_XZAPP_RENEW', 25);
define('STORE_TYPE_ALIAPP_RENEW', 26);
define('STORE_TYPE_BAIDUAPP_RENEW', 27);
define('STORE_TYPE_TOUTIAOAPP_RENEW', 28);

define('STORE_TYPE_MODULE', 1); define('STORE_TYPE_WXAPP_MODULE', 4); define('STORE_TYPE_WEBAPP_MODULE', 11); define('STORE_TYPE_PHONEAPP_MODULE', 12); define('STORE_TYPE_XZAPP_MODULE', 13); define('STORE_TYPE_ALIAPP_MODULE', 14); define('STORE_TYPE_BAIDUAPP_MODULE', 15); define('STORE_TYPE_TOUTIAOAPP_MODULE', 16); 
define('STORE_TYPE_PACKAGE', 5);
define('STORE_TYPE_API', 6);

define('STORE_TYPE_USER_PACKAGE', 9);
define('STORE_TYPE_ACCOUNT_PACKAGE', 10);

define('STORE_ORDER_PLACE', 1);
define('STORE_ORDER_DELETE', 2);
define('STORE_ORDER_FINISH', 3);
define('STORE_ORDER_DEACTIVATE', 4);
define('STORE_ORDER_APPLY_REFUND', 5);
define('STORE_ORDER_REFUND_FAIL', 6);
define('STORE_ORDER_REFUNDED', 7);
define('STORE_ORDER_FOUNDER_REFUND', 8);

define('STORE_ORDER_WISH', 1); define('STORE_ORDER_NORMAL', 0); define('STORE_ORDER_ALL', 3); 
define('STORE_GOODS_STATUS_OFFlINE', 0);
define('STORE_GOODS_STATUS_ONLINE', 1);
define('STORE_GOODS_STATUS_DELETE', 2);

define('ARTICLE_PCATE', 0);
define('ARTICLE_CCATE', 0);

define('USER_REGISTER_TYPE_QQ', 1);
define('USER_REGISTER_TYPE_WECHAT', 2);
define('USER_REGISTER_TYPE_MOBILE', 3);
define('USER_REGISTER_TYPE_OPEN_WECHAT', 4);

define('MESSAGE_ORDER_TYPE', 1);
define('MESSAGE_ORDER_WISH_TYPE', 13);
define('MESSAGE_ORDER_PAY_TYPE', 9);
define('MESSAGE_ACCOUNT_EXPIRE_TYPE', 2);
define('MESSAGE_WECHAT_EXPIRE_TYPE', 5);
define('MESSAGE_WEBAPP_EXPIRE_TYPE', 6);
define('MESSAGE_WORKORDER_TYPE', 3);
define('MESSAGE_REGISTER_TYPE', 4);
define('MESSAGE_USER_EXPIRE_TYPE', 7);
define('MESSAGE_WXAPP_MODULE_UPGRADE', 8);
define('MESSAGE_SYSTEM_UPGRADE', 10);
define('MESSAGE_OFFICIAL_DYNAMICS', 11);
define('MESSAGE_ORDER_APPLY_REFUND_TYPE', 12);

define('MESSAGE_ENABLE', 1);
define('MESSAGE_DISABLE', 2);

define('MESSAGE_NOREAD', 1);
define('MESSAGE_READ', 2);

define('FILE_NO_UNIACID', -1);

define('OAUTH_TYPE_BASE', 1);
define('OAUTH_TYPE_USERINFO', 2);

define('ARTICLE_COMMENT_DEFAULT', 0);
define('ARTICLE_NOCOMMENT', 1);
define('ARTICLE_COMMENT', 2);
define('ARTICLE_COMMENT_NOREAD', 1);
define('ARTICLE_COMMENT_READ', 2);

define('COMMENT_STATUS_OFF', 0);
define('COMMENT_STATUS_ON', 1);

define('WELCOME_DISPLAY_TYPE', 9);
define('PLATFORM_DISPLAY_TYPE', 6);
define('MODULE_DISPLAY_TYPE', 7);

define('USER_CREATE_PERMISSION_ALL_TYPE', 1);
define('USER_CREATE_PERMISSION_GROUP_TYPE', 2);

define('USER_ENDTIME_GROUP_EMPTY_TYPE', 0);
define('USER_ENDTIME_GROUP_DELETE_TYPE', 1);
define('USER_ENDTIME_GROUP_UNLIMIT_TYPE', 2);

define('USERS_OPERATE_TYPE_ACCOUNT', 1);
define('USERS_OPERATE_TYPE_MODULE', 2);

