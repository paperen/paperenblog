<?php

/**
 * 2012-4-25 00:44:28
 * 管理平台用戶公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/user/controllers/
 */
class Admin_User_Common_Module extends MY_Module
{

	/**
	 * 用戶面板
	 */
	public function panel()
	{
		$data = array( );
		$this->load->view( 'panel', $data );
	}

	/**
	 * 用戶設置
	 */
	public function setting()
	{

	}

	/**
	 * 用戶登出
	 */
	public function logout()
	{
		$this->adminverify->unset_userdata();
		redirect( 'login' );
	}

	/**
	 * 博客用戶列表
	 */
	public function index()
	{
		$data = array( );

		$data['total'] = $this->querycache->execute('user', 'total', array());

		$user_data = $this->querycache->get('user', 'get_all');
		$data['user_data'] = $user_data;

		$this->load->view( 'list', $data );
	}

	/**
	 * 添加用戶表單
	 */
	public function add()
	{
		$data = array();
		$this->load->view( 'form', $data );
	}

}

// end of common