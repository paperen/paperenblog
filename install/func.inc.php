<?php

if (!defined('IN_INSTALL'))
    exit('Access Denied');

function check_db($dbhost, $dbuser, $dbpw, $dbname='', $tablepre='', $c = TRUE)
{
    if (!function_exists('mysql_connect'))
    {
        show_msg('undefine_func:', 'mysql_connect', 0);
    }
    if (!@mysql_connect($dbhost, $dbuser, $dbpw))
    {
        $errno = mysql_errno();
        $error = mysql_error();
        if ($errno == 1045)
        {
            show_msg('database_errno_1045:', $error, 0);
        }
        elseif ($errno == 2003)
        {
            show_msg('database_errno_2003:', $error, 0);
        }
        else
        {
            show_msg('database_connect_error:', $error, 0);
        }
    }
    else if (!empty($dbname))
    {
        $query = @mysql_query("show databases");
        $exist = FALSE;
        while ($row = mysql_fetch_array($query))
        {
            if ($dbname == $row[0])
            {
                $exist = TRUE;
                break;
            }
        }
        if (!$exist && $c)
        {
            $query = mysql_query("CREATE DATABASE  {$dbname} DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
            if ($query)
            {
                return true;
            }
            elseif (!$exist && !$c)
            {
                show_msg("数据库{$dbname}不存在！", '', 0);
            }
            else
            {
                show_msg("创建数据库{$dbname}失败！", '', 0);
            }
        }
        elseif (!$exist && !$c)
        {
            show_msg("数据库{$dbname}不存在！", '', 0);
        }
    }
    else
    {
        if ($query = @mysql_query("SHOW TABLES FROM $dbname"))
        {
            while ($row = mysql_fetch_row($query))
            {
                if (preg_match("/^$tablepre/", $row[0]))
                {
                    return false;
                }
            }
        }
    }
    return true;
}

function show_msg($msg, $code, $status = 1)
{
    $comment = $msg . $code;
    header('content-Type:text/html;charset=utf8');
    if ($status)
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
    include_once(BASE_PATH . DIRECTORY_SEPARATOR .  'template' . DIRECTORY_SEPARATOR . $data['now_step'] . '.php');
}

function next_step()
{
	global $data, $step;
	$next_step = $data['step_key'] + 1;
	echo "?step_key={$next_step}";
}

function get_dir_able()
{
    global $dirs;
    $result = array();
    foreach ($dirs as $dir)
    {
        $result[$dir] = is_writable($dir);
    }
    return $result;
}

function runquery($sql, $delimiter=";\n", $table = TRUE)
{
    global $database;
    global $data;
    if (!isset($sql) || empty($sql))
        return;
    $sql = str_replace("\r", "\n", str_replace(ORIG_TABLEPRE, $database['table_pre'], $sql));
    $ret = array();
    $num = 0;

    foreach (explode($delimiter, trim($sql)) as $query)
    {
        $ret[$num] = '';
        $queries = explode("\n", trim($query));
        foreach ($queries as $query)
        {
            $ret[$num] .= ( isset($query[0]) && $query[0] == '#') || (isset($query[1]) && isset($query[1]) && $query[0] . $query[1] == '--') ? '' : $query;
        }
        $num++;
    }
    unset($sql);
    $key = '';
    if ($table)
    {
         $key = FALSE;
        foreach ($ret as $query)
        {
            $query = trim($query);
            if ($query)
            {
                if (substr($query, 0, 12) == 'CREATE TABLE')
                {
                    $name = preg_replace("/CREATE TABLE IF NOT EXISTS `([a-z0-9_]+)` .*/is", "\\1", $query);
                    if (mysql_query($query))
                    {
                        showjsmessage('创建表' . ' ' . $name . '.......................................................................................... ' . '<font color="#33FF33">√</font>');
                    }
                    else
                    {
                        showjsmessage('创建表' . ' ' . $name . '..........................................................................................' . '<font color="#FF0000">×</font>');
                        $key = FALSE;
                        break;
                    }
                }
                else
                {
                    mysql_query($query);
                }
            }
        }
    }
    else
    {
        $key = TRUE;
        foreach ($ret as $query)
        {
            $query = trim($query);
            if ($query)
            {
                if (substr($query, 0, 14) == 'CREATE TRIGGER')
                {
                    $name = preg_replace("/CREATE TRIGGER `([a-z0-9_]+)` .*/is", "\\1", $query);
                    if (mysql_query($query))
                    {
                        showjsmessage('创建触发器' . ' ' . $name . '.......................................................................................... ' . '<font color="#33FF33">√</font>');
                    }
                    else
                    {
                        showjsmessage('创建触发器' . ' ' . $name . '..........................................................................................' . '<font color="#FF0000">×</font>');
                        $key = FALSE;
                        break;
                    }
                }
                elseif (substr($query, 0, 16) == 'CREATE PROCEDURE')
                {
                    $name = preg_replace("/CREATE PROCEDURE `([a-z0-9_]+)` .*/is", "\\1", $query);
                    if (mysql_query($query))
                    {
                        showjsmessage('创建存储例程' . ' ' . $name . '.......................................................................................... ' . '<font color="#33FF33">√</font>');
                    }
                    else
                    {
                        showjsmessage('创建存储例程' . ' ' . $name . '..........................................................................................' . '<font color="#FF0000">×</font>');
                        $key = FALSE;
                        break;
                    }
                }
                else
                {
                    mysql_query($query);
                }
            }
        }
    }
    if ($key)
    {
        if (write_database_config())
        {

            showjsmessage('创建配置文件database.php .......................................................................................... ' . '<font color="#33FF33">√</font>');
            echo "<script>setTimeout('window.location.href=\"./index.php?step_key={$data[step_key]}\";', 4000);</script>";
        }
        else
        {
            showjsmessage('创建配置文件database.php .......................................................................................... ' . '<font color="#FF0000">×</font>');
        }
    }
    if(!$table){
    mysql_close();
    }
}

function showjsmessage($message)
{
    echo "<script type=\"text/javascript\">$('#database_info').append('<p>{$message}</p>');</script>\r\n";
    flush();
    ob_flush();
    sleep(1);
}

function write_database_config()
{
    global $database;
    $content = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\r\n";
    $content = $content . "\$active_group = 'default';\r\n";
    $content = $content . "\$active_record = TRUE;\r\n";
    $content = $content . "\$db['default']['hostname'] = '{$database['db_host']}';\r\n";
    $content = $content . "\$db['default']['username'] = '{$database['db_user_name']}';\r\n";
    $content = $content . "\$db['default']['password'] = '{$database['db_password']}';\r\n";
    $content = $content . "\$db['default']['database'] = '{$database['db_name']}';\r\n";
    $content = $content . "\$db['default']['dbdriver'] = 'mysql';\r\n";
    $content = $content . "\$db['default']['dbprefix'] = '{$database['table_pre']}';\r\n";
    $content = $content . "\$db['default']['pconnect'] = FALSE;\r\n";
    $content = $content . "\$db['default']['db_debug'] = TRUE;\r\n";
    $content = $content . "\$db['default']['cache_on'] = FALSE;\r\n";
    $content = $content . "\$db['default']['cachedir'] = '';\r\n";
    $content = $content . "\$db['default']['char_set'] = 'utf8';\r\n";
    $content = $content . "\$db['default']['dbcollat'] = 'utf8_general_ci';\r\n";
    $content = $content . "\$db['default']['swap_pre'] = '';\r\n";
    $content = $content . "\$db['default']['autoinit'] = TRUE;\r\n";
    $content = $content . "\$db['default']['stricton'] = FALSE;\r\n";
    $file_name = str_replace('install', '', BASE_PATH) . 'application\\config\\database.php';
    if (strlen($content) == file_put_contents($file_name, $content))
        return TRUE;
    return FALSE;
}

function write_app_config()
{

    global $appid;
    global $lyconfig;
    $content = "<?php\r\n";
    $sql = "select * from {$lyconfig} where appid={$appid}";
    $query = mysql_query($sql);
    if ($query)
    {
        while ($row = mysql_fetch_array($query, MYSQL_ASSOC))
        {
            $content.="//{$row['name']}\r\n\$config['{$row['key']}'] = " . (preg_match('/^[TRUE|FALSE|\d]+$/i', $row['value']) ? $row['value'] : ("'" . $row['value'] . "'")) . ";\r\n";
        }
    }
    if (!empty($content))
    {
        $content.="\r\n ?>";
        $size = write(APPPATH . '/config/dm_config.php', $content);
        $csize = strlen($content);
        return $size == $csize ? TRUE : FALSE;
    }
}

function write($file_path, $content, $c=TRUE)
{
    if (file_exists($file_path))
    {
        if (is_writeable($file_path))
        {

            if ($c)
            {
                return file_put_contents($file_path, $content);
            }
        }
    }
    else
    {
        if (mkdir($file_path))
        {
            if ($c)
            {
                return file_put_contents($file_path, $content);
            }
        }
    }
}

function create_admin()
{
    global $set;
    global $db;
    mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password']);
    mysql_query('set names ' . DBCHARSET);
    mysql_select_db($db['default']['database']);
    mysql_query("use {$db['default']['database']}");
    $now = time();
    mysql_query("INSERT INTO `{$db['default']['dbprefix']}user` (`name`, `password`, `realname`, `state`, `addtime`, `sectionid`, `email`, `phone`, `admin`, `avatar`) VALUES
	('超级管理员', '{$set['pass_word']}', 'LYCenter Administrat',
	1, {$now}, 0, '{$set['email']}',  '', 1, NULL)");
    $result = mysql_query("SELECT `id` FROM `{$db['default']['dbprefix']}access`");
    $sql = "INSERT INTO `{$db['default']['dbprefix']}groupaccess` (`groupid`, `accessid`) VALUES";
    $access_ids = array();
    while ($row = mysql_fetch_array($result))
    {
        $access_ids[] = "(1, {$row['id']})";
    }
    $sql = $sql . implode(' , ', $access_ids);
    mysql_free_result($result);
    mysql_query($sql);
    mysql_close();
}