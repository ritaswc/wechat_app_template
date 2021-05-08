<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/11 10:54
 */


namespace app\controllers;


class CustomServiceController extends Controller
{
    public function actionValidateToken($store_id, $token, $mch_id = null, $user_id = null)
    {
        return [
            'code' => 0,
            'msg' => 'success',
        ];
    }
}
