<?php
namespace app\modules\api\models\step;

use app\hejiang\ApiCode;
use app\modules\api\models\ApiModel;
use app\hejiang\ApiResponse;
use app\modules\api\models\wxbdc\WXBizDataCrypt;
use Curl\Curl;
use app\models\StepInvite;
use app\models\StepUser;
use app\models\Goods;
use app\models\StepSetting;
use app\models\StepLog;
use app\models\StepActivity;
use app\models\Banner;
use app\models\Ad;
use app\utils\GetInfo;

class IndexForm extends ApiModel
{
    public $wechat_app;

    public $code;
    public $encrypted_data;
    public $iv;
    public $store_id;
    public $user;

    public $parent_id;
    public $limit;
    public $page;

    public $type;
    public $user_id;
    public $status;
    public $currency;
    public $goods_id;

    public $num;
    public $activity_id;
    public $user_info;

    public function rules()
    {
        return [
            [['limit', 'page', 'store_id', 'num', 'activity_id'], 'integer'],
            [['wechat_app','code', 'encrypted_data', 'iv'], 'required'],
            [['parent_id'], 'default', 'value' => 0],
            [['limit'], 'default', 'value' => 6],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function index()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->user_info = StepUser::find()->where([
                'store_id' => $this->store_id,
                'user_id' => $this->user->id,
                'is_delete' => 0
            ])->asArray()->one();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'banner_list' => $this->getBanner(),
                'run_data' => $this->runData(),
                'user_data' => $this->userData(),
                'goods_data' => $this->goodsData(),
                'activity_data' => $this->activityData(),
                'ad_data' => $this->adData(1)
            ],
        ];
    }

    public function activity()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        };
        $date = date('Y-m-d', time());
        $user = StepUser::findOne([
                'user_id' => $this->user->id,
                'is_delete' => 0,
                'store_id' => $this->store_id
            ]);

        if (!$user) {
            $user = $this->newUser();
        }

        $next = $this->table(['AND',['l.type' => 0],['l.is_delete' => 0],['l.status' => 1],['l.store_id' => $this->store_id],['>=','l.open_date',$date]], $user->id);

        $data = $this->runData();
        if ($data->code == 1) {
            return $data;
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'list' => [
                'activity_data' => $next,
                'run_data' => end($data['stepInfoList'])['step'],
                'ad_data' => $this->adData(2)
            ],
        ];
    }

    public function goods()
    {
        if (!$this->user->id) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '尚未授权'
            ];
        }
        $user = StepUser::findOne([
            'user_id' => $this->user->id,
            'is_delete' => 0,
            'store_id' => $this->store_id
        ]);
        if (!$user) {
            $user = $this->newUser();
        }
        
        $goods_id = $this->goods_id;

        $query = Goods::find()->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'id' => $goods_id,
                'type' => 5,
            ]);
        $goods = $query->one();

        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在'
            ];
        }
        // 获取视频链接
        $resUrl = GetInfo::getVideoInfo($goods->video_url);

        $newGoods = [
           'pic_list' => $goods->goodsPicList,
           'attr_pic' =>  $goods->goodsPicList[0]['pic_url'],
           'attr_group_list' => $goods->getAttrGroupList(),
           'video_url' => $resUrl['url'],
           'name' => $goods->name,
           'attr' => $goods->attr,
           'num' => $goods->getNum(),
           'detail' => $goods->detail,
           'id' => $goods->id,
           'use_attr' => $goods->use_attr,
           'price' => number_format(floatval($goods->price), 2, '.', ''),
           'original_price' => number_format(floatval($goods->original_price), 2, '.', ''),

        ];
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'goods' => $newGoods
            ],
        ];
    }
    
    private function table($where, $user_id)
    {
        $cuQuery = StepLog::find()->where([
                    'store_id' => $this->store_id,
                    'status' => 2,
                    'type' => 2,
                ])->andWhere('type_id = l.id')->select('sum(step_currency)');
        $awQuery = StepLog::find()->where([
                    'store_id' => $this->store_id,
                    'status' => 2,
                    'type' => 2,
                ])->andWhere('type_id = l.id and num >= l.step_num')->select('sum(1)');

        $peQuery = StepLog::find()->where([
                    'store_id' => $this->store_id,
                    'status' => 2,
                    'type' => 2,
                ])->andWhere('type_id = l.id')->select('sum(1)');

        $query = StepActivity::find()->alias('l')->select(["l.*","award_num" => $awQuery,"currency_num" => $cuQuery,"people_num" => $peQuery])->where($where)
        ->with(['log' => function ($query) use ($user_id) {
            $query->where([
                'store_id' => $this->store_id,
                'step_id' => $user_id,
                'type' => 2
            ])->orderBy('status desc');
        }]);

        $this->limit = 3;
        $offset = $this->limit * ($this->page - 1);
        $one = $query->orderBy('open_date asc')->limit($this->limit)->offset($offset)->asArray()->all();

        foreach ($one as &$item) {
            $item['award_num'] = $item['award_num'] ? $item['award_num'] : 0; //达标人数
            $item['currency_num'] = floor(($item['currency_num'] + $item['currency']) * 100) / 100; // 奖金池
            $item['people_num'] = $item['people_num'] ? $item['people_num'] : 0;   // 参与人数
            $item['log_status'] = $this->deep_in_array($item['log'], $item['step_num']);
        };
        unset($item);

        return $one;
    }

    public function setting()
    {
        $setting = StepSetting::find()->where(['store_id' => $this->store_id])->asArray()->one();
   
        $keyword_arr = explode("\r\n", trim($setting['share_title']));
        $setting['share_title'] = $keyword_arr[array_rand($keyword_arr)];
        return new ApiResponse(0, '', $setting);
    }

    public function activitySubmit()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        };
        $data = $this->runData();
        if ($data->code == 1) {
            return $data;
        }
        $num = $this->num;
        $activity_id = $this->activity_id;

        $max_num = end($data['stepInfoList'])['step'];
        if ($num > $max_num) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '步数异常提交'
            ];
        };
        $user = StepUser::findOne([
                'store_id' => $this->store_id,
                'user_id' => $this->user->id,
                'is_delete' => 0,
            ]);

        $date = date('Y-m-d', time());
        $activity = StepActivity::findOne([
                'id' => $activity_id,
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'open_date' => $date,
                'status' => 1,
                'type' => 0,
            ]);
        if (!$activity) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '活动已过期或不存在'
            ];
        }
        $log = StepLog::findOne([
                'store_id' => $this->store_id,
                'step_id' => $user->id,
                'status' => 2,
                'type' => 2,
                'type_id' => $activity_id,
            ]);
        if ($log) {
            $log->num = $num;
            if ($log->save()) {
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'data' => $num,
                ];
            } else {
                return $this->getErrorResponse($log);
            }
        } else {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '记录不存在'
            ];
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        };
        $data = $this->runData();
        if ($data->code == 1) {
            return $data;
        }
        $num = $this->num;

        $max_num = end($data['stepInfoList'])['step'];
        if ($num > $max_num) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '步数异常'
            ];
        }

        $user = StepUser::findOne([
                'store_id' => $this->store_id,
                'user_id' => $this->user->id,
                'is_delete' => 0,
            ]);
        if (!$user) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '用户不存在'
            ];
        }

        $create_time = strtotime(date("Y-m-d"));
        $invite = StepUser::find()->select("SUM(invite_ratio) as ratio")->where([
                'AND',
                ['store_id' => $this->store_id],
                ['parent_id' => $user->id],
                ['>','create_time',$create_time]
            ])->one();
        $ratio = $user->ratio;
        if ($invite) {
            $ratio = $ratio - $invite->ratio;
        };
        //实际概率
        $new_ratio = $ratio / 1000;

        $num = floor($num + $num * $new_ratio);

        $log = StepLog::find()->select("SUM(num) as num")->where([
                'AND',
                ['store_id' => $this->store_id],
                ['type' => 0],
                ['status' => 1],
                ['>','create_time',$create_time],
                ['step_id' => $user->id]
            ])->one();
        $log['num'] = $log['num'] ? $log['num'] : 0;
        $num = $num - $log['num'];

        $setting = $this->setting()->data;
        $convert_max = $setting['convert_max'];
        $convert_ratio = $setting['convert_ratio'];



        if ($convert_max && $num >= $convert_max - $log['num']) {
            $num = $convert_max - $log['num'];
        };
        //兑换额
        $new_currency = floor($num / $convert_ratio * 100) / 100;
        if ($new_currency < 0.01) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '步数不足'
            ];
        }

        $t = \Yii::$app->db->beginTransaction();
        $model = new StepLog();
        $model->create_time = time();
        $model->store_id = $this->store_id;
        $model->step_id = $user->id;
        $model->status = 1;
        $model->type = 0;
        $model->num = $num;
        $model->step_currency = $new_currency;
        $model->save();

        $user->step_currency = $user->step_currency + $new_currency;
        if ($user->save()) {
            $t->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'list' => [
                    'num' => $num,
                    'convert' => $new_currency,
                ]
            ];
        } else {
            $t->rollBack();
            return $this->getErrorResponse($user);
        }
    }

    private function adData($type)
    {
        $ad = Ad::findOne(['store_id' => $this->store_id, 'type' => $type,'is_delete' => 0, 'status' => 1]);
        return $ad;
    }

    private function getBanner()
    {
        $banner = Banner::find()->where(['store_id' => $this->store_id, 'type' => 6, 'is_delete' => 0])->orderBy('sort ASC')->asArray()->all();
        return $banner;
    }

    private function activityData()
    {
        $user = $this->user_info;

        $data = StepActivity::find()->alias('a')->where([
                'AND',
                ['store_id' => $this->store_id],
                ['is_delete' => 0],
                ['type' => 0],
                ['status' => 1],
                ['>=', 'open_date', date('Y-m-d', time())]
            ])
        ->with(['log' => function ($query) use ($user) {
                $query->where([
                    'store_id' => $this->store_id,
                    'type' => 2,
                    'step_id' => $user['id']
                    ]);
        }])->orderBy('open_date asc')->limit(2)->asArray()->all();

        $data = array_reverse($data);
        $date = date('Y-m-d', time());
        $array = [];
        foreach ($data as $v) {
            if ($v['open_date'] == $date && $v['log']) {
                $array = $v;
            } elseif ($v['open_date'] == $date) {
                if (!$array) {
                    $array = $v;
                }
                break;
            } else {
                $array = $v;
            }
        }
        $list = $array;
        if ($list) {
            $query = StepLog::find()->where(['store_id' => $this->store_id,'type' => 2,'status' => 2,'type_id' => $list['id']]);
            $list['people_num'] = $query->count();
            $list['log_status'] = $this->deep_in_array($list['log'], $list['step_num']);

            if ($list['log_status'] == 4) {
                $suc = $query->select("SUM(step_currency) as currency")->asArray()->one();
                $list['suc_currency'] = floor(($suc['currency'] + $list['currency']) * 100) / 100;
                $list['suc_num'] = $query->andWhere(['>=','num',$list[step_num]])->count();
            }
            $list['open_date'] = str_replace('-', '.', $list['open_date']);
        }

        return $list;
    }

    private function deep_in_array($item, $step_num, &$user_num = null, &$user_currency = null)
    {
        $user_num  = 0; //用户记录步数
        $user_currency = 0; // 奖励金币
        if (!$item) {
            return 4; //未参与
        }
        foreach ($item as $v) {
            if ($v['status'] == 1) {
                $user_currency = $v['step_currency'];
                return 1; //成功兑换
            };
            $user_num = max($user_num, $v['num']);
        };
        if ($user_num >= $step_num) {
            return 2; //已达标
        } else {
            return 3; // 已参与
        }
    }

    private function goodsData()
    {
        $query = Goods::find()->where([
            'type' => 5, 'is_delete' => 0, 'status' => 1, 'store_id' => $this->store_id, 'mch_id' => 0]);

        $offset = $this->limit * ($this->page - 1);
        $list = $query->orderBy(['sort' => SORT_ASC])->limit($this->limit)->offset($offset)->all();
        return $list;
    }

    private function userData()
    {
        $model = $this->user_info;
        if ($model) {
            $create_time = strtotime(date("Y-m-d"));

            $query = StepUser::find()->alias('i')->select('i.id,u.nickname,u.avatar_url,i.create_time,i.invite_ratio')->where([
                'i.store_id' => $this->store_id,
                'i.parent_id' => $model['id'],
                'i.is_delete' => 0,
            ])->joinWith(['user u']);

            $model['all_invite'] = $query->count();//总邀请数

            $invite = $query->orderBy('create_time desc')->groupBy('i.user_id')->limit(4)->asArray()->all();//邀请列表

            $ratio = StepUser::find()->select("SUM(invite_ratio) as now_ratio,count(id) as now_invite")->where([
                'AND',
                ['store_id' => $this->store_id],
                ['parent_id' => $model['id']],
                ['is_delete' => 0],
                ['>','create_time',$create_time]
                ])->asArray()->one();
            $model['now_ratio'] = $model['ratio'] - $ratio['now_ratio'];//今天加成数
            $model['now_invite'] = $ratio['now_invite']; //今日邀请数

            $model['invite_list'] = $invite;
            $log = StepLog::find()->select("SUM(num) as num")->where([
                'AND',
                ['store_id' => $this->store_id],
                ['type' => 0],
                ['status' => 1],
                ['>','create_time',$create_time],
                ['step_id' => $model['id']]
            ])->one();
            $model['convert_num'] = $log['num'];
        } else {
            if (!$this->user->id) {
                return true;
            }
            $model = $this->newUser();
        };

        return $model;
    }

    private function newUser()
    {
        $t = \Yii::$app->db->beginTransaction();
        $model = new StepUser();
        if ($this->parent_id) {
            $parent = StepUser::findOne([
                    'store_id' => $this->store_id,
                    'user_id' => $this->parent_id,
                    'is_delete' => 0
                ]);
            if ($parent && $parent->user_id != $this->user->id) {
                $invite_ratio = $this->setting()->data['invite_ratio'];
                $parent->ratio = $parent->ratio + $invite_ratio;
                $parent->save();
                $model->invite_ratio = $invite_ratio ? $invite_ratio : 0;
                $model->parent_id = $parent->id;
            }
        }
        $model->user_id = $this->user->id;
        $model->store_id = $this->store_id;
        $model->create_time = time();
        if (!$model->save()) {
            $t->rollBack();
            return $this->getErrorResponse($model);
        }
        $model->save();

        $query = StepUser::find()->where(['store_id' => $this->store_id,'user_id' => $this->user->id,'is_delete' => 0]);
        $count = $query->count();

        if ($count > 1) {
            $t->rollBack();
        } else {
            $t->commit();
        }
        $list = $query->asArray()->one();
        $list['invite_list'] = [];
        $list['all_invite'] = 0;//总邀请数
        $list['now_invite'] = 0; //今日邀请数
        return $list;
    }

    private function runData()
    {
        $res = $this->getOpenid($this->code);

        if (!$res || empty($res['openid'])) {
            return new ApiResponse(1, '获取用户OpenId失败', $res);
        }

        $session_key = $res['session_key'];
        $pc = new WXBizDataCrypt($this->wechat_app->app_id, $session_key);
        $errCode = $pc->decryptData($this->encrypted_data, $this->iv, $data);
        return json_decode($data, true);
    }

    private function getOpenid($code)
    {
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->wechat_app->app_id}&secret={$this->wechat_app->app_secret}&js_code={$code}&grant_type=authorization_code";
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($api);
        $res = $curl->response;
        $res = json_decode($res, true);
        return $res;
    }
}
