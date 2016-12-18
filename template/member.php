<?php

if (!defined("IN_KKFRAME")) {
	exit();
}

echo "<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>用户中心 - 贴吧签到助手</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" />\r\n<meta name=\"HandheldFriendly\" content=\"true\" />\r\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\" />\r\n<meta name=\"author\" content=\"kookxiang\" />\r\n<meta name=\"copyright\" content=\"KK's Laboratory\" />\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\" />\r\n<meta name=\"renderer\" content=\"webkit\">\r\n<link rel=\"stylesheet\" href=\"./style/main.css?version=";
echo VERSION;
echo "\" type=\"text/css\" />\r\n<link rel=\"stylesheet\" href=\"./style/custom.css\" type=\"text/css\" />\r\n</head>\r\n<body>\r\n<div class=\"wrapper\" id=\"page_login\">\r\n<div class=\"center-box\">\r\n<div class=\"side-bar\">\r\n<span class=\"icon\"></span>\r\n<ul>\r\n<li id=\"menu_login\" class=\"current\">登录</li>\r\n";

if (!getsetting("block_register")) {
	echo "<li id=\"menu_register\">注册</li>\r\n";
}

echo "</ul>\r\n</div>\r\n<div class=\"main\" id=\"content-login\">\r\n<h1>登录</h1>\r\n<form method=\"post\" action=\"member.php?action=login\">\r\n<div class=\"login-info\">\r\n<p>用户名：</p>\r\n<p><input type=\"text\" name=\"username\" required tabindex=\"1\" /></p>\r\n<p>密码 (<a href=\"javascript:;\" onclick=\"switch_tabs('find_password');\" tabindex=\"0\">找回密码</a>)：</p>\r\n<p><input type=\"password\" name=\"password\" required tabindex=\"2\" /></p>\r\n<p>(此账号仅用于登陆代签系统，不同于百度通行证)</p>\r\n";
HOOK::run("login_form");
echo "</div>\r\n<p><input type=\"submit\" value=\"登录\" tabindex=\"3\" /></p>\r\n</form>\r\n</div>\r\n";

if (!getsetting("block_register")) {
	echo "<div class=\"main hidden\" id=\"content-register\">\r\n<h1>注册</h1>\r\n<form method=\"post\" action=\"member.php?action=register\">\r\n<div class=\"login-info\">\r\n<p>用户名：</p>\r\n<p><input type=\"text\" name=\"";
	echo $form_username;
	echo "\" required tabindex=\"1\" /></p>\r\n<p>密码：</p>\r\n<p><input type=\"password\" name=\"";
	echo $form_password;
	echo "\" required tabindex=\"2\" /></p>\r\n<p>邮箱：</p>\r\n<p><input type=\"text\" name=\"";
	echo $form_email;
	echo "\" required tabindex=\"3\" /></p>\r\n";

	if ($invite_code) {
		echo "<p>邀请码：</p><p><input type=\"text\" name=\"invite_code\" required /></p>";
	}

	echo "<p>(此账号仅用于登陆代签系统，不同于百度通行证)</p>\r\n";
	HOOK::run("register_form");
	echo "</div>\r\n<p><input type=\"submit\" value=\"注册\" tabindex=\"4\" /></p>\r\n</form>\r\n</div>\r\n";
}

echo "<div class=\"main hidden\" id=\"content-find_password\">\r\n<h1>找回密码</h1>\r\n<form method=\"post\" action=\"member.php?action=find_password\">\r\n<div class=\"login-info\">\r\n<p>用户名：</p>\r\n<p><input type=\"text\" name=\"username\" required tabindex=\"1\" /></p>\r\n<p>邮箱：</p>\r\n<p><input type=\"text\" name=\"email\" required tabindex=\"2\" /></p>\r\n</div>\r\n<p><input type=\"submit\" value=\"提交\" tabindex=\"3\" /></p>\r\n</form>\r\n</div>\r\n</div>\r\n<p class=\"copyright\">贴吧签到助手 ";
echo VERSION;
echo " - Designed by <a href=\"http://www.ikk.me\" target=\"_blank\">kookxiang</a>. 2013 &copy; <a href=\"http://www.kookxiang.com\" target=\"_blank\">KK's Laboratory</a> (<a href=\"https://me.alipay.com/kookxiang\" target=\"_blank\">赞助开发</a>)";

if (getsetting("beian_no")) {
	echo " - <a href=\"http://www.miibeian.gov.cn/\" target=\"_blank\" rel=\"nofollow\">" . getsetting("beian_no") . "</a>";
}

echo "<br>请勿擅自修改程序版权信息或将本程序用于商业用途！</p>\r\n<script src=\"";
echo jquery_path();
echo "\"></script>\r\n<script src=\"system/js/member.js?version=";
echo VERSION;
echo "\"></script>\r\n";
HOOK::run("member_footer");

if (getsetting("stat_code")) {
	echo "<div class=\"hidden\">" . getsetting("stat_code") . "</div>";
}

echo "</div>\r\n</body>\r\n</html>\r\n";
$template_loaded = true;

?>
