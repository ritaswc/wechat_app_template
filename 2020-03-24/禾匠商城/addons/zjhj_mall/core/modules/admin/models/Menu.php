<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\admin\models;

class Menu
{
    public static function getMenu()
    {
        return [
            [
                'name' => '账户管理',
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => '我的账户',
                        'route' => 'admin/user/me',
                        'icon' => 'icon-person',
                        'is_admin' => false,
                    ],
                    [
                        'name' => '账户列表',
                        'route' => 'admin/user/index',
                        'icon' => 'icon-liebiao',
                        'is_admin' => true,
                    ],
                    [
                        'name' => '新增子账户',
                        'route' => 'admin/user/edit',
                        'icon' => 'icon-add1',
                        'is_admin' => true,
                    ],
                    [
                        'name' => '注册审核',
                        'route' => 'admin/user/register',
                        'icon' => 'icon-liebiao',
                        'is_admin' => true,
                    ],
                ]
            ],
            [
                'name' => '小程序商城',
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => '我的小程序商城',
                        'route' => 'admin/app/index',
                        'icon' => 'icon-shanghu',
                        'is_admin' => false,
                        'sub' => [
                            [
                                'name' => '添加编辑小程序',
                                'route' => 'admin/app/edit',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '进入小程序后台',
                                'route' => 'admin/app/entry',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '删除小程序商城',
                                'route' => 'admin/app/delete',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '小程序回收站',
                                'route' => 'admin/app/recycle',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '设置回收站',
                                'route' => 'admin/app/set-recycle',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '小程序禁用',
                                'route' => 'admin/app/disabled',
                                'is_admin' => false,
                            ],
                        ]
                    ],
                    [
                        'name' => '回收站',
                        'route' => 'admin/app/recycle',
                        'icon' => 'icon-huishouzhan',
                        'is_admin' => false,
                    ],
                    [
                        'name' => '子账户的商城',
                        'route' => 'admin/app/other-app',
                        'icon' => 'icon-xiaochengxu4',
                        'is_admin' => true,
                    ],
                ]
            ],
            [
                'name' => '设置',
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => '系统设置',
                        'route' => 'admin/setting/index',
                        'icon' => 'icon-settings',
                        'is_admin' => true,
                    ],
                    [
                        'name' => '上传设置',
                        'icon' => 'icon-shangchuan',
                        'route' => 'admin/system/upload',
                        'is_admin' => true
                    ],
                    [
                        'name' => '更新',
                        'route' => 'admin/update/index',
                        'icon' => 'icon-upgrade',
                        'is_admin' => true,
                    ],
                    [
                        'name' => '清理缓存',
                        'route' => 'admin/cache/index',
                        'icon' => 'icon-qinglihuancun',
                        'is_admin' => false,
                    ],
                    [
                        'name' => '版权管理',
                        'route' => 'admin/admin/copyright-list',
                        'icon' => 'icon-banquan1',
                        'is_admin' => true,
                    ],
                    [
                        'name' => '数据库优化',
                        'route' => 'admin/system/db-optimize',
                        'icon' => 'icon-database',
                        'is_admin' => true,
                    ],
                    [
                        'name' => '超限设置',
                        'route' => 'admin/system/overrun',
                        'icon' => 'icon-database',
                        'is_admin' => true,
                    ],
                ]
            ],
        ];
    }
}
