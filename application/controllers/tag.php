<?php

/**
 * 2012-3-18 17:04:25
 * 标签控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/controllers
 */
class Tag extends MY_Controller
{

	public function index()
	{
		$data = array(
			'header' => $this->load->module( 'header/common', array( ), TRUE ),
			'sidebar' => $this->load->module( 'sidebar/common', array( ), TRUE ),
			'footer' => $this->load->module( 'footer/common', array( ), TRUE ),
		);
		$this->load->view( 'tag', $data );
	}

}

// end of home