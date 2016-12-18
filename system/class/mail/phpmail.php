<?php

if (!defined("IN_KKFRAME")) {
	exit("Access Denied");
}
class phpmail extends mailer
{
	public $id = "phpmail";
	public $name = "PHP Mail()";
	public $description = "通过 PHP 的 Mail() 函数发送邮件";
	public $config = array(
		array("发件人地址", "from", "", "system@domain.com")
		);

	public function isAvailable()
	{
		return function_exists("mail");
	}

	public function send($mail)
	{
		$address = $mail->address;
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html;charset=utf-8\r\n";
		$headers .= "Content-Transfer-Encoding: Base64\r\n";
		$headers .= "From: =?UTF-8?B?" . base64_encode("贴吧签到助手") . "?= <" . $this->_get_setting("from") . ">\r\n";
		return mail($address, "=?UTF-8?B?" . base64_encode($mail->subject) . "?=", base64_encode($mail->message), $headers);
	}
}


?>
