<?php

if (!defined("IN_KKFRAME")) {
	exit("Access Denied");
}

runquery("\r\nALTER TABLE `member_bind` ENGINE = InnoDB;\r\nALTER TABLE `update_source` ENGINE = InnoDB;\r\n\r\nCREATE TABLE IF NOT EXISTS `plugin_var` (\r\n  `pluginid` varchar(64) NOT NULL,\r\n  `key` varchar(32) NOT NULL DEFAULT '',\r\n  `value` text NOT NULL,\r\n  PRIMARY KEY (`pluginid`,`key`)\r\n) ENGINE=InnoDB DEFAULT CHARSET=utf8;\r\n");
savesetting("version", "1.14.2.6");
showmessage("成功更新到 1.14.2.6！", "./");

?>
