<?php

class Plugin
{
	public $name;
	public $description;
	public $modules = array();
	public $version = "0";

	public function getSetting($key, $default_value = false)
	{
		$vars = CACHE::get("plugin");
		$vars = $vars[$this->getPluginId()];
		return isset($vars[$key]) ? $vars[$key] : $default_value;
	}

	public function saveSetting($key, $value)
	{
		$pluginid = $this->getPluginId();
		$vars = CACHE::get("plugin");

		if (!$vars) {
			$vars = array();
		}

		if (!$vars[$pluginid]) {
			$vars[$pluginid] = array();
		}

		$vars[$pluginid][$key] = $value;
		DB::query("REPLACE INTO plugin_var SET `key` = '" . addslashes($key) . "', `value` = '" . addslashes($value) . "', pluginid='" . addslashes($pluginid) . "'");
		CACHE::clean("plugin");
	}

	private function getPluginId()
	{
		return str_replace("plugin_", "", get_class($this));
	}
}

if (!defined("IN_KKFRAME")) {
	exit();
}

?>
