<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

class QrcodeTable extends We7Table {
	public function searchTime($start_time, $end_time) {
		$this->query->where('createtime >=', $start_time)->where('createtime <=', $end_time);
		return $this;
	}

	public function searchKeyword($keyword) {
		$this->query->where('name LIKE', "%{$keyword}%");
		return $this;
	}

	public function qrcodeStaticList($status) {
		global $_W;
		$this->query->from('qrcode_stat')->where('uniacid', $_W['uniacid'])->where('acid', $_W['acid']);
		if (!empty($status)) {
			$this->query->groupby('qid');
			$this->query->groupby('openid');
			$this->query->groupby('type');
		}
		$this->query->orderby('createtime', 'DESC');
		return $this->query->getall();
	}

	public function qrcodeCount($status) {
		global $_W;
		$this->query->from('qrcode_stat')->select('count(*) as count')->where('uniacid', $_W['uniacid'])->where('acid', $_W['acid']);
		if (!empty($status)) {
			$this->query->groupby('qid');
			$this->query->groupby('openid');
			$this->query->groupby('type');
		}
		$count = $this->query->getall();
		if ($status) {
			return count($count);
		}
		return $count[0]['count'];
	}
}