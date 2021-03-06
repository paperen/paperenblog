<?php

/**
 * 2012-3-18 17:34:28
 * 關於模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/post/controllers/
 */
class About_Common_Module extends CI_Module
{

	public function index()
	{
		$data = array( );
		$about_data = $this->querycache->get('config', 'get_by_key', 'about');
        $data['about'] = stripslashes( $about_data['value'] );
		$this->load->view( 'index', $data );
	}

}

// end of common