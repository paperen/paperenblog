<?php

/**
 * 表单验证加入令牌扩展
 * @subpackage application/libraries
 * @author paperen@gmail.com
 */
if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class MY_Form_validation extends CI_Form_validation
{

	public $CI;
	private $debug = FALSE;
	// 错误定界符
	var $error_prefix = '<li>';
	var $error_suffix = '</li>';

	function __construct()
	{
		parent::__construct();
		$this->CI = & get_instance();
		$this->CI->load->library( 'session' );

		// 设置错误定界符
		$this->set_error_delimiters( $this->error_prefix, $this->error_suffix );
	}

	/**
	 * 使用自身的错误定界符封装错误信息
	 * @param string $message 错误信息
	 * @return string 封装后的信息
	 */
	public function wrap_error( $message )
	{
		return $this->error_prefix . $message . $this->error_suffix;
	}

	/**
	 * 检查session中是否存在token
	 */
	private function _is_token()
	{
		return $this->CI->session->userdata( 'token' ) ? TRUE : FALSE;
	}

	/**
	 * 判断是否超时
	 */
	function check_token()
	{
		// 调试模式
		if ( $this->debug ) return TRUE;

		// 判断是否存在token
		if ( $this->_is_token() )
		{
			$now = time();
			$timeout = config_item( 'token_timeout' ) * 60;
			$token = $this->get_token();
			if ( $token && $this->CI->input->post( 'token' ) == md5( $token ) )
			{
				// 销毁掉
				$this->destroy_token();

				// 没时间限制
				if ( $timeout == 0 ) return TRUE;
				return ($now - $token) < $timeout;
			}
		}

		// 销毁掉
		$this->destroy_token();
		return FALSE;
	}

	/**
	 * 获取token值
	 * @return string
	 */
	public function get_token()
	{
		return $this->CI->session->userdata( 'token' );
	}

	/**
	 * 销毁存在session的token值
	 */
	public function destroy_token()
	{
		$this->CI->session->unset_userdata( 'token' );
	}

	/**
	 * 判断是否是日期格式
	 * @param string $str
	 */
	public function is_date( $str )
	{
		return (bool)preg_match( '/\d{4}\-\d{1,2}\-\d{1,2}/', $str );
	}

}

?>
