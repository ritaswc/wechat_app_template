<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function pdo() {
	global $_W;
	static $db;
	if (empty($db)) {
		if ($_W['config']['db']['slave_status'] == true && !empty($_W['config']['db']['slave'])) {
			load()->classs('slave.db');
			$db = new SlaveDb('master');
		} else {
			load()->classs('db');
			if (empty($_W['config']['db']['master'])) {
				$_W['config']['db']['master'] = $GLOBALS['_W']['config']['db'];
				$db = new DB($_W['config']['db']);
			} else {
				$db = new DB('master');
			}
		}
	}

	return $db;
}


function pdos($table = '') {
	return load()->singleton('Query');
}


function pdo_query($sql, $params = array()) {
	return pdo()->query($sql, $params);
}


function pdo_fetchcolumn($sql, $params = array(), $column = 0) {
	return pdo()->fetchcolumn($sql, $params, $column);
}

function pdo_fetch($sql, $params = array()) {
	return pdo()->fetch($sql, $params);
}

function pdo_fetchall($sql, $params = array(), $keyfield = '') {
	return pdo()->fetchall($sql, $params, $keyfield);
}


function pdo_get($tablename, $condition = array(), $fields = array()) {
	return pdo()->get($tablename, $condition, $fields);
}

function pdo_getall($tablename, $condition = array(), $fields = array(), $keyfield = '', $orderby = array(), $limit = array()) {
	return pdo()->getall($tablename, $condition, $fields, $keyfield, $orderby, $limit);
}

function pdo_getslice($tablename, $condition = array(), $limit = array(), &$total = null, $fields = array(), $keyfield = '', $orderby = array()) {
	return pdo()->getslice($tablename, $condition, $limit, $total, $fields, $keyfield, $orderby);
}

function pdo_getcolumn($tablename, $condition = array(), $field) {
	return pdo()->getcolumn($tablename, $condition, $field);
}


function pdo_exists($tablename, $condition = array()) {
	return pdo()->exists($tablename, $condition);
}


function pdo_count($tablename, $condition = array(), $cachetime = 15) {
	return pdo()->count($tablename, $condition, $cachetime);
}


function pdo_update($table, $data = array(), $params = array(), $glue = 'AND') {
	return pdo()->update($table, $data, $params, $glue);
}


function pdo_insert($table, $data = array(), $replace = false) {
	return pdo()->insert($table, $data, $replace);
}


function pdo_delete($table, $params = array(), $glue = 'AND') {
	return pdo()->delete($table, $params, $glue);
}


function pdo_insertid() {
	return pdo()->insertid();
}


function pdo_begin() {
	pdo()->begin();
}


function pdo_commit() {
	pdo()->commit();
}


function pdo_rollback() {
	pdo()->rollBack();
}


function pdo_debug($output = true, $append = array()) {
	return pdo()->debug($output, $append);
}

function pdo_run($sql) {
	return pdo()->run($sql);
}


function pdo_fieldexists($tablename, $fieldname = '') {
	return pdo()->fieldexists($tablename, $fieldname);
}

function pdo_fieldmatch($tablename, $fieldname, $datatype = '', $length = '') {
	return pdo()->fieldmatch($tablename, $fieldname, $datatype, $length);
}

function pdo_indexexists($tablename, $indexname = '') {
	return pdo()->indexexists($tablename, $indexname);
}


function pdo_fetchallfields($tablename) {
	$fields = pdo_fetchall("DESCRIBE {$tablename}", array(), 'Field');
	$fields = array_keys($fields);

	return $fields;
}


function pdo_tableexists($tablename) {
	return pdo()->tableexists($tablename);
}
