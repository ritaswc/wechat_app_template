<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$GLOBALS['_W']['config']['db']['tablepre'] = empty($GLOBALS['_W']['config']['db']['tablepre']) ? $GLOBALS['_W']['config']['db']['master']['tablepre'] : $GLOBALS['_W']['config']['db']['tablepre'];

function db_table_schema($db, $tablename = '') {
	$result = $db->fetch("SHOW TABLE STATUS LIKE '" . trim($db->tablename($tablename), '`') . "'");
	if (empty($result)) {
		return array();
	}
	$ret['tablename'] = $result['Name'];
	$ret['charset'] = $result['Collation'];
	$ret['engine'] = $result['Engine'];
	$ret['increment'] = $result['Auto_increment'];
	$result = $db->fetchall('SHOW FULL COLUMNS FROM ' . $db->tablename($tablename));
	foreach ($result as $value) {
		$temp = array();
		$type = explode(' ', $value['Type'], 2);
		$temp['name'] = $value['Field'];
		$pieces = explode('(', $type[0], 2);
		$temp['type'] = $pieces[0];
		$temp['length'] = rtrim($pieces[1], ')');
		$temp['null'] = 'NO' != $value['Null'];
										$temp['signed'] = empty($type[1]);
		$temp['increment'] = 'auto_increment' == $value['Extra'];
		$ret['fields'][$value['Field']] = $temp;
	}
	$result = $db->fetchall('SHOW INDEX FROM ' . $db->tablename($tablename));
	foreach ($result as $value) {
		$ret['indexes'][$value['Key_name']]['name'] = $value['Key_name'];
		$ret['indexes'][$value['Key_name']]['type'] = ('PRIMARY' == $value['Key_name']) ? 'primary' : (0 == $value['Non_unique'] ? 'unique' : 'index');
		$ret['indexes'][$value['Key_name']]['fields'][] = $value['Column_name'];
	}

	return $ret;
}


function db_table_serialize($db, $dbname) {
	$tables = $db->fetchall('SHOW TABLES');
	if (empty($tables)) {
		return '';
	}
	$struct = array();
	foreach ($tables as $value) {
		$structs[] = db_table_schema($db, substr($value['Tables_in_' . $dbname], strpos($value['Tables_in_' . $dbname], '_') + 1));
	}

	return iserializer($structs);
}

function db_table_create_sql($schema) {
	$pieces = explode('_', $schema['charset']);
	$charset = $pieces[0];
	$engine = $schema['engine'];
	$schema['tablename'] = str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $schema['tablename']);
	$sql = "CREATE TABLE IF NOT EXISTS `{$schema['tablename']}` (\n";
	foreach ($schema['fields'] as $value) {
		$piece = _db_build_field_sql($value);
		$sql .= "`{$value['name']}` {$piece},\n";
	}
	foreach ($schema['indexes'] as $value) {
		$fields = implode('`,`', $value['fields']);
		if ('index' == $value['type']) {
			$sql .= "KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if ('unique' == $value['type']) {
			$sql .= "UNIQUE KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if ('primary' == $value['type']) {
			$sql .= "PRIMARY KEY (`{$fields}`),\n";
		}
	}
	$sql = rtrim($sql);
	$sql = rtrim($sql, ',');

	$sql .= "\n) ENGINE=$engine DEFAULT CHARSET=$charset;\n\n";

	return $sql;
}


function db_schema_compare($table1, $table2) {
	$table1['charset'] == $table2['charset'] ? '' : $ret['diffs']['charset'] = true;

	$fields1 = array_keys($table1['fields']);
	$fields2 = array_keys($table2['fields']);
	$diffs = array_diff($fields1, $fields2);
	if (!empty($diffs)) {
		$ret['fields']['greater'] = array_values($diffs);
	}
	$diffs = array_diff($fields2, $fields1);
	if (!empty($diffs)) {
		$ret['fields']['less'] = array_values($diffs);
	}
	$diffs = array();
	$intersects = array_intersect($fields1, $fields2);
	if (!empty($intersects)) {
		foreach ($intersects as $field) {
			if ($table1['fields'][$field] != $table2['fields'][$field]) {
				$diffs[] = $field;
			}
		}
	}
	if (!empty($diffs)) {
		$ret['fields']['diff'] = array_values($diffs);
	}

	$indexes1 = is_array($table1['indexes']) ? array_keys($table1['indexes']) : array();
	$indexes2 = is_array($table2['indexes']) ? array_keys($table2['indexes']) : array();
	$diffs = array_diff($indexes1, $indexes2);
	if (!empty($diffs)) {
		$ret['indexes']['greater'] = array_values($diffs);
	}
	$diffs = array_diff($indexes2, $indexes1);
	if (!empty($diffs)) {
		$ret['indexes']['less'] = array_values($diffs);
	}
	$diffs = array();
	$intersects = array_intersect($indexes1, $indexes2);
	if (!empty($intersects)) {
		foreach ($intersects as $index) {
			if ($table1['indexes'][$index] != $table2['indexes'][$index]) {
				$diffs[] = $index;
			}
		}
	}
	if (!empty($diffs)) {
		$ret['indexes']['diff'] = array_values($diffs);
	}

	return $ret;
}

function db_table_fix_sql($schema1, $schema2, $strict = false) {
	if (empty($schema1)) {
		return array(db_table_create_sql($schema2));
	}
	$diff = $result = db_schema_compare($schema1, $schema2);
	if (!empty($diff['diffs']['tablename'])) {
		return array(db_table_create_sql($schema2));
	}
	$sqls = array();
	if (!empty($diff['diffs']['engine'])) {
		$sqls[] = "ALTER TABLE `{$schema1['tablename']}` ENGINE = {$schema2['engine']}";
	}

	if (!empty($diff['diffs']['charset'])) {
		$pieces = explode('_', $schema2['charset']);
		$charset = $pieces[0];
		$sqls[] = "ALTER TABLE `{$schema1['tablename']}` DEFAULT CHARSET = {$charset}";
	}

	if (!empty($diff['fields'])) {
		if (!empty($diff['fields']['less'])) {
			foreach ($diff['fields']['less'] as $fieldname) {
				$field = $schema2['fields'][$fieldname];
				$piece = _db_build_field_sql($field);
				if (!empty($field['rename']) && !empty($schema1['fields'][$field['rename']])) {
					$sql = "ALTER TABLE `{$schema1['tablename']}` CHANGE `{$field['rename']}` `{$field['name']}` {$piece}";
					unset($schema1['fields'][$field['rename']]);
				} else {
					if ($field['position']) {
						$pos = ' ' . $field['position'];
					}
					$sql = "ALTER TABLE `{$schema1['tablename']}` ADD `{$field['name']}` {$piece}{$pos}";
				}
								$primary = array();
				$isincrement = array();
				if (strexists($sql, 'AUTO_INCREMENT')) {
					$isincrement = $field;
					$sql = str_replace('AUTO_INCREMENT', '', $sql);
					foreach ($schema1['fields'] as $field) {
						if (1 == $field['increment']) {
							$primary = $field;
							break;
						}
					}
					if (!empty($primary)) {
						$piece = _db_build_field_sql($primary);
						if (!empty($piece)) {
							$piece = str_replace('AUTO_INCREMENT', '', $piece);
						}
						$sqls[] = "ALTER TABLE `{$schema1['tablename']}` CHANGE `{$primary['name']}` `{$primary['name']}` {$piece}";
					}
				}
				$sqls[] = $sql;
			}
		}
		if (!empty($diff['fields']['diff'])) {
			foreach ($diff['fields']['diff'] as $fieldname) {
				$field = $schema2['fields'][$fieldname];
				$piece = _db_build_field_sql($field);
				if (!empty($schema1['fields'][$fieldname])) {
					$sqls[] = "ALTER TABLE `{$schema1['tablename']}` CHANGE `{$field['name']}` `{$field['name']}` {$piece}";
				}
			}
		}
		if ($strict && !empty($diff['fields']['greater'])) {
			foreach ($diff['fields']['greater'] as $fieldname) {
				if (!empty($schema1['fields'][$fieldname])) {
					$sqls[] = "ALTER TABLE `{$schema1['tablename']}` DROP `{$fieldname}`";
				}
			}
		}
	}

	if (!empty($diff['indexes'])) {
		if (!empty($diff['indexes']['less'])) {
			foreach ($diff['indexes']['less'] as $indexname) {
				$index = $schema2['indexes'][$indexname];
				$piece = _db_build_index_sql($index);
				$sqls[] = "ALTER TABLE `{$schema1['tablename']}` ADD {$piece}";
			}
		}
		if (!empty($diff['indexes']['diff'])) {
			foreach ($diff['indexes']['diff'] as $indexname) {
				$index = $schema2['indexes'][$indexname];
				$piece = _db_build_index_sql($index);

				$sqls[] = "ALTER TABLE `{$schema1['tablename']}` DROP " . ('PRIMARY' == $indexname ? ' PRIMARY KEY ' : "INDEX {$indexname}") . ", ADD {$piece}";
			}
		}
		if ($strict && !empty($diff['indexes']['greater'])) {
			foreach ($diff['indexes']['greater'] as $indexname) {
				$sqls[] = "ALTER TABLE `{$schema1['tablename']}` DROP `{$indexname}`";
			}
		}
	}
	if (!empty($isincrement)) {
		$piece = _db_build_field_sql($isincrement);
		$sqls[] = "ALTER TABLE `{$schema1['tablename']}` CHANGE `{$isincrement['name']}` `{$isincrement['name']}` {$piece}";
	}

	return $sqls;
}

function _db_build_index_sql($index) {
	$piece = '';
	$fields = implode('`,`', $index['fields']);
	if ('index' == $index['type']) {
		$piece .= " INDEX `{$index['name']}` (`{$fields}`)";
	}
	if ('unique' == $index['type']) {
		$piece .= "UNIQUE `{$index['name']}` (`{$fields}`)";
	}
	if ('primary' == $index['type']) {
		$piece .= "PRIMARY KEY (`{$fields}`)";
	}

	return $piece;
}

function _db_build_field_sql($field) {
	if (!empty($field['length'])) {
		$length = "({$field['length']})";
	} else {
		$length = '';
	}
	if (false !== strpos(strtolower($field['type']), 'int') || in_array(strtolower($field['type']), array('decimal', 'float', 'dobule'))) {
		$signed = empty($field['signed']) ? ' unsigned' : '';
	} else {
		$signed = '';
	}
	if (empty($field['null'])) {
		$null = ' NOT NULL';
	} else {
		$null = '';
	}
	if (isset($field['default'])) {
		$default = " DEFAULT '" . $field['default'] . "'";
	} else {
		$default = '';
	}
	if ($field['increment']) {
		$increment = ' AUTO_INCREMENT';
	} else {
		$increment = '';
	}

	return "{$field['type']}{$length}{$signed}{$null}{$default}{$increment}";
}

function db_table_schemas($table) {
	$dump = "DROP TABLE IF EXISTS {$table};\n";
	$sql = "SHOW CREATE TABLE {$table}";
	$row = pdo_fetch($sql);
	$dump .= $row['Create Table'];
	$dump .= ";\n\n";

	return $dump;
}

function db_table_insert_sql($tablename, $start, $size) {
	$data = '';
	$tmp = '';
	$sql = "SELECT * FROM {$tablename} LIMIT {$start}, {$size}";
	$result = pdo_fetchall($sql);
	if (!empty($result)) {
		foreach ($result as $row) {
			$tmp .= '(';
			foreach ($row as $k => $v) {
				$value = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $v);
				$tmp .= "'" . $value . "',";
			}
			$tmp = rtrim($tmp, ',');
			$tmp .= "),\n";
		}
		$tmp = rtrim($tmp, ",\n");
		$data .= "INSERT INTO {$tablename} VALUES \n{$tmp};\n";
		$datas = array(
				'data' => $data,
				'result' => $result,
		);

		return $datas;
	} else {
		return false;
	}
}