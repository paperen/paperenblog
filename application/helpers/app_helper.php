<?php

/**
 * 2012-3-18 17:58:04
 * 博客函数库
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/helpers/
 */

/**
 * 引入CSS
 * @param string $css css文件
 * @return string
 */
function css( $css )
{
	$css = strpos( $css, 'http://' ) !== FALSE ? $css : base_url( 'theme' ) . '/' . trim( config_item( 'theme' ), '/' ) . '/' . $css;
	return "<link href=\"{$css}\" rel=\"stylesheet\">";
}

/**
 * 引入JS
 * @param string $js js文件
 * @return string
 */
function js( $js )
{
	$js = strpos( $js, 'http://' ) !== FALSE ? $js : rtrim( base_url( 'js' ), '/' ) . '/' . $js;
	return "<script src=\"{$js}\"></script>";
}

/**
 * 生成页面标题
 * @param string $page_title 标题
 * @return string
 */
function page_title( $page_title = '', $delimiter = '&raquo;' )
{
	$page_title = empty( $page_title ) ? config_item( 'sitename' ) : $page_title . ' ' . $delimiter . ' ' . config_item( 'sitename' );
	return "<title>{$page_title}</title>";
}

/**
 * 获取文章片段
 * @param string $content 文章内容
 * @param string $delimiter 分割标识符
 * @return string
 */
function get_post_fragment( $content, $delimiter = '<!--more-->' )
{
	if ( strpos( $content, $delimiter ) === FALSE ) return $content;
	return array_shift( explode( $delimiter, $content ) );
}

/**
 * 根據UNIX時間戳獲取中文的星期
 * @param int $unixtime UNIX時間戳[option]
 * @return string
 */
function get_weekday_from_unixtime( $unixtime = '' )
{
	$unixtime = empty( $unixtime ) ? time() : $unixtime;
	$weekday_CN = array( '日', '壹', '貳', '叁', '肆', '伍', '陸' );
	return $weekday_CN[date( 'w', $unixtime )];
}

/**
 * 根據UNIX時間戳獲取時間距離的描述
 * @param int $unixtime 時間戳
 * @return string
 */
function get_time_diff( $unixtime, $prefix = '<strong>', $subfix = '</strong>' )
{
	$now = time();
	if ( $unixtime > $now ) return '未來進行時';
	$diff = $now - $unixtime;
	if ( $diff >= 0 && $diff < 60 )
	{
		return "{$prefix}{$diff}{$subfix}秒前";
	}
	else if ( $diff >= 60 && $diff < 3600 )
	{
		$min = floor( $diff / 60 );
		return "{$prefix}{$min}{$subfix}分鐘前";
	}
	else if ( $diff >= 3600 && $diff < 86400 )
	{
		$hour = floor( $diff / 3600 );
		return "{$prefix}{$hour}{$subfix}小時前";
	}
	else if ( $diff >= 86400 && $diff < 2592000 )
	{
		$day = floor( $diff / 86400 );
		return "{$prefix}{$day}{$subfix}天前";
	}
	else if ( $diff >= 2592000 && $diff < 31104000 )
	{
		$month = floor( $diff / 2592000 );
		return "{$prefix}{$month}{$subfix}月前";
	}
	else
	{
		$year = floor( $diff / 31104000 );
		return "{$prefix}{$year}{$subfix}年前";
	}
}

if ( !function_exists( 'gbk_substr' ) )
{

	/**
	 * 文本截取
	 * @param string $str 中文文本
	 * @param int $length 截取长度
	 * @return string 截取后的文本
	 */
	function gbk_substr( $string, $length, $from = 0 )
	{
		$string = strip_tags( $string );
		if ( $length == 0 )
		{
			return $string;
		}
		else
		{
			return preg_replace( '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $from . '}' .
							'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $length . '}).*#s', '$1', $string );
		}
	}

}

/**
 * 为连接附加http
 * @param string $url URL
 * @return string 补全后的URL
 */
function add_http( $url )
{
	$str = 'http://';
	if ( strpos( $url, $str ) === FALSE ) return $str . $url;
	return $url;
}

/**
 * 獲取文章固定連接URL
 * @param array $urltitle 文章URL標題或ID
 * @return string
 */
function post_permalink( $urltitle )
{
	return base_url( "post/{$urltitle}" );
}

/**
 * 獲取作者URL
 * @param string $author 作者
 * @return string
 */
function author_url( $author )
{
	return base_url( "author/{$author}" );
}

/**
 * 獲取文章類別URL
 * @param string $category 類別名稱
 * @return string
 */
function category_url( $category )
{
	return base_url( "category/{$category}" );
}

/**
 * 獲取標籤URL
 * @param string $tag 標籤
 * @return string
 */
function tag_url( $tag )
{
	return base_url( "tag/{$tag}" );
}

/**
 * 獲取gravatar路徑
 * @param string $email 郵箱
 * @param int $size 尺寸
 * @return string
 */
function gravatar_url( $email, $size = '' )
{
	$size = ( $size ) ? "?s={$size}" : '';
	return 'http://www.gravatar.com/avatar/' . md5( strtolower( trim( $email ) ) ) . $size;
}

/**
 * 獲取文件路徑
 * @param int $id 附件ID
 * @return string
 */
function file_url( $id )
{
	return base_url( "file/{$id}" );
}

/**
 * 生成歸檔路徑
 * @param int $year 年份
 * @param int $month 月份
 * @param int $day 日
 * @return string
 */
function archive_url( $year = '', $month = '', $day = '' )
{
	if ( empty( $year ) )
	{
		return base_url( 'archive' );
	}
	else
	{
		$args = func_get_args();
		$delimiter = '/';
		$temp = trim( implode( $delimiter, $args ), $delimiter );
		return base_url( "archive/{$temp}" );
	}
}

/**
 * 生成歸檔路徑(按文章類別)
 * @param int $category_name 類別名稱
 * @return string
 */
function archive_category_url( $category_name = '' )
{
	if ( $category_name == NULL )
	{
		return base_url( 'archive/category' );
	}
	else
	{
		return base_url( "archive/category/{$category_name}" );
	}
}

/**
 * 生成評論URL
 * @param string $urltitle 文章URL標題
 * @param int $comment_id 評論ID[option]
 * @return string
 */
function comment_url( $urltitle, $comment_id = '' )
{
	$urltitle .= ( $comment_id ) ? "#comment-{$comment_id}" : '#comment-form';
	return base_url( "post/{$urltitle}" );
}

/**
 * 獲取RSS路徑
 * @return string
 */
function rss_url()
{
	return base_url( 'rss' );
}

/**
 * 創建表單令牌
 * @param bool 是否返回令牌值
 * @return string
 */
function create_token( $ret = FALSE )
{
	$CI = & get_instance();
	$time = time();
	$CI->session->set_userdata( 'token', $time );
	if ( $ret ) return md5( $time );
	return form_hidden( 'token', md5( $time ), 'id="token"' );
}

/**
 * 純屬惡搞，隨機獲取變形金剛圖片
 * @return string
 */
function bxjg_random()
{
	return base_url( 'upload/thumbnail' ) . '/' . 'bxjg_' . rand( 1, 11 ) . '.gif';
}

/**
 * 404頁面URL
 * @return string
 */
function page_not_found()
{
	redirect( base_url( '404' ) );
}

function favicon_ico( $name = '' )
{
	if ( empty( $name ) ) $name = 'favicon.ico';
	$url = base_url( 'theme' ) . '/' . trim( config_item( 'theme' ), '/' ) . '/image/' . $name;
	return "<link rel=\"shortcut icon\" href=\"{$url}\" />";
}

/**
 * 拒絕操作
 * @param string $url
 */
function deny( $url = '' )
{
	$url = empty( $url ) ? base_url( 'deny' ) : $url;
	redirect( $url );
}

/**
 * 获得文本中的图片（用作縮略圖）
 * @param string $string
 * @param int $index
 * @return int
 */
function get_thumbimg_from_string( $string, $index = 1 )
{

	// 匹配所有图片
	preg_match_all( '/\<img.*src=\"(.*)\".*\/\>/Ui', $string, $matches );
	$img_arr = $matches[1];
	if ( empty( $img_arr ) ) return;

	// 过滤非本站图片
	$self_img_arr = array( );
	foreach ( $img_arr as $img_url )
	{
		if ( strpos( $img_url, '/file/' ) === FALSE ) continue;
		$self_img_arr[] = $img_url;
	}
	if ( empty( $self_img_arr ) ) return;

	if ( $index > count( $self_img_arr ) || $index < 0 ) $index = 1;
	$self_img = $self_img_arr[$index - 1];
	preg_match( '/.*\/file\/(\d+)/', $self_img, $matches );
	return $matches[1];
}

/**
 * 從文章內容中篩選出上傳文件的ID
 * @param string $string
 * @param int $index
 * @return mixed
 */
function get_file_from_string( $string, $index = -1 )
{
	// 匹配所有图片
	preg_match_all( '/\<img.*src=\".*\/file\/(\d+)\/?\".*\/\>/Ui', $string, $matches );
	if ( empty( $matches[1] ) ) return;
	if ( $index < 0 ) return $matches[1];
	if ( $index > count( $matches[1] ) ) return $matches[1][0];
	return $matches[1][$index];
}

/**
 * 獲取文件原始名（不含後綴）
 * @param string $filename
 * @return string
 */
function get_file_rawname( $filename )
{
	return substr( $filename, 0, strrpos( $filename, '.' ) );
}

/**
 * 根據十進制權限值獲取身份
 * @param int $level
 * @return string
 */
function get_role( $level )
{
	$role_arr = array(
		'讀者',
		'作者',
		'管理員'
	);
	$CI = & get_instance();
	$CI->load->library( 'level' );
	$role_val = $CI->level->GetLevel( $level );
	$role = '';
	if ( is_array( $role_val ) )
	{
		foreach( $role_val as $k => $v ) $role .= $role_arr[$v] . ',';
	}
	else
	{
		$role .= $role_arr[$role_val];
	}
	return trim( $role, ',' );
}
/**
 * ip to long
 * @param string $ip
 * @return int
 */
function mip2long( $ip )
{
	return bindec( decbin( ip2long( $ip ) ) );
}

// end of app_helper