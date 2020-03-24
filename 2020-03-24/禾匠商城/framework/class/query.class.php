<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


class Query {
		private $clauses;
		private $statements = array();
		private $parameters = array();
		private $mainTable = '';
		private $currentTableAlias = '';
	private $error = array();
	private $lastsql = '';
	private $lastparams = '';
		private $values;
		public $fixTable;

	public function __construct() {
		$this->initClauses();
	}

	private function initClauses() {
		$this->clauses = array(
			'SELECT' => array(),
			'DELETE' => '',
			'UPDATE' => '',
			'INSERT INTO' => '',

			'FROM' => '',
			'LEFTJOIN' => array(),
			'INNERJOIN' => array(),
			'ON' => array(),
			'SET' => '',
			'WHERE' => array(),
			'WHEREOR' => array(),
			'GROUPBY' => array(),
			'HAVING' => array(),
			'ORDERBY' => array(),
			'LIMIT' => '',
			'PAGE' => '',
		);
		foreach ($this->clauses as $clause => $value) {
			$this->statements[$clause] = $value;
		}
		$this->parameters = array();
		if (!empty($this->fixTable)) {
			$this->from($this->fixTable);
		}
	}

	private function resetClause($clause = '') {
		if (empty($clause)) {
			$this->initClauses();

			return $this;
		}
		$this->statements[$clause] = null;
		$this->parameters = array();
		$this->values = array();
		if (isset($this->clauses[$clause]) && is_array($this->clauses[$clause])) {
			$this->statements[$clause] = array();
		}

		return $this;
	}

	
	private function addStatement($clause, $statement, $parameters = array()) {
		if (null === $statement) {
			return $this->resetClause($clause);
		}
				if (isset($this->statements[$clause]) && is_array($this->statements[$clause])) {
			if (is_array($statement)) {
				$this->statements[$clause] = array_merge($this->statements[$clause], $statement);
			} else {
				if (empty($parameters) && is_array($parameters)) {
					$this->statements[$clause][] = $statement;
				} else {
					$this->statements[$clause][$statement] = empty($parameters) && is_array($parameters) ? '' : $parameters;
				}
			}
		} else {
			$this->statements[$clause] = $statement;
		}

		return $this;
	}

	public function __call($clause, $statement = array()) {
		$origin_clause = $clause;
		$clause = strtoupper($clause);

		if ('HAVING' == $clause) {
			array_unshift($statement, $clause);

			return call_user_func_array(array($this, 'condition'), $statement);
		}

		if ('LEFTJOIN' == $clause || 'INNERJOIN' == $clause) {
			array_unshift($statement, $clause);

			return call_user_func_array(array($this, 'join'), $statement);
		}

										return $this->addStatement($clause, $statement);
	}

	public function where($condition, $parameters = array(), $operator = 'AND') {
		if (!is_array($condition) && !($condition instanceof Closure)) {
			$condition = array($condition => $parameters);
		}
		$this->addStatement('WHERE', array(array($operator, $condition)));

		return $this;
	}

	public function whereor($condition, $parameters = array()) {
		return $this->where($condition, $parameters, 'OR');
	}

	public function from($tablename, $alias = '') {
		if (empty($tablename)) {
			return $this;
		}
		$this->mainTable = $tablename;
		$this->currentTableAlias = $alias;

		$this->statements['FROM'] = $this->mainTable;

		return $this;
	}

	public function join($clause, $tablename, $alias = '') {
		if (empty($tablename)) {
			return $this;
		}
		$this->joinTable = $tablename;

		return $this->addStatement($clause, $tablename . ' ' . $alias);
	}

	public function on($condition, $parameters = array()) {
		if (null === $condition) {
			return $this->resetClause('ON');
		}
		if (empty($condition)) {
			return $this;
		}
		if (is_array($condition)) {
			foreach ($condition as $key => $val) {
				$this->on($key, $val);
			}

			return $this;
		}
		if (empty($this->statements['ON'][$this->joinTable])) {
			$this->statements['ON'][$this->joinTable] = array();
		}
		$this->statements['ON'][$this->joinTable][$condition] = $parameters;

		return $this;
	}

	public function select($field) {
		if (is_string($field)) {
			$field = func_get_args();
		}

		if (empty($field)) {
			return $this;
		}
				if (1 == count($this->statements['SELECT'])) {
			$this->resetClause('SELECT');
		}

		return $this->addStatement('SELECT', $field);
	}

	
	private function condition($operator, $condition, $parameters = array()) {
		if (null === $condition) {
			return $this->resetClause('WHERE');
		}
		if (empty($condition)) {
			return $this;
		}

		if (is_array($condition)) {
			foreach ($condition as $key => $val) {
				$this->condition($operator, $key, $val);
			}

			return $this;
		}

		return $this->addStatement($operator, $condition, $parameters);
	}

	public function orderby($field, $direction = 'ASC') {
		if (is_array($field)) {
			foreach ($field as $column => $order) {
				$this->orderby($column, $order);
			}

			return $this;
		}
		if (strtoupper($field) == 'RAND()') {
			return $this->addStatement('ORDERBY', $field);
		}
		$direction = strtoupper($direction);
		$direction = in_array($direction, array('ASC', 'DESC')) ? $direction : 'ASC';

		return $this->addStatement('ORDERBY', $field . ' ' . $direction);
	}

	public function fill($field, $value = '') {
		if (is_array($field)) {
			foreach ($field as $column => $val) {
				$this->fill($column, $val);
			}

			return $this;
		}
		$this->values[$field] = $value;

		return $this;
	}

	public function hasWhere() {
		return count($this->statements['WHERE']) > 0;
	}

	public function get() {
		if (empty($this->statements['SELECT'])) {
			$this->addStatement('SELECT', '*');
		}
		$this->lastsql = $this->buildQuery();
		$this->lastparams = $this->parameters;
		$result = pdo_fetch($this->lastsql, $this->parameters);

				$this->resetClause();

		return $result;
	}

	public function getcolumn($field = '') {
		if (!empty($field)) {
			$this->select($field);
		}
		if (empty($this->statements['SELECT'])) {
			$this->addStatement('SELECT', '*');
		}
		$this->lastsql = $this->buildQuery();
		$this->lastparams = $this->parameters;
		$result = pdo_fetchcolumn($this->lastsql, $this->parameters);

				$this->resetClause();

		return $result;
	}

	public function getall($keyfield = '') {
		if (empty($this->statements['SELECT'])) {
			$this->addStatement('SELECT', '*');
		}
		$this->lastsql = $this->buildQuery();
		$this->lastparams = $this->parameters;
		$result = pdo_fetchall($this->lastsql, $this->parameters, $keyfield);

				$this->resetClause();

		return $result;
	}

	
	public function getLastQueryTotal() {
		$lastquery = $this->getLastQuery();
				$countsql = str_replace(substr($lastquery[0], 0, strpos($lastquery[0], 'FROM')), 'SELECT COUNT(*) ', $lastquery[0]);
				if (false !== strpos($countsql, 'LIMIT')) {
			$countsql = substr($countsql, 0, strpos($countsql, 'LIMIT'));
		}
		if (strexists(strtoupper($countsql), 'GROUP BY')) {
			$result = pdo_fetchall($countsql, $this->lastparams);
			$result = count($result);
		} else {
			$result = pdo_fetchcolumn($countsql, $this->lastparams);
		}

		return $result;
	}

	public function count() {
		$where = array();
		if (!empty($this->statements['WHERE'])) {
			foreach ($this->statements['WHERE'] as $row) {
				$where = array_merge($where, $row[1]);
			}
		}

		return pdo_count($this->statements['FROM'], $where);
	}

	public function exists() {
		$where = array();
		if (!empty($this->statements['WHERE'])) {
			foreach ($this->statements['WHERE'] as $row) {
				$where = array_merge($where, $row[1]);
			}
		}

		return pdo_exists($this->statements['FROM'], $where);
	}

	public function delete() {
		$where = $this->buildWhereArray();
		$result = pdo_delete($this->statements['FROM'], $where);

				$this->resetClause();

		return $result;
	}

	public function insert($replace = false) {
		$result = pdo_insert($this->statements['FROM'], $this->values, $replace);
				$this->resetClause();

		return $result;
	}

	public function update() {
		$where = $this->buildWhereArray();
		if (empty($where)) {
			return error(-1, '未指定更新条件');
		}
		$result = pdo_update($this->statements['FROM'], $this->values, $where);
				$this->resetClause();

		return $result;
	}

	private function buildQuery() {
		$query = '';
		foreach ($this->clauses as $clause => $separator) {
			if (!empty($this->statements[$clause])) {
				if (method_exists($this, 'buildQuery' . $clause)) {
					$query .= call_user_func(array($this, 'buildQuery' . $clause), $this->statements[$clause]);
				} elseif (is_string($separator)) {
					$query .= " $clause " . implode($separator, $this->statements[$clause]);
				} elseif (null === $separator) {
					$query .= " $clause " . $this->statements[$clause];
				}
			}
		}

		return trim($query);
	}

	private function buildQueryWhere() {
		$closure = array();
		$sql = '';
		foreach ($this->statements['WHERE'] as $i => $wheregroup) {
			$where = array();
			if (!empty($wheregroup[1]) && $wheregroup[1] instanceof Closure) {
				$closure[] = $wheregroup;
			} else {
				$where = \SqlPaser::parseParameter($wheregroup[1], 'AND', $this->currentTableAlias);
				$this->parameters = array_merge($this->parameters, $where['params']);
				$sql .= ' ' . $wheregroup[0] . ' ' . $where['fields'];
			}
			unset($this->statements['WHERE'][$i]);
		}
		foreach ($closure as $callback) {
			$callback[1]($this);

			$subsql = '';
			$where = array();
			foreach ($this->statements['WHERE'] as $i => $wheregroup) {
				$where = \SqlPaser::parseParameter($wheregroup[1], 'AND', $this->currentTableAlias);
				$this->parameters = array_merge($this->parameters, $where['params']);
				$subsql .= ' ' . $wheregroup[0] . ' ' . $where['fields'];
				unset($this->statements['WHERE'][$i]);
			}
			$subsql = ltrim(ltrim($subsql, ' AND '), ' OR ');
			$sql .= " {$callback[0]} ( $subsql )";
		}

		return empty($where['fields']) ? '' : ' WHERE ' . ltrim(ltrim($sql, ' AND '), ' OR ');
	}

	private function buildQueryWhereor() {
		$where = \SqlPaser::parseParameter($this->statements['WHEREOR'], 'OR', $this->currentTableAlias);
		$this->parameters = array_merge($this->parameters, $where['params']);
		if (empty($where['fields'])) {
			return '';
		}
		if (empty($this->statements['WHERE'])) {
			return " WHERE {$where['fields']} ";
		} else {
			return " OR {$where['fields']} ";
		}
	}

	private function buildQueryHaving() {
		$where = \SqlPaser::parseParameter($this->statements['HAVING'], 'AND', $this->currentTableAlias);
		$this->parameters = array_merge($this->parameters, $where['params']);

		return empty($where['fields']) ? '' : " HAVING {$where['fields']} ";
	}

	private function buildQueryFrom() {
		return ' FROM ' . tablename($this->statements['FROM']) . ' ' . $this->currentTableAlias;
	}

	private function buildQueryLeftjoin() {
		return $this->buildQueryJoin('LEFTJOIN');
	}

	private function buildQueryInnerjoin() {
		return $this->buildQueryJoin('INNERJOIN');
	}

	private function buildQueryJoin($clause) {
		if (empty($this->statements[$clause])) {
			return '';
		}
		$clause_operator = array(
			'LEFTJOIN' => ' LEFT JOIN ',
			'INNERJOIN' => ' INNER JOIN ',
		);
		$sql = '';
		foreach ($this->statements[$clause] as $tablename) {
			list($tablename, $alias) = explode(' ', $tablename);
			$sql .= $clause_operator[$clause] . tablename($tablename) . ' ' . $alias;
			if (!empty($this->statements['ON'][$tablename])) {
				$sql .= ' ON ';
				$split = '';
				foreach ($this->statements['ON'][$tablename] as $field => $condition) {
					$operator = '';
					if (strexists($field, ' ')) {
						list($field, $operator) = explode(' ', $field);
					}
					$operator = $operator ? $operator : '=';
					$field = '`' . str_replace('.', '`.`', $field) . '`';
					if (strexists($condition, '.')) {
						$condition = '`' . str_replace('.', '`.`', $condition) . '`';
					}
					$sql .= " $split $field $operator $condition ";
					$split = ' AND ';
				}
			}
		}

		return $sql;
	}

	private function buildQuerySelect() {
		return \SqlPaser::parseSelect($this->statements['SELECT'], $this->currentTableAlias);
	}

	private function buildQueryLimit() {
		return \SqlPaser::parseLimit($this->statements['LIMIT'], false);
	}

	private function buildQueryPage() {
		return \SqlPaser::parseLimit($this->statements['PAGE'], true);
	}

	private function buildQueryOrderby() {
		return \SqlPaser::parseOrderby($this->statements['ORDERBY'], $this->currentTableAlias);
	}

	private function buildQueryGroupby() {
		return \SqlPaser::parseGroupby($this->statements['GROUPBY'], $this->currentTableAlias);
	}

	private function buildWhereArray() {
		$where = array();
		if (!empty($this->statements['WHERE'])) {
			foreach ($this->statements['WHERE'] as $row) {
				$where = array_merge($where, $row[1]);
			}
		}

		return $where;
	}

	public function getLastQuery() {
		return array($this->lastsql, $this->lastparams);
	}
}