<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

class MemberTable extends We7Table {

	public function accountMemberFields($uniacid, $is_available, $select = array()) {
		if ($is_available) {
			$this->query->where('a.available', 1);
		}
		if (empty($select)) {
			$select = array('a.title', 'b.field');
		}
		return $this->query->from('mc_member_fields', 'a')->leftjoin('profile_fields', 'b')->on('a.fieldid', 'b.id')->where('a.uniacid', $uniacid)->select($select)->orderby('displayorder DESC')->getall('field');
	}

	public function emailExist($uid, $email) {
		load()->model('mc');
		if (empty($email)) {
			return false;
		}
		$this->query->from('mc_members')->where('uniacid', mc_current_real_uniacid())->where('email', trim($email));
		if (!empty($uid)) {
			$this->query->where('uid <>', $uid);
		}
		$emailexists = $this->query->getcolumn('email');
		if ($emailexists) {
			return true;
		} else {
			return false;
		}
	}

	public function mobileExist($uid, $mobile) {
		load()->model('mc');
		if (empty($mobile)) {
			return false;
		}
		$this->query->from('mc_members')->where('uniacid', mc_current_real_uniacid())->where('mobile', trim($mobile));
		if (!empty($uid)) {
			$this->query->where('uid <>', $uid);
		}
		$mobilexists = $this->query->getcolumn('mobile');
		if ($mobilexists) {
			return true;
		} else {
			return false;
		}
	}

	public function updateMember($uid, $fields = array()) {
		load()->model('mc');
		$member = $this->query->from('mc_members')->where('uid', $uid)->get();
		if (!empty($fields['email']) && $this->emailExist($uid, $fields['email'])) {
			unset($fields['email']);
		}
		if (!empty($fields['mobile']) && $this->mobileExist($uid, $fields['mobile'])) {
			unset($fields['mobile']);
		}
		if (empty($member)) {
			if(empty($fields['mobile']) && empty($fields['email'])) {
				return false;
			}
			$fields['uniacid'] = mc_current_real_uniacid();
			$fields['createtime'] = TIMESTAMP;
			pdo_insert('mc_members', $fields);
			$insert_id = pdo_insertid();
			return $insert_id;
		} else {
			if (!empty($fields)) {
				$result = pdo_update('mc_members', $fields, array('uid' => $uid));
			} else {
				$result = 0;
			}
			return $result > 0;
		}
	}

	public function creditsRecordList()
	{
		global $_W;
		$this->query->from('mc_credits_record', 'r')
				->select('r.*, u.username as username')
				->leftjoin('users', 'u')
				->on(array('r.operator' => 'u.uid'))
				->where('r.uniacid', $_W['uniacid']);
		$this->query->orderby('r.id', 'desc');
		return $this->query->getall();
	}

	public function searchCreditsRecordUid($uid) {
		$this->query->where('r.uid', $uid);
		return $this;
	}

	public function searchCreditsRecordType($type) {
		$this->query->where('r.credittype', $type);
		return $this;
	}

	public function searchWithMember() {
		return $this->query->from('mc_members')->get();
	}

	public function searchWithMemberEmail($email) {
		$this->query->where('email', $email);
		return $this;
	}

	public function searchWithMobile($mobile) {
		$this->query->where('mobile', $mobile);
		return $this;
	}

	public function searchWithRandom($info) {
		$this->query->where('mobile', $info)->whereor('email', $info);
		return $this;
	}

	public function mcFieldsList($uniacid) {
		return $this->query->from('mc_member_fields', 'm')->select('m.title, m.fieldid, p.field')->leftjoin('profile_fields', 'p')
			->on(array('m.fieldid' => 'p.id'))->where('m.uniacid', $uniacid)->getall('field');

	}
}