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
		$data = array();
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

}

// end of common