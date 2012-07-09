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
class Admin_Setting_Common_Module extends MY_Module
{

	public function index()
	{
		if ( $this->input->post('submit_btn') )
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
		$data = array();
		try
		{
			if ( !$this->form_validation->check_token() ) throw new Exception( '错误操作', 0 );
			
			foreach( $_POST as $k => $v )
			{
				$this->querycache->execute('config', 'update', array( $k, $v )  );
			}
			$data['success'] = TRUE;
		}
		catch( Exception $e )
		{
			$err_msg = $e->getMessage();
			$data['err'] = $this->from_validation->wrap_errors( $err_msg );
		}
		$this->load->view('form', $data);
	}
	
	private function _form()
	{
		$data = array();
		$data['config'] = $this->querycache->get('config', 'all');
		$this->load->view('form', $data);
	}

}

// end of common