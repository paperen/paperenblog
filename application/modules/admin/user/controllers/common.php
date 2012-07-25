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

    private function _test_mail( $email )
    {
		$email_config = email_config();
        $this->load->library( 'email', $email_config );
		$this->email->from( $email_config['smtp_user'], config_item( 'sitename' ) );
		$this->email->to( $email );
		$this->email->subject( "邀請您加入" . config_item( 'sitename' ) );
		$this->email->message( '发送邮件测试' );
		$this->email->send();
        echo $this->email->print_debugger();
    }

	/**
	 * 用戶面板
	 */
	public function panel()
	{
		$data = array( );

		// 用戶ID
		$user_id = intval( $this->adminverify->id );

		// 上次登陸IP與本次登陸IP
		$data['lastip'] = long2ip( $this->adminverify->lastip );
		$data['currentip'] = $this->input->ip_address();
		$data['lastlogin'] = $this->adminverify->lastlogin;

		// 距上次發表的文章天數
		$latest_post_arr = $this->querycache->get( 'post', 'get_by_authorid', $user_id, 1 );
		$latest_post = array_shift( $latest_post_arr );
		$data['lastposttime'] = (int)( (time() -  $latest_post['posttime'])/86400 );

		// 文章草稿
		$draft_post = $this->querycache->get( 'post', 'get_draft_by_authorid', $user_id );
		$data['draft_post'] = $draft_post;

		// 未回覆評論
		// @todo

		$this->load->view( 'panel', $data );
	}

	/**
	 * 用戶設置
	 */
	public function setting()
	{
		if ( $this->input->post( 'submit_btn' ) )
		{
			$this->_edit_personal();
		}
		else
		{
			$this->_form_personal();
		}
	}

	/**
	 * 獲取個人信息表單數據
	 * @return array
	 */
	private function _form_data_personal()
	{
		return array(
			'email' => $this->input->post( 'email' ),
			'url' => $this->form_validation->prep_url( $this->input->post( 'url' ) ),
			'password' => $this->input->post( 'password' ),
			'password_cur' => $this->input->post( 'password_cur' ),
			'job' => $this->input->post( 'job' ),
			'socialname' => $this->input->post( 'socialname' ),
			'socialurl' => $this->input->post( 'socialurl' ),
			'content' => $this->input->post( 'content' ),
		);
	}

	/**
	 * 表單驗證
	 */
	private function _validation_personal()
	{
		$this->form_validation->set_rules( 'email', '郵箱', 'required|valid_email' );
		if ( $this->input->post('password') ) $this->form_validation->set_rules( 'password', '密碼', 'required|min_length[6]|matches[password2]' );
		$this->form_validation->set_rules( 'job', '職業', 'required' );
		$this->form_validation->set_rules( 'content', '個人介紹', 'required' );
		return $this->form_validation->run();
	}

	/**
	 * 更改个人信息
	 */
	private function _edit_personal()
	{
		$data = array( );
		try
		{
			$user_data = $this->_form_data_personal();
			$data['user_data'] = $user_data;

			$user_id = intval( $this->adminverify->id );
			$org_user_data = $this->querycache->get( 'user', 'get_by_id', $user_id );
			if ( empty( $org_user_data ) ) throw new Exception( '错误操作', 0 );

			if ( !$this->_validation_personal() ) throw new Exception( validation_errors(), -1 );

			// 填寫了密碼
			if ( $user_data['password_cur'] && $user_data['password'] )
			{
				if ( md5( $user_data['password_cur'] ) != $org_user_data['password'] ) throw new Exception( '密碼填寫錯誤', -2 );
			}

			$format_social = array( );
			if ( $user_data['socialname'] )
			{
				foreach ( $user_data['socialname'] as $k => $v )
				{
					if ( empty( $v ) || !isset( $user_data['socialurl'][$k] ) || empty( $user_data['socialurl'][$k] ) ) continue;
					$format_social[$v] = $this->form_validation->prep_url( $user_data['socialurl'][$k] );
				}
			}
			$extra_data = array(
				'job' => $user_data['job'],
				'socialname' => $format_social,
				'content' => $user_data['content'],
			);
			$user_data['data'] = serialize( $extra_data );

			$affected = $this->querycache->execute( 'user', 'update', array( $user_data, $user_id ) );
			if ( empty( $affected ) ) throw new Exception( '系統出錯，請重試', -1 );
			$data['success'] = TRUE;
		}
		catch ( Exception $e )
		{
			$err_msg = $e->getMessage();
			$err_code = $e->getCode();
			if ( $err_code != -1 ) $err_msg = $this->form_validation->wrap_error( $err_msg );
			$data['err'] = $err_msg;
		}
		$this->load->view( 'form_personal', $data );
	}

	/**
	 * 个人信息表单
	 */
	private function _form_personal()
	{
		$data = array( );

		$user_data = $this->querycache->get( 'user', 'get_by_id', $this->adminverify->id );
		// user is empty
		if ( empty( $user_data ) ) redirect( base_url( 'manage' ) );

		// 附加信息
		$extra_data = $user_data['data'];
		if ( $extra_data )
		{
			$extra_data = unserialize( $extra_data );
			$user_data['job'] = $extra_data['job'];
			$social_data = $extra_data['socialname'];
			$social_name = $social_url = array( );
			// 社交站点
			foreach ( $social_data as $social => $url )
			{
				$social_name[] = $social;
				$social_url[] = $url;
			}
			$user_data['socialname'] = $social_name;
			$user_data['socialurl'] = $social_url;
			$user_data['content'] = $extra_data['content'];
		}

		$data['user_data'] = $user_data;
		$this->load->view( 'form_personal', $data );
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

		$data['total'] = $this->querycache->execute( 'user', 'total', array( ) );

		$user_data = $this->querycache->get( 'user', 'get_all' );

		// unserialize
		foreach ( $user_data as $k => $user )
		{
			if ( $user['data'] ) $user_data[$k]['data'] = unserialize( $user['data'] );
		}
		$data['user_data'] = $user_data;

		$this->load->view( 'list', $data );
	}

	/**
	 * 添加用戶表單
	 */
	public function add()
	{
		if ( deny_permission( Level::$ADMIN ) ) deny();
		if ( $this->input->post( 'submit_btn' ) )
		{
			$this->_add();
		}
		else
		{
			$this->_form();
		}
	}

	/**
	 * 表单
	 */
	private function _form()
	{
		$data = array( );
		$all_roles = $this->level->GetAllRole();
		$data['all_roles'] = $all_roles;
		$this->load->view( 'form', $data );
	}

	/**
	 * 添加动作
	 */
	private function _add()
	{
		$data = array( );
		try
		{
			if ( !$this->form_validation->check_token() ) throw new Exception( '非法操作', 0 );

			$user_data = $this->_form_data();
			$data['user_data'] = $user_data;

			if ( !$this->_validation() ) throw new Exception( validation_errors(), -1 );

			$format_social = array( );
			if ( $user_data['socialname'] )
			{
				foreach ( $user_data['socialname'] as $k => $v )
				{
					if ( empty( $v ) || !isset( $user_data['socialurl'][$k] ) || empty( $user_data['socialurl'][$k] ) ) continue;
					$format_social[$v] = $this->form_validation->prep_url( $user_data['socialurl'][$k] );
				}
			}
			$extra_data = array(
				'job' => $user_data['job'],
				'socialname' => $format_social,
				'content' => $user_data['content'],
			);
			$user_data['role'] = $this->level->SetLevel( $user_data['role'] );
			$user_data['data'] = serialize( $extra_data );

			if ( $user_data['notice'] )
			{
				// 發郵件通知
				$this->_send_invite( $user_data );
			}

			$user_id = $this->querycache->execute( 'user', 'insert', array( $user_data ) );
			if ( empty( $user_id ) ) throw new Exception( '系統出錯，請重試', -2 );
			$data['success'] = TRUE;
		}
		catch ( Exception $e )
		{
			$err_code = $e->getCode();
			$all_roles = $this->level->GetAllRole();
			$data['all_roles'] = $all_roles;
			$data['err'] = ( $err_code == -1 ) ? $e->getMessage() : $this->form_validation->wrap_error( $e->getMessage() );
		}
		$this->load->view( 'form', $data );
	}

	/**
	 * 發送邀請
	 */
	private function _send_invite( $user_data )
	{
		$email_config = email_config();

		$data['user_data'] = $user_data;
		$email_content = $this->load->view( 'invite', $data, TRUE );

		$this->load->library( 'email', $email_config );
		$this->email->from( $email_config['smtp_user'], config_item( 'sitename' ) );
		$this->email->to( $user_data['email'] );
		$this->email->subject( "邀請您加入" . config_item( 'sitename' ) );
		$this->email->message( $email_content );
		$this->email->send();
	}

	/**
	 * 表單驗證
	 */
	private function _validation( $is_edit = FALSE )
	{
		if ( $is_edit )
		{
			$this->form_validation->set_rules( 'name', '用戶名', 'required' );
			$this->form_validation->set_rules( 'email', '郵箱', 'required|valid_email' );
		}
		else
		{
			$this->form_validation->set_rules( 'name', '用戶名', 'required|is_unique[user.name]' );
			$this->form_validation->set_rules( 'email', '郵箱', 'required|valid_email|is_unique[user.email]' );
		}
		if ( !$is_edit ) $this->form_validation->set_rules( 'password', '密碼', 'required|min_length[6]|matches[password2]' );
		$this->form_validation->set_rules( 'job', '職業', 'required' );
		$this->form_validation->set_rules( 'content', '個人介紹', 'required' );
		return $this->form_validation->run();
	}

	/**
	 * 收集表单数据
	 */
	private function _form_data()
	{
		return array(
			'id' => intval( $this->input->post( 'id' ) ),
			'name' => $this->input->post( 'name' ),
			'email' => $this->input->post( 'email' ),
			'url' => $this->form_validation->prep_url( $this->input->post( 'url' ) ),
			'password' => $this->input->post( 'password' ),
			'job' => $this->input->post( 'job' ),
			'role' => $this->input->post( 'role' ),
			'socialname' => $this->input->post( 'socialname' ),
			'socialurl' => $this->input->post( 'socialurl' ),
			'content' => $this->input->post( 'content' ),
			'notice' => $this->input->post( 'notice' ),
		);
	}

	/**
	 * 修改用户
	 */
	public function edit( $user_id )
	{
		if ( deny_permission( Level::$ADMIN ) ) deny();
		if ( $this->input->post( 'submit_btn' ) )
		{
			$this->_edit();
		}
		else
		{
			$this->_edit_form( $user_id );
		}
	}

	/**
	 * 修改用户信息
	 */
	private function _edit()
	{
		$data = array( );
		try
		{
			if ( !$this->form_validation->check_token() ) throw new Exception( '非法操作', 0 );

			$user_data = $this->_form_data();
			$data['user_data'] = $user_data;

			$user_id = intval( $user_data['id'] );
			$org_user_data = $this->querycache->get( 'user', 'get_by_id', $user_id );
			if ( empty( $org_user_data ) ) throw new Exception( '错误操作', 0 );

			if ( !$this->_validation( TRUE ) ) throw new Exception( validation_errors(), -1 );

			$format_social = array( );
			if ( $user_data['socialname'] )
			{
				foreach ( $user_data['socialname'] as $k => $v )
				{
					if ( empty( $v ) || !isset( $user_data['socialurl'][$k] ) || empty( $user_data['socialurl'][$k] ) ) continue;
					$format_social[$v] = $this->form_validation->prep_url( $user_data['socialurl'][$k] );
				}
			}
			$extra_data = array(
				'job' => $user_data['job'],
				'socialname' => $format_social,
				'content' => $user_data['content'],
			);
			$user_data['role'] = $this->level->SetLevel( $user_data['role'] );
			$user_data['data'] = serialize( $extra_data );


			$affected = $this->querycache->execute( 'user', 'update', array( $user_data, $user_id ) );
			if ( empty( $affected ) ) throw new Exception( '系統出錯，請重試', -1 );
			$data['success'] = TRUE;
		}
		catch ( Exception $e )
		{
			$all_roles = $this->level->GetAllRole();
			$data['all_roles'] = $all_roles;
			$data['err'] = $e->getMessage();
		}
		$this->load->view( 'form', $data );
	}

	/**
	 * 修改用户信息表单
	 * @param int $user_id 用户ID
	 */
	private function _edit_form( $user_id )
	{
		$data = array( );

		$all_roles = $this->level->GetAllRole();
		$data['all_roles'] = $all_roles;
		$data['isedit'] = TRUE;
		try
		{
			$user_id = intval( $user_id );
			$user_data = $this->querycache->get( 'user', 'get_by_id', $user_id );
			// user is empty
			if ( empty( $user_data ) ) redirect( base_url( 'user' ) );

			// 附加信息
			$extra_data = $user_data['data'];
			if ( $extra_data )
			{
				$extra_data = unserialize( $extra_data );
				$user_data['job'] = $extra_data['job'];
				$social_data = $extra_data['socialname'];
				$social_name = $social_url = array( );
				// 社交站点
				foreach ( $social_data as $social => $url )
				{
					$social_name[] = $social;
					$social_url[] = $url;
				}
				$user_data['socialname'] = $social_name;
				$user_data['socialurl'] = $social_url;
				$user_data['content'] = $extra_data['content'];
			}
			// 用户权限
			$user_role = $this->level->GetLevel( $user_data['role'] );
			$user_data['role'] = is_array( $user_role ) ? $user_role : array( $user_role );
			$data['user_data'] = $user_data;
		}
		catch ( Exception $e )
		{

		}
		$this->load->view( 'form', $data );
	}

}

// end of common
