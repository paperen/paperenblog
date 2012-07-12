<?php

/**
 * 作為後臺用戶檢測
 * 依賴于CI模型
 * @author paperen
 */
class Adminverify
{

	private $_CI;
	private $_session_key = 'paperenblog_admin';
	private $_user_data;

	const ADMIN = 2;
	const AUTHOR = 1;
	const READER = 0;

	function __construct()
	{
		$this->_CI = & get_instance();
		$this->_CI->load->library( 'level' );
		$this->_CI->load->library( 'session' );

		$this->_init();
	}

	private function _init()
	{
		$this->_user_data = $this->_CI->session->userdata( $this->_session_key );
	}

	/**
	 * 驗證
	 * @return bool
	 */
	public function valid()
	{
		if ( empty( $this->_user_data ) ) return FALSE;
		$level = $this->_CI->level->GetLevel( $this->_user_data['role'] );
		if ( $level <= 0 )
		{
			$this->unset_userdata();
			return FALSE;
		}
		return $level;
	}

	/**
	 * 設置用戶session數據
	 * @param mixed $user_data
	 */
	public function set_userdata( $user_data )
	{
		$this->_CI->session->set_userdata( $this->_session_key, $user_data );
	}

	/**
	 * 銷毀用戶session數據
	 */
	public function unset_userdata()
	{
		$this->_CI->session->unset_userdata( $this->_session_key );
	}

	/**
	 * __Get
	 * @param string $name
	 * <code>
	 * id
	 * name
	 * url
	 * email
	 * lastlogin
	 * lastip
	 * identity
	 * role
	 * token
	 * </code>
	 * @return mixed
	 */
	function __get( $name )
	{
		return isset( $this->_user_data[$name] ) ? $this->_user_data[$name] : '';
	}

	function __set( $name, $value )
	{
		$this->_user_data[$name] = $value;
		$this->set_userdata( $this->_user_data );
	}

	/**
	 * 檢查是否有權限操作
	 * @param int $upper_level 最低權限要求
	 */
	public function deny_permission()
	{
		$need_role = func_get_args();
		$no_permission = FALSE;
		$role_val = array();
		foreach( $need_role as $v )
		{
			$role_val[] = $this->_CI->level->SetLevel( $v );
		}

		$user_role = $this->_CI->level->GetLevel( $this->role );
		if ( is_array( $user_role ) )
		{
			foreach( $user_role as $v )
			{
				$user_val[] = $this->_CI->level->SetLevel( $v );
			}
		}
		else
		{
			$user_val[] = $this->_CI->level->SetLevel( $user_role );
		}

		if( array_intersect( $user_val, $role_val ) ) return FALSE;
		return TRUE;
	}

}

// end of Adminverify