<?php

/**
 * 2012-3-18 17:34:28
 * 边栏模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/sidebar/controllers/
 */
class Sidebar_Common_Module extends CI_Module
{

	/**
	 * 加载边栏
	 */
	public function index()
	{
		$data = array(
		);
		$this->load->view( 'index', $data );
	}

}

// end of common