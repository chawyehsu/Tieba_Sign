<?php

class DEBUG
{
	public function INIT()
	{
		$GLOBALS["debug"]["time_start"] = self::getmicrotime();
		$GLOBALS["debug"]["query_num"] = 0;
	}

	public function getmicrotime()
	{
		list($usec, $sec) = explode(" ", microtime());
		return (double) $usec + (double) $sec;
	}

	public function output()
	{
		$return[] = "MySQL 请求 " . $GLOBALS["debug"]["query_num"] . " 次";
		$return[] = "运行时间：" . number_format(self::getmicrotime() - $GLOBALS["debug"]["time_start"], 6) . "秒";
		return implode(" , ", $return);
	}

	public function query_counter()
	{
		$$GLOBALS["debug"]["query_num"]++;
	}

	public function MSG($string)
	{
		if ($_GET["debug"]) {
			echo "{$string}\r\n";
		}
	}
}

if (!defined("IN_KKFRAME")) {
	exit();
}

?>
