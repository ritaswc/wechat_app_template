<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Core;

class ProfileFields extends \We7Table {
	protected $tableName = 'profile_fields';
	protected $primaryKey = 'id';
	protected $field = array(
		'field',
		'available',
		'title',
		'description',
		'displayorder',
		'required',
		'unchangeable',
		'showinregister',
		'field_length',
	);
	protected $default = array(
		'field' => '',
		'available' => 1,
		'title' => '',
		'description' => '',
		'displayorder' => 0,
		'required' => 0,
		'unchangeable' => 0,
		'showinregister' => 0,
		'field_length' => 0,
	);
	
	public function searchWithKeyword($keyword) {
		$this->query->where('title LIKE', "%{$keyword}%");
		return $this;
	}
	
	public function getFieldsList() {
		return $this->query->orderby('displayorder', 'DESC')->getall();
	}

	public function getAvailableAndShowableFields() {
		return $this->query->where('available', 1)->where('showinregister', 1)->orderby('displayorder', 'desc')->getall('field');
	}
}