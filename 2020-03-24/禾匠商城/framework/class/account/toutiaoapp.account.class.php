<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


class ToutiaoappAccount extends WeAccount {
	protected $tablename = 'account_toutiaoapp';
	protected $menuFrame = 'wxapp';
	protected $type = ACCOUNT_TYPE_TOUTIAOAPP_NORMAL;
	protected $typeName = '头条小程序';
	protected $typeTempalte = '-toutiaoapp';
	protected $typeSign = TOUTIAOAPP_TYPE_SIGN;
	protected $supportVersion = STATUS_ON;

	protected function getAccountInfo($acid) {
		return table('account_toutiaoapp')->getAccount($acid);
	}
}