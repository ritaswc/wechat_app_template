<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class ExtraTemplates extends \We7Table {
	protected $tableName = 'users_extra_templates';
	protected $primaryKey = 'id';
	protected $field = array(
		'uid',
		'template_id',

	);
	protected $default = array(
		'uid' => '',
		'template_id' => '',

	);

	public function addExtraTemplate($uid, $template_id) {
		$data = array(
			'uid' => $uid,
			'template_id' => $template_id,
		);
		$res = $this->fill($data)->save();
		return $res;
	}

	public function getExtraTemplateByUidAndTemplateid($uid, $template_id) {
		$where = array(
			'uid' => $uid,
			'template_id' => $template_id,
		);
		$extra_template = $this->where($where)->get();
		return $extra_template;
	}

	public function getExtraTemplatesByUid($uid) {
		return $this->where(array('uid' => $uid))->get();
	}

	public function getExtraTemplatesIdsByUid($uid) {
		return $this->where(array('uid' => $uid))->getall('template_id');
	}

	public function deleteExtraTemplatesByUid($uid) {
		return $this->where(array('uid' => $uid))->delete();
	}


}