<?php

session_start();
error_reporting( 0 );
set_magic_quotes_runtime( 1 );
if ( function_exists( 'date_default_timezone_set' ) ) date_default_timezone_set( 'PRC' );

define( 'IN_INSTALL', TRUE );
define( 'BASEPATH', dirname( __FILE__ ) );
define( 'ROOT', dirname( dirname( __FILE__ ) ) );
define( 'APPPATH', dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'application' );
define( 'ORIG_TABLEPRE', '' );
define( 'DBCHARSET', 'utf8' );
define( 'ADMINROLE', 4 );

$lock_file = 'install.lock';
if ( file_exists( BASEPATH . DIRECTORY_SEPARATOR . $lock_file ) )
{
	header( "location:../" );
	exit;
}

require BASEPATH . '/func.inc.php';

$dirs = array(
	APPPATH . DIRECTORY_SEPARATOR . 'config',
	ROOT . DIRECTORY_SEPARATOR . 'upload',
);
$data = array( );
$step = array( 'installed', 'welcome', 'system_info', 'database_info', 'user_setting', 'success' );
if ( !isset( $_GET['step_key'] ) )
{
	$data['step_key'] = 1;
}
else
{
	$data['step_key'] = (int)$_GET['step_key'];
	if ( !array_key_exists( $data['step_key'], $step ) ) $data['step_key'] = 1;
}
$data['now_step'] = isset( $_SESSION['now_step'] ) && in_array( $_SESSION['now_step'], $step ) ? $_SESSION['now_step'] : $step[$data['step_key']];

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
	$data['git'] = 'https://github.com/paperen/paperenblog';
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
	foreach ( $data['writeable'] as $dir => $bool )
	{
		if ( !$bool ) $allow_install = false;
	}
	foreach ( $data['required'] as $bool )
	{
		if ( !$bool ) $allow_install = false;
	}
	$data['allow_install'] = $allow_install;
	get_tpl();
	exit;
}

if ( $data['now_step'] == 'database_info' )
{
	if ( isset( $_POST['submit'] ) && $_POST['submit'] )
	{
		$data['process'] = true;
		get_tpl();
		$database = array(
			'dbhost' => $_POST['dbhost'],
			'dbuser' => $_POST['dbuser'],
			'dbpwd' => $_POST['dbpwd'],
			'dbname' => $_POST['dbname'],
			'tablepre' => '',
		);
		check_db( $database['dbhost'], $database['dbuser'], $database['dbpwd'], $database['dbname'] );
		mysql_select_db( $database['dbname'] );
		mysql_query( 'set names ' . DBCHARSET );
		$sql = file_get_contents( 'data_base.sql' );
		runquery( $sql );

		write_database_config();

		$_SESSION['now_step'] = $data['now_step'] = 'user_setting';
		exit;
	}
	else
	{
		// 表單
		get_tpl();
	}
	exit;
}

if ( $data['now_step'] == 'user_setting' )
{
	if ( isset( $_POST['submit'] ) && $_POST['submit'] )
	{
		// 管理員
		$admin = $_POST['name'];
		$blogemail = $_POST['blogemail'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];

		$data['err'] = array( );
		if ( empty( $admin ) ) $data['err'][] = '請填寫管理員帳號';
		if ( empty( $blogemail ) ) $data['err'][] = '請填寫管理員郵箱';
		if ( empty( $password ) ) $data['err'][] = '請填寫管理員密碼';
		if ( $password2 != $password ) $data['err'][] = '重複密碼不正確';

		if ( count( $data['err'] ) == 0 )
		{
			// 沒錯
			// 插入管理員信息
			$admin_id = create_admin( $admin, $blogemail, $password );

			// 寫入config表與生成配置
			write_app_config();

			// 生成lock文件
			chmod( BASEPATH . DIRECTORY_SEPARATOR . $lock_file, 777 );
			file_put_contents( BASEPATH . DIRECTORY_SEPARATOR . $lock_file, '' );

			$_SESSION['now_step'] = $data['now_step'] = 'success';
		}
	}
	else
	{
		get_tpl();
		exit;
	}
}

if ( $data['now_step'] == 'success' )
{
	$_SESSION['now_step'] = '';
	get_tpl();
	exit;
}