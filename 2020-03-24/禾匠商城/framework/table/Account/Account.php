<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Account;

class Account extends \We7Table {
	protected $tableName = 'account';
	protected $primaryKey = 'acid';
	protected $field = array(
		'uniacid',
		'hash',
		'type',
		'isconnect',
		'isdeleted',
		'endtime',
		'send_account_expire_status',
		'send_api_expire_status'
	);
	protected $default = array(
		'uniacid' => '0',
		'hash' => '',
		'type' => '1',
		'isconnect' => '',
		'isdeleted' => '0',
		'endtime' => '0',
		'send_account_expire_status' => 0,
		'send_api_expire_status' => 0
	);

	public function getByUniacid($uniacid) {
		return $this->where('uniacid', $uniacid)->get();
	}

	public function getUniAccountByAcid($acid) {
		return $this->query
			->from($this->tableName, 'a')
			->leftjoin('uni_account', 'u')
			->on('a.uniacid', 'u.uniacid')
			->where('a.acid' , intval($acid))
			->get();
	}

	public function getUniAccountByUniacid($uniacid) {
		return $this->query
			->from($this->tableName, 'a')
			->leftjoin('uni_account', 'u')
			->on('a.uniacid', 'u.uniacid')
			->where('a.uniacid' , intval($uniacid))
			->get();
	}

	public function getUserAccountInfo($uid, $uniacid, $account_type) {
		$type_info = uni_account_type($account_type);
		return $this->query->from('uni_account', 'a')
			->leftjoin($type_info['table_name'], 'w')
			->on(array('w.uniacid' => 'a.uniacid'))
			->leftjoin('uni_account_users', 'au')
			->on(array('a.uniacid' => 'au.uniacid'))
			->where(array('a.uniacid' => $uniacid))
			->where(array('au.uid' => $uid))
			->orderby('a.uniacid', 'asc')
			->getall('uniacid');
	}

	
	public function searchAccount($expire_type, $fields, $isdeleted = 1, $uid = 0) {
		global $_W;
		$uid = empty($uid) ? $_W['uid'] : $uid;
		$this->query->from('uni_account', 'a')
			->select($fields)
			->leftjoin('account', 'b')
			->on(array('a.uniacid' => 'b.uniacid', 'a.default_acid' => 'b.acid'))
			->where('b.isdeleted !=', $isdeleted)
			->where('a.default_acid !=', '0')
			->leftjoin('uni_account_users', 'c')
			->on(array('a.uniacid' => 'c.uniacid'))
			->leftjoin('users', 'u')
			->on(array('u.uid' => 'c.uid'));

				if (!user_is_founder($uid, true)) {
			if (user_is_vice_founder($uid)) {
				$users_uids = table('users_founder_own_users')->getFounderOwnUsersList($uid);
				$users_uids = array_keys($users_uids);
				$users_uids[] = $uid;
				$this->query->where('c.uid', $users_uids)->where('c.role', array('manager', 'owner', 'vice_founder'));
			} else {
				$this->query->where('c.uid', $uid)->where('c.role <>', 'clerk');
			}
		}
		if ($expire_type == 'expire') {
			$this->searchWithExprie();
		} elseif ($expire_type == 'unexpire') {
			$this->searchWithUnExprie();
		}
		return $this;
	}

	public function searchWithRole($role) {
		global $_W;
		return $this->query->where('c.role', $role)->where('c.uid', $_W['uid']);
	}
	public function searchWithExprie() {
		$this->query->where(array(
			'b.endtime <' => TIMESTAMP,
			'b.endtime >' => USER_ENDTIME_GROUP_UNLIMIT_TYPE
		));
		return $this;
	}

	public function searchWithUnExprie() {
		$this->query->where(function ($query) {
			$query->where(array('b.endtime' => 0))
				->whereor(array('b.endtime' => USER_ENDTIME_GROUP_UNLIMIT_TYPE))
				->whereor(array('b.endtime >' => TIMESTAMP));
		});
		return $this;
	}

	public function searchAccountList($expire = false, $isdeleted = 1, $fields = 'a.uniacid', $uid = 0) {
		$this->searchAccount($expire, $fields, $isdeleted, $uid);
		$this->query->groupby('a.uniacid');
		$list = $this->query->getall('uniacid');
		return $list;
	}

	public function searchAccounTotal($expire = false) {
		$this->searchAccount($expire, 'count(*) as total, b.type');
		$this->query->groupby('b.type');
		$list = $this->query->getall();
		return $list;
	}

	public function searchWithKeyword($title) {
		global $_W;
		if (empty($title)) {
			return $this;
		}
		if ($title == 'admin' && user_is_founder($_W['uid'], true)) {
			$this->query->where('ISNULL(c.uid)', true);
		} else {
			if (user_is_founder($_W['uid'], true) || user_is_vice_founder($_W['uid'])){
				$users = table('uni_account_users')
					->searchWithUsers()
					->where('a.role', 'owner')
					->where('b.username', $title);
				if (user_is_vice_founder($_W['uid'])) {
					$uids = table('users_founder_own_users')->where('founder_uid', $_W['uid'])->getall('uid');
					if (!empty($uid)) {
						$users->where('a.uid', array_keys($uids));
					}
				}
				$users = $users->getall();
			}
			!empty($users) ? $this->query->where('CONCAT(a.name,u.username) LIKE', "%{$title}%")->where('c.role', 'owner') : $this->query->where('a.name LIKE', "%{$title}%");
		}
		return $this;
	}

	public function searchWithTitle($title) {
		$this->query->where('a.name', $title);
		return $this;
	}

	public function searchWithType($types = array()) {
		$this->query->where(array('b.type' => $types));
		return $this;
	}

	public function searchWithLetter($letter) {
		if (!empty($letter) && strlen($letter) == 1) {
			$this->query->where('a.title_initial', $letter);
		}
		return $this;
	}

	public function accountRankOrder() {
		global $_W;
		if (!user_is_founder($_W['uid'], true)) {
			$this->query->orderby('c.rank', 'desc');
		} else {
			$this->query->orderby('a.rank', 'desc');
		}
		return $this;
	}

	public function accountUniacidOrder($order = 'desc') {
		$order = !empty($order) ? $order : 'desc';
		$this->query->orderby('a.uniacid', $order);
		return $this;
	}

	public function accountInitialsOrder($order = 'asc') {
		$order = !empty($order) ? $order : 'asc';
		$this->query->orderby('a.title_initial', $order);
		return $this;
	}

	public function searchWithViceFounder($vice_founder_id) {
		$this->query
			->leftjoin('uni_account_users', 'c')
			->on(array('a.uniacid' => 'c.uniacid'))
			->where('c.role', 'vice_founder')
			->where('c.uid', $vice_founder_id);
		return $this;
	}


	
	public function accountGroupModules($uniacid, $type = '') {
		$packageids = $this->query->from('uni_account_group')->where('uniacid', $uniacid)->select('groupid')->getall('groupid');
		$packageids = empty($packageids) ? array() : array_keys($packageids);
		if (in_array('-1', $packageids)) {
			$modules = $this->query->from('modules')->select('name')->getall('name');
			return array_keys($modules);
		}
		$uni_modules = array();
		
			$site_store_buy_package = table('site_store_order')->getUserBuyPackage($uniacid);
			$packageids = array_merge($packageids, array_keys($site_store_buy_package));
		
		$uni_groups = $this->query->from('uni_group')->where('uniacid', $uniacid)->whereor('id', $packageids)->getall('modules');
		if (!empty($uni_groups)) {
			if (empty($type)) {
				$account = table('account')->getByUniacid($uniacid);
				$type = $account['type'];
			}
			foreach ($uni_groups as $group) {
				$group_module = (array)iunserializer($group['modules']);
				if (empty($group_module)) {
					continue;
				}
				switch ($type) {
					case ACCOUNT_TYPE_OFFCIAL_NORMAL:
					case ACCOUNT_TYPE_OFFCIAL_AUTH:
						$uni_modules = is_array($group_module['modules']) ? array_merge($group_module['modules'], $uni_modules) : $uni_modules;
						break;
					case ACCOUNT_TYPE_APP_NORMAL:
					case ACCOUNT_TYPE_APP_AUTH:
					case ACCOUNT_TYPE_WXAPP_WORK:
						$uni_modules = is_array($group_module['wxapp']) ? array_merge($group_module['wxapp'], $uni_modules) : $uni_modules;
						break;
					case ACCOUNT_TYPE_WEBAPP_NORMAL:
						$uni_modules = is_array($group_module['webapp']) ? array_merge($group_module['webapp'], $uni_modules) : $uni_modules;
						break;
					case ACCOUNT_TYPE_XZAPP_NORMAL:
					case ACCOUNT_TYPE_XZAPP_AUTH:
						$uni_modules = is_array($group_module['xzapp']) ? array_merge($group_module['xzapp'], $uni_modules) : $uni_modules;
						break;
					case ACCOUNT_TYPE_PHONEAPP_NORMAL:
						$uni_modules = is_array($group_module['phoneapp']) ? array_merge($group_module['phoneapp'], $uni_modules) : $uni_modules;
						break;
					case ACCOUNT_TYPE_ALIAPP_NORMAL:
						$uni_modules = is_array($group_module['aliapp']) ? array_merge($group_module['aliapp'], $uni_modules) : $uni_modules;
						break;
				}
			}
			$uni_modules = array_unique($uni_modules);
		}
		return $uni_modules;
	}

	
	public function userOwnedAccount($uid = 0) {
		global $_W;
		$uid = intval($uid) > 0 ? intval($uid) : $_W['uid'];
		if (!user_is_founder($uid, true)) {
			$uniacid_list = table('uni_account_users')->getUsableAccountsByUid($uid);
			if (empty($uniacid_list)) {
				return array();
			}
			$this->query->where('u.uniacid', array_keys($uniacid_list));
		}
		return $this->query->from('uni_account', 'u')
			->leftjoin('account', 'a')
			->on(array('u.default_acid' => 'a.acid'))
			->where('a.isdeleted', 0)
			->orderby('a.uniacid', 'DESC')
			->getall('uniacid');
	}

	public function searchWithuniAccountUsers() {
		return $this->query->from('account', 'a')
			->leftjoin('uni_account_users', 'b')
			->on(array('b.uniacid' => 'a.uniacid'));
	}

	public function searchWithUniAccount() {
		return $this->query->from($this->tableName, 'a')
			->leftjoin('uni_account', 'b')
			->on('b.uniacid', 'a.uniacid');
	}

}