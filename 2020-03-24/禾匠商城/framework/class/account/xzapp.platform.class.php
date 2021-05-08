<?php

defined('IN_IA') or exit('Access Denied');

load()->classs('xzapp.account');

class XzappPlatform extends XzappAccount {
	protected $appid;
	protected $appsecret;
	protected $encodingaeskey;
	protected $token;
	protected $refreshtoken;
	protected $account;
	protected $tablename = 'account_xzapp';
	protected $menuFrame = 'account';
	protected $type = ACCOUNT_TYPE_XZAPP_NORMAL;
	protected $typeName = '熊掌号';
	protected $typeSign = XZAPP_TYPE_SIGN;
	protected $typeTempalte = '-xzapp';

	public function __construct($uniaccount = array()) {
		$setting['token'] = 'we7';
		$setting['encodingaeskey'] = 'g4LUbkbCbYmdXBeilamDMsU905IXfqjT5avgMETyV0e';
		$setting['appid'] = 'TrarDDV5IcTTxOffEXx58Gt5LsqlGyVi';
		$setting['appsecret'] = 'jCfdywGiBpaGxp2ivS5uHXIsEOLrzhZY';
		$setting['authstate'] = 1;
		$setting['url'] = 'https://ccceshi.w7.cc/xiongzhang_api.php';

		$_W['setting']['xzapp'] = $setting;
		$this->appid = $setting['appid'];
		$this->appsecret = $setting['appsecret'];
		$this->token = $setting['token'];
		$this->encodingaeskey = $setting['encodingaeskey'];
	}

	protected function getAccountInfo($acid) {
		return table('account_xzapp')->getByAcid($acid);
	}
}
