<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/6 15:00
 */


namespace app\modules\mch\controllers;

use app\models\common\CommonFormId;
use app\models\FormId;
use app\models\tplmsg\BindWechatPlatform;
use app\models\User;
use app\modules\mch\models\wechatplatform\AddTplForm;
use app\modules\mch\models\wechatplatform\SendMsgForm;
use yii\data\Pagination;

class WechatPlatformController extends Controller
{

    //参数配置
    public function actionSetting()
    {
        $form = new BindWechatPlatform();
        $form->store_id = $this->store->id;
        if (\Yii::$app->request->isPost) {
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        } else {
            $data = $form->search();
            return $this->render('setting', [
                'data' => $data
            ]);
        }
    }

    //群发、发送模板消息
    public function actionSendMsg()
    {
        if (\Yii::$app->request->isPost) {
            $form = new SendMsgForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            return $form->send();
        } else {
            if (\Yii::$app->request->isAjax) {
                $form = new BindWechatPlatform();
                $form->store_id = $this->store->id;
                return [
                    'code' => 0,
                    'data' => [
                        'tpl_list' => $form->getTplList(),
                    ],
                ];
            } else {
                return $this->render('send-msg');
            }
        }
    }

    public function actionAddTpl()
    {
        $form = new AddTplForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        return $form->save();
    }

    public function actionDeleteTpl($id)
    {
        $form = new BindWechatPlatform();
        $form->store_id = $this->store->id;
        $form->deleteTpl($id);
        return [
            'code' => 0,
        ];
    }

    public function actionSearchUser($keyword = null, $page = 1)
    {
        $keyword = trim($keyword);
        $query = User::find()->where([
            'AND',
            ['is_delete' => 0,],
            ['store_id' => $this->store->id,],
        ]);

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['id' => $keyword,],
                ['LIKE', 'nickname', $keyword,],
                ['LIKE', 'wechat_platform_open_id', $keyword,],
            ]);
        }
        $query->select('id,nickname,avatar_url,wechat_open_id');
        $count = $query->count('1');
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10, 'page' => $page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();

        foreach ($list as &$item) {
            $formId = CommonFormId::getFormId($item['wechat_open_id']);
            $item['form_id'] = $formId->form_id;
        }

        return [
            'code' => 0,
            'data' => [
                'list' => $list,
            ],
        ];
    }

    public function actionUserList()
    {
        $form = new SendMsgForm();
        $res = $form->getUserList();

        return $res;
    }
}
