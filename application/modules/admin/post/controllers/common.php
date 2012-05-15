<?php

/**
 * 2012-4-25 00:44:28
 * 管理平台文章公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/post/controllers/
 */
class Admin_Post_Common_Module extends MY_Module
{

	/**
	 * 發表新文章
	 */
	public function add()
	{
		$data = array();
		$this->load->view( 'form', $data );
	}

	/**
	 * 保存文章數據
	 */
	public function save()
	{
		echo 'save data';
	}

}

// end of common