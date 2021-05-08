<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class MemberFields extends \We7Table {
	protected $tableName = 'mc_member_fields';
	protected $primaryKey = 'id';
	protected $field = array(
		'available',
		'displayorder',
		'fieldid',
		'title',
		'uniacid',
	);
	protected $default = array(
		'available' => '1',
		'displayorder' => '0',
		'fieldid' => '',
		'title' => '',
		'uniacid' => '',
	);

	public function getAllFields() {
		return $this->query->from('mc_member_fields', 'a')
					->leftjoin('profile_fields', 'b')
					->on('a.fieldid', 'b.id')
					->orderby('displayorder DESC')
					->getall('field');
	}

	public function selectFields($select) {
		return $this->query->select($select);
	}

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}

	public function searchWithAvailable($available) {
		return $this->query->where('available', $available);
	}

	public function searchWithProfileFields() {
		return $this->query->from($this->tableName, 'mf')
			->leftjoin('profile_fields', 'pf')
			->on(array('mf.fieldid' => 'pf.id'));
	}
}