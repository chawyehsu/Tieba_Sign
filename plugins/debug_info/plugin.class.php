<?php

class plugin_debug_info
{
	public $description = "此插件将会在页脚输出一行调试信息，便于了解服务器状态";
	public $modules = array();

	public function page_footer()
	{
		return DEBUG::output();
	}
}

if (!defined("IN_KKFRAME")) {
	exit("Access Denied!");
}

?>
