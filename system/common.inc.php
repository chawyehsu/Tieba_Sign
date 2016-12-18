<?php

function class_loader($class_name)
{
	list($type, $plugin_id) = explode("_", $class_name, 2);

	if ($type == "plugin") {
		$file_path = "plugins/$plugin_id/plugin.class.php";
	}
	else {
		if (($type == "mail") || ($class_name == "mailer")) {
			$file_path = "system/class/mail.php";
		}
		else {
			$file_path = "system/class/$class_name.php";
		}
	}

	$real_path = ROOT . strtolower($file_path);

	if (!file_exists($real_path)) {
		throw new Exception("Ooops, system file is losing: " . strtolower($file_path));
	}
	else {
		require_once ($real_path);
	}
}

error_reporting(30719 ^ 8);
define("IN_KKFRAME", true);
define("SYSTEM_ROOT", dirname(__FILE__) . "/");
define("ROOT", dirname(SYSTEM_ROOT) . "/");
define("TIMESTAMP", time());
define("VERSION", "1.14.2.6");
define("DEBUG_ENABLED", isset($_GET["debug"]));
error_reporting(DEBUG_ENABLED ? 1 | 2 | 4 : 1 | 4);
require_once (SYSTEM_ROOT . "./class/error.php");
set_exception_handler(array("error", "exception_error"));

if (!file_exists(SYSTEM_ROOT . "./config.inc.php")) {
	header("Location: ./install/");
	exit();
}

if (function_exists("spl_autoload_register")) {
	spl_autoload_register("class_loader");
	function __autoload($class_name)
	{
		class_loader($class_name);
	}
}
else {
	function __autoload($class_name)
	{
		class_loader($class_name);
	}
}

$system = new core();
$formhash = substr(md5(substr(TIMESTAMP, 0, -7) . $username . $uid . ENCRYPT_KEY . ROOT), 8, 8);
$sitepath = substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"));
$siteurl = htmlspecialchars("http://" . $_SERVER["HTTP_HOST"] . $sitepath . "/");

?>
