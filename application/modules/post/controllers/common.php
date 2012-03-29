<?php

/**
 * 2012-3-18 17:34:28
 * 文章模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/post/controllers/
 */
class Post_Common_Module extends CI_Module
{

	/**
	 * 文章分栏
	 * @param array $post_data 文章数据
	 * @param int $col_num 分栏数
	 * @return array
	 */
	private function _posts_by_col( $posts_data, $col_num = 2 )
	{
		$result = array( );
		if ( empty( $posts_data ) ) return $result;
		for ( $i = 0; $i < $col_num; $i++ )
			$result[$i][] = array_shift( $posts_data );

		return $result;
	}

	/**
	 * 文章列表
	 * @param int $offset 游标
	 */
	public function fragment( $offset = 0 )
	{
		$data = array( );

		$this->load->model( 'post_model' );
		$posts_data = $this->post_model->get_all( config_item( 'per_page' ), $offset );

		// 分栏显示
		$posts_data_by_col = $this->_posts_by_col( $posts_data );
		$data['posts_data_by_col'] = $posts_data_by_col;

		// 博文数据
		$this->load->view( 'fragment', $data );
	}

	/**
	 * 根据文章ID或URL标题获取显示详细文章
	 * @param string $postid_or_urltitle 文章ID或URL标题
	 */
	public function single( $postid_or_urltitle )
	{
		$data = array();
		$this->load->view( 'single', $data );
	}

	/**
	 * 归档
	 */
	public function archive()
	{
		$data = array( );

		$this->load->view( 'archive', $data );
	}

}

// end of common