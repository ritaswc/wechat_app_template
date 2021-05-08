<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class Verifycode extends \We7Table {
	protected $tableName = 'uni_verifycode';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'receiver',
		'verifycode',
		'total',
		'createtime',
		'failed_count',
	);
	protected $default = array(
		'uniacid' => '',
		'receiver' => '',
		'verifycode' => '',
		'total' => '',
		'createtime' => '',
		'failed_count' => '',
	);

	public function getByReceiverVerifycode($uniacid, $receiver, $verifycode) {
		if (!empty($verifycode)) {
			$this->query->where('verifycode', $verifycode);
		}
		$row = $this->query
			->where('receiver', $receiver)
			->where('uniacid', $uniacid)
			->getall();
		return $row[0];
	}

	public function getFailedCountByReceiver($receiver) {
		return $this->query
			->where('receiver', $receiver)
			->getcolumn('failed_count');
	}

	public function updateFailedCount($receiver) {
		$sql = "update " . tablename('uni_verifycode') . " set failed_count=failed_count + '1' where receiver = :receiver";
		return pdo_query($sql, array(':receiver' => $receiver));
	}
}