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

}

// end of common