<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/31
 * Time: 18:12
 */

namespace app\models;

use yii\helpers\VarDumper;

class HomePageModule extends Model
{
    public $store_id;
    public $userAuth;

    private $module_list = [
        // 这些是必须有的，无需进行权限判断
        'mustModule' => [
            [
                'name' => 'banner',
                'display_name' => '轮播图',
            ],
            [
                'name' => 'search',
                'display_name' => '搜索框',
            ],
            [
                'name' => 'nav',
                'display_name' => '导航图标',
            ],
            [
                'name' => 'cat',
                'display_name' => '所有分类',
            ],
            [
                'name' => 'notice',
                'display_name' => '公告',
            ]
        ],
        // 需要根据插件权限来显示，根据key值
        'authModule' => [
            [
                'key' =>  'topic',
                'name' => 'topic',
                'display_name' => '专题',
            ],
            [
                'key' => 'coupon',
                'name' => 'coupon',
                'display_name' => '领券中心',
            ],
            [
                'key' => 'miaosha',
                'name' => 'miaosha',
                'display_name' => '整点秒杀',
            ],
            [
                'key' => 'pintuan',
                'name' => 'pintuan',
                'display_name' => '拼团',
            ],
            [
                'key' => 'video',
                'name' => 'video',
                'display_name' => '视频',
            ],
            [
                'key' => 'book',
                'name' => 'yuyue',
                'display_name' => '预约',
            ],
            [
                'key' => 'mch',
                'name' => 'mch',
                'display_name' => '好店推荐',
            ],
        ]
    ];

    private function getModuleList()
    {
        $mustModule = $this->module_list['mustModule'];
        $authModule = $this->module_list['authModule'];

        $newArr = [];
        foreach ($authModule as $item) {
            if (in_array($item['key'], $this->userAuth)) {
                $newArr[] = $item;
            }
        }

        $module = array_merge($mustModule, $newArr);

        return $module;
    }


    /**
     * 获取首页模块列表
     * @param bool $store_module_list 是否获取本商城已设置的模块列表（否则获取所有可用模块）
     * @param bool $with_content
     * @return array|mixed
     */
    public function search($store_module_list = false, $with_content = true)
    {
        if ($store_module_list) {
            $store = Store::findOne($this->store_id);
            $module_list = json_decode($store->home_page_module, true);
            $module_list = $module_list ? $module_list : [];
        } else {
            $module_list = $this->getModuleList();
            $module_list = array_merge($module_list, $this->getCatList());
            $module_list = array_merge($module_list, $this->getBlockList());
        }
        foreach ($module_list as $i => $item) {
            $content = $this->getContent($item['name']);
            $module_list[$i]['content'] = $content ? $content : '<div style="padding: 1rem;text-align: center;color: #888">无内容</div>';
        }

        return $module_list;
    }

    private function getCatList()
    {
        $list = Cat::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'parent_id' => 0,
        ])->orderBy('addtime DESC')->select('id,name')->all();
        $new_list = [];
        foreach ($list as $item) {
            $new_list[] = [
                'name' => 'single_cat-' . $item->id,
                'display_name' => $item->name,
            ];
        }
        return $new_list;
    }

    private function getBlockList()
    {
        $list = HomeBlock::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0,
        ])->orderBy('addtime DESC')->all();
        $new_list = [];
        foreach ($list as $item) {
            $new_list[] = [
                'name' => 'block-' . $item->id,
                'display_name' => $item->name,
            ];
        }
        return $new_list;
    }

    private function getContent($name)
    {
        $content = false;
        switch ($name) {
            case 'banner': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'search': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'nav': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'cat': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'coupon': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'topic': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'miaosha': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'pintuan': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'notice': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'yuyue': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            case 'mch': {
                $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                break;
            }
            default: {
                $names = explode('-', $name);
                $name = $names[0];
                $id = $names[1];
                if ($name == 'block') {//自定义首页板块
                    $block = HomeBlock::findOne($id);
                    $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php', [
                        'block' => $block,
                    ]);
                }
                if ($name == 'single_cat') {//单个分类
                    $cat = Cat::findOne($id);
                    $content = \Yii::$app->view->render('/store/home-page-module/cat.php', [
                        'cat' => $cat,
                    ]);
                }
                if ($name == 'video') {
                    $content = \Yii::$app->view->render('/store/home-page-module/' . $name . '.php');
                }
                break;
            }
        }
        return $content;
    }

    /**
     * 获取自定义信息
     */
    public function search_1()
    {
        $data = Option::get('home_page_data', $this->store_id, 'app');
        if (!$data) {
            $data = $this->getDefaultData();
        } else {
            $data = json_decode($data, true);
            $data = $this->getDefaultData($data);
        }
//        var_dump($data);exit();
        return $data;
    }

    public function getDefaultData($data = null)
    {
        $list = [
            'topic' => [
                'logo_2' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/home-page/icon-topic.png',
                'logo_1' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/home-page/icon-topic-1.png',
                'heated' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/home-page/icon-topic-r.png',
                'count' => 1,
            ],
            'notice' => [
                'name' => '公告',
                'bg_color' => '#f67f79',
                'color' => '#ffffff',
                'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/home-page/icon-notice.png'
            ],
            'video' => [
                [
                    'name' => 0,
                    'url' => '',
                    'pic_url' => '',
                ]
            ],
            'coupon' => [
                'bg' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/home-page/icon-coupon-index.png',
                'bg_1' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/home-page/icon-coupon-no.png',
            ]
        ];
        if ($data) {
            $new_list = self::checkData($data, $list);
        } else {
            $new_list = $list;
        }
        return $new_list;
    }

    /**
     * @param array $data 已存数据数组
     * @param array $list 默认数组
     */
    public function checkData($data = array(), $list = array())
    {
        $new_list = [];
        foreach ($list as $index => $value) {
            if (isset($data[$index])) {
                if (is_array($data[$index])) {
                    if (in_array($index, ['video'])) {
                        $new_list[$index][] = $list[$index][0];
                        foreach ($data[$index] as $k => $v) {
                            $value[0]['name'] += 1;
                            if (is_array($v)) {
                                $new_list[$index][] = self::checkData($data[$index][$k], $value[0]);
                            } else {
                                $new_list[$index][] = self::checkData($data[$index], $value[0]);
                            }
                        }
                    } else {
                        $new_list[$index] = self::checkData($data[$index], $list[$index]);
                    }
                } else {
                    $new_list[$index] = $data[$index];
                }
            } else {
                $new_list[$index] = $list[$index];
            }
        }
        return $new_list;
    }

    public function search_2()
    {
        return [
            'my-topic' => \Yii::$app->view->render('/store/home-page-edit/topic.php'),
            'my-notice' => \Yii::$app->view->render('/store/home-page-edit/notice.php'),
        ];
    }
}
