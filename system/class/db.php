<?php

class db_mysql
{
	public $curlink;
	public $last_query;

	public function connect()
	{
		global $_config;
		$this->curlink = $this->_dbconnect($_config["db"]["server"] . ":" . $_config["db"]["port"], $_config["db"]["username"], $_config["db"]["password"], "utf8", $_config["db"]["name"], $_config["db"]["pconnect"]);
	}

	public function _dbconnect($dbhost, $dbuser, $dbpw, $dbcharset, $dbname, $pconnect)
	{
		$link = NULL;
		$func = (empty($pconnect) ? "mysql_connect" : "mysql_pconnect");

		if (!$link = @$func($dbhost, $dbuser, $dbpw, 1)) {
			$this->halt("Couldn't connect to MySQL Server");
		}
		else {
			$this->curlink = $link;

			if ("4.1" < $this->version()) {
				$serverset = ($dbcharset ? "character_set_connection=" . $dbcharset . ", character_set_results=" . $dbcharset . ", character_set_client=binary" : "");
				$serverset .= ("5.0.1" < $this->version() ? (empty($serverset) ? "" : ",") . "sql_mode=''" : "");
				$serverset && mysql_query("SET $serverset", $link);
			}

			$dbname && @mysql_select_db($dbname, $link);
		}

		return $link;
	}

	public function select_db($dbname)
	{
		return mysql_select_db($dbname, $this->curlink);
	}

	public function fetch_array($query, $result_type = MYSQL_ASSOC)
	{
		return mysql_fetch_array($query, $result_type);
	}

	public function fetch_first($sql)
	{
		return $this->fetch_array($this->query($sql));
	}

	public function result_first($sql)
	{
		return $this->result($this->query($sql), 0);
	}

	public function query($sql, $type = "")
	{
		$func = (($type == "UNBUFFERED") && @function_exists("mysql_unbuffered_query") ? "mysql_unbuffered_query" : "mysql_query");

		if (!$this->curlink) {
			$this->connect();
		}

		if (!$query = $func($sql, $this->curlink)) {
			if ($type != "SILENT") {
				$this->halt("MySQL Query ERROR", $sql);
			}
		}

		DEBUG::query_counter();
		return $this->last_query = $query;
	}

	public function affected_rows()
	{
		return mysql_affected_rows($this->curlink);
	}

	public function error()
	{
		return $this->curlink ? mysql_error($this->curlink) : mysql_error();
	}

	public function errno()
	{
		return intval($this->curlink ? mysql_errno($this->curlink) : mysql_errno());
	}

	public function result($query, $row = 0)
	{
		$query = @mysql_result($query, $row);
		return $query;
	}

	public function num_rows($query)
	{
		$query = mysql_num_rows($query);
		return $query;
	}

	public function num_fields($query)
	{
		return mysql_num_fields($query);
	}

	public function free_result($query)
	{
		return mysql_free_result($query);
	}

	public function insert_id()
	{
		return 0 <= $id = mysql_insert_id($this->curlink) ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	public function fetch_row($query)
	{
		$query = mysql_fetch_row($query);
		return $query;
	}

	public function fetch_fields($query)
	{
		return mysql_fetch_field($query);
	}

	public function version()
	{
		if (empty($this->version)) {
			$this->version = mysql_get_server_info($this->curlink);
		}

		return $this->version;
	}

	public function close()
	{
		return mysql_close($this->curlink);
	}

	public function halt($message = "", $sql = "")
	{
		error::db_error($message, $sql);
	}

	public function __destruct()
	{
		$this->close();
	}
}

class DB
{
	public function delete($table, $condition, $limit = 0, $unbuffered = true)
	{
		if (empty($condition)) {
			$where = "1";
		}
		else if (is_array($condition)) {
			$where = DB::implode_field_value($condition, " AND ");
		}
		else {
			$where = $condition;
		}

		$sql = "DELETE FROM $table WHERE $where " . ($limit ? "LIMIT $limit" : "");
		return DB::query($sql, $unbuffered ? "UNBUFFERED" : "");
	}

	public function insert($table, $data, $return_insert_id = true, $replace = false, $silent = false)
	{
		$sql = DB::implode_field_value($data);
		$cmd = ($replace ? "REPLACE INTO" : "INSERT INTO");
		$silent = ($silent ? "SILENT" : "");
		$return = DB::query("$cmd $table SET $sql", $silent);
		return $return_insert_id ? DB::insert_id() : $return;
	}

	public function update($table, $data, $condition, $unbuffered = false, $low_priority = false)
	{
		$sql = DB::implode_field_value($data);
		$cmd = "UPDATE " . ($low_priority ? "LOW_PRIORITY" : "");
		$where = "";

		if (empty($condition)) {
			$where = "1";
		}
		else if (is_array($condition)) {
			$where = DB::implode_field_value($condition, " AND ");
		}
		else {
			$where = $condition;
		}

		$res = DB::query("$cmd $table SET $sql WHERE $where", $unbuffered ? "UNBUFFERED" : "");
		return $res;
	}

	public function implode_field_value($array, $glue = ",")
	{
		$sql = $comma = "";

		foreach ($array as $k => $v ) {
			$sql .= $comma . "`$k`='$v'";
			$comma = $glue;
		}

		return $sql;
	}

	public function insert_id()
	{
		return DB::_execute("insert_id");
	}

	public function fetch($resourceid, $type = MYSQL_ASSOC)
	{
		return DB::_execute("fetch_array", $resourceid, $type);
	}

	public function fetch_first($sql)
	{
		return DB::_execute("fetch_first", $sql);
	}

	public function fetch_all($sql)
	{
		$query = DB::_execute("query", $sql);
		$return = array();

		while ($result = DB::fetch($query)) {
			$return[] = $result;
		}

		return $return;
	}

	public function result($resourceid, $row = 0)
	{
		return DB::_execute("result", $resourceid, $row);
	}

	public function result_first($sql)
	{
		return DB::_execute("result_first", $sql);
	}

	public function query($sql, $type = "")
	{
		return DB::_execute("query", $sql, $type);
	}

	public function num_rows($resourceid)
	{
		return DB::_execute("num_rows", $resourceid);
	}

	public function affected_rows()
	{
		return DB::_execute("affected_rows");
	}

	public function free_result($query)
	{
		return DB::_execute("free_result", $query);
	}

	public function error()
	{
		return DB::_execute("error");
	}

	public function errno()
	{
		return DB::_execute("errno");
	}

	public function _execute($cmd, $arg1 = "", $arg2 = "")
	{
		static $db;

		if (empty($db)) {
			$db = &DB::object();
		}

		$res = $db->{$cmd}($arg1, $arg2);
		return $res;
	}

	public function object()
	{
		static $db;

		if (empty($db)) {
			$db = new db_mysql();
		}

		return $db;
	}
}

if (!defined("IN_KKFRAME")) {
	exit();
}

?>
