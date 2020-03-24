<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Wxapp;

class GeneralAnalysis extends \We7Table {
	protected $tableName = 'wxapp_general_analysis';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'session_cnt',
		'visit_pv',
		'visit_uv',
		'visit_uv_new',
		'type',
		'stay_time_uv',
		'stay_time_session',
		'visit_depth',
		'ref_date',
	);
	protected $default = array(
		'uniacid' => '',
		'session_cnt' => '',
		'visit_pv' => '',
		'visit_uv' => '',
		'visit_uv_new' => '',
		'type' => '',
		'stay_time_uv' => '',
		'stay_time_session' => '',
		'visit_depth' => '',
		'ref_date' => '',
	);
}