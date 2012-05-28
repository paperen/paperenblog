<?php

/**
 * 2012-4-25 00:44:28
 * 管理平台文章類別公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/post/controllers/
 */
class Admin_Category_Common_Module extends MY_Module
{

	/**
	 * 該用戶文章類別列表
	 * @param int $page 頁數
	 */
	public function my( $page = 1 )
	{
		$data = array( );

		// 當前人的文章類別總數
		$total = $this->querycache->get( 'category', 'total_by_authorid', $this->adminverify->id );
		$data['total'] = $total;

		// 數據
		$category_data = $this->querycache->get( 'category', 'get_all_by_author', $this->adminverify->id );
		$data['category_data'] = $category_data;

		$this->load->view( 'list', $data );
	}

	/**
	 * 添加文章類別
	 */
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


	private function _form_data()
	{
		return array(
			'category' => $this->input->post('category'),
			'pid' => $this->input->post('pid'),
			'userid' => $this->adminverify->id,
		);
	}

	/**
	 * 處理添加文章類別
	 */
	private function _add()
	{
		$data = array( );
		try
		{
			$category_data = $this->_form_data();
			$data['category_data'] = $category_data;

			if ( !$this->form_validation->check_token() ) throw new Exception( '提交錯誤', 0 );

			if ( !$this->_validation() ) throw new Exception( validation_errors(), -1 );

			if ( $this->exists( $category_data['category'] ) ) throw new Exception( '該類別已經存在，請不要重複添加', -2 );

			if ( $category_data['pid'] )
			{
				// 存在上級
				$pid = $category_data['pid'];
				$parent_category = $this->querycache->get( 'category', 'get_by_id', $pid );
				if ( empty( $parent_category ) ) throw new Exception( '錯誤操作', -3 );
				$category_data['pidlevel'] = $parent_category['pidlevel'] . $pid . '-';
			}
			else
			{
				// 沒有上級
				$category_data['pidlevel'] = '0-';
			}
			$category_id = $this->querycache->execute( 'category', 'insert', array( $category_data ) );
			if ( empty( $category_id ) ) throw new Exception( '抱歉，系統出錯', -4 );

			$data['success'] = TRUE;
		}
		catch ( Exception $e )
		{
			// 獲取該作者添加的類別
			$all_category_data = $this->querycache->get( 'category', 'get_all_by_author', $this->adminverify->id );
			$data['category_all'] = $all_category_data;

			$err_code = $e->getCode();
			$err_msg = $e->getMessage();
			if ( $err_code != -1 ) $err_msg = $this->form_validation->wrap_error( $err_msg );
			$data['err'] = $err_msg;
		}
		$this->load->view( 'form', $data );
	}

	/**
	 * 檢查指定文章類別是否存在
	 * @param string $category
	 * @return bool
	 */
	public function exists( $category )
	{
		return $this->querycache->get( 'category', 'exists', $category, $this->adminverify->id );
	}

	private function _validation()
	{
		$this->form_validation->set_rules('category', '類別名稱', 'required');
		$this->form_validation->set_rules('pid', '父級類別', 'is_natural');
		return $this->form_validation->run();
	}

	/**
	 * 添加類別表單
	 */
	private function _form()
	{
		$data = array( );

		// 獲取該作者添加的類別
		$category_data = $this->querycache->get( 'category', 'get_all_by_author', $this->adminverify->id );
		$data['category_all'] = $category_data;

		$this->load->view( 'form', $data );
	}

}

// end of common