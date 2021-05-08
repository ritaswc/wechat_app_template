<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


abstract class We7Table {
	const ONE_TO_ONE = 'ONE_TO_ONE';
	const ONE_TO_MANY = 'ONE_TO_MANY';
	const BELONGS_TO = 'BELONGS_TO';
	const MANY_TO_MANY = 'MANY_TO_MANY';

		protected $tableName = '';
		protected $primaryKey = 'id';
	protected $field = array('group_id');
		protected $rule = array();
		protected $default = array();
		protected $cast = array();
		protected $query;
		private $attribute = array();
	
	private $relationDefine = array();

	public function __construct() {
				load()->classs('validator');
		$this->query = load()->object('Query');
		$this->query->fixTable = $this->tableName;
		$this->query->from($this->tableName);
	}

	
	public function searchWithPage($pageindex, $pagesize) {
		if (!empty($pageindex) && !empty($pagesize)) {
			$this->query->page($pageindex, $pagesize);
		}

		return $this;
	}

	
	public function getLastQueryTotal() {
		return $this->query->getLastQueryTotal();
	}

	
	public function count() {
		return $this->query->count();
	}

	
	public function fill($field, $value = '') {
		if (is_array($field)) {
			foreach ($field as $column => $val) {
				$this->fillField($column, $val);
			}

			return $this;
		}
		$this->fillField($field, $value);

		return $this;
	}

	
	private function fillField($column, $val) {
		if (in_array($column, $this->field)) {
			$val = $this->getColumnVal($column, $val);
			$this->attribute[$column] = $val;
			$this->query->fill($column, $val);
		}
	}

	
	private function getColumnVal($column, $val) {
		$method = 'set' . $this->studly($column) . 'Field';
		if (method_exists($this, $method)) {
			return $this->{$method}($val);
		}

		return $this->cast($column, $val);
	}

	
	private function cast($column, $val) {
		if (isset($this->cast[$column])) {
			switch ($this->cast[$column]) {
				case 'int': return intval($val); break;
				case 'string': return strval($val); break;
				case 'float': return floatval($val); break;
				case 'double': return doubleval($val); break;
				case 'bool': return boolval($val); break;
			}
		}

		return $val;
	}

	
	private function appendDefault() {
		foreach ($this->default as $field => $value) {
			if (!isset($this->attribute[$field])) {
				if ('custom' === $value) {
					$method = 'default' . $this->studly($field);
					if (!method_exists($this, $method)) {
						trigger_error($method . '方法未找到');
					}
					$value = call_user_func(array($this, $method));
				}
				$this->fillField($field, $value);
			}
		}
	}

	
	protected function valid($data) {
		if (count($this->rule) <= 0) {
			return error(0);
		}
		$validator = Validator::create($data, $this->rule);
		$result = $validator->valid();

		return $result;
	}

	public function select($fields = '*') {
		return $this->query->select($fields);
	}

	public function limit($limit) {
		return $this->query->limit($limit);
	}

	public function get() {
		$data = $this->query->get();
		if (!$data || empty($data)) {
			return $data;
		}
		$this->loadRelation($data);

		return $data;
	}

	public function getall($keyfield = '') {
		$data = $this->query->getall($keyfield);
		if (!$data || empty($data)) {
			return $data;
		}
		$this->loadRelation($data, true);

		return $data;
	}

	
	public function getQuery() {
		return $this->query;
	}

	public function getTableName() {
		return $this->tableName;
	}

	
	public function with($relation) {
		$relations = is_string($relation) ? func_get_args() : $relation;
		foreach ($relations as $relation => $val) {
			if (is_numeric($relation)) {
				$relation = $val;
			}
			if (!is_callable($val)) {
				$val = null;
			}
			$this->relationDefine[$relation] = $val;
		}

		return $this;
	}

	
	private function loadRelation(array &$data, $muti = false) {
		foreach ($this->relationDefine as $relation => $closure) {
			$this->doload($relation, $data, $muti, $closure); 		}
	}

	
	private function doload($relation, &$data, $muti = false, callable $closure = null) {
		if (method_exists($this, $relation)) {
			$relation_param = call_user_func(array($this, $relation));
			list($type, $table, $foreign_key, $owner_key) = $relation_param;
			if (self::MANY_TO_MANY == $type) {
				$this->doManyToMany($relation, $relation_param, $data, $muti);

				return;
			}
			
			$single = $this->isGetSingle($type);
			
			$foreign_vals = $this->getForeignVal($data, $owner_key, $muti);
			
			$second_table_data = $this->getSecondTableData($table, $foreign_key, $foreign_vals, $single, $closure);
			if (!$muti) {
				$data[$relation] = $second_table_data;

				return;
			}
			if ($single) {
				$second_table_data = array($second_table_data);
			}
			$second_table_data = $this->groupBy($foreign_key, $second_table_data);

			foreach ($data as &$item) {
				$relation_val = isset($second_table_data[$item[$owner_key]]) ? $second_table_data[$item[$owner_key]] : array();
				if ($single) {
					$relation_val = count($relation_val) > 0 ? current($relation_val) : array();
				}
				$item[$relation] = $relation_val;
			}
		}
	}

	
	private function doManyToMany($relation, $relation_param, &$data, $muti = false) {
		list($type, $table, $foreign_key, $owner_key, $center_table, $center_foreign_key, $center_owner_key)
			= $relation_param;

		$foreign_vals = $this->getForeignVal($data, $owner_key, $muti);
		$three_table = table($table);
		$nativeQuery = $three_table->getQuery();

		$nativeQuery->from($three_table->getTableName(), 'three')
			->innerjoin($center_table, 'center')
			->on(array('center.' . $center_foreign_key => 'three.' . $foreign_key))
			->select('center.*')
			->where('center.' . $center_owner_key, $foreign_vals);

		$three_table_data = $three_table->getall(); 		if (!$muti) {
			$data[$relation] = $three_table_data;

			return;
		}

		$three_table_data = $this->groupBy($center_owner_key, $three_table_data);
		
		foreach ($data as &$item) {
			$three_val = isset($three_table_data[$item[$owner_key]]) ? $three_table_data[$item[$owner_key]] : array();
			$item[$relation] = $three_val;
		}
	}

	
	private function isGetSingle($type) {
		return in_array($type, array(self::ONE_TO_ONE, self::BELONGS_TO)) ? true : false;
	}

	
	private function getForeignVal($data, $owner_key, $muti = false) {
		if (!$muti) {
			return $data[$owner_key];
		}

		return array_map(function ($item) use ($owner_key) {
			return $item[$owner_key];
		}, $data);
	}

	
	private function getSecondTableData($table, $foreign_key, $foreign_vals, $single = false, $closure = null) {
		$table_instance = table($table)->where($foreign_key, $foreign_vals);
		if ($closure) {
			call_user_func($closure, $table_instance->getQuery()); 		}
		if ($single) {
			return $table_instance->get();
		}

		return $table_instance->getall();
	}

	
	private function groupBy($key, $array) {
		$result = array();

		foreach ($array as $item) {
			$val = $item[$key];
			if (isset($result[$val])) {
				$result[$val][] = $item;
			} else {
				$result[$val] = array($item);
			}
		}

		return $result;
	}

	
	protected function hasOne($table, $foreign_key, $owner_key = false) {
		return $this->relationArray(self::ONE_TO_ONE, $table, $foreign_key, $owner_key);
	}

	
	protected function hasMany($table, $foreign_key, $owner_key = false) {
		return $this->relationArray(self::ONE_TO_MANY, $table, $foreign_key, $owner_key);
	}

	
	protected function belongsTo($table, $foreign_key, $owner_key = false) {
		return $this->relationArray(self::BELONGS_TO, $table, $foreign_key, $owner_key);
	}

	
	protected function belongsMany($table, $foreign_key, $owner_key, $center_table, $center_foreign_key = false,
								   $center_owner_key = false) {
		if (!$owner_key) {
			$owner_key = $this->primaryKey;
		}
		if (!$center_foreign_key) {
			$center_foreign_key = $foreign_key;
		}
		if (!$center_owner_key) {
			$center_owner_key = $owner_key;
		}

		return array(self::MANY_TO_MANY, $table, $foreign_key, $owner_key, $center_table, $center_foreign_key, $center_owner_key);
	}

	
	private function relationArray($type, $table, $foreign_key, $owner_key) {
		if (!$owner_key) {
			$owner_key = $this->primaryKey;
		}
		if (!in_array($type, array(self::ONE_TO_ONE, self::ONE_TO_MANY, self::BELONGS_TO), true)) {
			trigger_error('不支持的关联类型');
		}

		return array($type, $table, $foreign_key, $owner_key);
	}

	
	public function getById($id, $uniacid = 0) {
		$this->query->from($this->tableName)->where($this->primaryKey, $id);
		if (!empty($uniacid)) {
			$this->where('uniacid', $uniacid);
		}
		if (is_array($id)) {
			return $this->getall();
		}

		return $this->get();
	}

	public function getcolumn($field = '') {
		$data = $this->query->getcolumn($field);

		return $data;
	}

	
	public function where($condition, $parameters = array(), $operator = 'AND') {
		$this->query->where($condition, $parameters, $operator);

		return $this;
	}

	
	public function whereor($condition, $parameters = array()) {
		return $this->where($condition, $parameters, 'OR');
	}

	public function orderby($field, $direction = 'ASC') {
		return $this->query->orderby($field, $direction);
	}

	
	public function save($replace = false) {
				if ($this->query->hasWhere()) {
			$result = $this->valid($this->attribute);
			if (is_error($result)) {
				return $result;
			}

			return $this->query->update();
		}

		$this->appendDefault();
		$result = $this->valid($this->attribute);
		if (is_error($result)) {
			return $result;
		}

		return $this->query->insert($replace);
	}

	
	public function delete() {
		if ($this->query->hasWhere()) {
			return $this->query->delete();
		}

		return false;
	}

	private function doWhere($field, $params, $operator = 'AND') {
		if (0 == $params) {
			return $this;
		}
		$value = $params[0];
		if (count($params) > 1) {
						$field = $field . ' ' . $params[1];
		}
		$this->query->where($field, $value, $operator);

		return $this;
	}

	
	private function snake($value) {
		$delimiter = '_';
		if (!ctype_lower($value)) {
			$value = preg_replace('/\s+/u', '', ucwords($value));
			$value = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
		}

		return $value;
	}

	
	private function studly($value) {
		$value = ucwords(str_replace(array('-', '_'), ' ', $value));

		return str_replace(' ', '', $value);
	}

	
	public function __call($method, $params) {
				$actions = array(
			'searchWith',
			'whereor',
			'where',
			'fill',
		);
		foreach ($actions as $action) {
			$fields = explode($action, $method);
			if (count($fields) > 1 && empty($fields[0]) && !empty($fields[1])) {
				$field = $this->snake($fields[1]);
				switch ($action) {
					case 'whereor':
						return $this->doWhere($field, $params, 'OR');
					case 'fill':
						$this->fill($field, $params[0]);

						return $this;
					default:
						return $this->doWhere($field, $params);
				}
			}
		}

		return $this;
	}
}