<?php
if (! defined ( 'IN_KKFRAME' ))	exit ( 'Access Denied!' );
class plugin_x_tdou {
	var $description = '贴吧自动领取T豆并自动砸蛋';
	var $modules = array (
			array ('id' => 'index',	'type' => 'page','title' => 'T豆获取记录','file' => 'index.php')
	);
	var $nowversion='0.1.8_13-12-12';
	function __construct() {
		if (getSetting ( 'x_tdou' ) != $this->nowversion) {
			DB::query ( "delete from plugin where name='x_tdou'" );
			CACHE::update ( 'plugins' );
		}
	}
	public function page_footer_js() {
?>
<script>
$("#menu_x_tdou-index").click(function (){load_tdou_log();});
function load_tdou_log(){
	showloading();
	$.getJSON("plugins/x_tdou/ajax.php?v=show_log", function(result){
		$('#x_tdou_log').html('');
		if(result.count){
			$.each(result.logs, function(i, field){
			$("#x_tdou_log").append("<tr><td>"+field.date+"</td><td>"+field.num+"</td></tr>");
		});}else{
			$('#x_tdou_log').html('<tr><td colspan="2">暂无记录</td></tr>');
		}
	}).fail(function() { createWindow().setTitle('系统错误').setContent('发生未知错误: 无法获取设置').addButton('确定', function(){ location.reload(); }).append(); }).always(function(){ hideloading(); });
}
</script>
<?php 
	}
	public function on_install() {
		$query = DB::query ( 'SHOW TABLES' );
		$tables = array ();
		while ($table= DB::fetch($query)) $tables[]=implode ('', $table );
		if (!in_array ( 'x_tdou_log', $tables )){
			$nowtime=TIMESTAMP;
			DB::query ("CREATE TABLE IF NOT EXISTS `x_tdou_log` (`uid` int(10) unsigned NOT NULL, `date` int(11) NOT NULL DEFAULT '0', `nextrun` int(10) unsigned NOT NULL DEFAULT '0', `num` int(4) NOT NULL DEFAULT '0', `retry` tinyint(1) NOT NULL DEFAULT '0', UNIQUE KEY `uid` (`uid`,`date`))ENGINE=InnoDB DEFAULT CHARSET=utf8");
			DB::query ("REPLACE INTO `cron` (`id`, `enabled`, `nextrun`, `order`) VALUES ('x_tdou_daily', 1, $nowtime, 72),('x_tdou_get', 1, $nowtime, 73)");
			saveSetting ( 'x_tdou', $this->nowversion );
			showmessage ( '获取T豆插件'.substr($this->nowversion, 0,5).'版安装成功！');
		}
		$version = getSetting ( 'x_tdou' );
		switch ($version){
			case '0.1.0_13-12-09':
			case '0.1.1_13-12-10':
			case '0.1.2_13-12-10':
			case '0.1.3_13-12-10':
			case '0.1.4_13-12-10':
			case '0.1.5_13-12-11':
			case '0.1.6_13-12-11':
			case '0.1.7_13-12-11':
			DB::query ("ALTER TABLE  `x_tdou_log` CHANGE  `num`  `num` INT( 4 ) NOT NULL DEFAULT  '0';");
			default:
				saveSetting ('x_tdou', $this->nowversion);
				showmessage ('获取T豆插件已升级到'.substr($this->nowversion, 0,5).'版！');
		}
	}
	public function on_uninstall() {
		DB::query ( "DROP TABLE x_tdou_log");
		DB::query ( "DELETE FROM cron WHERE id in('x_tdou_daily','x_tdou_get')");
		CACHE::update ( 'plugins' );
		showmessage ( "卸载成功。" );
	}
}
?>