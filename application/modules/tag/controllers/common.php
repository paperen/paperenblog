<?php

/**
 * 2012-3-18 17:34:28
 * 標籤模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/post/controllers/
 */
class Tag_Common_Module extends CI_Module
{

	public function index()
	{
		$data = array( );
		$this->load->view( 'tag', $data );
	}

}

// end of common