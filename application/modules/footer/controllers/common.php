<?php

/**
 * 2012-3-18 17:34:28
 * 脚部模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/footer/controllers/
 */
class Footer_Common_Module extends CI_Module
{

	public function index()
	{
		$data = array( );
		$this->load->view( 'index', $data );

		// 调试数据
		$this->output->enable_profiler( defined( 'ENVIRONMENT' ) && ENVIRONMENT == 'development' );
	}

}

// end of common