<?php

if (!defined("IN_KKFRAME")) {
	exit("Access Denied");
}

runquery("\r\nCREATE TABLE IF NOT EXISTS `download` (\r\n  `path` varchar(128) NOT NULL,\r\n  `content` text NOT NULL,\r\n  PRIMARY KEY (`path`)\r\n) ENGINE=InnoDB DEFAULT CHARSET=utf8;\r\n\r\nCREATE TABLE IF NOT EXISTS `update_source` (\r\n  `id` varchar(16) NOT NULL,\r\n  `path` varchar(128) NOT NULL,\r\n  PRIMARY KEY (`id`)\r\n) ENGINE=InnoDB DEFAULT CHARSET=utf8;\r\n");
savesetting("version", "1.14.1.23");
showmessage("成功更新到 1.14.1.23！", "./");

?>
