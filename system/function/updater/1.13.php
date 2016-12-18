<?php

if (!defined("IN_KKFRAME")) {
	exit("Access Denied");
}

if ($current_version == "1.13.9.5") {
	DB::query("ALTER TABLE `member_setting` ADD `zhidao_sign` TINYINT(1) NOT NULL DEFAULT '0'");
	DB::query("ALTER TABLE `member_setting` ADD `wenku_sign` TINYINT(1) NOT NULL DEFAULT '0'");
	savesetting("version", "1.13.9.6");
	showmessage("成功更新到 1.13.9.6！", "./", 1);
}
else if ($current_version == "1.13.9.6") {
	savesetting("version", "1.13.9.8");
	showmessage("成功更新到 1.13.9.8！", "./", 1);
}
else if ($current_version == "1.13.9.8") {
	DB::query("CREATE TABLE IF NOT EXISTS `cache` ( `k` varchar(32) NOT NULL, `v` TEXT NOT NULL, PRIMARY KEY (`k`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	savesetting("version", "1.13.9.23");
	showmessage("成功更新到 1.13.9.23！", "./", 1);
}
else if ($current_version == "1.13.9.23") {
	savesetting("version", "1.13.9.24");
	showmessage("成功更新到 1.13.9.24！", "./", 1);
}
else if ($current_version == "1.13.9.24") {
	$sql = "CREATE TABLE IF NOT EXISTS `cron` (\r\n  `id` varchar(16) NOT NULL,\r\n  `enabled` tinyint(1) NOT NULL,\r\n  `nextrun` int(10) unsigned NOT NULL,\r\n  `order` tinyint(4) NOT NULL,\r\n  PRIMARY KEY (`id`)\r\n) ENGINE=InnoDB DEFAULT CHARSET=utf8;\r\nINSERT INTO `cron` (`id`, `enabled`, `nextrun`, `order`) VALUES\r\n('daily', 1, 0, 0),\r\n('update_tieba', 1, 0, 10),\r\n('sign', 1, 0, 20),\r\n('ext_sign', 1, 0, 50),\r\n('mail', 1, 0, 100);\r\nCREATE TABLE IF NOT EXISTS `mail_queue` (\r\n  `id` int(11) NOT NULL AUTO_INCREMENT,\r\n  `to` varchar(255) NOT NULL,\r\n  `subject` varchar(255) NOT NULL,\r\n  `content` text NOT NULL,\r\n  PRIMARY KEY (`id`)\r\n) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
	runquery($sql);
	savesetting("version", "1.13.10.4");
	showmessage("成功更新到 1.13.10.4！<br><br>请修改计划任务为以下内容：<br>http://域名/cron.php &nbsp; * * * * *（每分钟一次）");
}
else if ($current_version == "1.13.9.4") {
	DB::insert("cron", array("id" => "sign_retry", "enabled" => 1, "nextrun" => TIMESTAMP, "order" => "110"));
	savesetting("version", "1.13.10.6");
	showmessage("成功更新到 1.13.10.6！", "./");
}
else if ($current_version == "1.13.10.6") {
	DB::query("ALTER TABLE `member` CHANGE `username` `username` VARCHAR(24)");
	savesetting("version", "1.13.10.13");
	showmessage("成功更新到 1.13.10.13！", "./", 1);
}
else if ($current_version == "1.13.10.13") {
	DB::query("ALTER TABLE `my_tieba` DROP INDEX `name`");
	DB::query("ALTER TABLE `my_tieba` ADD INDEX (`uid`)");
	DB::query("ALTER TABLE `member_setting` DROP `use_bdbowser`, DROP `sign_method`");
	savesetting("version", "1.13.10.20");
	showmessage("成功更新到 1.13.10.20！", "./");
}
else if ($current_version == "1.13.10.20") {
	savesetting("version", "1.13.11.5");
	showmessage("成功更新到 1.13.11.5！", "./");
}
else if ($current_version == "1.13.11.5") {
	DB::query("\r\nCREATE TABLE IF NOT EXISTS `plugin` (\r\n  id int(11) NOT NULL AUTO_INCREMENT,\r\n  `name` varchar(64) NOT NULL,\r\n  module text NOT NULL,\r\n  PRIMARY KEY (id),\r\n  UNIQUE KEY `name` (`name`)\r\n) ENGINE=InnoDB DEFAULT CHARSET=utf8\r\n");
	DB::insert("plugin", array("name" => "debug_info"));
	DB::insert("plugin", array("name" => "update_log"));
	savesetting("version", "1.13.11.9");
	showmessage("成功更新到 1.13.11.9！", "./");
}
else if ($current_version == "1.13.11.9") {
	runquery("\r\nALTER TABLE `plugin` ADD `enable` TINYINT(1) NOT NULL DEFAULT '1' AFTER `id`;\r\nALTER TABLE `plugin` ADD `version` VARCHAR(8) NOT NULL DEFAULT '0';\r\nALTER TABLE `member_setting` ADD `cookie` TEXT BINARY CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;\r\n");
	$query = DB::query("SELECT uid, cookie FROM member");

	while ($result = DB::fetch($query)) {
		save_cookie($result["uid"], $result["cookie"]);
	}

	DB::query("ALTER TABLE `member` DROP `cookie`");
	$query = DB::query("SHOW columns FROM `plugin`");

	while ($result = DB::fetch($query)) {
		if ($result["Field"] == "module") {
			DB::query("ALTER TABLE `plugin` DROP `module`");
		}
	}

	CACHE::clear();
	CACHE::update("plugins");
	savesetting("register_limit", 1);
	savesetting("register_check", 1);
	savesetting("jquery_mode", 2);
	savesetting("version", "1.13.12.15");
	showmessage("成功更新到 1.13.12.15！", "./");
}
else if ($current_version == "1.13.12.15") {
	savesetting("version", "1.13.12.25");
	showmessage("成功更新到 1.13.12.25！", "./");
}
else if ($current_version == "1.13.12.25") {
	if ($_config["adminid"]) {
		savesetting("admin_uid", $_config["adminid"]);
	}

	savesetting("version", "1.14.1.15");
	showmessage("成功更新到 1.14.1.15！", "./");
}

?>
