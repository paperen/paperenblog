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
		if ( $this->input->post( 'post_btn' ) && $this->form_validation->check_token() )
		{
			// 發佈文章
			$this->_publish();
		}
		else
		{
			// 顯示表單
			$this->_form();
		}
	}

	/**
	 * 顯示表單
	 * @param int $post_id
	 */
	private function _form( $post_id = '' )
	{
		$data = array( );

		// 獲取分類數據
		$data['category_data'] = $this->_category_all();

		// 微博令牌
		$data['token'] = $this->adminverify->token;
		
		try
		{
			if ( $post_id )
			{
				// 獲取文章數據
				$post_data = $this->_valid_post( $post_id );
				if ( empty( $post_data ) || $post_data['istrash'] ) throw new Exception( '非法操作', -1 );
				$data['post_data'] = $post_data;
			}
		}
		catch ( Exception $e )
		{
			$data['illegal_msg'] = $e->getMessage();
		}

		$this->load->view( 'form', $data );
	}

	/**
	 * 獲取所有文章分類（用於下拉框）
	 * @return array
	 */
	private function _category_all()
	{
		$category_data = $this->querycache->get( 'category', 'get_all' );
		$category_format_data = array( );
		foreach ( $category_data as $single )
			$category_format_data[$single['id']] = $single['category'];
		return $category_format_data;
	}

	/**
	 * 處理文章提交數據
	 */
	private function _publish()
	{
		$data = array( );

		$post_data = $this->_form_data();
		$data['post_data'] = $post_data;
		try
		{
			// 表單驗證
			if ( !$this->_validation() ) throw new Exception( validation_errors(), 0 );

			// 檢查文章類別是否合法
			if ( !$this->_valid_category( $post_data['categoryid'] ) ) throw new Exception( '非法操作', -1 );

			$post_id = $post_data['id'];
			if ( empty( $post_id ) )
			{
				// 插入
				$post_id = $this->querycache->execute( 'post', 'insert', array( $post_data ) );
				$is_insert = TRUE;
				if ( empty( $post_id ) ) throw new Exception( '系統錯誤', -3 );
			}
			else
			{
				// 更新
				// 是否合法
				$post_raw_data = $this->_valid_post( $post_id );
				if ( empty( $post_raw_data ) || $post_raw_data['istrash'] ) throw new Exception( '錯誤操作', -4 );
				if ( !$this->querycache->execute( 'post', 'update', array( $post_data, $post_data['id'] ) ) ) throw new Exception( '系統錯誤', -5 );
			}

			// 添加標籤
			$this->_add_tag( $post_id, $post_data['tag'] );

			// 設置特色圖像
			$this->_set_thumbimg( $post_id, $post_data['thumbimg'], $post_data['content'] );

			// 關聯文章與圖片
			$this->_set_postimage( $post_id, $post_data['content'] );

			// 標記為不是草稿
			$this->querycache->execute( 'post', 'update_undraft', array( $post_id ) );

			// 同步微博
			if ( $post_data['syncweibo'] && $this->adminverify->token )
			{
				$post_attachment = $this->querycache->get('attachment', 'get_by_post_id', $post_id);
				$post_images = array();
				foreach( $post_attachment as $k => $single )
				{
					if ( $single['isimage'] ) $post_images[] = $single;
				}
				$image_data = empty( $post_images ) ? NULL : array_shift( $post_images );
				$this->load->module('third_party/common/weibo_api_update',
					array(
						gbk_substr( $post_data['content'], 150 ),
						( $image_data ) ? file_url( $image_data['id'] ) : NULL,
						NULL,
						NULL,
					)
				);
			}
			$data['success'] = array(
				'title' => "{$post_data['title']} 已成功發佈",
				'post_url' => post_permalink( $post_data['urltitle'] ),
			);
		}
		catch ( Exception $e )
		{
			$err_code = $e->getCode();
			$err_message = $e->getMessage();
			if ( $err_code != 0 ) $err_message = $this->form_validation->wrap_error( $err_message );
			$data['error'] = $err_message;
		}

		// 獲取所有文章分類
		$data['token'] = $this->adminverify->token;
		$data['category_data'] = $this->_category_all();
		$this->load->view( 'form', $data );
	}

	/**
	 * 關聯文章與圖片
	 * @param int $post_id
	 * @param string $post_content
	 */
	private function _set_postimage( $post_id, $post_content )
	{
		if ( empty( $post_id ) || empty( $post_content ) ) return;

		// 解除之前的關聯
		$this->querycache->execute( 'post', 'delete_attachment', array( $post_id ) );

		$file_ids = get_file_from_string( $post_content, -1 );
		if ( empty( $file_ids ) ) return;

		$this->querycache->execute( 'post', 'insert_attachment', array( $file_ids, $post_id ) );
	}

	/**
	 * 設置指定
	 * @param type $thumbimg_id
	 * @param type $post_id
	 */
	private function _set_thumbimg( $post_id, $thumbimg_id, $post_content )
	{
		if ( empty( $thumbimg_id ) && empty( $post_content ) ) return;
		if ( $thumbimg_id )
		{
			// nothing
		}
		else if ( $post_content )
		{
			$thumbimg_id = get_thumbimg_from_string( $post_content );
			if ( empty( $thumbimg_id ) ) return;
		}
		return $this->querycache->execute( 'attachment', 'update_isthumbnail', array( $thumbimg_id ) );
	}

	/**
	 * 表單驗證
	 * @return bool
	 */
	private function _validation()
	{
		$this->form_validation->set_rules( 'title', '文章標題', 'required|max_length[50]' );
		$this->form_validation->set_rules( 'urltitle', '固定鏈接', 'required|max_length[50]' );
		$this->form_validation->set_rules( 'categoryid', '文章所屬類別', 'required|is_natural_no_zero' );
		$this->form_validation->set_rules( 'content', '內容', 'required' );
		return $this->form_validation->run();
	}

	/**
	 * 收集表單數據
	 * @return array
	 */
	private function _form_data()
	{
		return array(
			'id' => intval( $this->input->post( 'postid' ) ),
			'title' => $this->input->post( 'title' ),
			'urltitle' => $this->input->post( 'urltitle' ),
			'categoryid' => intval( $this->input->post( 'categoryid' ) ),
			'content' => $this->input->post( 'content' ),
			'tag' => $this->input->post( 'tag' ),
			'thumbimg' => intval( $this->input->post( 'thumbimg' ) ),
			'authorid' => $this->adminverify->id,
			'ispublic' => $this->input->post( 'ispublic' ),
			'posttime' => time(),
			'savetime' => time(),
			'isdraft' => 0,
			'syncweibo' => $this->input->post( 'syncweibo' ),
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

			if ( empty( $post_data['id'] ) )
			{
				// 未發佈
				$post_data['ispublic'] = 0;
				// 發佈時間為0
				$post_data['posttime'] = 0;
				// 是草稿
				$post_data['isdraft'] = 1;

				// 插入
				$post_id = $this->querycache->execute( 'post', 'insert', array( $post_data ) );
				if ( empty( $post_id ) ) throw new Exception( '系統錯誤', -3 );
			}
			else
			{
				// 更新
				$post_id = $post_data['id'];

				// 是否合法
				$post_raw_data = $this->_valid_post( $post_id );
				if ( empty( $post_raw_data ) ) throw new Exception( '錯誤操作', -4 );

				// 如果未发布
				if ( $post_raw_data['ispublic'] == 0 )
				{
					// 發佈時間為0
					$post_data['posttime'] = 0;
					// 是草稿
					$post_data['isdraft'] = 1;
				}

				if ( !$this->querycache->execute( 'post', 'update', array( $post_data, $post_id ) ) ) throw new Exception( '系統錯誤', -5 );
			}

			// 添加標籤
			$this->_add_tag( $post_id, $post_data['tag'] );

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
	private function _add_tag( $post_id, $tags )
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
		$tags_ids = array( );
		foreach ( $tags_data as $single )
			$tags_ids[] = $single['id'];
		$this->querycache->execute( 'tag', 'insert_post_tag', array( $tags_ids, $post_id ) );
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
	 * @return array
	 */
	private function _valid_post( $post_id )
	{
		$post_data = $this->querycache->get( 'post', 'get_by_id', $post_id );
		if ( empty( $post_data ) ) return FALSE;

		// 不是該文章的作者
		if ( $post_data['authorid'] != $this->adminverify->id ) return FALSE;
		// 獲取標籤
		$post_tags = $this->querycache->get( 'tag', 'get_by_post_ids', $post_id );
		$tags = array( );
		foreach ( $post_tags as $single )
			$tags[] = $single['tag'];
		$post_data['tag'] = implode( ',', $tags );

		return $post_data;
	}

	/**
	 * 文章列表
	 * @param int $page 頁數
	 */
	public function my( $page = 1 )
	{
		$data = array( );

		// 每頁顯示條數
		$per_page = config_item( 'per_page' );

		// 當前人的文章總數
		$total = $this->querycache->get( 'post', 'total_by_authorid', $this->adminverify->id );
		$data['total'] = $total;

		// 分頁
		$this->load->library( 'pagination' );
		$pagination_config = array(
			'base_url' => base_url( 'my_post' ),
			'total_rows' => $total,
			'per_page' => $per_page,
			'uri_segment' => 2,
		);
		$this->pagination->initialize( $pagination_config );
		$pagination = $this->pagination->create_links();
		$data['pagination'] = $pagination;

		// 數據
		$post_data = $this->querycache->get( 'post', 'get_by_authorid', $this->adminverify->id, $per_page, ( $this->pagination->get_cur_page() - 1 ) * $per_page );
		$data['post_data'] = $post_data;

		$this->load->view( 'list', $data );
	}

	/**
	 * 修改某篇文章
	 * @param int $post_id
	 */
	public function edit( $post_id )
	{
		// 檢查是否具有編輯權限
		if ( $this->adminverify->deny_permission( adminverify::AUTHOR ) ) deny();
		if ( $this->input->post( 'post_btn' ) && $this->form_validation->check_token() )
		{
			// 發佈文章
			$this->_publish();
		}
		else
		{
			// 顯示表單
			$this->_form( $post_id );
		}
	}

	/**
	 * 回收站列表
	 */
	public function trash( $page = 1 )
	{
		$data = array( );

		// 每頁顯示條數
		$per_page = config_item( 'per_page' );

		// 當前人的文章總數
		$total = $this->querycache->get( 'post', 'total_trash_by_authorid', $this->adminverify->id );
		$data['total'] = $total;

		// 分頁
		$this->load->library( 'pagination' );
		$pagination_config = array(
			'base_url' => base_url( 'trash' ),
			'total_rows' => $total,
			'per_page' => $per_page,
			'uri_segment' => 2,
		);
		$this->pagination->initialize( $pagination_config );
		$pagination = $this->pagination->create_links();
		$data['pagination'] = $pagination;

		// 數據
		$post_data = $this->querycache->get( 'post', 'get_trash_by_authorid', $this->adminverify->id, $per_page, ( $this->pagination->get_cur_page() - 1 ) * $per_page );
		$data['post_data'] = $post_data;

		$this->load->view( 'trash_list', $data );
	}

	/**
	 * 將指定文章放入回收站
	 * @param int $post_id 文章ID
	 */
	public function trash_add( $post_id )
	{
		$data = array( );
		try
		{
			if ( empty( $post_id ) ) throw new Exception( '錯誤操作', 0 );

			$post_data = $this->_valid_post( $post_id );
			if ( empty( $post_data ) ) throw new Exception( '錯誤操作', -1 );

			$this->querycache->execute( 'post', 'update_trash', array( $post_id ) );
		}
		catch ( Exception $e )
		{
			$error_msg = $e->getMessage();
		}
		redirect( base_url( 'trash' ) );
	}

	/**
	 * 將指定文章從回收站撤銷
	 * @param int $post_id 文章ID
	 */
	public function trash_revoke( $post_id )
	{
		$data = array( );
		try
		{
			if ( empty( $post_id ) ) throw new Exception( '錯誤操作', 0 );

			$post_data = $this->_valid_post( $post_id );
			if ( empty( $post_data ) || !$post_data['istrash'] ) throw new Exception( '錯誤操作', -1 );

			$this->querycache->execute( 'post', 'update_trash_revoke', array( $post_id ) );
		}
		catch ( Exception $e )
		{
			$error_msg = $e->getMessage();
		}
		redirect( base_url( 'trash' ) );
	}

	/**
	 * 刪除指定文章數據
	 * @param int $post_id
	 */
	public function delete( $post_id )
	{
		$data = array( );
		try
		{
			if ( empty( $post_id ) ) throw new Exception( '錯誤操作', 0 );

			//
			$post_data = $this->_valid_post( $post_id );
			if ( !$post_data['istrash'] ) throw new Exception( '錯誤操作', -1 );
			// 刪除相關的附件
			$post_attachment = $this->querycache->get( 'attachment', 'get_by_post_id', $post_id );
			$attachment_ids = array();
			foreach ( $post_attachment as $single )
			{
				$attachment_path = config_item( 'upload_path' ) . $single['path'];
				@unlink( $attachment_path );
				$attachment_ids[] = $single['id'];
			}
			$this->querycache->execute( 'attachment', 'delete_by_ids', array( $attachment_ids ) );
			$this->querycache->execute( 'post', 'delete_attachment', array( $post_id ) );

			// 刪除相關的評論
			$this->querycache->execute( 'comment', 'delete_by_post_id', array( $post_id ) );

			// 刪除文章
			$this->querycache->execute( 'post', 'delete_by_id', array( $post_id ) );

			$data['error'] = FALSE;
		}
		catch ( Exception $e )
		{
			$data['error'] = $e->getMessage();
		}
		$this->load->view( 'delete', $data );
	}

}

// end of common