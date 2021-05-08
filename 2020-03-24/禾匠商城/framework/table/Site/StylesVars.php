<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Site;

class StylesVars extends \We7Table {
	protected $tableName = 'site_styles_vars';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'templateid',
		'styleid',
		'variable',
		'content',
		'description',
	);
	protected $default = array(
		'uniacid' => '0',
		'templateid' => '',
		'styleid' => '',
		'variable' => '',
		'content' => '',
		'description' => '',
	);

	public function getAllByStyleid($styleid) {
		global $_W;
		return $this->query->where('uniacid', $_W['uniacid'])->where('styleid', $styleid)->getall('variable');
	}
}