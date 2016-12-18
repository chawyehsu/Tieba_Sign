<?php

if (!defined("IN_KKFRAME")) {
	exit();
}

echo "<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>贴吧签到助手</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" />\r\n<meta name=\"HandheldFriendly\" content=\"true\" />\r\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\" />\r\n<meta name=\"author\" content=\"kookxiang\" />\r\n<meta name=\"copyright\" content=\"KK's Laboratory\" />\r\n<link rel=\"shortcut icon\" href=\"favicon.ico\" />\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\" />\r\n<meta name=\"renderer\" content=\"webkit\">\r\n<link rel=\"stylesheet\" href=\"./style/main.css?version=";
echo VERSION;
echo "\" type=\"text/css\" />\r\n<link rel=\"stylesheet\" href=\"./style/custom.css\" type=\"text/css\" />\r\n</head>\r\n<body>\r\n<div class=\"wrapper\" id=\"page_index\">\r\n<div id=\"append_parent\"><div class=\"cover hidden\"></div><div class=\"loading-icon\"><img src=\"style/loading.gif\" /> 载入中...</div></div>\r\n<div class=\"main-box clearfix\">\r\n<h1>贴吧签到助手</h1>\r\n<div class=\"avatar\">";
echo $username;
echo $_COOKIE["avatar_$uid"] ? "<img id=\"avatar_img\" src=\"" . $_COOKIE["avatar_$uid"] . "\">" : "<img id=\"avatar_img\" class=\"hidden\" src=\"style/member.png\">";
echo "</div>\r\n<ul class=\"menu hidden\" id=\"member-menu\">\r\n<li id=\"menu_password\"><a href=\"javascript:;\">修改密码</a></li>\r\n";

if (getsetting("account_switch")) {
	foreach ($users as $_uid => $username ) {
		echo "<li class=\"menu_switch_user\"><span class=\"del\" href=\"member.php?action=unbind_user&uid=" . $_uid . "&formhash=" . $formhash . "\">x</span><a href=\"member.php?action=switch&uid=" . $_uid . "&formhash=" . $formhash . "\">切换至: " . $username . "</a></li>";
	}

	echo "<li id=\"menu_adduser\"><a href=\"#user-new\">关联其他帐号</a></li>";
}

echo "<li id=\"menu_logout\"><a href=\"member.php?action=logout&hash=";
echo $formhash;
echo "\">退出登录</a></li>\r\n</ul>\r\n<div class=\"menubtn\"><p>-</p><p>-</p><p>-</p></div>\r\n<div class=\"main-wrapper\">\r\n<div class=\"sidebar\">\r\n<ul id=\"menu\" class=\"menu\">\r\n<li id=\"menu_guide\"><a href=\"#guide\">配置向导</a></li>\r\n<li id=\"menu_sign_log\"><a href=\"#sign_log\">签到记录</a></li>\r\n<li id=\"menu_liked_tieba\"><a href=\"#liked_tieba\">我喜欢的贴吧</a></li>\r\n<li id=\"menu_baidu_bind\"><a href=\"#baidu_bind\">百度账号绑定</a></li>\r\n<li id=\"menu_setting\"><a href=\"#setting\">设置</a></li>\r\n";
HOOK::page_menu();

if (is_admin($uid)) {
	echo "<li id=\"menu_updater\"><a href=\"admin.php#updater\">检查更新</a></li><li id=\"menu_admincp\"><a href=\"admin.php\">管理面板</a></li>";
}

echo "</ul>\r\n</div>\r\n<div class=\"main-content\">\r\n<div id=\"content-guide\" class=\"hidden\">\r\n<h2>贴吧签到助手 配置向导</h2>\r\n<div id=\"guide_pages\">\r\n<div id=\"guide_page_1\">\r\n<p>Hello，欢迎使用 贴吧签到助手~</p><br>\r\n<p><b>这是一款免费软件，作者 <a href=\"http://www.ikk.me\" target=\"_blank\">kookxiang</a>，你可以从 www.kookxiang.com 上下载到这个项目的最新版本。</b></p>\r\n<p>如果有人向您兜售本程序，麻烦您给个差评。</p><br>\r\n<p>配置签到助手之后，我们会在每天的 0:30 左右为您自动签到。</p>\r\n<p>签到过程不需要人工干预，您可以选择签到之后发送一封邮件报告到您的注册邮箱。</p><br>\r\n<p>准备好了吗？点击下面的“下一步”按钮开始配置吧</p>\r\n<p class=\"btns\"><button class=\"btn submit\" onclick=\"$('#guide_page_1').hide();$('#guide_page_2').show();\">下一步 &raquo;</button></p>\r\n</div>\r\n<div id=\"guide_page_2\" class=\"hidden\">\r\n<p>首先，你需要绑定你的百度账号。</p><br>\r\n<p>为了确保账号安全，我们只储存你的百度 Cookie，不会保存你的账号密码信息。</p>\r\n<p>你可以通过修改密码的方式来让这些 Cookie 失效。</p><br>\r\n<h2>手动绑定百度账号</h2>\r\n<p>请填写百度贴吧 Cookie:</p>\r\n<form method=\"post\" action=\"index.php?action=update_cookie\">\r\n<p>\r\n<input type=\"text\" name=\"cookie\" style=\"width: 60%\" placeholder=\"请在此粘贴百度贴吧的 cookie\" />\r\n<input type=\"submit\" value=\"更新\" />\r\n</p>\r\n</form>\r\n<br>\r\n<p>Cookie 获取工具:</p>\r\n<p>将本链接拖到收藏栏，在新页面点击收藏栏中的链接（推荐使用 Chrome 隐身窗口模式），按提示登陆wapp.baidu.com，登陆成功后，在该页面再次点击收藏栏中的链接即可复制cookies信息。</p>\r\n<p><a href=\"javascript:(function(){prompt('您的 Cookie 信息如下（Cookie 不一定能获取完整，如果更新失败请多试几次）:', document.cookie);})();\" onclick=\"alert('请拖动到收藏夹');return false;\" class=\"btn\">获取手机百度贴吧 Cookie</a></p>\r\n</div>\r\n<div id=\"guide_page_manual\" class=\"hidden\"></div>\r\n<div id=\"guide_page_3\" class=\"hidden\">\r\n<p>一切准备就绪~</p><br>\r\n<p>我们已经成功接收到你百度账号信息，自动签到已经准备就绪。</p>\r\n<p>您可以点击 <a href=\"#setting\">高级设置</a> 更改邮件设定，或更改其他附加设定。</p><br>\r\n<p>感谢您的使用！</p><br>\r\n<p>程序作者：kookxiang (<a href=\"http://www.ikk.me\" target=\"_blank\">http://www.ikk.me</a>)</p>\r\n<p>赞助开发：<a href=\"https://me.alipay.com/kookxiang\" target=\"_blank\">https://me.alipay.com/kookxiang</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n<div id=\"content-liked_tieba\" class=\"hidden\">\r\n<h2>我喜欢的贴吧</h2>\r\n<p>如果此处显示的贴吧有缺失，请<a href=\"index.php?action=refresh_liked_tieba\" onclick=\"return msg_redirect_action(this.href+'&formhash='+formhash)\">点此刷新喜欢的贴吧</a>.</p>\r\n<table>\r\n<thead><tr><td style=\"width: 40px\">#</td><td>贴吧</td><td style=\"width: 65px\">忽略签到</td></tr></thead>\r\n<tbody></tbody>\r\n</table>\r\n</div>\r\n<div id=\"content-sign_log\">\r\n<h2>签到记录</h2>\r\n<span id=\"page-flip\" class=\"float-right\"></span>\r\n<p id=\"sign-stat\"></p>\r\n<table>\r\n<thead><tr><td style=\"width: 40px\">#</td><td>贴吧</td><td class=\"mobile_min\">状态</td><td class=\"mobile_min\">经验</td></tr></thead>\r\n<tbody></tbody>\r\n</table>\r\n</div>\r\n<div id=\"content-setting\" class=\"hidden\">\r\n<h2>设置</h2>\r\n<form method=\"post\" action=\"index.php?action=update_setting\" id=\"setting_form\" onsubmit=\"return post_win(this.action, this.id)\">\r\n<input type=\"hidden\" name=\"formhash\" value=\"";
echo $formhash;
echo "\">\r\n<p>签到方式：</p>\r\n<p><label><input type=\"radio\" name=\"sign_method\" id=\"sign_method_3\" value=\"3\" checked readonly /> V3.0 (模拟客户端签到)</label></p>\r\n<p>附加签到：</p>\r\n<p><label><input type=\"checkbox\" disabled name=\"zhidao_sign\" id=\"zhidao_sign\" value=\"1\" /> 自动签到百度知道</label></p>\r\n<p><label><input type=\"checkbox\" disabled name=\"wenku_sign\" id=\"wenku_sign\" value=\"1\" /> 自动签到百度文库</label></p>\r\n<p>报告设置：</p>\r\n<p><label><input type=\"checkbox\" checked disabled name=\"error_mail\" id=\"error_mail\" value=\"1\" /> 当天有无法签到的贴吧时给我发送邮件</label></p>\r\n<p><label><input type=\"checkbox\" disabled name=\"send_mail\" id=\"send_mail\" value=\"1\" /> 每日发送一封签到报告邮件</label></p>\r\n<p><input type=\"submit\" value=\"保存设置\" /></p>\r\n</form>\r\n";
HOOK::run("user_setting");
echo "<br>\r\n<p>签到测试：</p>\r\n<p>随机选取一个贴吧，进行一次签到测试，检查你的设置有没有问题</p>\r\n<p><a href=\"index.php?action=test_sign&formhash=";
echo $formhash;
echo "\" class=\"btn\" onclick=\"return msg_redirect_action(this.href)\">测试签到</a></p>\r\n</div>\r\n<div id=\"content-baidu_bind\" class=\"hidden\">\r\n<h2>百度账号绑定</h2>\r\n<div class=\"tab tab-binded hidden\">\r\n<p>您的百度账号绑定正常。</p>\r\n<br>\r\n<div class=\"baidu_account\"></div>\r\n<br>\r\n<p><a href=\"index.php?action=clear_cookie&formhash=";
echo $formhash;
echo "\" id=\"unbind_btn\" class=\"btn red\">解除绑定</a> &nbsp; (解除绑定后自动签到将停止)</p>\r\n</div>\r\n<div class=\"tab tab-bind\">\r\n<p>您还没有绑定百度账号！</p>\r\n<br>\r\n<p>只有绑定百度账号之后程序才能自动进行签到。</p>\r\n<p>您可以使用百度通行证登陆，或是手动填写 Cookie 进行绑定。</p>\r\n<br>\r\n<p><a href=\"#guide\" class=\"btn submit\">使用“配置向导”进行绑定</a></p>\r\n</div>\r\n</div>\r\n";
HOOK::page_contents();
echo "<p>贴吧签到助手 - Designed by <a href=\"http://www.ikk.me\" target=\"_blank\">kookxiang</a>. 2014 &copy; <a href=\"http://www.kookxiang.com\" target=\"_blank\">KK's Laboratory</a> - <a href=\"https://me.alipay.com/kookxiang\" target=\"_blank\">赞助开发</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n<p class=\"copyright\">";

if (getsetting("beian_no")) {
	echo "<a href=\"http://www.miibeian.gov.cn/\" target=\"_blank\" rel=\"nofollow\">" . getsetting("beian_no") . "</a> - ";
}

HOOK::run("page_footer");
echo "</p>\r\n</div>\r\n<script src=\"";
echo jquery_path();
echo "\"></script>\r\n<script type=\"text/javascript\">var formhash = '";
echo $formhash;
echo "';var version = '";
echo VERSION;
echo "';</script>\r\n<script src=\"system/js/kk_dropdown.js?version=";
echo VERSION;
echo "\"></script>\r\n<script src=\"system/js/main.js?version=";
echo VERSION;
echo "\"></script>\r\n<script src=\"system/js/fwin.js?version=";
echo VERSION;
echo "\"></script>\r\n";
HOOK::run("page_footer_js");

if (getsetting("stat_code")) {
	echo "<div class=\"hidden\">" . getsetting("stat_code") . "</div>";
}

if (defined("CLOUD_NOT_INITED")) {
	echo "<div class=\"hidden\"><img src=\"api.php?action=register_cloud\" /></div>";
}

echo "</body>\r\n</html>\r\n";
$template_loaded = true;

?>
