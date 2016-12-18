<?php

if (!defined("IN_ADMINCP")) {
	exit();
}

echo "<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>管理中心 - 贴吧签到助手</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" />\r\n<meta name=\"HandheldFriendly\" content=\"true\" />\r\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\" />\r\n<meta name=\"author\" content=\"kookxiang\" />\r\n<meta name=\"copyright\" content=\"KK's Laboratory\" />\r\n<link rel=\"shortcut icon\" href=\"favicon.ico\" />\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\" />\r\n<meta name=\"renderer\" content=\"webkit\">\r\n<link rel=\"stylesheet\" href=\"./style/main.css?version=";
echo VERSION;
echo "\" type=\"text/css\" />\r\n<link rel=\"stylesheet\" href=\"./style/custom.css\" type=\"text/css\" />\r\n</head>\r\n<body>\r\n<div class=\"wrapper\" id=\"page_index\">\r\n<div id=\"append_parent\"><div class=\"cover hidden\"></div><div class=\"loading-icon\"><img src=\"style/loading.gif\" /> 载入中...</div></div>\r\n<div class=\"main-box clearfix\">\r\n<h1>贴吧签到助手 - 管理中心</h1>\r\n<div class=\"menubtn\"><p>-</p><p>-</p><p>-</p></div>\r\n<div class=\"main-wrapper\">\r\n<div class=\"sidebar\">\r\n<ul id=\"menu\" class=\"menu\">\r\n<li id=\"menu_user\"><a href=\"#user\">用户管理</a></li>\r\n<li id=\"menu_stat\"><a href=\"#stat\">用户签到统计</a></li>\r\n<li id=\"menu_plugin\"><a href=\"#plugin\">插件管理</a></li>\r\n<li id=\"menu_setting\"><a href=\"#setting\">系统设置</a></li>\r\n<li id=\"menu_mail\"><a href=\"#mail\">邮件群发</a></li>\r\n<li id=\"menu_updater\"><a href=\"#updater\">检查更新</a></li>\r\n<li><a href=\"./\">返回前台</a></li>\r\n</ul>\r\n</div>\r\n<div class=\"main-content\">\r\n<div id=\"content-user\">\r\n<h2>用户管理</h2>\r\n<table>\r\n<thead><tr><td style=\"width: 40px\">UID</td><td>用户名</td><td>邮箱</td><td>操作</td></tr></thead>\r\n<tbody></tbody>\r\n</table>\r\n</div>\r\n<div id=\"content-stat\" class=\"hidden\">\r\n<h2>用户签到统计</h2>\r\n<table>\r\n<thead><tr><td style=\"width: 40px\">UID</td><td>用户名</td><td>已成功</td><td>已跳过</td><td>待签到</td><td>待重试</td><td>不支持</td></tr></thead>\r\n<tbody></tbody>\r\n</table>\r\n</div>\r\n<div id=\"content-setting\" class=\"hidden\">\r\n<h2>系统设置</h2>\r\n<form method=\"post\" action=\"admin.php?action=save_setting\" id=\"setting_form\" onsubmit=\"return post_win(this.action, this.id)\">\r\n<p>功能增强:</p>\r\n<input type=\"hidden\" name=\"formhash\" value=\"";
echo $formhash;
echo "\">\r\n<p><label><input type=\"checkbox\" id=\"account_switch\" name=\"account_switch\" /> 允许多用户切换</label></p>\r\n<p><label><input type=\"checkbox\" id=\"autoupdate\" name=\"autoupdate\" /> 每天自动更新用户喜欢的贴吧 (稍占服务器资源)</label></p>\r\n<p>功能限制:</p>\r\n<p>\r\n<select name=\"max_tieba\" id=\"max_tieba\">\r\n<option value=\"0\" selected>不限制单用户的最大喜欢贴吧数量</option>\r\n<option value=\"50\">每个用户最多喜欢 50 个贴吧</option>\r\n<option value=\"80\">每个用户最多喜欢 80 个贴吧</option>\r\n<option value=\"100\">每个用户最多喜欢 100 个贴吧</option>\r\n<option value=\"120\">每个用户最多喜欢 120 个贴吧</option>\r\n<option value=\"180\">每个用户最多喜欢 180 个贴吧</option>\r\n<option value=\"250\">每个用户最多喜欢 250 个贴吧</option>\r\n</select>\r\n</p>\r\n<p>防恶意注册:</p>\r\n<p><label><input type=\"checkbox\" id=\"block_register\" name=\"block_register\" /> 彻底关闭新用户注册功能</label></p>\r\n<p><label><input type=\"checkbox\" id=\"register_check\" name=\"register_check\" /> 启用内置的简单防恶意注册系统 (可能会导致无法注册)</label></p>\r\n<p><label><input type=\"checkbox\" id=\"register_limit\" name=\"register_limit\" /> 限制并发注册 (开启后可限制注册机注册频率)</label></p>\r\n<p><input type=\"text\" name=\"invite_code\" id=\"invite_code\" placeholder=\"邀请码 (留空为不需要)\" /></p>\r\n<p>jQuery 加载方式:</p>\r\n<p><label><input type=\"radio\" id=\"jquery_1\" name=\"jquery_mode\" value=\"1\" /> 从 Google API 提供的 CDN 加载 (默认, 推荐)</label></p>\r\n<p><label><input type=\"radio\" id=\"jquery_2\" name=\"jquery_mode\" value=\"2\" /> 从 Sina App Engine 提供的 CDN 加载</label></p>\r\n<p><label><input type=\"radio\" id=\"jquery_3\" name=\"jquery_mode\" value=\"3\" /> 从 Baidu App Engine 提供的 CDN 加载 (不支持 SSL)</label></p>\r\n<p><label><input type=\"radio\" id=\"jquery_4\" name=\"jquery_mode\" value=\"4\" /> 使用程序自带的 jQuery 类库 (推荐)</label></p>\r\n<p>网站备案编号:</p>\r\n<p><input type=\"text\" id=\"beian_no\" name=\"beian_no\" placeholder=\"未备案的不需要填写\" /></p>\r\n<p>自定义统计代码:</p>\r\n<p><textarea name=\"stat_code\" id=\"stat_code\" rows=\"3\" style=\"width: 300px; max-width: 100%;\"></textarea></p>\r\n<p><input type=\"submit\" value=\"保存设置\" /></p>\r\n</form>\r\n<br>\r\n<p>邮件发送方式:</p>\r\n<form method=\"post\" action=\"admin.php?action=mail_setting\" id=\"mail_setting\" onsubmit=\"return post_win(this.action, this.id)\">\r\n<input type=\"hidden\" name=\"formhash\" value=\"";
echo $formhash;
echo "\">\r\n";

foreach ($classes as $id => $obj ) {
	$desc = ($obj->description ? " - " . $obj->description : "");

	if (!$obj->isAvailable()) {
		$desc = " (当前服务器环境不支持)";
	}

	echo "<p><label><input type=\"radio\" name=\"mail_sender\" value=\"" . $id . "\"" . ($obj->isAvailable() ? "" : " disabled") . ($id == getsetting("mail_class") ? " checked" : "") . " /> " . $obj->name . $desc . "</label></p>";
}

echo "<p>\r\n<input type=\"submit\" value=\"保存设置\" />\r\n &nbsp; <a href=\"javascript:;\" class=\"btn\" id=\"mail_advanced_config\">高级设置</a>\r\n &nbsp; <a href=\"admin.php?action=mail_test&formhash=";
echo $formhash;
echo "\" class=\"btn\" onclick=\"return msg_win_action(this.href)\">发送测试</a>\r\n</p>\r\n</form>\r\n</div>\r\n<div id=\"content-mail\" class=\"hidden\">\r\n<h2>邮件群发</h2>\r\n<p>此功能用于向本站已经注册的所有用户发送邮件公告</p>\r\n<p>为避免用户反感，建议您不要经常发送邮件</p>\r\n<br>\r\n<form method=\"post\" action=\"admin.php?action=send_mail\" id=\"send_mail\" onsubmit=\"return post_win(this.action, this.id)\">\r\n<input type=\"hidden\" name=\"formhash\" value=\"";
echo $formhash;
echo "\">\r\n<p>邮件标题：</p>\r\n<p><input type=\"text\" name=\"title\" style=\"width: 80%\" /></p>\r\n<p>邮件内容：</p>\r\n<p><textarea name=\"content\" rows=\"10\" style=\"width: 80%\"></textarea></p>\r\n<p><input type=\"submit\" value=\"确认发送\" /></p>\r\n</form>\r\n</div>\r\n<div id=\"content-plugin\" class=\"hidden\">\r\n<h2>插件管理</h2>\r\n<p>安装相关插件能够增强 贴吧签到助手 的相关功能.（部分插件可能会影响系统运行效率）</p>\r\n<p>插件的设计可以参考 Github 上的项目介绍.</p>\r\n<p>将插件文件放到 /plugins/ 文件夹下即可在此处看到对应的插件程序.</p>\r\n<p>如果你觉得某个插件有问题，你可以先尝试禁用它，禁用操作不会丢失数据.</p>\r\n<p>插件下载: <a href=\"http://bbs.kookxiang.com/forum-addon-1.html\" target=\"_blank\">http://bbs.kookxiang.com/forum-addon-1.html</a></p>\r\n<table>\r\n<thead><tr><td style=\"width: 40px\">#</td><td>插件标识符 (ID)</td><td>插件介绍</td><td>当前版本</td><td>操作</td></tr></thead>\r\n<tbody></tbody>\r\n</table>\r\n</div>\r\n<div id=\"content-updater\" class=\"hidden\">\r\n<style type=\"text/css\">\r\n#content-updater .result { padding: 10px 15px; margin-bottom: 0; background: #efefef; }\r\n#content-updater .filelist ul { margin-top: -5px; padding: 0 15px 10px; background: #efefef; }\r\n#content-updater .filelist ul li { list-style: disc; line-height: 25px; margin: 0 0 0 25px; }\r\n</style>\r\n<h2>检测升级</h2>\r\n<p>此功能将联网更新您的贴吧签到助手. 升级过程采用差量升级的方式.</p>\r\n<p>升级过程需要保证文件被更新的文件可读可写.</p>\r\n<br>\r\n<p class=\"result\">正在检查更新...</p>\r\n<div class=\"filelist hidden\">\r\n<ul></ul>\r\n<p><button>开始更新</button></p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<p class=\"copyright\">";
echo DEBUG::output();
echo "</p>\r\n</div>\r\n<script src=\"";
echo jquery_path();
echo "\"></script>\r\n<script type=\"text/javascript\">var formhash = '";
echo $formhash;
echo "';var version = '";
echo VERSION;
echo "';</script>\r\n<script src=\"system/js/kk_dropdown.js?version=";
echo VERSION;
echo "\"></script>\r\n<script src=\"system/js/admin.js?version=";
echo VERSION;
echo "\"></script>\r\n<script src=\"system/js/fwin.js?version=";
echo VERSION;
echo "\"></script>\r\n</body>\r\n</html>\r\n";
$template_loaded = true;

?>
