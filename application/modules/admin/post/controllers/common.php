<?php

/**
 * 2012-4-25 00:44:28
 * 管理平台文章公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/post/controllers/
 */
class Admin_Post_Common_Module extends MY_Module
{

	/**
	 * 發表新文章
	 */
	public function add()
	{
		// 檢查是否具有編輯權限
		if ( $this->adminverify->deny_permission( adminverify::AUTHOR ) ) deny();
		if ( $this->input->post('post_btn') && $this->form_validation->check_token() )
		{
			// 提交
			$this->_submit();
		}
		else
		{
			$this->load->view( 'form', $data );
		}
	}

	/**
	 * 處理文章提交數據
	 */
	private function _submit()
	{
		$post_data = $this->_form_data();
		$post_id = $post_data['postid'];
		
		try
		{
			// 表單驗證
			if ( !$this->_validation() ) throw new Exception( '非法操作', 0 );
		
			// 檢查文章類別是否合法
			if ( !$this->_valid_category( $post_data['categoryid'] ) ) throw new Exception( '非法操作', -1 );
			
			if ( empty( $post_id ) )
			{
				// 
			}
			
		}
		catch ( Exception $e )
		{
			
		}
		
		
	}
	
	/**
	 * 收集表單數據
	 * @return array
	 */
	private function _form_data()
	{
		return array(
			'postid' => intval( $this->input->post( 'postid' ) ),
			'title' => $this->input->post( 'title' ),
			'urltitle' => $this->input->post( 'urltitle' ),
			'categoryid' => intval( $this->input->post( 'categoryid' ) ),
			'content' => $this->input->post( 'content' ),
			'tag' => $this->input->post( 'tag' ),
			'thumbimg' => intval( $this->input->post( 'thumbimg' ) ),
			'authorid' => $this->adminverify->id,
			'ispublic' => 1,
			'posttime' => time(),
			'savetime' => time(),
			'isdraft' => 0,
		);
	}

	/**
	 * 保存文章數據
	 */
	public function save()
	{
		try
		{
			// 檢查是否具有編輯權限
			if ( $this->adminverify->deny_permission( adminverify::AUTHOR ) ) throw new Exception( '沒有權限', 0 );
			if ( !$this->input->is_ajax_request() ) throw new Exception( '非法操作', -1 );
			$post_data = $this->_form_data();

			// 檢查文章類別是否合法
			if ( !$this->_valid_category( $post_data['categoryid'] ) ) throw new Exception( '非法操作', -2 );
			// 未發佈
			$post_data['ispublic'] = 0;
			// 發佈時間為0
			$post_data['posttime'] = 0;
			// 是草稿
			$post_data['isdraft'] = 1;


			if ( empty( $post_data['postid'] ) )
			{
				// 插入
				$post_id = $this->querycache->execute( 'post', 'insert', array( $post_data ) );
				if ( empty( $post_id ) ) throw new Exception( '系統錯誤', -3 );
			}
			else
			{
				// 更新
				$post_id = $post_data['postid'];

				// 是否合法
				if ( !$this->_valid_post( $post_id ) ) throw new Exception( '錯誤操作', -4 );

				if ( !$this->querycache->execute( 'post', 'update', array( $post_data, $post_data['postid'] ) ) ) throw new Exception( '系統錯誤', -5 );
			}

			// 添加標籤
			$this->_add_tag( $post_data['tag'], $post_id );

			$result = array(
				'err' => 0,
				'msg' => $post_id,
			);
		}
		catch ( Exception $e )
		{
			$result = array(
				'err' => 1,
				'msg' => $e->getMessage(),
			);
		}
		echo json_encode( $result );
	}

	/**
	 * 添加標籤
	 * @param string $tags
	 */
	private function _add_tag( $tags, $post_id )
	{
		if ( empty( $tags ) ) return;

		$tags = explode( ',', $tags );
		$tags_data = $this->querycache->get( 'tag', 'get_by_names', $tags );

		// 已存在的tag
		$tags_exists = array( );
		foreach ( $tags_data as $single )
			$tags_exists[] = $single['tag'];

		if ( count( $tags_exists ) != count( $tags ) )
		{
			// 如果數量不等
			$tags_toadd = array_diff( $tags, $tags_exists );
			foreach ( $tags_toadd as $single )
			{
				$tag_new_id = $this->querycache->execute( 'tag', 'insert', array( $single ) );
				$tags_data[] = array(
					'id' => $tag_new_id,
					'tag' => $single,
				);
			}
		}

		// 關聯文章
		// 刪除之前的標籤
		$this->querycache->execute( 'tag', 'delete_by_postid', array( $post_id ) );
		$tags_ids = array();
		foreach( $tags_data as $single ) $tags_ids[] = $single['id'];
		$this->querycache->execute('tag', 'insert_post_tag', array( $tags_ids , $post_id ) );
	}

	/**
	 * 檢查某類別是否合法
	 * @param int $categoryid 文章類別ID
	 * @return bool
	 */
	private function _valid_category( $categoryid )
	{
		$category_data = $this->querycache->get( 'category', 'get_by_id', $categoryid );
		return $category_data;
	}

	/**
	 * 檢測某文章是否合法
	 * @param int $post_id
	 */
	private function _valid_post( $post_id )
	{
		$post_data = $this->querycache->get( 'post', 'get_by_id', $post_id );
		if ( empty( $post_data ) ) return FALSE;

		// 不是該文章的作者
		if ( $post_data['authorid'] != $this->adminverify->id ) return FALSE;
		return $post_data;
	}

}

// end of common