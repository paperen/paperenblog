<?php

error_reporting( E_ERROR | E_WARNING | E_PARSE );
@set_time_limit( 1000 );
set_magic_quotes_runtime( 0 );
if ( function_exists( 'date_default_timezone_set' ) ) date_default_timezone_set( 'PRC' );

define( 'IN_INSTALL', TRUE );
define( 'BASE_PATH', dirname( __FILE__ ) );
define( 'ROOT', dirname( dirname( __FILE__ ) ) );
define( 'APPPATH', dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'application' );
define( 'ORIG_TABLEPRE', '' );
define( 'DBCHARSET', 'utf8' );

require BASE_PATH . '/func.inc.php';

$dirs = array(
	APPPATH . DIRECTORY_SEPARATOR . 'config',
	ROOT . DIRECTORY_SEPARATOR . 'upload',
);
$data = array( );
$step = array( 'installed', 'welcome', 'system_info', 'database_info', 'create_database', 'user_setting', 'setting', 'success' );
if ( !isset( $_GET['step_key'] ) )
{
	$data['step_key'] = 1;
}
else
{
	$data['step_key'] = (int)$_GET['step_key'];
	if ( !array_key_exists( $data['step_key'], $step ) ) $data['step_key'] = 1;
}
$data['now_step'] = $step[$data['step_key']];

if ( file_exists( BASE_PATH . 'intall.lock' ) )
{
	get_tpl();
	exit;
}
$data['now_step'] = $step[$data['step_key']];

if ( $data['now_step'] == 'welcome' )
{
	$data['feature'] = array(
		'基於Codeigniter框架(2.1.0)',
		'使用Bootstrap',
		'使用kindeditor HTML編輯器',
		'多用戶',
		'輕量',
		'仍不夠完美',
	);
	get_tpl();
	exit;
}

if ( $data['now_step'] == 'system_info' )
{
	$data['info'] = array(
		'系統與PHP版本' => PHP_OS . ' + ' . $_SERVER['SERVER_SOFTWARE'],
		'安裝目錄' => $_SERVER['SERVER_NAME'],
	);
	$data['writeable'] = get_dir_able();
	$data['required'] = array(
		'支持rewrite模块' => in_array( 'mod_rewrite', apache_get_modules() ),
	);
	$allow_install = true;
	foreach( $data['writeable'] as $dir => $bool )
	{
		if ( !$bool ) $allow_install = false;
	}
	foreach( $data['required'] as $bool )
	{
		if ( !$bool ) $allow_install = false;
	}
	$data['allow_install'] = $allow_install;
	get_tpl();
	exit;
}

if ( $data['now_step'] == 'database_info' )
{
	get_tpl();
	exit;
}
$database = array( );
if ( $data['now_step'] == 'create_database' )
{
	$database['table_pre'] = $_POST['table_pre'];
	$database['db_host'] = $_POST['db_host'];
	$database['db_user_name'] = $_POST['db_user_name'];
	$database['db_password'] = $_POST['db_password'];
	$database['db_name'] = $_POST['db_name'];
	//@todo 判断下表单不全的情况
	check_db( $database['db_host'], $database['db_user_name'], $database['db_password'], $database['db_name'] );
	mysql_query( 'set names ' . DBCHARSET );
	mysql_query( "use {$database['db_name']}" );
	$data['sql'] = file_get_contents( 'data_base.sql' );
	get_tpl();
	exit;
}

if ( $data['now_step'] == 'user_setting' )
{
	get_tpl();
	exit;
}

if ( $data['now_step'] == 'setting' )
{
	//@todo判断表单不完全
	$set = array( );
	define( 'BASEPATH', TRUE );
	include_once(str_replace( 'install', '', BASE_PATH ) . 'application\\config\\database.php');
	$set['base_url'] = addslashes( $_POST['base_url'] );
	$set['app_name'] = addslashes( $_POST['app_name'] );
	$set['app_authkey'] = addslashes( $_POST['app_authkey'] );
	$set['lycenter_authkey'] = addslashes( $_POST['lycenter_authkey'] );
	$set['taskman_url'] = addslashes( $_POST['taskman_url'] );
	$set['lycenter_api'] = addslashes( $_POST['lycenter_api'] );
	$dbly['db_host'] = $_POST['db_host'];
	$dbly['db_user_name'] = $_POST['db_username'];
	$dbly['db_password'] = $_POST['db_pass'];
	$dbly['db_name'] = $_POST['db_name'];
	check_db( $dbly['db_host'], $dbly['db_user_name'], $dbly['db_password'], $dbly['db_name'], '', FALSE );
	mysql_query( "use {$dbly['db_name']}" );
	$apptable = '';
	$lyconfig = '';
	$access_tb = '';
	$group_tb = '';
	$groupaccess_tb = '';
	$appid = '';
	$query = mysql_query( "show tables" );
	while ( $row = mysql_fetch_array( $query ) )
	{
		if ( strpos( $row[0], 'application' ) )
		{
			$apptable = $row[0];
			break;
		}
	}
	if ( !empty( $apptable ) )
	{
		$postion = stripos( $apptable, 'application' );
		$tbpre = substr( $apptable, 0, $postion );
		$lyconfig = $tbpre . 'config';
		$access_tb = $tbpre . 'access';
		$group_tb = $tbpre . 'group';
		$groupaccess_tb = $tbpre . 'groupaccess';
	}
	else
	{
		show_msg( '请检查LYCENTER数据库', '', 0 );
	}
	if ( !empty( $apptable ) )
	{
		mysql_query( "use {$dbly['db_name']}" );
		$query = mysql_query( "insert into {$apptable} (`name`,`authkey`,`url`,`type`,`api`) values (\"{$set['app_name']}\",\"{$set['app_authkey']}\",\"{$set['base_url']}\",'docman','api/')" ) or die( "Invalid query: " . mysql_error() );
		if ( $query )
		{
			$appid = mysql_insert_id();
			if ( !empty( $lyconfig ) )
			{
				$sql = "INSERT INTO {$lyconfig } (`appid`, `key`, `value`, `name`, `state`) VALUES ({$appid},'lycenter_authkey',\"{$set['lycenter_authkey']}\",\"{$set['app_name']}\",0),
({$appid}, 'appid',{$appid}, '应用ID', 1),
({$appid}, 'appname', \"{$set['app_name']}\", '应用名', 0),
({$appid}, 'base_url', \"{$set['base_url']}\", '应用URL', 0),
({$appid}, 'taskman_url', \"{$set['taskman_url']}\", '任务系统URL', 0),
({$appid}, 'theme', 'default', '主题', 0),
({$appid}, 'js_calendar', 'blue', '日历主题', 0),
({$appid}, 'jquery-ui', 'redmond', 'jqueryUI主题', 0),
({$appid}, 'per_page', '5', '列表每页显示条数', 0),
({$appid}, 'num_links', '4', '分页显示前后页数范围', 0),
({$appid}, 'table_prefix', \"{$database['table_pre']}\", '数据表前缀', 1),
({$appid}, 'tipbox_timeout', '5', '提示框定时跳转时间(秒)', 0),
({$appid}, 'autosave_timeout', '30', '自动保存草稿时间(秒)', 0),
({$appid}, 'app_authkey', \"{$set['app_authkey']}\", '应用身份号', 0),
({$appid}, 'lycenter_authkey', \"{$set['lycenter_authkey']}\", 'lycenter身份号', 0),
({$appid}, 'lycenter_api', \"{$set['lycenter_api']}\", '应用API URL', 0),
({$appid}, 'token_timeout', '30', '表单令牌超时时间(分)', 0),
({$appid}, 'upload_path', './upload/', '附加上传路径', 0),
({$appid}, 'paper_upload_path', './upload/paper/', '扫描件上传路径', 0),
({$appid}, 'editor_upload_path', './upload/image/', '编辑器上传文件路径', 0),
({$appid}, 'allowed_types', 'jpg|gif|png|jpeg|bmp', '允许上传文件后缀(|隔开)', 0),
({$appid}, 'overwrite', 'FALSE', '是否允许文件重写', 1),
({$appid}, 'max_size', '8192', '上传文件最大大小(kb)', 0),
({$appid}, 'encrypt_name', 'TRUE', '是否重新生成上传文件名字', 0),
({$appid}, 'remove_spaces', 'TRUE', '去掉上传文件空格', 0),
({$appid}, 'span_year', '10', '统计年份间隔', 0),
({$appid}, 'eachday_can_postdaily', '1', '每天允许发表日志篇数 默认1篇 0为不限制', 0),
({$appid}, 'daily_wordlimit', '200', '日记字数限制', 0);n$$
INSERT INTO `{$access_tb}` ( `node`, `name`, `appid`) VALUES
('proposeappoint', '新建下达', {$appid}),
('proposeapply', '新建申请', {$appid}),
('editappoint', '修改下达', {$appid}),
('editapply', '修改申请', {$appid}),
('approvalpropose', '新建审批', {$appid}),
('approvaledit', '修改审批', {$appid}),
('approvalinput', '录入审批', {$appid}),
('archive', '归档', {$appid}),
('input', '录入', {$appid}),
('review', '复核', {$appid}),
('paymentrecord', '支付录入', {$appid}),
('search', '搜索', {$appid}),
('searchadvanced', '高级搜索', {$appid}),
('dailyadd', '编写日志', {$appid}),
('dailyedit', '修改自己的日志', {$appid}),
('dailysubstuff', '查看下级日志', {$appid}),
('countquantity', '合同数量统计', {$appid}),
('countamount', '合同金额统计', {$appid}),
('countexpried', '合同到期统计', {$appid}),
('viewalldoc', '是否能查看所有文档', {$appid}),
('paperadd', '附加扫描件', {$appid});n$$
INSERT INTO `{$group_tb}` (`pid`, `appid`, `name`, `state`, `default`) VALUES(0, {$appid}, '普通用户', 1, 1);
";
				$queries = explode( ";n$$", $sql );
				foreach ( $queries as $key => $sql )
				{
					mysql_query( $sql ) or die( "Invalid query: " . mysql_error() );
				}
				$groupid = '';
				$sql = "select `id` from {$group_tb} where `appid`={$appid}";
				$query = mysql_query( $sql );
				if ( $query )
				{
					$row = mysql_fetch_row( $query );
					if ( $row )
					{
						$groupid = $row[0];
					}
					else
					{
						show_msg( '用户组无数据', '', 0 );
					}
				}
				else
				{
					show_msg( '读取用户组出错', '', 0 );
				}
				if ( !empty( $groupid ) )
				{
					$sql = "select `id` from {$accesss_tb} where `appid`={$appid}";
					$insert_sql = "INSERT INTO {$groupaccess_tb} (`groupid`,`accessid`) VALUES ";
					$query = mysql_query( $sql );
					if ( $query )
					{
						while ( $row = mysql_fetch_array( $query, MYSQLI_ASSOC ) )
						{
							$insert_sql .= "({$groupid},{$row['id']}),";
						}
						mysql_query( trim( $insert_sql, ',' ) ) or die( mysql_error() );
					}
				}
			}
			else
			{
				show_msg( '未找到lycenter的配置表', '', 0 );
			}
		}
		else
		{
			show_msg( '不能连接lycenter数据库或者插入数据出错!', '', 0 );
		}
	}
	else
	{
		show_msg( '未找到lycenter的应用程序配置表', '', 0 );
	}
	echo write_app_config() ? "<script>window.location.href=\"./index.php?step_key={$data[step_key]}\";</script>" : '配置文件生成失败,请检查权限!';
	exit;
}

if ( $data['now_step'] == 'success' )
{
	get_tpl();
	exit;
}
