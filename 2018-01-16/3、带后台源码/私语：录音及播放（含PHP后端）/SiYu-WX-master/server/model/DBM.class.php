<?php
	/**
	* 数据库管理类
	*/
	
	class DBM {

		//数据库连接
		private $link = null;

		/** 
	     * 构造函数
	     *  链接数据库
	     */  
		function __construct() {
			$this->link = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if ($this->link->connect_error) {
				$this->link = null;
				$result['success'] = false;
				$result['msg'] = 'mysqli connect failed!';
				return $result;
			}else{
				$this->link->autocommit(false);	//关闭自动提交
				$this->link->set_charset(CHARSET);	//设置字符集
				return true;
			}
		}

		/** 
	     * 查询
	     *  
	     * @param $sql sql语句
	     */  
		public function query($sql){
			$result = $this->link->query($sql);
			if (!$result){
				return false;
			}else{
				return $result->fetch_all(MYSQLI_ASSOC);
			}
		}

		/** 
	     * 更新表
	     *  
	     * @param $sql sql语句
	     */  
		public function update($sql){
			$result = $this->link->query($sql);
			return $result;
		}

		/** 
	     * 插入
	     *  
	     * @param $sql sql语句
	     */  
		public function insert($sql){
			$result = $this->link->query($sql);
			return $result;
		}

		/** 
	     * 开始事务
	     *  
	     */  
		public function begin_transaction(){
			$this->link->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		}

		/** 
	     * 提交事务
	     *  
	     */  
		public function commit(){
			$this->link->commit();
		}

		/** 
	     * 回滚事务
	     */  
		public function rollback(){
			$this->link->rollback();
		}

		/** 
	     * 最后一次插入id
	     */  
		public function last_insert_id(){
			return mysqli_insert_id($this->link);
		}

		/** 
	     * 析构函数
	     * 关闭数据库连接
	     */  
		function __destruct(){
			if (!is_null($this->link)){
				$this->link->close();
			}
		}
	}
?>