<?php

class CACHE
{
	static public function load($keys)
	{
		global $_CACHE;

		if (!is_array($keys)) {
			$keys = array($keys);
		}

		$k_list = implode("', '", $keys);
		$query = DB::query("SELECT * FROM cache WHERE k IN ('$k_list')", "SILENT");

		while ($row = DB::fetch($query)) {
			$arr = @unserialize($row["v"]);
			$_CACHE[$row["k"]] = ($arr ? $arr : $row["v"]);
		}
	}

	static public function get($key)
	{
		global $_CACHE;

		if (isset($_CACHE[$key])) {
			return $_CACHE[$key];
		}

		$query = DB::query("SELECT v FROM cache WHERE k='$key'", "SILENT");
		$result = DB::fetch($query);
		$arr = @unserialize($result["v"]);
		$_CACHE[$key] = ($arr !== false ? $arr : $result["v"]);

		if (!$_CACHE[$key]) {
			return $_CACHE[$key] = self::update($key);
		}

		return $_CACHE[$key];
	}

	static public function save($key, $value)
	{
		if (is_array($value)) {
			$value = serialize($value);
		}

		$value = addslashes($value);
		DB::query("REPLACE INTO cache SET k='$key', v='$value'", "SILENT");
	}

	static public function update($key)
	{
		$builder_file = SYSTEM_ROOT . "./function/cache/cache_$key.php";

		if (file_exists($builder_file)) {
			$cache = array();
			include ($builder_file);
			self::save($key, $cache);
			return $cache;
		}
	}

	static public function clean($key)
	{
		DB::query("DELETE FROM cache WHERE k='$key'", "SILENT");
	}

	static public function clear()
	{
		DB::query("TRUNCATE TABLE cache", "SILENT");
	}
}

if (!defined("IN_KKFRAME")) {
	exit();
}

$_CACHE = array();

?>
