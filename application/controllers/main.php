<?php

/**
 * 2012-3-18 17:04:25
 * 单一控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/controllers
 */
class Main extends MY_Controller
{

	/**
	 * 首页
	 * @param int $offset 偏移
	 */
	public function index( $offset = 0 )
	{
		$data = array(
			'offset' => $offset,
		);
		$this->load->view( 'home', $data );
	}

	/**
	 * 404頁面
	 */
	public function not_found()
	{
		echo '404';
	}

	/**
	 * 显示指定ID或者URL标题的文章
	 * @param string $postid_or_urltitle ID或者URL标题
	 */
	public function post( $postid_or_urltitle )
	{
		$post_data = $this->load->module( 'post/common/get_by_postid_or_urltitle', $postid_or_urltitle, TRUE );

		// 如果文章數據為空 404
		if ( empty( $post_data ) ) show_404();

		$data = array(
			'postid_or_urltitle' => $postid_or_urltitle,
			'post_data' => $post_data,
		);
		$this->load->view( 'post', $data );
	}

}

// end of home