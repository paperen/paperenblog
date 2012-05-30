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
			'id' => $this->input->post( 'id' ),
			'category' => $this->input->post( 'category' ),
			'pid' => $this->input->post( 'pid' ),
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

			$category_data['pidlevel'] = $this->_gen_pidlevel( $category_data['pid'] );
			if ( $category_data['pidlevel'] === FALSE ) throw new Exception( '錯誤操作', -3 );

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

	private function _gen_pidlevel( $pid )
	{
		if ( $pid )
		{
			// 存在上級
			$parent_category = $this->querycache->get( 'category', 'get_by_id', $pid );
			if ( empty( $parent_category ) ) return FALSE;
			return $parent_category['pidlevel'] . $pid . '-';
		}
		else
		{
			// 沒有上級
			return '0-';
		}
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
		$this->form_validation->set_rules( 'category', '類別名稱', 'required' );
		$this->form_validation->set_rules( 'pid', '父級類別', 'is_natural' );
		return $this->form_validation->run();
	}

	/**
	 * 添加類別表單
	 */
	private function _form( $category_id = '' )
	{
		$data = array( );

		if ( $category_id )
		{
			$category_data = $this->querycache->get( 'category', 'get_by_id', $category_id );
			$data['category_data'] = $category_data;
			if ( $category_data )
			{
				$data['form_action'] = base_url( 'my_category/edit_submit' );
				$data['isedit'] = TRUE;
			}
		}

		// 獲取該作者添加的類別
		$category_all_data = $this->querycache->get( 'category', 'get_all_by_author', $this->adminverify->id );
		$data['category_all'] = $category_all_data;

		$this->load->view( 'form', $data );
	}

	/**
	 * 修改類別
	 * @param int $category_id
	 */
	public function edit( $category_id = '' )
	{
		if ( $this->input->post( 'submit_btn' ) )
		{
			$this->_edit();
		}
		else
		{
			$category_id = intval( $category_id );
			if ( empty( $category_id ) ) redirect( base_url( 'my_category' ) );
			$this->_form( $category_id );
		}
	}

	/**
	 * 修改操作
	 */
	private function _edit()
	{
		$data = array( );
		$data['isedit'] = TRUE;
		try
		{
			$category_post_data = $this->_form_data();
			$category_id = $category_post_data['id'];
			if ( !$this->_valid( $category_id ) ) throw new Exception( '錯誤操作', 0 );
			$data['category_data'] = $category_post_data;

			if ( !$this->_validation() ) throw new Exception( validation_errors(), -1 );

			$category_by_name = $this->querycache->get( 'category', 'get_by_name', $category_post_data['category'] );

			// 重複類別
			if ( !empty( $category_by_name ) && $category_by_name['id'] != $category_id ) throw new Exception( "{$category_by_name['category']}類別已存在 由{$category_by_name['name']} 添加的", -2 );

			$category_post_data['pidlevel'] = $this->_gen_pidlevel( $category_post_data['pid'] );
			if ( $category_post_data['pidlevel'] === FALSE ) throw new Exception( '錯誤操作', -3 );

			if ( !$this->querycache->execute( 'category', 'update', array( $category_post_data, $category_id ) ) ) throw new Exception( '系統錯誤', -4 );
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
	 * 驗證指定ID的類別是否合法
	 * @param int $category_id
	 * @return bool
	 */
	private function _valid( $category_id )
	{
		if ( empty( $category_id ) ) return FALSE;
		$category_data = $this->querycache->get( 'category', 'get_by_id', $category_id );
		if ( empty( $category_data ) || $category_data['userid'] != $this->adminverify->id ) return FALSE;

		return $category_data;
	}

	/**
	 * 刪除類別
	 * @param int $category_id
	 */
	public function delete( $category_id )
	{
		$data = array( );
		try
		{
			$category_data = $this->_valid( $category_id );
			if ( !$category_data ) throw new Exception( '錯誤操作', 0 );

			$pid = $category_data['pid'];

			// 修改屬於該類別的文章類別
			if ( $pid == 0 )
			{
				// 無老豆
				$default_category = $this->querycache->get( 'category', 'get_default', '' );
				$to_pid = $default_category['id'];
			}
			else
			{
				$to_pid = $pid;
			}
			$this->querycache->execute( 'post', 'update_category_to_category', array( $category_data['id'], $to_pid ) );

			// 將子類提升到父類級別
			// 獲取所有兒子
			$category_son_data = $this->querycache->get( 'category', 'get_by_pid', $category_id );


			// 直系
			$this->querycache->execute( 'category', 'update_pid_to_pid', array( $category_id, $pid ) );
			// 子系
		}
		catch ( Exception $e )
		{

		}
		$this->load->view( 'delete', $data );
	}

}

// end of common