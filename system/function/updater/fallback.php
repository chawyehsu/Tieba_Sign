<?php

if (!defined("IN_KKFRAME")) {
	exit("Access Denied");
}

if (!$current_version) {
	if (getsetting("version")) {
		saveversion(getsetting("version"));
		header("Location: ./");
		exit();
	}
}

throw new Exception("找不到更新程序，无法进行更新！<br>Error while upgrade from version $current_version to version " . VERSION . ".");

?>
