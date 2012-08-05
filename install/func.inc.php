<?php

if ( !defined( 'IN_INSTALL' ) ) exit( 'Access Denied' );

function check_db( $dbhost, $dbuser, $dbpw, $dbname = '', $tablepre = '', $c = TRUE )
{
	if ( !function_exists( 'mysql_connect' ) )
	{
		show_msg( 'undefine_func:', 'mysql_connect', 0 );
	}
	if ( !@mysql_connect( $dbhost, $dbuser, $dbpw ) )
	{
		$errno = mysql_errno();
		$error = mysql_error();
		if ( $errno == 1045 )
		{
			show_msg( 'database_errno_1045:', $error, 0 );
		}
		elseif ( $errno == 2003 )
		{
			show_msg( 'database_errno_2003:', $error, 0 );
		}
		else
		{
			show_msg( 'database_connect_error:', $error, 0 );
		}
	}
	else if ( !empty( $dbname ) )
	{
		$query = @mysql_query( "show databases" );
		$exist = FALSE;
		while ( $row = mysql_fetch_array( $query ) )
		{
			if ( $dbname == $row[0] )
			{
				$exist = TRUE;
				break;
			}
		}
		if ( !$exist && $c )
		{
			$query = mysql_query( "CREATE DATABASE  {$dbname} DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci" );
			if ( $query )
			{
				return true;
			}
			elseif ( !$exist && !$c )
			{
				show_msg( "数据库{$dbname}不存在！", '', 0 );
			}
			else
			{
				show_msg( "创建数据库{$dbname}失败！", '', 0 );
			}
		}
		elseif ( !$exist && !$c )
		{
			show_msg( "数据库{$dbname}不存在！", '', 0 );
		}
	}
	else
	{
		if ( $query = @mysql_query( "SHOW TABLES FROM $dbname" ) )
		{
			while ( $row = mysql_fetch_row( $query ) )
			{
				if ( preg_match( "/^$tablepre/", $row[0] ) )
				{
					return false;
				}
			}
		}
	}
	return true;
}

function show_msg( $msg, $code, $status = 1 )
{
	$comment = $msg . $code;
	header( 'content-Type:text/html;charset=utf8' );
	if ( $status )
	{
		echo '<em sytle=\"color:green\">' . $comment . '</em>';
	}
	else
	{
		echo '<em sytle=\"color:red\">' . $comment . '</em>';
		exit;
	}
}

function get_tpl()
{
	global $data;
	include_once(BASEPATH . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $data['now_step'] . '.php');
}

function next_step( $ret = false )
{
	global $data, $step;
	$next_step = $data['step_key'] + 1;
	if ( $ret ) return "?step_key={$next_step}";
	echo "?step_key={$next_step}";
}

function prev_step( $ret = false )
{
	global $data, $step;
	$next_step = $data['step_key'] - 1;
	if ( $ret ) return "?step_key={$next_step}";
	echo "?step_key={$next_step}";
}

function get_dir_able()
{
	global $dirs;
	$result = array( );
	foreach ( $dirs as $dir )
	{
		$result[$dir] = is_writable( $dir );
	}
	return $result;
}

function runquery( $sql, $delimiter = ";\n", $table = TRUE )
{
	if ( !isset( $sql ) || empty( $sql ) ) return;
	$sql = str_replace( "\r", "\n", str_replace( ORIG_TABLEPRE, '', $sql ) );
	$ret = array( );
	$num = 0;

	foreach ( explode( $delimiter, trim( $sql ) ) as $query )
	{
		$ret[$num] = '';
		$queries = explode( "\n", trim( $query ) );
		foreach ( $queries as $query )
		{
			$ret[$num] .= ( isset( $query[0] ) && $query[0] == '#') || (isset( $query[1] ) && isset( $query[1] ) && $query[0] . $query[1] == '--') ? '' : $query;
		}
		$num++;
	}
	unset( $sql );
	$key = '';
	if ( $table )
	{
		$key = TRUE;
		foreach ( $ret as $query )
		{
			$query = trim( $query );
			if ( $query )
			{
				if ( substr( $query, 0, 12 ) == 'CREATE TABLE' )
				{
					$name = preg_replace( "/CREATE TABLE IF NOT EXISTS `([a-z0-9_]+)` .*/is", "\\1", $query );
					if ( mysql_query( stripslashes( $query ) ) )
					{
						showjsmessage( '创建表 <strong>' . $name . '</strong> 成功' );
					}
					else
					{
						showjsmessage( '创建表 <strong>' . $name . '</strong> 失敗', true );
						$key = FALSE;
						break;
					}
				}
				else
				{
					mysql_query( $query );
				}
			}
		}
	}
	else
	{
		$key = TRUE;
		foreach ( $ret as $query )
		{
			$query = trim( $query );
			if ( $query )
			{
				if ( substr( $query, 0, 14 ) == 'CREATE TRIGGER' )
				{
					$name = preg_replace( "/CREATE TRIGGER `([a-z0-9_]+)` .*/is", "\\1", $query );
					if ( mysql_query( stripslashes( $query ) ) )
					{
						showjsmessage( '创建觸發器表 <strong>' . $name . '</strong> 成功' );
					}
					else
					{
						showjsmessage( '创建触发器 <strong>' . $name . '</strong> 失敗', true );
						$key = FALSE;
						break;
					}
				}
				elseif ( substr( $query, 0, 16 ) == 'CREATE PROCEDURE' )
				{
					$name = preg_replace( "/CREATE PROCEDURE `([a-z0-9_]+)` .*/is", "\\1", $query );
					if ( mysql_query( stripslashes( $query ) ) )
					{
						showjsmessage( '创建存储例程 <strong>' . $name . '</strong> 成功' );
					}
					else
					{
						showjsmessage( '创建存储例程 <strong>' . $name . '</strong> 失敗', true );
						$key = FALSE;
						break;
					}
				}
				else
				{
					mysql_query( $query );
				}
			}
		}
	}
	if ( $key )
	{
		if ( write_database_config() )
		{
			showjsmessage( '创建配置文件 <strong>database.php</strong> 成功' );
			$redirect_url = next_step( true );
			echo "<script>setTimeout('window.location.href=\"{$redirect_url}\";', 3000);</script>";
		}
		else
		{
			showjsmessage( '创建配置文件 <strong>database.php</strong> 失敗', true );
		}
	}
	if ( !$table )
	{
		mysql_close();
	}
}

function showjsmessage( $message, $err = false )
{
	$message = ( $err ) ? "<div class=\"alert alert-error\">{$message}</div>" : "<div class=\"alert alert-success\">{$message}</div>";
	echo "<script type=\"text/javascript\">$('#process').append('{$message}');</script>";
	flush();
	ob_flush();
	sleep( 1 );
}

function write_database_config()
{
	global $database;
	$content = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
	$content = $content . "\$active_group = 'default';";
	$content = $content . "\$active_record = TRUE;";
	$content = $content . "\$db['default']['hostname'] = '{$database['dbhost']}';";
	$content = $content . "\$db['default']['username'] = '{$database['dbuser']}';";
	$content = $content . "\$db['default']['password'] = '{$database['dbpwd']}';";
	$content = $content . "\$db['default']['database'] = '{$database['dbname']}';";
	$content = $content . "\$db['default']['dbdriver'] = 'mysql';";
	$content = $content . "\$db['default']['dbprefix'] = '{$database['tablepre']}';";
	$content = $content . "\$db['default']['pconnect'] = FALSE;";
	$content = $content . "\$db['default']['db_debug'] = TRUE;";
	$content = $content . "\$db['default']['cache_on'] = FALSE;";
	$content = $content . "\$db['default']['cachedir'] = '';";
	$content = $content . "\$db['default']['char_set'] = 'utf8';";
	$content = $content . "\$db['default']['dbcollat'] = 'utf8_general_ci';";
	$content = $content . "\$db['default']['swap_pre'] = '';";
	$content = $content . "\$db['default']['autoinit'] = TRUE;";
	$content = $content . "\$db['default']['stricton'] = FALSE;";
	$file_name = APPPATH . '/config/database.php';
	if ( strlen( $content ) == write( $file_name, $content ) ) return TRUE;
	return FALSE;
}

function write_app_config()
{
	init_db();

	// 插入config表
	$sql = stripslashes( file_get_contents( 'config.sql' ) );
	mysql_query( $sql );

	$content = "<?php ";
	$sql = "select * from config";
	$query = mysql_query( $sql );
	if ( $query )
	{
		while ( $row = mysql_fetch_array( $query, MYSQL_ASSOC ) )
		{
			$content.="\$config['{$row['key']}'] = " . (preg_match( '/^[TRUE|FALSE|\d]+$/i', $row['value'] ) ? $row['value'] : ("'" . $row['value'] . "'")) . ";";
		}
	}
	if ( !empty( $content ) )
	{
		$content.=" ?>";
		$size = write( APPPATH . '/config/app.php', $content );
		return strlen( $content ) == $size ? TRUE : FALSE;
	}
	return FALSE;
}

function write( $file_path, $content )
{
	if ( !file_exists( $file_path ) ) mkdir( $file_path );
	if ( !is_writeable( $file_path ) ) return FALSE;
    $fhandle = @fopen( $file_path, 'wb+' );
    $size = @fwrite( $fhandle, $content );
    @fclose( $fhandle );
    return $size;
}

function create_admin( $admin, $blogemail, $password )
{
	init_db();
	$password = md5( $password );
	$role = ADMINROLE;
	$sql = "INSERT INTO `user` (`name`, `email`, `password`, `identity`, `role`) VALUES
	('{$admin}', '{$blogemail}', '{$password}', 'admin', '{$role}')";
	mysql_query( $sql );
	return mysql_insert_id();
}

function init_db()
{
	static $already_init = false;
	if ( !$already_init )
	{
		require APPPATH . '/config/database.php';
		mysql_connect( $db['default']['hostname'], $db['default']['username'], $db['default']['password'] );
		mysql_select_db( $db['default']['database'] );
		mysql_query( 'set names ' . DBCHARSET );
		$already_init = true;
	}
}