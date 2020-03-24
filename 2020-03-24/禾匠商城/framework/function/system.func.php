<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */


if (!function_exists('getglobal')) {
	function getglobal($key) {
		global $_W;
		$key = explode('/', $key);

		$v = &$_W;
		foreach ($key as $k) {
			if (!isset($v[$k])) {
				return null;
			}
			$v = &$v[$k];
		}

		return $v;
	}
}

if (!function_exists('strip_gpc')) {
	function strip_gpc($values, $type = 'g') {
		$filter = array(
			'g' => "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)",
			'p' => '\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)',
			'c' => '\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)',
		);
		if (!isset($values)) {
			return '';
		}
		if (is_array($values)) {
			foreach ($values as $key => $val) {
				$values[addslashes($key)] = strip_gpc($val, $type);
			}
		} else {
			if (1 == preg_match('/' . $filter[$type] . '/is', $values, $match)) {
				$values = '';
			}
		}

		return $values;
	}
}
