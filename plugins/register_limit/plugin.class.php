<?php

if (!defined("IN_KKFRAME")) {
	exit("Access Denied!");
}
class plugin_register_limit extends Plugin
{
	public $description = "限制单个 IP 的注册上限";
	public $modules = array();

	public function on_install()
	{
		DB::query("CREATE TABLE IF NOT EXISTS `kk_ip_limit` ( `a` tinyint(3) unsigned NOT NULL, `b` tinyint(3) unsigned NOT NULL, `c` tinyint(3) unsigned NOT NULL, `d` tinyint(3) unsigned NOT NULL, `count` tinyint(3) unsigned NOT NULL, `lastact` int(10) unsigned NOT NULL, UNIQUE KEY `ip` (`a`,`b`,`c`,`d`)) ENGINE=MEMORY DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	}

	public function on_uninstall()
	{
		DB::query("DROP TABLE kk_ip_limit");
	}

	public function on_load()
	{
		if ($_GET["action"] != "register") {
			return NULL;
		}

		if (!$_POST) {
			return NULL;
		}

		list($a, $b, $c, $d) = explode(".", $_SERVER["REMOTE_ADDR"]);
		$a = intval($a);
		$b = intval($b);
		$c = intval($c);
		$d = intval($d);
		$count = DB::result_first("SELECT count FROM kk_ip_limit WHERE a='$a' AND b='$b' AND c='$c' AND d='$d'");
		$time = TIMESTAMP;
		DB::query("DELETE FROM kk_ip_limit WHERE lastact<$time-86400");

		if (0 < $count) {
			DB::query("UPDATE kk_ip_limit SET lastact='$time' WHERE a='$a' AND b='$b' AND c='$c' AND d='$d'");
		}

		if ($this->getSetting("ip_reglimit", 5) <= $count) {
			showmessage("达到单 IP 注册上限，禁止注册。", dreferer());
		}

		if (0 < $count) {
			DB::query("UPDATE kk_ip_limit SET count=count+1 WHERE a='$a' AND b='$b' AND c='$c' AND d='$d'");
		}
		else {
			DB::query("INSERT INTO kk_ip_limit SET count=1, lastact='$time', a='$a', b='$b', c='$c', d='$d'");
		}
	}

	public function on_config()
	{
		if ($_POST["limit"]) {
			$this->saveSetting("ip_reglimit", $_POST["limit"]);
			showmessage("设置已经保存！");
		}
		else {
			return "<p>单个 IP 注册上限：<input type=\"text\" name=\"limit\" value=\"" . $this->getSetting("ip_reglimit", 5) . "\" /></p>";
		}
	}
}


?>
