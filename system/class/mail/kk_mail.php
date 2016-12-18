<?php

if (!defined("IN_KKFRAME")) {
	exit("Access Denied");
}
class kk_mail extends mailer
{
	public $id = "kk_mail";
	public $name = "KK Mailer";
	public $description = "KK 提供的邮件代理发送邮件 (发送者显示 KK-Open-Mail-System &lt;open_mail_api@ikk.me&gt;)";
	public $config = array();

	public function isAvailable()
	{
		return true;
	}

	public function send($mail)
	{
		$result = cloud::request_public("mail", $mail->address, $mail->subject, $mail->message, VERSION);
		return $result["status"] == "ok";
	}
}


?>
