<?php
use \PDO;

class Model{
	public $db;

	public $table;

	public $where;
	public $sql;
	public $last_insert_id;
	public $last_exec;

	/**
	 * 注入或自定义
	 * @param database $db  注入的数据库连接
	 * @param array $config 自定义配置
	 */
	public function __construct($db=null, $config=null){
		if (!is_null($db)){
			$this->db = $db;
		} else if (!is_null($config)){
			$this->db = $this->getInstance($config);
		}
	}

	public function getInstance($config){
		$this->db = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", 
							$config['user'],
							$config['password']);
		$this->exec("set names utf-8");
		if (isset($config['table'])){
			$this->table = $config['table'];
		}
		return $this->db;
	}

	public function select($column='*', $where=null, $append=null){
		$this->checkTable();

		$sql = "select ";
		$sql .= $this->buildColumn($column);
		$sql .= " from " . $this->table;
		if (!is_null($where)){
			$sql .= $this->buildWhere($where);
		}
		if (!is_null($append)){
			$sql .= " " . $append;
		}
		$this->sql = $sql;
		$statement = $this->query($sql);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		return $statement->fetchAll();
	}

	public function find($column='*', $where=null, $append=null){
		if (is_null($append))
			$append = "limit 1";
		else {
			$start = '-' . strlen('limit 1');
			if (substr(rtrim($append), $start) != 'limit 1')
				$append .= ' limit 1';
		}
		$find_arr = $this->select($column, $where, $append);
		if (!empty($find_arr))
			return $find_arr[0];
		return $find_arr;
	}

	/**
	 * buildColumn('*')
	 *                    -> '*'
	 * buildColumn(['id', 'name'])
	 *                    -> "`id`,`name`"
	 * buildColumn(['count(*) as count', 'id'])
	 *                    -> 'count(*) as count,`id`'
	 */
	public function buildColumn($column){
		if (is_string($column)){
			return $column;
		}
		$col = '';
		foreach ($column as $v) {
			if (strpos($v, '(') !== false)
				continue;
			$col .= '`' . $v . '`,';
		}
		$col = substr($col, 0, -1);
		return $col;
	}

	/**
	 * bulidWhere(array("key", "value"))
	 *                          -> key"value"
	 * bulidWhere(array("id!", "value")) 
	 *                          -> id!='value'
	 */
	public function buildWhere($where){
		$this->where = $where;
		if (is_string($where)){
			return ' where ' . $where;
		}
		$wh = ' where ';
		foreach ($where as $condition => $value) {
			$wh .= $condition . $this->quote($value) . ' ';
		}
		return $wh;
	}

	public function insert($data){
		$this->checkTable();

		$columns = $this->buildColumn(array_keys($data));
		$values = join(", ", $this->quoteArrayValue(array_values($data)));
		$this->sql = "insert into " . $this->table . "(" . $columns . ") values(" . $values . ")";
		$this->exec($this->sql);
		$this->last_insert_id = $this->lastInsertId();
		return $this->last_insert_id;
	}

	public function update($data, $where){
		$this->checkTable();

		$where = $this->buildWhere($where);
		$sql = "update " . $this->table . " set ";
		foreach ($data as $column => $value) {
			$sql .= $this->buildColumn($column) . "=" . $this->quote($value) . ",";
		}
		$this->sql = substr($sql, 0, -1) . ' ' . $where;
		return $this->exec($this->sql);
	}

	public function save($data){
		if (empty($this->where)){
			return $this->insert($data);
		} else {
			return $this->update($data, $this->where);
		}
	}

	/**
	 * PDO method
	 */
	public function __call($method, $param){
		if (!isset($this->db))
			throw new Exception("没有数据库连接");
		return call_user_func_array(array($this->db, $method), $param);
	}

	public function quoteArrayValue($arr){
		$quoted = array();
		foreach ($arr as $k => $v) {
			$quoted[$k] = $this->quote($v);
		}
		return $quoted;
	}

	protected function checkTable(){
		if (!isset($this->table)){
			throw new Exception("there is no table property");
			
		}
	}
}
