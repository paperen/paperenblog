<?php

/**
 * 2012-3-18 17:04:25
 * 归档控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/controllers
 */

class Archive extends MY_Controller
{
    public function index()
	{
		$data = array(
			'header' => $this->load->module( 'header/common', array( ), TRUE ),
			'archive' => $this->load->module( 'post/common/archive', array( ), TRUE ),
			'sidebar' => $this->load->module( 'sidebar/common', array( ), TRUE ),
			'footer' => $this->load->module( 'footer/common', array( ), TRUE ),
		);
		$this->load->view( 'archive', $data );
	}
}

// end of home