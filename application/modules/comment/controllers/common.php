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
			$total = $this->querycache->get('comment', 'total_by_postid', $post_id);
			$data['total'] = $total;
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
		$this->load->view( 'all', $data );
	}

	/**
	 * 显示评论表单
	 * @param int $post_id 文章ID
	 */
	public function form( $post_id )
	{
		$data = array( );
		$this->load->view( 'form', $data );
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