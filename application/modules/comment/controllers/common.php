<?php

/**
 * 2012-3-18 17:34:28
 * 评论模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/comment/controllers/
 */
class Comment_Common_Module extends CI_Module
{

	/**
	 * 加载评论区域（评论列表+评论表单）
	 * @param int $post_id 文章ID
	 */
	public function index( $post_id )
	{
		$data = array( );
		try
		{
			$total = $this->querycache->get( 'comment', 'total_by_postid', $post_id );
			$data['total'] = $total;

			$data['post_id'] = $post_id;
		}
		catch ( Exception $e )
		{
			//@todo
		}
		$this->load->view( 'comment', $data );
	}

	/**
	 * 显示指定文章的评论列表
	 * @param int $post_id 文章ID
	 */
	public function all( $post_id )
	{
		$data = array( );
		try
		{
			$total = $this->querycache->get( 'comment', 'total_by_postid', $post_id );
			$data['total'] = $total;

			// 頂級評論數據
			$comment_data = $this->querycache->get( 'comment', 'get_by_postid', $post_id );
			// 回覆數據
			$reply_data = $this->querycache->get( 'comment', 'get_reply_by_postid', $post_id );
			$format_reply_data = array( );
			if ( $reply_data )
			{
				// 使用PID格式化
				foreach ( $reply_data as $single )
				{
					$format_reply_data[$single['pid']][] = $single;
				}
			}

			$data['comment_data'] = $comment_data;
			$data['reply_data'] = $format_reply_data;
		}
		catch ( Exception $e )
		{
			//@todo
		}
		$this->load->view( 'all', $data );
	}

	/**
	 * 显示评论表单
	 * @param int $post_id 文章ID
	 */
	public function form( $post_id )
	{
		$data = array(
			'postid' => $post_id,
		);
		$this->load->view( 'form', $data );
	}

	/**
	 * 收集評論表單數據
	 * @return array
	 */
	private function _form_data()
	{
		return array(
			'postid' => $this->input->post( 'postid' ),
			'pid' => $this->input->post( 'pid' ),
			'nickname' => $this->input->post( 'nickname' ),
			'email' => $this->input->post( 'email' ),
			'content' => $this->input->post( 'content' ),
			'blog' => $this->input->post( 'blog' ),
			'ip' => bindec( decbin( ip2long( $this->input->ip_address() ) ) ),
			'ispublic' => config_item( 'comment_ispublic' ),
		);
	}

	/**
	 * 評論表單驗證
	 * @return bool
	 */
	private function _form_validation()
	{
		$this->form_validation->set_rules( 'postid', '文章ID', 'required|is_natural_no_zero' );
		$this->form_validation->set_rules( 'nickname', '暱稱', 'required' );
		$this->form_validation->set_rules( 'email', '郵箱', 'required|valid_email' );
		$this->form_validation->set_rules( 'content', '評論內容', 'required|min_length[8]' );
		return $this->form_validation->run();
	}

	/**
	 * 添加評論處理
	 */
	public function add()
	{
		$data = array( );
		try
		{

			if ( !$this->input->post( 'comment-submit-btn' ) ) throw new Exception( '親，請不要進行非法操作~', 0 );

			// 令牌
			if ( !$this->form_validation->check_token() ) throw new Exception( '親，您的評論令牌不正確~', -1 );

			// 表單驗證失敗
			if ( !$this->_form_validation() ) throw new Exception( validation_errors(), -2 );

			// 表單數據
			$comment_data = $this->_form_data();
			// 檢測文章ID合法性
			$post_id = $comment_data['postid'];
			$post_data = $this->querycache->get( 'post', 'get_by_id', $post_id );
			if ( empty( $post_data ) ) throw new Exception( '親，請不要進行非法操作~', 0 );
			$data['post_data'] = $post_data;

			// 評論ID
			if ( $comment_data['pid'] && !$this->form_validation->is_natural_no_zero( $comment_data['pid'] ) ) throw new Exception( '您要回覆誰的評論', -1 );

			// 個人站點URL
			if ( $comment_data['blog'] ) $comment_data['blog'] = $this->form_validation->prep_url( $comment_data['blog'] );

			$comment_id = $this->querycache->execute( 'comment', 'insert', $comment_data );
			if ( empty( $comment_id ) ) throw new Exception( '親，博客出現一些問題，請重試看看', -3 );

			$data['success'] = TRUE;
		}
		catch ( Exception $e )
		{
			$error_code = $e->getCode();
			$error_message = $e->getMessage();
			switch ( $error_code )
			{
				case 0:
					//
				case -1:
					// 令牌
				case -3:
					// 系統錯誤
					$data['message'] = $this->form_validation->wrap_error( $error_message );
					break;
				case -2:
					// 表單驗證失敗
					$data['message'] = $error_message;
					break;
			}
			$data['success'] = FALSE;
		}
		echo json_encode(
			array(
				'success' => $data['success'],
				'data' => $this->load->view( 'add', $data, TRUE ),
			)
		);
	}

	/**
	 * 最近评论
	 * @param int $limit 显示条数
	 */
	public function recent( $limit = 5 )
	{
		$limit = intval( $limit );
		if ( empty( $limit ) ) $limit = 5;

		$data = array( );

		$comments_data = $this->querycache->get( 'comment', 'get_all', $limit );
		$data['comments_data'] = $comments_data;

		$this->load->view( 'recent', $data );
	}

}

// end of common