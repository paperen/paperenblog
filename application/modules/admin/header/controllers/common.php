<?php

/**
 * 2012-4-25 00:44:28
 * 管理头部模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/header/controllers/
 */
class Admin_Header_Common_Module extends MY_Module
{

	public function index()
	{
		$data = array();
		$this->load->view( 'index', $data );
	}

}

// end of common