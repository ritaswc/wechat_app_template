<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/23 14:02
 */


namespace app\controllers\cloud;


use app\behaviors\CloudBehavior;
use app\hejiang\cloud\Cloud;
use app\hejiang\cloud\CloudApi;
use app\hejiang\cloud\Config;
use app\hejiang\cloud\forms\UpdateAuthInfoForm;

class IndexController extends CloudController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'cloudBehavior' => [
                'class' => CloudBehavior::className(),
                'ignores' => ['test-site'],
            ],
        ]);
    }

    public function beforeAction($action)
    {
        if ($action->id === 'test-site') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $res = Cloud::getHostInfo();
        $res['remoteTestSiteUrl'] = Config::BASE_URL . CloudApi::TEST_SITE;
        if ($res['code'] === 0) {
            $res['localTestSiteUrl'] = $res['data']['host']['protocol']
                . $res['data']['host']['domain']
                . \Yii::$app->urlManager->createUrl(['cloud/index/test-site']);
            $localAuthInfo = Cloud::getLocalAuthInfo();
            if ($localAuthInfo && !empty($localAuthInfo['domain'])) {
                $res['localDomain'] = $localAuthInfo['domain'];
            } else {
                $res['localDomain'] = \Yii::$app->request->getHostName();
            }
        } else {
            $res['localTestSiteUrl'] = '';
        }
        return $this->render('index', $res);
    }

    public function actionUpdateAuthInfo()
    {
        $form = new UpdateAuthInfoForm();
        $form->attributes = \Yii::$app->request->post();
        return $form->save();
    }

    public function actionTestSite()
    {
        return \Yii::$app->request->post('data');
    }
}
