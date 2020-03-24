<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property integer $addtime
 * @property integer $is_delete
 * @property integer $app_max_count
 * @property string $permission
 * @property string $remark
 * @property integer $expire_time
 * @property string $mobile
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $type;
    public $nickname;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'auth_key', 'access_token'], 'required'],
            [['addtime', 'is_delete', 'app_max_count', 'expire_time'], 'integer'],
            [['permission'], 'string'],
            [['username', 'password', 'auth_key', 'access_token', 'remark', 'mobile'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'app_max_count' => 'App Max Count',
            'permission' => 'Permission',
            'remark' => 'Remark',
            'expire_time' => '账户有效期至，0表示永久',
            'mobile' => '手机号',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne([
            'access_token' => $token,
        ]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /* 所有权限列表 */
    public static $permission_list = [
        [
            'id' => 'coupon',
            'name' => '优惠券',
        ],
        [
            'id' => 'share',
            'name' => '分销',
        ],
        [
            'id' => 'topic',
            'name' => '专题',
        ],
        [
            'id' => 'video',
            'name' => '视频专区',
        ],
    ];

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
