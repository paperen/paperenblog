<?php

/**
 * 2012-3-30 0:23:36
 * 重寫CI異常類
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/core/
 */
class MY_Exceptions extends CI_Exceptions
{

	private $_url404;
	private $_urlerr;

	function __construct()
	{
		parent::__construct();
		$this->_url404 = config_item('base_url') . '404';
		$this->_urlerr = config_item('base_url') . 'error';
	}

	/**
	 * 404處理重定向
	 */
	public function show_404()
	{
		$this->_redirect( $this->_url404 );
	}

	/**
	 * Error
	 * @param type $heading
	 * @param string $message
	 * @param type $template
	 * @param type $status_code
	 * @return type
	 */
	function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		$this->_redirect( $this->_urlerr );
	}

	/**
	 * Native PHP error handler
	 *
	 * @access	private
	 * @param	string	the error severity
	 * @param	string	the error string
	 * @param	string	the error filepath
	 * @param	string	the error line number
	 * @return	string
	 */
	function show_php_error($severity, $message, $filepath, $line)
	{
		$this->_redirect( config_item('base_url') );
	}

	private function _redirect( $uri = '', $method = 'location', $http_response_code = 302 )
	{
		if ( !preg_match( '#^https?://#i', $uri ) )
		{
			$uri = site_url( $uri );
		}

		switch ( $method )
		{
			case 'refresh' : header( "Refresh:0;url=" . $uri );
				break;
			default : header( "Location: " . $uri, TRUE, $http_response_code );
				break;
		}
		exit;
	}

}

// end of MY_Exceptions