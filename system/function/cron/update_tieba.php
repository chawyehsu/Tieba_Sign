<?php

if (!defined("IN_KKFRAME")) {
	exit();
}

if (getsetting("autoupdate")) {
	$num = 0;
	$_uid = (getsetting("autoupdate_uid") ? getsetting("autoupdate_uid") : 1);

	while ($_uid) {
		update_liked_tieba($_uid, true, false);
		$_uid = DB::result_first("SELECT uid FROM member WHERE uid>'$_uid' ORDER BY uid ASC LIMIT 0,1");
		savesetting("autoupdate_uid", $_uid);

		if (20 < ++$num) {
			exit();
		}
	}

	savesetting("autoupdate_uid", 0);
	define("CRON_FINISHED", true);
}
else {
	define("CRON_FINISHED", true);
}

?>
