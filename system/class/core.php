<?php

class core
{
	public function core()
	{
		global $_config;
		global $template_loaded;
		require_once (SYSTEM_ROOT . "./config.inc.php");
		DEBUG::INIT();
		require_once (SYSTEM_ROOT . "./function/core.php");
		CACHE::load(array("plugins", "setting"));
		$this->init_header();
		$this->init_useragent();
		Updater::init();
		$this->init_syskey();
		$this->init_cookie();
		cloud::init();
		HOOK::INIT();
		$template_loaded = true;
		$this->init_final();
	}

	public function __destruct()
	{
		global $template_loaded;

		if (!defined("SYSTEM_STARTED")) {
			return NULL;
		}

		if (!$template_loaded) {
			error::system_error("Undefined error.");
		}

		HOOK::run("on_unload");
		flush();
		ob_end_flush();
		$this->init_cron();
		$this->init_mail();
	}

	public function init_header()
	{
		ob_start();
		header("Content-type: text/html; charset=utf-8");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Cache-Control: no-cache");
		header("Pragma: no-cache");
		@date_default_timezone_set("Asia/Shanghai");
	}

	public function init_useragent()
	{
		$ua = strtolower($_SERVER["HTTP_USER_AGENT"]);
		if (strpos($ua, "wap") || strpos($ua, "mobi") || strpos($ua, "opera") || $_GET["mobile"]) {
			define("IN_MOBILE", true);
		}
		else {
			define("IN_MOBILE", false);
		}

		if (strpos($ua, "bot") || strpos($ua, "spider")) {
			define("IN_ROBOT", true);
		}
	}

	public function init_syskey()
	{
		define("ENCRYPT_KEY", getsetting("SYS_KEY"));
	}

	public function init_cookie()
	{
		global $cookiever;
		global $uid;
		global $username;
		$cookiever = "2";

		if (!empty($_COOKIE["token"])) {
			list($cc, $uid, $username, $exptime, $password) = explode("\t", authcode($_COOKIE["token"], "DECODE"));
			if (!$uid || ($cc != $cookiever)) {
				unset($uid);
				unset($username);
				unset($exptime);
				dsetcookie("token");
			}
			else if ($exptime < TIMESTAMP) {
				$user = DB::fetch_first("SELECT * FROM member WHERE uid='$uid'");
				$_password = substr(md5($user["password"]), 8, 8);
				if ($user && ($password == $_password)) {
					$exptime = TIMESTAMP + 900;
					dsetcookie("token", authcode("{$cookiever}\t$uid\t$user[username]\t$exptime\t$password", "ENCODE"));
				}
				else {
					unset($uid);
					unset($username);
					unset($exptime);
					dsetcookie("token");
				}
			}
		}
		else {
			$uid = $username = "";
		}
	}

	public function init_final()
	{
		define("SYSTEM_STARTED", true);
		@ignore_user_abort(true);
		HOOK::run("on_load");
	}

	public function init_cron()
	{
		if (defined("DISABLE_CRON")) {
			return NULL;
		}

		$n = mktime(0, 0, 0);
		$p = TIMESTAMP;
		$c = getsetting("next_cron");
		$d = date("Ymd", TIMESTAMP);
		$dd = getsetting("date");

		if ($d != $dd) {
			$r = $n + 1800;
			DB::query("UPDATE cron SET enabled='1', nextrun='$r'");
			DB::query("UPDATE cron SET nextrun='$n' WHERE id='daily'");
			savesetting("date", $d);
			savesetting("next_cron", TIMESTAMP);
			return NULL;
		}

		if ($p < $c) {
			return NULL;
		}

		$t = DB::fetch_first("SELECT * FROM cron WHERE enabled='1' AND nextrun<'$p' ORDER BY `order` LIMIT 0,1");
		$s = SYSTEM_ROOT . "./function/cron/$t[id].php";

		if (file_exists($s)) {
			include ($s);
		}
		else {
			define("CRON_FINISHED", true);
		}

		if (defined("CRON_FINISHED")) {
			DB::query("UPDATE cron SET enabled='0' WHERE id='$t[id]'");
		}

		$r = DB::fetch_first("SELECT nextrun FROM cron WHERE enabled='1' ORDER BY nextrun ASC LIMIT 0,1");
		savesetting("next_cron", $r ? $r["nextrun"] : TIMESTAMP + 1200);
	}

	public function init_mail()
	{
		$q = getsetting("mail_queue");

		if (!$q) {
			return NULL;
		}

		$m = DB::fetch_first("SELECT * FROM mail_queue LIMIT 0,1");

		if ($m) {
			DB::query("DELETE FROM mail_queue WHERE id='$m[id]'");
			send_mail($m["to"], $m["subject"], $m["content"], false);
		}
		else {
			savesetting("mail_queue", 0);
		}
	}
}

if (!defined("IN_KKFRAME")) {
	exit();
}

?>
