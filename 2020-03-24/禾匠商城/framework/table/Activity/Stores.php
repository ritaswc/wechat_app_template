<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Activity;

class Stores extends \We7Table {
	protected $tableName = 'activity_stores';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'business_name',
		'branch_name',
		'category',
		'province',
		'city',
		'district',
		'address',
		'longitude',
		'latitude',
		'telephone',
		'photo_list',
		'avg_price',
		'recommend',
		'special',
		'introduction',
		'open_time',
		'location_id',
		'status',
		'source',
		'message',
		'sosomap_poi_uid',
		'license_no',
		'license_name',
		'other_files',
		'audit_id',
		'on_show',
	);
	protected $default = array(
		'uniacid' => '',
		'business_name' => '',
		'branch_name' => '',
		'category' => '',
		'province' => '',
		'city' => '',
		'district' => '',
		'address' => '',
		'longitude' => '',
		'latitude' => '',
		'telephone' => '',
		'photo_list' => '',
		'avg_price' => '',
		'recommend' => '',
		'special' => '',
		'introduction' => '',
		'open_time' => '',
		'location_id' => '',
		'status' => '',
		'source' => 1,
		'message' => '',
		'sosomap_poi_uid' => '',
		'license_no' => '',
		'license_name' => '',
		'other_files' => '',
		'audit_id' => '',
		'on_show' => 2,
	);

	public function getAllByUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid)->getall();
	}
}