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
	 * 文章数据
	 * @var array
	 */
	private $_post_data = array( );

	/**
	 * 对文章数据进行加工处理
	 * @param bool $multi 是否文章数据是多个
	 */
	private function _prepare( $multi = TRUE )
	{
		if ( empty( $this->_post_data ) ) return NULL;

		// 单篇文章
		if ( !$multi ) $this->_post_data = array( $this->_post_data );

		// 文章ID
		$post_ids = array( );
		// 多于一篇文章
		foreach ( $this->_post_data as $single )
			$post_ids[] = $single['id'];

		// 查询文章的标签
		$post_tags = $this->querycache->get( 'tag', 'get_by_post_ids', $post_ids );
		$post_tags_format = array( );
		foreach ( $post_tags as $single )
			$post_tags_format[$single['postid']][] = $single['tag'];

		// 获取评论数
		$post_comments = $this->querycache->get( 'comment', 'total_by_post_ids', $post_ids );
		$post_comments_format = array( );
		foreach ( $post_comments as $single )
			$post_comments_format[$single['postid']] = $single['num'];

		// 拼合文章数据
		$result = array( );
		foreach ( $this->_post_data as $single )
		{
			// 拼入标签
			$single['tags'] = isset( $post_tags_format[$single['id']] ) ? $post_tags_format[$single['id']] : array( );
			// 拼入评论数
			$single['commentnum'] = isset( $post_comments_format[$single['id']] ) ? $post_comments_format[$single['id']] : 0;

			$result[] = $single;
		}
		$this->_post_data = ( !$multi ) ? array_shift( $result ) : $result;
	}

	/**
	 * 分栏格式化
	 * @param array $post_data 文章数据
	 * @param int $col_num 分栏数
	 */
	private function _format_by_col( $col_num = 2 )
	{
		$result = array( );
		if ( empty( $this->_post_data ) ) return NULL;
		$total = count( $this->_post_data );
		for ( $i = 0; $i < $total; $i++ )
			$result[$i%$col_num][] = array_shift( $this->_post_data );
		$this->_post_data = $result;
	}

	/**
	 * 文章列表
	 * @param int $offset 游标
	 */
	public function fragment( $offset = 0 )
	{
		$data = array( );

		$this->_post_data = $this->querycache->get( 'post', 'get_all', config_item( 'per_page' ), $offset );
		// 加工
		$this->_prepare();
		// 分栏
		$this->_format_by_col();

		$data['posts_data_by_col'] = $this->_post_data;

		// 博文数据
		$this->load->view( 'fragment', $data );
	}

	/**
	 * 归档
	 */
	public function archive()
	{
		$data = array( );

		$this->load->view( 'archive', $data );
	}

	/**
	 * 最近文章
	 * @param int $limit 获取条数
	 */
	public function latest_posts( $limit = 5 )
	{
		$limit = intval( $limit );
		if ( empty( $limit ) ) $limit = 5;

		$data = array( );

		$this->_post_data = $this->querycache->get( 'post', 'get_all', 5 );
		$data['posts_data'] = $this->_post_data;

		$this->load->view( 'latest_posts', $data );
	}

	/**
	 * 热门文章
	 * @param int $limit 显示篇数
	 */
	public function hot( $limit = 5 )
	{
		$limit = intval( $limit );
		if ( empty( $limit ) ) $limit = 5;

		$data = array( );

		$this->_post_data = $this->querycache->get( 'post', 'get_hot', $limit );
		$data['posts_data'] = $this->_post_data;

		$this->load->view( 'hot', $data );
	}

	/**
	 * 根据文章ID或URL标题获取文章数据
	 * @param string $postid_or_urltitle 文章ID或URL标题
	 * @return array
	 */
	public function get_by_postid_or_urltitle( $postid_or_urltitle )
	{
		// 如果是數字
		if ( is_numeric( $postid_or_urltitle ) )
		{
			$post_id = intval( $postid_or_urltitle );
			$post_data = $this->querycache->get( 'post', 'get_by_id', $post_id );
		}
		else
		{
			$post_data = $this->querycache->get( 'post', 'get_by_urltitle', $postid_or_urltitle );
		}
		return $post_data;
	}

	/**
	 * 加载指定ID或URL标题的文章
	 * @param string $postid_or_urltitle 指定ID或URL标题
	 */
	public function single( $postid_or_urltitle )
	{
		//
		$post_data = $this->get_by_postid_or_urltitle( $postid_or_urltitle );

		// 没有找到
		if ( empty( $post_data ) ) show_404();
		$this->_post_data = $post_data;

		// 加工
		$this->_prepare( FALSE );

		$data = array(
			'post' => $this->_post_data,
		);

		$this->load->view( 'single', $data );
	}

	/**
	 * 輸出文章屬性數據欄
	 * @param array $post_data 文章數據
	 * @param mixed $extra_class 額外CSS class
	 * @param bool $display 是否顯示附加操作欄目（頂、踩）
	 */
	public function meta( $post_data, $extra_class = '', $display_op = FALSE )
	{
		if ( empty( $post_data ) ) return FALSE;

		// 對額外的CSS class處理
		$class = $extra_class;
		if ( is_array( $extra_class ) )
		{
			foreach ( $extra_class as $single )
				$class .= ' ' . $single;
		}
		$class = ' ' . trim( $class );

		$data = array(
			'post' => $post_data,
			'display_op' => $display_op,
			'extra_class' => $class,
		);
		$this->load->view( 'meta', $data );
	}

}

// end of common