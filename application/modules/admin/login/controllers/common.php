<?php

/**
 * 2012-4-25 00:44:28
 * 管理平台边登录公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/login/controllers/
 */
class Admin_Login_Common_Module extends CI_Module
{

	function __construct()
	{
		parent::__construct();
		$this->load->library( 'adminverify' );

		// 如果已登錄的話
		if ( $this->adminverify->valid() ) redirect( 'manage' );
	}

	public function index()
	{
		if ( $this->form_validation->check_token() )
		{
			// 登錄數據提交
			$this->_login();
		}
		else
		{
			$this->_form();
		}
	}

	/**
	 * 登錄處理
	 */
	private function _login()
	{
		$data = array( );
		try
		{
			// 收集表單數據
			$form_data = $this->_form_data();
			$data['user_data'] = $form_data;

			// 表單驗證
			if ( !$this->_form_validation() ) throw new Exception( validation_errors(), 0 );

			// 驗證用戶名與密碼
			$user_data = $this->querycache->get( 'user', 'get_by_name', $form_data['username'] );
			if ( empty( $user_data ) ) throw new Exception( '沒有此用戶，估計是外星人吧', -1 );

			if ( $this->get_password_hash( $form_data['password'] ) != $user_data['password'] ) throw new Exception( '親，密碼錯誤鳥，別說你忘記密碼了…', -2 );

			$this->adminverify->set_userdata(
					array(
						'id' => $user_data['id'],
						'name' => $user_data['name'],
						'url' => $user_data['url'],
						'email' => $user_data['email'],
						'lastlogin' => $user_data['lastlogin'],
						'lastip' => $user_data['lastip'],
						'identity' => $user_data['identity'],
						'role' => $user_data['role'],
					)
			);
			redirect( base_url( 'manage' ) );
		}
		catch ( Exception $e )
		{
			$error_message = $e->getMessage();
			$data['error'] = $error_message;
		}
		$this->load->view( 'index', $data );
	}

	/**
	 * 表單驗證
	 * @return bool
	 */
	private function _form_validation()
	{
		$this->form_validation->set_rules( 'username', '用戶名', 'required' );
		$this->form_validation->set_rules( 'password', '密碼', 'required' );
		return $this->form_validation->run();
	}

	/**
	 * 獲取表單數據
	 * @return array
	 */
	private function _form_data()
	{
		return array(
			'username' => $this->input->post( 'username' ),
			'password' => $this->input->post( 'password' ),
		);
	}

	/**
	 * 登錄表單
	 */
	private function _form()
	{
		$data = array( );
		$this->load->view( 'index', $data );
	}

	public function get_password_hash( $password_raw )
	{
		return md5( $password_raw );
	}

}

// end of common