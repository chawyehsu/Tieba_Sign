<?php

class mailer
{
	public $_setting;

	public function isAvailable()
	{
		return false;
	}

	public function send()
	{
		return false;
	}

	public function _get_setting($key)
	{
		if (!$this->_setting) {
			$this->_load_setting();
		}

		return $this->_setting[$key];
	}

	public function _load_setting()
	{
		$this->_setting = CACHE::get("mail_" . $this->id);

		if ($this->_setting) {
			return NULL;
		}

		$this->_setting = array();

		if ($this->config) {
			foreach ($this->config as $k => $v ) {
				$this->_setting[$v[1]] = $v[3];
			}
		}

		$class = getsetting("mail_class");
		$query = DB::query("SELECT * FROM setting WHERE k LIKE '_mail_{$class}_%'");

		while ($result = DB::fetch($query)) {
			$key = str_replace("_mail_{$class}_", "", $result["k"]);
			$this->_setting[$key] = $result["v"];
		}

		CACHE::save("mail_" . $this->id, $this->_setting);
	}
}

class mail_content
{
	public $address;
	public $subject;
	public $message;
}

class mail_sender
{
	public $obj;

	public function __construct()
	{
		$sender = getsetting("mail_class");
		$file = SYSTEM_ROOT . "./class/mail/$sender.php";

		if (file_exists($file)) {
			require_once ($file);
			$this->obj = new $sender();
		}
	}

	public function sendMail($mail)
	{
		if (!$this->obj) {
			return false;
		}

		return $this->obj->send($mail);
	}
}

if (!defined("IN_KKFRAME")) {
	exit();
}

?>
