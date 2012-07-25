<?php

/**
 * 2012-4-25 00:44:28
 * 管理平台设置公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/setting/controllers/
 */
class Admin_Config_Common_Module extends MY_Module
{

	private $_config_weibo_prefix = 'weibo';
	private $_config_email_prefix = 'email';

	private $_email_type = array(
		'mail',
		'smtp',
	);

	public function index()
	{
		if ( $this->input->post( 'submit_btn' ) )
		{
			$this->_update();
		}
		else
		{
			$this->_form();
		}
	}

	private function _update()
	{
		$data = array( );
		try
		{
			if ( !$this->form_validation->check_token() ) throw new Exception( '错误操作', 0 );

			foreach ( $_POST as $k => $v )
			{
				$this->querycache->execute( 'config', 'update', array( $k, addslashes( $v ) ) );
			}
			// 更新配置缓存数据
			$this->_update_cache();

			$data['success'] = TRUE;
		}
		catch ( Exception $e )
		{
			$err_msg = $e->getMessage();
			$data['err'] = $this->from_validation->wrap_errors( $err_msg );
		}
		$this->load->view( 'form', $data );
	}

	/**
	 * 更新配置缓存数据
	 */
	private function _update_cache()
	{
		$config_file = APPPATH . '/config/app.php';
		$fhandle = @fopen( $config_file, 'wb+' );
		if ( empty( $fhandle ) ) return FALSE;

		$config = $this->querycache->get( 'config', 'all' );
		$config_data = '<?php ';
		foreach ( $config as $single )
		{
			if ( strpos( $single['key'], $this->_config_weibo_prefix ) !== false )
			{
				//weibo config
				$config_data .= "\$config['{$this->_config_weibo_prefix}']['{$single['key']}'] = '{$single['value']}';";
			}
			else if ( strpos( $single['key'], $this->_config_email_prefix ) !== false )
			{
				// email config
				$config_data .= "\$config['{$this->_config_email_prefix}']['{$single['key']}'] = '{$single['value']}';";
			}
			else
			{
				$config_data .= "\$config['{$single['key']}'] = '{$single['value']}';";
			}
		}
		$config_data .= ' ?>';

		fwrite( $fhandle, $config_data );
		fclose( $fhandle );

		return TRUE;
	}

	private function _form()
	{
		$data = array( );
		$all_config = $this->querycache->get( 'config', 'all' );

		// config_weibo
		$config_weibo = array( );
		foreach ( $all_config as $k => $single )
		{
			if ( strpos( $single['key'], $this->_config_weibo_prefix ) !== false ) $config_weibo[] = $all_config[$k];
		}
		$data['config_weibo'] = $config_weibo;

		// config_email
		$config_email = array( );
		foreach ( $all_config as $k => $single )
		{
			if ( strpos( $single['key'], $this->_config_email_prefix ) !== false ) $config_email[] = $all_config[$k];
		}
		$data['config_email'] = $config_email;

		// config_basic
		$config_basic = array( );
		foreach ( $all_config as $k => $single )
		{
			if ( strpos( $single['key'], $this->_config_email_prefix ) === false && strpos( $single['key'], $this->_config_weibo_prefix ) === false ) $config_basic[] = $all_config[$k];
		}
		$data['config_basic'] = $config_basic;

		$data['email_type'] = $this->_email_type;

		// 获取关于数据
		$data['about'] = $this->querycache->get( 'config', 'get_by_key', 'about' );
		$this->load->view( 'form', $data );
	}

}

// end of common