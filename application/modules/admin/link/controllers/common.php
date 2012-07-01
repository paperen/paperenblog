<?php

/**
 * 2012-4-25 00:44:28
 * 管理平台链接公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/link/controllers/
 */
class Admin_Link_Common_Module extends MY_Module
{

	/**
	 * 博客用戶列表
	 */
	public function index()
	{
		
		$data = array( );

		$data['total'] = $this->querycache->execute('link', 'total', array());

		$link_data = $this->querycache->get('link', 'get_all');
		$data['link_data'] = $link_data;

		$this->load->view( 'list', $data );
	}
	
	public function add()
	{
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
	 * 添加友链表单
	 */
	private function _form()
	{
		$data = array();
		$this->load->view('form', $data);
	}
	
	/**
	 * 添加链接信息操作
	 */
	private function _add()
	{
		$data = array();
		try
		{
			if( !$this->form_validation->check_token() ) throw new Exception( '非法操作', 0 );
			
			$link_data = $this->_form_data();
			if( !$this->_validation() ) throw new Exception( validation_errors(), -1 );
			
			$link_id = $this->querycache->execute('link', 'insert', array( $link_data ) );
			if ( empty( $link_id ) ) throw new Exception('系統出錯，請重試', -2);
			$data['success'] = TRUE;
		}
		catch( Exception $e )
		{
			$all_roles = $this->level->GetAllRole();
			$data['all_roles'] = $all_roles;
			$data['err'] = ( $err_code == -1 ) ? $e->getMessage() : $this->form_validation->wrap_error( $e->getMessage() );
		}
		$this->load->view('form', $data);
	}

	/**
	 * 收集添加友链表单的数据
	 * @return array
	 */
	private function _form_data()
	{
		return array(
			'id' => intval( $this->input->post('id') ),
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'url' => $this->input->post('url'),
			'order' => abs( intval( $this->input->post('order') ) ),
			'meta' => $this->input->post('meta'),
		);
	}

	/**
	 * 表单验证
	 * @return bool
	 */	
	private function _validation()
	{
		$this->form_validation->set_rules('name', '用戶名', 'required');
		$this->form_validation->set_rules('email', '郵箱', 'required|valid_email');
		$this->form_validation->set_rules('url', 'URL', 'required|prep_url');
		$this->form_validation->set_rules('order', '排序', 'is_natural');
		return $this->form_validation->run();
	}
}

// end of common
