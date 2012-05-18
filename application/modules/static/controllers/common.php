<?php

/**
 * 2012-3-18 17:34:28
 * 靜態模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/tag/controllers/
 */
class Static_Common_Module extends CI_Module
{

	/**
	 * 404頁面
	 */
	public function not_found()
	{
		$data = array( );
		$this->load->view( '404', $data );
	}

	/**
	 * 錯誤頁面
	 */
	public function error()
	{
		$data = array( );
		$this->load->view( 'error', $data );
	}

}

// end of common