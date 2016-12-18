<?php

if (!defined("IN_KKFRAME")) {
	exit();
}

$num = 0;
$_uid = (getsetting("extsign_uid") ? getsetting("extsign_uid") : 1);

while ($_uid) {
	if (20 < ++$num) {
		exit();
	}

	$setting = get_setting($_uid);

	if ($setting["zhidao_sign"]) {
		zhidao_sign($_uid);
	}

	if ($setting["wenku_sign"]) {
		wenku_sign($_uid);
	}

	$_uid = DB::result_first("SELECT uid FROM member WHERE uid>'$_uid' ORDER BY uid ASC LIMIT 0,1");
	savesetting("extsign_uid", $_uid);
}

define("CRON_FINISHED", true);

?>
