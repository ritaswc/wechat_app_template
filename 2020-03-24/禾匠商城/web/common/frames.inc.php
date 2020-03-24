<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
global $_W;

$we7_system_menu = array();
$we7_system_menu['welcome'] = array(
	'title' => '首页',
	'icon' => 'wi wi-home',
	'url' => url('home/welcome/system', array('page' => 'home')),
	'section' => array(),
);



$we7_system_menu['account_manage'] = array(
	'title' => '平台管理',
	'icon' => 'wi wi-platform-manage',
	'dimension' => 2,
	'url' => url('account/manage'),
	'section' => array(
		'account_manage' => array(
			'title' => '平台管理',
			'menu' => array(
				'account_manage_display' => array(
					'title' => '平台列表',
					'url' => url('account/manage'),
					'permission_name' => 'account_manage_display',
					'sub_permission' => array(
						array(
							'title' => '帐号停用',
							'permission_name' => 'account_manage_stop',
						),
					),
				),
				'account_manage_recycle' => array(
					'title' => '回收站',
					'url' => url('account/recycle'),
					'permission_name' => 'account_manage_recycle',
					'sub_permission' => array(
						array(
							'title' => '帐号删除',
							'permission_name' => 'account_manage_delete',
						),
						array(
							'title' => '帐号恢复',
							'permission_name' => 'account_manage_recover',
						),
					),
				),
				'account_manage_system_platform' => array(
					'title' => ' 微信开放平台',
					'url' => url('system/platform'),
					'permission_name' => 'account_manage_system_platform',
				),
				'account_manage_expired_message' => array(
					'title' => ' 自定义到期提示',
					'url' => url('account/expired-message'),
					'permission_name' => 'account_manage_expired_message',
				),
			),
		),
	),
);

$we7_system_menu['module_manage'] = array(
	'title' => '应用管理',
	'icon' => 'wi wi-module-manage',
	'dimension' => 2,
	'url' => url('module/manage-system/installed'),
	'section' => array(
		'module_manage' => array(
			'title' => '应用管理',
			'menu' => array(
				'module_manage_installed' => array(
					'title' => '已安装列表',
					'url' => url('module/manage-system/installed'),
					'permission_name' => 'module_manage_installed',
					'sub_permission' => array(),
				),
				'module_manage_stoped' => array(
					'title' => '已停用列表',
					'url' => url('module/manage-system/recycle', array('type' => MODULE_RECYCLE_INSTALL_DISABLED)),
					'permission_name' => 'module_manage_stoped',
					'sub_permission' => array(),
				),
				'module_manage_not_installed' => array(
					'title' => '未安装列表',
					'url' => url('module/manage-system/not_installed'),
					'permission_name' => 'module_manage_not_installed',
					'sub_permission' => array(),
				),
				'module_manage_recycle' => array(
					'title' => '回收站',
					'url' => url('module/manage-system/recycle', array('type' => MODULE_RECYCLE_UNINSTALL_IGNORE)),
					'permission_name' => 'module_manage_recycle',
					'sub_permission' => array(),
				),
				'module_manage_subscribe' => array(
					'title' => '订阅管理',
					'url' => url('module/manage-system/subscribe'),
					'permission_name' => 'module_manage_subscribe',
					'sub_permission' => array(),
				),
				'module_manage_expire' => array(
					'title' => '应用停用提醒',
					'url' => url('module/expire'),
					'permission_name' => 'module_manage_expire',
					'sub_permission' => array(),
				),
			),
		),
	),
);

$we7_system_menu['user_manage'] = array(
	'title' => '用户管理',
	'icon' => 'wi wi-user-group',
	'dimension' => 2,
	'url' => url('user/display'),
	'section' => array(
		'user_manage' => array(
			'title' => '用户管理',
			'menu' => array(
				'user_manage_display' => array(
					'title' => '普通用户',
					'url' => url('user/display'),
					'permission_name' => 'user_manage_display',
					'sub_permission' => array(),
				),
				
				'user_manage_founder' => array(
					'title' => '副站长',
					'url' => url('founder/display'),
					'permission_name' => 'user_manage_founder',
					'sub_permission' => array(),
					'founder' => true,
				),
				
				'user_manage_clerk' => array(
					'title' => '店员管理',
					'url' => url('user/display', array('type' => 'clerk')),
					'permission_name' => 'user_manage_clerk',
					'sub_permission' => array(),
				),
				'user_manage_check' => array(
					'title' => '审核用户',
					'url' => url('user/display', array('type' => 'check')),
					'permission_name' => 'user_manage_check',
					'sub_permission' => array(),
				),
				'user_manage_recycle' => array(
					'title' => '回收站',
					'url' => url('user/display', array('type' => 'recycle')),
					'permission_name' => 'user_manage_recycle',
					'sub_permission' => array(),
				),
				'user_manage_fields' => array(
					'title' => '用户属性设置',
					'url' => url('user/fields/display'),
					'permission_name' => 'user_manage_fields',
					'sub_permission' => array(),
					'founder' => true,
				),
			),
		),
	),
);

$we7_system_menu['permission'] = array(
	'title' => '权限组',
	'icon' => 'wi wi-userjurisdiction',
	'dimension' => 2,
	'url' => url('module/group'),
	'section' => array(
		'permission' => array(
			'title' => '权限组',
			'menu' => array(
				'permission_module_group' => array(
					'title' => '应用权限组',
					'url' => url('module/group'),
					'permission_name' => 'permission_module_group',
					'sub_permission' => array(),
				),
				'permission_create_account_group' => array(
					'title' => '账号权限组',
					'url' => url('user/create-group'),
					'permission_name' => 'permission_create_account_group',
					'sub_permission' => array(),
				),
				'permission_user_group' => array(
					'title' => '用户权限组合',
					'url' => url('user/group'),
					'permission_name' => 'permission_user_group',
					'sub_permission' => array(),
				),
				
				'permission_founder_group' => array(
					'title' => '副站长权限组合',
					'url' => url('founder/group'),
					'permission_name' => 'permission_founder_group',
					'sub_permission' => array(),
					'founder' => true,
				),
				
			),
		),
	),
);

$we7_system_menu['system'] = array(
	'title' => '系统功能',
	'icon' => 'wi wi-setting',
	'dimension' => 3,
	'url' => user_is_founder($_W['uid'], true) ? url('article/notice') : url('system/updatecache'),
	'section' => array(
		'article' => array(
			'title' => '站内公告',
			'menu' => array(
				'system_article' => array(
					'title' => '站内公告',
					'url' => url('article/notice'),
					'icon' => 'wi wi-article',
					'permission_name' => 'system_article',
					'sub_permission' => array(
						array(
							'title' => '公告列表',
							'permission_name' => 'system_article_notice_list',
						),
						array(
							'title' => '公告分类',
							'permission_name' => 'system_article_notice_category',
						),
					),
				),
			),
			'founder' => true,
		),
		'system_template' => array(
			'title' => '模板',
			'menu' => array(
				'system_template' => array(
					'title' => '微官网模板',
					'url' => url('system/template'),
					'icon' => 'wi wi-wx-template',
					'permission_name' => 'system_template',
				),
			),
			'founder' => true,
		),

		
		'system_welcome' => array(
			'title' => '系统首页',
			'menu' => array(
				'system_welcome' => array(
					'title' => '系统首页应用',
					'url' => url('module/manage-system', array('support' => MODULE_SUPPORT_SYSTEMWELCOME_NAME)),
					'icon' => 'wi wi-system-welcome',
					'permission_name' => 'system_welcome',
				),
				'system_news' => array(
					'title' => '系统新闻',
					'url' => url('article/news'),
					'icon' => 'wi wi-article',
					'permission_name' => 'system_news',
					'sub_permission' => array(
						array(
							'title' => '新闻列表 ',
							'permission_name' => 'system_article_news_list',
						),
						array(
							'title' => '新闻分类 ',
							'permission_name' => 'system_article_news_category',
						),
					),
				),
			),
			'founder' => true,
		),
		

		
		'system_statistics' => array(
			'title' => '统计',
			'menu' => array(
				'system_account_analysis' => array(
					'title' => '访问统计',
					'url' => url('statistics/account'),
					'icon' => 'wi wi-statistical',
					'permission_name' => 'system_account_analysis',
				),
			),
			'founder' => true,
		),
		
		'cache' => array(
			'title' => '缓存',
			'menu' => array(
				'system_setting_updatecache' => array(
					'title' => '更新缓存',
					'url' => url('system/updatecache'),
					'icon' => 'wi wi-update',
					'permission_name' => 'system_setting_updatecache',
				),
			),
		),
	),
);

$we7_system_menu['site'] = array(
	'title' => '站点设置',
	'icon' => 'wi wi-system-site',
	'dimension' => 3,
	'url' => url('cloud/upgrade'),
	'section' => array(
/*		'cloud' => array(
			'title' => '云服务',
			'menu' => array(
				'system_profile' => array(
					'title' => '系统升级',
					'url' => url('cloud/upgrade'),
					'icon' => 'wi wi-cache',
					'permission_name' => 'system_cloud_upgrade',
				),
				'system_cloud_register' => array(
					'title' => '注册站点',
					'url' => url('cloud/profile'),
					'icon' => 'wi wi-registersite',
					'permission_name' => 'system_cloud_register',
				),
				'system_cloud_diagnose' => array(
					'title' => '云服务诊断',
					'url' => url('cloud/diagnose'),
					'icon' => 'wi wi-diagnose',
					'permission_name' => 'system_cloud_diagnose',
				),
			),
		),*/
		'setting' => array(
			'title' => '设置',
			'menu' => array(
				'system_setting_site' => array(
					'title' => '站点设置',
					'url' => url('system/site'),
					'icon' => 'wi wi-site-setting',
					'permission_name' => 'system_setting_site',
				),
				'system_setting_menu' => array(
					'title' => '菜单设置',
					'url' => url('system/menu'),
					'icon' => 'wi wi-menu-setting',
					'permission_name' => 'system_setting_menu',
				),
				'system_setting_attachment' => array(
					'title' => '附件设置',
					'url' => url('system/attachment'),
					'icon' => 'wi wi-attachment',
					'permission_name' => 'system_setting_attachment',
				),
				'system_setting_systeminfo' => array(
					'title' => '系统信息',
					'url' => url('system/systeminfo'),
					'icon' => 'wi wi-system-info',
					'permission_name' => 'system_setting_systeminfo',
				),
				'system_setting_logs' => array(
					'title' => '查看日志',
					'url' => url('system/logs'),
					'icon' => 'wi wi-log',
					'permission_name' => 'system_setting_logs',
				),
				'system_setting_ipwhitelist' => array(
					'title' => 'IP白名单',
					'url' => url('system/ipwhitelist'),
					'icon' => 'wi wi-ip',
					'permission_name' => 'system_setting_ipwhitelist',
				),
				'system_setting_sensitiveword' => array(
					'title' => '过滤敏感词',
					'url' => url('system/sensitiveword'),
					'icon' => 'wi wi-sensitive',
					'permission_name' => 'system_setting_sensitiveword',
				),
				'system_setting_thirdlogin' => array(
					'title' => '用户登录/注册设置',
					'url' => url('user/registerset'),
					'icon' => 'wi wi-user',
					'permission_name' => 'system_setting_thirdlogin',
				),
				'system_setting_oauth' => array(
					'title' => '全局借用权限',
					'url' => url('system/oauth'),
					'icon' => 'wi wi-oauth',
					'permission_name' => 'system_setting_oauth',
				),
			),
		),
		'utility' => array(
			'title' => '常用工具',
			'menu' => array(
				'system_utility_filecheck' => array(
					'title' => '系统文件校验',
					'url' => url('system/filecheck'),
					'icon' => 'wi wi-file',
					'permission_name' => 'system_utility_filecheck',
				),
				'system_utility_optimize' => array(
					'title' => '性能优化',
					'url' => url('system/optimize'),
					'icon' => 'wi wi-optimize',
					'permission_name' => 'system_utility_optimize',
				),
				'system_utility_database' => array(
					'title' => '数据库',
					'url' => url('system/database'),
					'icon' => 'wi wi-sql',
					'permission_name' => 'system_utility_database',
				),
				'system_utility_scan' => array(
					'title' => '木马查杀',
					'url' => url('system/scan'),
					'icon' => 'wi wi-safety',
					'permission_name' => 'system_utility_scan',
				),
				'system_utility_bom' => array(
					'title' => '检测文件BOM',
					'url' => url('system/bom'),
					'icon' => 'wi wi-bom',
					'permission_name' => 'system_utility_bom',
				),
				'system_utility_check' => array(
					'title' => '系统常规检测',
					'url' => url('system/check'),
					'icon' => 'wi wi-bom',
					'permission_name' => 'system_utility_check',
				),
			),
		),
		'backjob' => array(
			'title' => '后台任务',
			'menu' => array(
				'system_job' => array(
					'title' => '后台任务',
					'url' => url('system/job/display'),
					'icon' => 'wi wi-job',
					'permission_name' => 'system_job',
				),
			),
		),
	),
	'founder' => true,
);

$we7_system_menu['myself'] = array(
	'title' => '我的账户',
	'icon' => 'wi wi-bell',
	'dimension' => 2,
	'url' => url('user/profile'),
	'section' => array(),
);

$we7_system_menu['message'] = array(
	'title' => '消息管理',
	'icon' => 'wi wi-xiaoxi',
	'dimension' => 2,
	'url' => url('message/notice'),
	'section' => array(
		'message' => array(
			'title' => '消息管理',
			'menu' => array(
				'message_notice' => array(
					'title' => '消息提醒',
					'url' => url('message/notice'),
					'permission_name' => 'message_notice',
				),
				'message_setting' => array(
					'title' => '消息设置',
					'url' => url('message/notice/setting'),
					'permission_name' => 'message_setting',
				),
				'message_wechat_setting' => array(
					'title' => '微信提醒设置',
					'url' => url('message/notice/wechat_setting'),
					'permission_name' => 'message_wechat_setting',
					'founder' => true,
				),
			),
		),
	),
);

$we7_system_menu['account'] = array(
	'title' => '公众号',
	'icon' => 'wi wi-white-collar',
	'dimension' => 3,
	'url' => url('home/welcome/platform'),
	'section' => array(
		'platform' => array(
			'title' => '增强功能',
			'menu' => array(
				'platform_reply' => array(
					'title' => '自动回复',
					'url' => url('platform/reply'),
					'icon' => 'wi wi-reply',
					'permission_name' => 'platform_reply',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
					),
					'sub_permission' => array(
						'platform_reply_keyword' => array(
							'title' => '关键字自动回复',
							'url' => url('platform/reply', array('m' => 'keyword')),
							'permission_name' => 'platform_reply_keyword',
							'active' => 'keyword',
						),
						'platform_reply_special' => array(
							'title' => '非关键字自动回复',
							'url' => url('platform/reply', array('m' => 'special')),
							'permission_name' => 'platform_reply_special',
							'active' => 'special',
						),
						'platform_reply_welcome' => array(
							'title' => '首次访问自动回复',
							'url' => url('platform/reply', array('m' => 'welcome')),
							'permission_name' => 'platform_reply_welcome',
							'active' => 'welcome',
						),
						'platform_reply_default' => array(
							'title' => '默认回复',
							'url' => url('platform/reply', array('m' => 'default')),
							'permission_name' => 'platform_reply_default',
							'active' => 'default',
						),
						'platform_reply_service' => array(
							'title' => '常用服务',
							'url' => url('platform/reply', array('m' => 'service')),
							'permission_name' => 'platform_reply_service',
							'active' => 'service',
						),
						'platform_reply_userapi' => array(
							'title' => '自定义接口回复',
							'url' => url('platform/reply', array('m' => 'userapi')),
							'permission_name' => 'platform_reply_userapi',
							'active' => 'userapi',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
						'platform_reply_setting' => array(
							'title' => '回复设置',
							'url' => url('profile/reply-setting'),
							'permission_name' => 'platform_reply_setting',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
					),
				),
				'platform_menu' => array(
					'title' => '自定义菜单',
					'url' => url('platform/menu/post'),
					'icon' => 'wi wi-custommenu',
					'permission_name' => 'platform_menu',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
					),
					'sub_permission' => array(
						'platform_menu_default' => array(
							'title' => '默认菜单',
							'url' => url('platform/menu/post'),
							'permission_name' => 'platform_menu_default',
							'active' => 'post',
						),
						'platform_menu_conditional' => array(
							'title' => '个性化菜单',
							'url' => url('platform/menu/display', array('type' => MENU_CONDITIONAL)),
							'permission_name' => 'platform_menu_conditional',
							'active' => 'display',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
					),
				),
				'platform_qr' => array(
					'title' => '二维码/转化链接',
					'url' => url('platform/qr'),
					'icon' => 'wi wi-qrcode',
					'permission_name' => 'platform_qr',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
					),
					'sub_permission' => array(
						'platform_qr_qr' => array(
							'title' => '二维码',
							'url' => url('platform/qr/list'),
							'permission_name' => 'platform_qr_qr',
							'active' => 'list',
						),
						'platform_qr_statistics' => array(
							'title' => '二维码扫描统计',
							'url' => url('platform/qr/display'),
							'permission_name' => 'platform_qr_statistics',
							'active' => 'display',
						),
					),
				),
				'platform_masstask' => array(
					'title' => '定时群发',
					'url' => url('platform/mass'),
					'icon' => 'wi wi-crontab',
					'permission_name' => 'platform_masstask',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
					),
					'sub_permission' => array(
						'platform_masstask_post' => array(
							'title' => '定时群发',
							'url' => url('platform/mass/post'),
							'permission_name' => 'platform_masstask_post',
							'active' => 'post',
						),
						'platform_masstask_send' => array(
							'title' => '群发记录',
							'url' => url('platform/mass/send'),
							'permission_name' => 'platform_masstask_send',
							'active' => 'send',
						),
					),
				),
				'platform_material' => array(
					'title' => '素材/编辑器',
					'url' => url('platform/material'),
					'icon' => 'wi wi-redact',
					'permission_name' => 'platform_material',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
					),
					'sub_permission' => array(
						'platform_material_news' => array(
							'title' => '图文',
							'url' => url('platform/material', array('type' => 'news')),
							'permission_name' => 'platform_material_news',
							'active' => 'news',
						),
						'platform_material_image' => array(
							'title' => '图片',
							'url' => url('platform/material', array('type' => 'image')),
							'permission_name' => 'platform_material_image',
							'active' => 'image',
						),
						'platform_material_voice' => array(
							'title' => '语音',
							'url' => url('platform/material', array('type' => 'voice')),
							'permission_name' => 'platform_material_voice',
							'active' => 'voice',
						),
						'platform_material_video' => array(
							'title' => '视频',
							'url' => url('platform/material', array('type' => 'video')),
							'permission_name' => 'platform_material_video',
							'active' => 'video',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
						'platform_material_delete' => array(
							'title' => '删除',
							'permission_name' => 'platform_material_delete',
							'is_display' => false,
						),
					),
				),
				'platform_site' => array(
					'title' => '微官网-文章',
					'url' => url('site/multi'),
					'icon' => 'wi wi-home',
					'permission_name' => 'platform_site',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
					),
					'sub_permission' => array(
						'platform_site_multi' => array(
							'title' => '微官网',
							'url' => url('site/multi/display'),
							'permission_name' => 'platform_site_multi',
							'active' => 'multi',
						),
						'platform_site_style' => array(
							'title' => '微官网模板',
							'url' => url('site/style/template'),
							'permission_name' => 'platform_site_style',
							'active' => 'style',
						),
						'platform_site_article' => array(
							'title' => '文章管理',
							'url' => url('site/article/display'),
							'permission_name' => 'platform_site_article',
							'active' => 'article',
						),
						'platform_site_category' => array(
							'title' => '文章分类管理',
							'url' => url('site/category/display'),
							'permission_name' => 'platform_site_category',
							'active' => 'category',
						),
					),
				),
			),
			'permission_display' => array(
				ACCOUNT_TYPE_OFFCIAL_NORMAL,
				ACCOUNT_TYPE_OFFCIAL_AUTH,
				ACCOUNT_TYPE_XZAPP_NORMAL,
				ACCOUNT_TYPE_XZAPP_AUTH,
			),
		),
		'platform_module' => array(
			'title' => '应用模块',
			'menu' => array(),
			'is_display' => true,
		),
		'mc' => array(
			'title' => '粉丝',
			'menu' => array(
				'mc_fans' => array(
					'title' => '粉丝管理',
					'url' => url('mc/fans'),
					'icon' => 'wi wi-fansmanage',
					'permission_name' => 'mc_fans',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
					),
					'sub_permission' => array(
						'mc_fans_display' => array(
							'title' => '全部粉丝',
							'url' => url('mc/fans/display'),
							'permission_name' => 'mc_fans_display',
							'active' => 'display',
						),
						'mc_fans_fans_sync_set' => array(
							'title' => '粉丝同步设置',
							'url' => url('mc/fans/fans_sync_set'),
							'permission_name' => 'mc_fans_fans_sync_set',
							'active' => 'fans_sync_set',
						),
					),
				),
				'mc_member' => array(
					'title' => '会员管理',
					'url' => url('mc/member', array('version_id' => $_GPC['version_id'])),
					'icon' => 'wi wi-fans',
					'permission_name' => 'mc_member',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
						ACCOUNT_TYPE_WEBAPP_NORMAL,
					),
					'sub_permission' => array(
						'mc_member_diaplsy' => array(
							'title' => '会员管理',
							'url' => url('mc/member/display'),
							'permission_name' => 'mc_member_diaplsy',
							'active' => 'display',
						),
						'mc_member_group' => array(
							'title' => '会员组',
							'url' => url('mc/group/display'),
							'permission_name' => 'mc_member_group',
							'active' => 'display',
						),
						'mc_member_uc' => array(
							'title' => '会员中心',
							'url' => url('site/editor/uc'),
							'permission_name' => 'mc_member_uc',
							'active' => 'uc',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
						'mc_member_quickmenu' => array(
							'title' => '快捷菜单',
							'url' => url('site/editor/quickmenu'),
							'permission_name' => 'mc_member_quickmenu',
							'active' => 'quickmenu',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
						'mc_member_register_seting' => array(
							'title' => '注册设置',
							'url' => url('mc/member/register_setting'),
							'permission_name' => 'mc_member_register_seting',
							'active' => 'register_setting',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
						'mc_member_credit_setting' => array(
							'title' => '积分设置',
							'url' => url('mc/member/credit_setting'),
							'permission_name' => 'mc_member_credit_setting',
							'active' => 'credit_setting',
						),
						'mc_member_fields' => array(
							'title' => '会员字段管理',
							'url' => url('mc/fields/list'),
							'permission_name' => 'mc_member_fields',
							'active' => 'list',
						),
					),
				),
				'mc_message' => array(
					'title' => '留言管理',
					'url' => url('mc/message'),
					'icon' => 'wi wi-message',
					'permission_name' => 'mc_message',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
					),
				),
			),
			'permission_display' => array(
				ACCOUNT_TYPE_OFFCIAL_NORMAL,
				ACCOUNT_TYPE_OFFCIAL_AUTH,
				ACCOUNT_TYPE_XZAPP_NORMAL,
				ACCOUNT_TYPE_XZAPP_AUTH,
				ACCOUNT_TYPE_WEBAPP_NORMAL,
			),
		),
		'profile' => array(
			'title' => '配置',
			'menu' => array(
				'profile_setting' => array(
					'title' => '参数配置',
					'url' => url('profile/remote'),
					'icon' => 'wi wi-parameter-setting',
					'permission_name' => 'profile_setting',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
						ACCOUNT_TYPE_WEBAPP_NORMAL,
					),
					'sub_permission' => array(
						'profile_setting_remote' => array(
							'title' => '远程附件',
							'url' => url('profile/remote/display'),
							'permission_name' => 'profile_setting_remote',
							'active' => 'display',
						),
						'profile_setting_passport' => array(
							'title' => '借用权限',
							'url' => url('profile/passport/oauth'),
							'permission_name' => 'profile_setting_passport',
							'active' => 'oauth',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
						'profile_setting_tplnotice' => array(
							'title' => '微信通知设置',
							'url' => url('profile/tplnotice/list'),
							'permission_name' => 'profile_setting_tplnotice',
							'active' => 'list',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
						'profile_setting_notify' => array(
							'title' => '邮件通知参数',
							'url' => url('profile/notify/mail'),
							'permission_name' => 'profile_setting_notify',
							'active' => 'mail',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
						'profile_setting_upload_file' => array(
							'title' => '上传JS接口文件',
							'url' => url('profile/common/upload_file'),
							'permission_name' => 'profile_setting_upload_file',
							'active' => 'upload_file',
							'is_display' => array(
								ACCOUNT_TYPE_OFFCIAL_NORMAL,
								ACCOUNT_TYPE_OFFCIAL_AUTH,
							),
						),
					),
				),
				'profile_payment' => array(
					'title' => '支付参数',
					'url' => url('profile/payment'),
					'icon' => 'wi wi-pay-setting',
					'permission_name' => 'profile_payment',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_WEBAPP_NORMAL,
					),
					'sub_permission' => array(
						'profile_payment_pay' => array(
							'title' => '支付配置',
							'url' => url('profile/payment'),
							'permission_name' => 'profile_payment_pay',
							'active' => 'payment',
						),
						'profile_payment_refund' => array(
							'title' => '退款配置',
							'url' => url('profile/refund/display'),
							'permission_name' => 'profile_payment_refund',
							'active' => 'refund',
						),
					),
				),
				'profile_app_module_link' => array(
					'title' => '数据同步',
					'url' => url('profile/module-link-uniacid'),
					'is_display' => 1,
					'icon' => 'wi wi-data-synchro',
					'permission_name' => 'profile_app_module_link_uniacid',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
					),
				),
				
				'profile_bind_domain' => array(
					'title' => '域名绑定',
					'url' => url('profile/bind-domain'),
					'icon' => 'wi wi-bind-domain',
					'permission_name' => 'profile_bind_domain',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
					),
				),
				
				'webapp_module_link' => array(
					'title' => '数据同步',
					'url' => url('profile/module-link-uniacid'),
					'is_display' => 1,
					'icon' => 'wi wi-data-synchro',
					'permission_name' => 'webapp_module_link',
					'is_display' => array(
						ACCOUNT_TYPE_WEBAPP_NORMAL,
					),
				),
				'webapp_rewrite' => array(
					'title' => '伪静态',
					'url' => url('webapp/rewrite'),
					'icon' => 'wi wi-rewrite',
					'permission_name' => 'webapp_rewrite',
					'is_display' => array(
						ACCOUNT_TYPE_WEBAPP_NORMAL,
					),
				),
				
				'webapp_bind_domain' => array(
					'title' => '域名访问设置',
					'url' => url('webapp/bind-domain'),
					'icon' => 'wi wi-bind-domain',
					'permission_name' => 'webapp_bind_domain',
					'is_display' => array(
						ACCOUNT_TYPE_WEBAPP_NORMAL,
					),
				),
				
			),
			'permission_display' => array(
				ACCOUNT_TYPE_OFFCIAL_NORMAL,
				ACCOUNT_TYPE_OFFCIAL_AUTH,
				ACCOUNT_TYPE_XZAPP_NORMAL,
				ACCOUNT_TYPE_XZAPP_AUTH,
				ACCOUNT_TYPE_WEBAPP_NORMAL,
			),
		),
		
		'statistics' => array(
			'title' => '统计',
			'menu' => array(
				'statistics_visit' => array(
					'title' => '访问统计',
					'url' => url('statistics/app'),
					'icon' => 'wi wi-statistical',
					'permission_name' => 'statistics_visit',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
						ACCOUNT_TYPE_WEBAPP_NORMAL,
					),
					'sub_permission' => array(
						'statistics_visit_app' => array(
							'title' => 'app端访问统计信息',
							'url' => url('statistics/app/display'),
							'permission_name' => 'statistics_visit_app',
							'active' => 'app',
						),
						'statistics_visit_site' => array(
							'title' => '所有用户访问统计',
							'url' => url('statistics/site/current_account'),
							'permission_name' => 'statistics_visit_site',
							'active' => 'site',
						),
						'statistics_visit_setting' => array(
							'title' => '访问统计设置',
							'url' => url('statistics/setting/display'),
							'permission_name' => 'statistics_visit_setting',
							'active' => 'setting',
						),
					),
				),
				'statistics_fans' => array(
					'title' => '用户统计',
					'url' => url('statistics/fans'),
					'icon' => 'wi wi-statistical',
					'permission_name' => 'statistics_fans',
					'is_display' => array(
						ACCOUNT_TYPE_OFFCIAL_NORMAL,
						ACCOUNT_TYPE_OFFCIAL_AUTH,
						ACCOUNT_TYPE_XZAPP_NORMAL,
						ACCOUNT_TYPE_XZAPP_AUTH,
					),
				),
			),
			'permission_display' => array(
				ACCOUNT_TYPE_OFFCIAL_NORMAL,
				ACCOUNT_TYPE_OFFCIAL_AUTH,
				ACCOUNT_TYPE_XZAPP_NORMAL,
				ACCOUNT_TYPE_XZAPP_AUTH,
				ACCOUNT_TYPE_WEBAPP_NORMAL,
			),
		),
		
	),
);

$we7_system_menu['wxapp'] = array(
	'title' => '微信小程序',
	'icon' => 'wi wi-small-routine',
	'dimension' => 3,
	'url' => url('wxapp/display/home'),
	'section' => array(
		'wxapp_entrance' => array(
			'title' => '小程序入口',
			'menu' => array(
				'module_entrance_link' => array(
					'title' => '入口页面',
					'url' => url('wxapp/entrance-link'),
					'is_display' => array(
						ACCOUNT_TYPE_APP_NORMAL,
						ACCOUNT_TYPE_APP_AUTH,
						ACCOUNT_TYPE_WXAPP_WORK,
					),
					'icon' => 'wi wi-data-synchro',
					'permission_name' => 'wxapp_entrance_link',
				),
			),
			'permission_display' => array(
				ACCOUNT_TYPE_APP_NORMAL,
				ACCOUNT_TYPE_APP_AUTH,
				ACCOUNT_TYPE_WXAPP_WORK,
			),
		),
		'platform_module' => array(
			'title' => '应用',
			'menu' => array(),
			'is_display' => true,
		),
		'mc' => array(
			'title' => '粉丝',
			'menu' => array(
				'mc_member' => array(
					'title' => '会员',
					'url' => url('mc/member'),
					'is_display' => array(
						ACCOUNT_TYPE_APP_NORMAL,
						ACCOUNT_TYPE_APP_AUTH,
						ACCOUNT_TYPE_WXAPP_WORK,
					),
					'icon' => 'wi wi-fans',
					'permission_name' => 'mc_wxapp_member',
					'sub_permission' => array(
						'mc_member_diaplsy' => array(
							'title' => '会员管理',
							'url' => url('mc/member/display'),
							'permission_name' => 'mc_member_diaplsy',
							'active' => 'display',
						),
						'mc_member_group' => array(
							'title' => '会员组',
							'url' => url('mc/group/display'),
							'permission_name' => 'mc_member_group',
							'active' => 'display',
						),
						'mc_member_credit_setting' => array(
							'title' => '积分设置',
							'url' => url('mc/member/credit_setting'),
							'permission_name' => 'mc_member_credit_setting',
							'active' => 'credit_setting',
						),
						'mc_member_fields' => array(
							'title' => '会员字段管理',
							'url' => url('mc/fields/list'),
							'permission_name' => 'mc_member_fields',
							'active' => 'list',
						),
					),
				),
			),
			'permission_display' => array(
				ACCOUNT_TYPE_APP_NORMAL,
				ACCOUNT_TYPE_APP_AUTH,
				ACCOUNT_TYPE_WXAPP_WORK,
			),
		),
		'wxapp_profile' => array(
			'title' => '配置',
			'menu' => array(
				'wxapp_profile_module_link_uniacid' => array(
					'title' => '数据同步',
					'url' => url('wxapp/module-link-uniacid'),
					'is_display' => array(
						ACCOUNT_TYPE_APP_NORMAL,
						ACCOUNT_TYPE_APP_AUTH,
						ACCOUNT_TYPE_WXAPP_WORK,
						ACCOUNT_TYPE_PHONEAPP_NORMAL,
						ACCOUNT_TYPE_ALIAPP_NORMAL,
						ACCOUNT_TYPE_BAIDUAPP_NORMAL,
						ACCOUNT_TYPE_TOUTIAOAPP_NORMAL,
					),
					'icon' => 'wi wi-data-synchro',
					'permission_name' => 'wxapp_profile_module_link_uniacid',
				),
				'wxapp_profile_payment' => array(
					'title' => '支付参数',
					'url' => url('wxapp/payment'),
					'is_display' => array(
						ACCOUNT_TYPE_APP_NORMAL,
						ACCOUNT_TYPE_APP_AUTH,
						ACCOUNT_TYPE_WXAPP_WORK,
					),
					'icon' => 'wi wi-appsetting',
					'permission_name' => 'wxapp_profile_payment',
					'sub_permission' => array(
						'wxapp_payment_pay' => array(
							'title' => '支付参数',
							'url' => url('wxapp/payment/display'),
							'permission_name' => 'wxapp_payment_pay',
							'active' => 'payment',
						),
						'wxapp_payment_refund' => array(
							'title' => '退款配置',
							'url' => url('wxapp/refund/display'),
							'permission_name' => 'wxapp_payment_refund',
							'active' => 'refund',
						),
					),
				),
				'wxapp_profile_front_download' => array(
					'title' => $_W['account']['type_sign'] == 'wxapp' ? '上传微信审核' : '下载程序包',
					'url' => $_W['account']['type_sign'] == 'phoneapp' ? url('phoneapp/front-download') : url('wxapp/front-download'),
					'is_display' => 1,
					'icon' => 'wi wi-examine',
					'permission_name' => 'wxapp_profile_front_download',
				),
				'wxapp_profile_domainset' => array(
					'title' => '域名设置',
					'url' => url('wxapp/domainset', array('version_id' => $_GPC['version_id'])),
					'is_display' => array(
						ACCOUNT_TYPE_APP_NORMAL,
						ACCOUNT_TYPE_APP_AUTH,
						ACCOUNT_TYPE_WXAPP_WORK,
					),
					'icon' => 'wi wi-examine',
					'permission_name' => 'wxapp_profile_domainset',
				),
				'profile_setting_remote' => array(
					'title' => '参数配置',
					'url' => url('profile/remote'),
					'is_display' => array(
						ACCOUNT_TYPE_APP_NORMAL,
						ACCOUNT_TYPE_APP_AUTH,
						ACCOUNT_TYPE_WXAPP_WORK,
						ACCOUNT_TYPE_PHONEAPP_NORMAL,
						ACCOUNT_TYPE_ALIAPP_NORMAL,
						ACCOUNT_TYPE_BAIDUAPP_NORMAL,
						ACCOUNT_TYPE_TOUTIAOAPP_NORMAL,
					),
					'icon' => 'wi wi-parameter-setting',
					'permission_name' => 'profile_setting_remote',
				),
				'wxapp_profile_platform_material' => array(
					'title' => '素材管理',
					'is_display' => 0,
					'permission_name' => 'wxapp_profile_platform_material',
					'sub_permission' => array(
						array(
							'title' => '删除',
							'permission_name' => 'wxapp_profile_platform_material_delete',
						),
					),
				),
			),
			'permission_display' => array(
				ACCOUNT_TYPE_APP_NORMAL,
				ACCOUNT_TYPE_APP_AUTH,
				ACCOUNT_TYPE_WXAPP_WORK,
				ACCOUNT_TYPE_PHONEAPP_NORMAL,
				ACCOUNT_TYPE_ALIAPP_NORMAL,
				ACCOUNT_TYPE_BAIDUAPP_NORMAL,
				ACCOUNT_TYPE_TOUTIAOAPP_NORMAL,
			),
		),
		'statistics' => array(
			'title' => '统计',
			'menu' => array(
				'statistics_visit' => array(
					'title' => '访问统计',
					'url' => url('statistics/app'),
					'icon' => 'wi wi-statistical',
					'permission_name' => 'statistics_visit_wxapp',
					'is_display' => array(
						ACCOUNT_TYPE_APP_NORMAL,
						ACCOUNT_TYPE_APP_AUTH,
						ACCOUNT_TYPE_WXAPP_WORK,
						ACCOUNT_TYPE_PHONEAPP_NORMAL,
						ACCOUNT_TYPE_ALIAPP_NORMAL,
						ACCOUNT_TYPE_BAIDUAPP_NORMAL,
						ACCOUNT_TYPE_TOUTIAOAPP_NORMAL,
					),
					'sub_permission' => array(
						'statistics_visit_app' => array(
							'title' => 'app端访问统计信息',
							'url' => url('statistics/app/display'),
							'permission_name' => 'statistics_visit_app',
							'active' => 'app',
						),
						'statistics_visit_site' => array(
							'title' => '所有用户访问统计',
							'url' => url('statistics/site/current_account'),
							'permission_name' => 'statistics_visit_site',
							'active' => 'site',
						),
						'statistics_visit_setting' => array(
							'title' => '访问统计设置',
							'url' => url('statistics/setting/display'),
							'permission_name' => 'statistics_visit_setting',
							'active' => 'setting',
						),
					),
				),
				'statistics_fans' => array(
					'title' => '用户统计',
					'url' => url('wxapp/statistics'),
					'icon' => 'wi wi-statistical',
					'permission_name' => 'statistics_fans_wxapp',
					'is_display' => array(
						ACCOUNT_TYPE_APP_NORMAL,
						ACCOUNT_TYPE_APP_AUTH,
						ACCOUNT_TYPE_WXAPP_WORK,
					),
				),
			),
			'permission_display' => array(
				ACCOUNT_TYPE_APP_NORMAL,
				ACCOUNT_TYPE_APP_AUTH,
				ACCOUNT_TYPE_WXAPP_WORK,
				ACCOUNT_TYPE_PHONEAPP_NORMAL,
				ACCOUNT_TYPE_ALIAPP_NORMAL,
				ACCOUNT_TYPE_BAIDUAPP_NORMAL,
				ACCOUNT_TYPE_TOUTIAOAPP_NORMAL,
			),
		),
	),
);

$we7_system_menu['webapp'] = array(
	'title' => 'PC',
	'icon' => 'wi wi-pc',
	'url' => url('webapp/home/display'),
	'section' => array(),
);

$we7_system_menu['phoneapp'] = array(
	'title' => 'APP',
	'icon' => 'wi wi-white-collar',
	'url' => url('phoneapp/display/home'),
	'section' => array(
		'platform_module' => array(
			'title' => '应用',
			'menu' => array(),
			'is_display' => true,
		),
		'phoneapp_profile' => array(
			'title' => '配置',
			'menu' => array(
				'profile_phoneapp_module_link' => array(
					'title' => '数据同步',
					'url' => url('wxapp/module-link-uniacid'),
					'is_display' => array(
						ACCOUNT_TYPE_PHONEAPP_NORMAL,
					),
					'icon' => 'wi wi-data-synchro',
					'permission_name' => 'profile_phoneapp_module_link',
				),
				'front_download' => array(
					'title' => '下载APP',
					'url' => url('phoneapp/front-download'),
					'is_display' => true,
					'icon' => 'wi wi-examine',
					'permission_name' => 'phoneapp_front_download',
				),
			),
			'is_display' => true,
			'permission_display' => array(
				ACCOUNT_TYPE_PHONEAPP_NORMAL,
			),
		),
	),
);

$we7_system_menu['xzapp'] = array(
	'title' => '熊掌号',
	'icon' => 'wi wi-xzapp',
	'url' => url('xzapp/home/display'),
	'section' => array(
		'platform_module' => array(
			'title' => '应用模块',
			'menu' => array(),
			'is_display' => true,
		),
	),
);
$we7_system_menu['aliapp'] = array(
	'title' => '支付宝小程序',
	'icon' => 'wi wi-aliapp',
	'url' => url('miniapp/display/home'),
	'section' => array(
		'platform_module' => array(
			'title' => '应用',
			'menu' => array(),
			'is_display' => true,
		),
	),
);

$we7_system_menu['baiduapp'] = array(
	'title' => '百度小程序',
	'icon' => 'wi wi-baiduapp',
	'url' => url('miniapp/display/home'),
	'section' => array(
		'platform_module' => array(
			'title' => '应用',
			'menu' => array(),
			'is_display' => true,
		),
	),
);

$we7_system_menu['toutiaoapp'] = array(
	'title' => '头条小程序',
	'icon' => 'wi wi-toutiaoapp',
	'url' => url('miniapp/display/home'),
	'section' => array(
		'platform_module' => array(
			'title' => '应用',
			'menu' => array(),
			'is_display' => true,
		),
	),
);


	$we7_system_menu['store'] = array(
		'title' => '商城',
		'icon' => 'wi wi-store',
		'url' => url('home/welcome/ext', array('m' => 'store')),
		'section' => array(
			'store_goods' => array(
				'title' => '商品分类',
				'menu' => array(
					'store_goods_module' => array(
						'title' => '应用模块',
						'url' => url('site/entry/goodsbuyer', array('m' => 'store', 'direct' => 1, 'type' => 'module')),
						'icon' => 'wi wi-apply',
						'type' => 'module',
					),
					'store_goods_account' => array(
						'title' => '平台个数',
						'url' => url('site/entry/goodsbuyer', array('m' => 'store', 'direct' => 1, 'type' => 'account_num')),
						'icon' => 'wi wi-account',
						'type' => 'account_num',
					),
					'store_goods_account_renew' => array(
						'title' => '平台续费',
						'url' => url('site/entry/goodsbuyer', array('m' => 'store', 'direct' => 1,  'type' => 'renew')),
						'icon' => 'wi wi-appjurisdiction',
						'type' => 'renew',
					),
					'store_goods_package' => array(
						'title' => '应用权限组',
						'url' => url('site/entry/goodsbuyer', array('m' => 'store', 'direct' => 1,  'type' => STORE_TYPE_PACKAGE)),
						'icon' => 'wi wi-appjurisdiction',
						'type' => STORE_TYPE_PACKAGE,
					),
					'store_goods_users_package' => array(
						'title' => '用户权限组',
						'url' => url('site/entry/goodsbuyer', array('m' => 'store', 'direct' => 1, 'type' => STORE_TYPE_USER_PACKAGE)),
						'icon' => 'wi wi-userjurisdiction',
						'type' => STORE_TYPE_USER_PACKAGE,
					),
					'store_goods_api' => array(
						'title' => '应用访问流量(API)',
						'url' => url('site/entry/goodsbuyer', array('m' => 'store', 'direct' => 1,  'type' => STORE_TYPE_API)),
						'icon' => 'wi wi-api',
						'type' => STORE_TYPE_API,
					),
				),
			),
			'store_wish_goods' => array(
				'title' => '预购应用',
				'menu' => array(
					'store_wish_goods_list' => array(
						'title' => '应用列表',
						'founder' => false,
						'url' => url('site/entry/goodsbuyer', array('m' => 'store', 'direct' => 1, 'type' => 'module_wish', 'is_wish' => 1)),
						'icon' => 'wi wi-apply',
						'type' => 'module_wish',
					),
					'store_wish_goods_edit' => array(
						'title' => '添加/设置应用',
						'founder' => true,
						'url' => url('site/entry/wishgoodsEdit', array('m' => 'store', 'direct' => 1, 'op' => 'wishgoods', 'status' => 1)),
						'icon' => 'wi wi-goods-add',
						'type' => 'wish_goods_edit',
					),
				),
			),
			'store_manage' => array(
				'title' => '商城管理',
				'founder' => true,
				'menu' => array(
					'store_manage_goods' => array(
						'title' => '添加商品',
						'url' => url('site/entry/goodsSeller', array('m' => 'store', 'direct' => 1)),
						'icon' => 'wi wi-goods-add',
						'type' => 'goodsSeller',
					),
					'store_manage_setting' => array(
						'title' => '商城设置',
						'url' => url('site/entry/setting', array('m' => 'store', 'direct' => 1)),
						'icon' => 'wi wi-store',
						'type' => 'setting',
					),
					'store_manage_payset' => array(
						'title' => '支付设置',
						'url' => url('site/entry/paySetting', array('m' => 'store', 'direct' => 1)),
						'icon' => 'wi wi-money',
						'type' => 'paySetting',
					),
					'store_manage_permission' => array(
						'title' => '商城访问权限',
						'url' => url('site/entry/permission', array('m' => 'store', 'direct' => 1)),
						'icon' => 'wi wi-blacklist',
						'type' => 'blacklist',
					),
				),
			),
			'store_orders' => array(
				'title' => '订单管理',
				'menu' => array(
					'store_orders_my' => array(
						'title' => '我的订单',
						'url' => url('site/entry/orders', array('m' => 'store', 'direct' => 1)),
						'icon' => 'wi wi-sale-record',
						'type' => 'orders',
					),
					'store_cash_orders' => array(
						'title' => '分销订单',
						'vice_founder' => true,
						'url' => url('site/entry/cash', array('m' => 'store', 'operate' => 'cash_orders', 'direct' => 1)),
						'icon' => 'wi wi-order',
						'type' => 'cash_orders',
					),
				),
			),
			'store_payments' => array(
				'title' => '收入明细',
				'founder' => true,
				'menu' => array(
					'payments' => array(
						'title' => '收入明细',
						'url' => url('site/entry/payments', array('m' => 'store', 'direct' => 1)),
						'icon' => 'wi wi-sale-record',
						'type' => 'payments',
					),
				),
			),
			'store_cash_manage' => array(
				'title' => '分销管理',
				'founder' => true,
				'menu' => array(
					'store_manage_cash_setting' => array(
						'title' => '分销设置',
						'url' => url('site/entry/cashsetting', array('m' => 'store', 'direct' => 1)),
						'icon' => 'wi wi-site-setting',
						'type' => 'cashsetting',
					),
					'store_check_cash' => array(
						'title' => '提现审核',
						'url' => url('site/entry/cash', array('m' => 'store', 'operate' => 'consume_order', 'direct' => 1)),
						'icon' => 'wi wi-check-select',
						'type' => 'consume_order',
					),
				),
			),
			'store_cash' => array(
				'title' => '佣金管理',
				'vice_founder' => true,
				'menu' => array(
					'payments' => array(
						'title' => '我的佣金',
						'url' => url('site/entry/cash', array('m' => 'store', 'operate' => 'mycash', 'direct' => 1)),
						'icon' => 'wi wi-list',
						'type' => 'mycash',
					),
				),
			),
		),
	);






$we7_system_menu['custom_help'] = array(
	'title' => '本站帮助',
	'icon' => 'wi wi-bangzhu',
	'url' => url('help/display/custom'),
	'section' => array(),
);

return $we7_system_menu;
