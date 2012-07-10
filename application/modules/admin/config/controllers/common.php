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
				$this->querycache->execute( 'config', 'update', array( $k, $v ) );
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
			$config_data .= "\$config['{$single['key']}'] = '{$single['value']}';";
		}
		$config_data .= ' ?>';

		fwrite( $fhandle, $config_data );
		fclose( $fhandle );

		return TRUE;
	}

	private function _form()
	{
		$data = array( );
		$data['config'] = $this->querycache->get( 'config', 'all' );

		// 获取关于数据
		$data['about'] = $this->querycache->get('config', 'get_by_key', 'about');
		$this->load->view( 'form', $data );
	}

}

// end of common