<?php

/**
 * 2012-4-25 00:44:28
 * 管理平台边栏模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/sidebar/controllers/
 */
class Admin_Sidebar_Common_Module extends MY_Module
{

	public function index()
	{
		$data = array( );

		// 用戶文章總數
		$data['post_total'] = $this->querycache->get( 'post', 'total_by_authorid', $this->adminverify->id );

		// 回收站文章總數
		$data['trash_total'] = $this->querycache->get( 'post', 'total_trash_by_authorid', $this->adminverify->id );

		$this->load->view( 'index', $data );
	}

}

// end of common