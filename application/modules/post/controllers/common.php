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
		$post_comments = $this->querycache->get( 'comment', 'total_by_postids', $post_ids );
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
		$this->_post_data = (!$multi ) ? array_shift( $result ) : $result;
	}

	/**
	 * 分栏格式化
	 * @param array $data 待格式化数据
	 * @param int $col_num 分栏数
	 */
	private function _format_by_col( $data, $col_num = 2 )
	{
		$result = array( );
		if ( empty( $data ) ) return NULL;
		$total = count( $data );
		for ( $i = 0; $i < $total; $i++ )
			$result[$i % $col_num][] = array_shift( $data );
		return $result;
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
		$this->_post_data = $this->_format_by_col( $this->_post_data, 2 );

		$data['posts_data_by_col'] = $this->_post_data;

		// 博文数据
		$this->load->view( 'fragment', $data );
	}

	/**
	 * 文章歸檔
	 */
	public function archive()
	{
		$data = array( );
		// 按時間歸檔
		$data['order_by'] = 'time';
		$result = array( );
		// 所有文章數據
		$post_data = $this->querycache->get( 'post', 'get_all' );
		foreach ( $post_data as $single )
		{
			$year = date( 'Y', $single['posttime'] );
			$month = date( 'm', $single['posttime'] );
			$result[$year][$month][] = $single;
		}
		$data['result'] = $result;

		// 標記是歸檔
		$data['is_archive'] = TRUE;

		$this->load->view( 'archive', $data );
	}

	/**
	 * 按照類別歸檔
	 */
	public function archive_category()
	{
		$data = array( );
		// 按類別歸檔
		$data['order_by'] = 'category';
		$result = array( );
		// 所有文章數據
		$post_data = $this->querycache->get( 'post', 'get_all' );
		foreach ( $post_data as $single )
		{
			$result[$single['category']][] = $single;
		}

		// 標記是歸檔
		$data['is_archive'] = TRUE;

		$data['result'] = $result;
		$this->load->view( 'archive_category', $data );
	}

	/**
	 * 按年份歸檔
	 * @param int $year[option]
	 * @param int $offset
	 */
	public function archive_by_year( $year = '', $offset = 0 )
	{
		if ( empty( $year ) ) $year = date( 'Y' );
		$data = array( );

		$start = mktime( 0, 0, 0, 1, 1, $year );
		$end = mktime( 0, 0, 0, 1, 1, $year + 1 );

		$this->_post_data = $this->querycache->get( 'post', 'get_posttime_between', $start, $end, config_item( 'per_page' ), $offset );
		// 準備文章
		$this->_prepare();

		// 分栏
		$this->_post_data = $this->_format_by_col( $this->_post_data, 2 );
		$data['posts_data_by_col'] = $this->_post_data;

		// 標記是歸檔
		$data['is_archive'] = TRUE;

		$this->load->view( 'fragment', $data );
	}

	/**
	 * 按照月份進行文章的歸檔
	 * @param int $year 年份
	 * @param int $month 月份
	 * @param int $offset 游標
	 */
	public function archive_by_month( $year = '', $month = '', $offset = 0 )
	{
		if ( $year == NULL || $month == NULL )
		{
			$year = date('Y');
			$month = date('m');
		}

		$start = mktime( 0, 0, 0, $month, 1, $year );
		$end = mktime( 0, 0, 0, $month+1, 1, $year );

		$this->_post_data = $this->querycache->get( 'post', 'get_posttime_between', $start, $end, config_item( 'per_page' ), $offset );
		// 分栏
		$this->_post_data = $this->_format_by_col( $this->_post_data, 2 );
		$data['posts_data_by_col'] = $this->_post_data;

		// 標記是歸檔
		$data['is_archive'] = TRUE;

		$this->load->view( 'fragment', $data );
	}

	/**
	 * 按照日進行文章的歸檔
	 * @param int $year 年份
	 * @param int $month 月份
	 * @param int $day 日
	 * @param int $offset 游標
	 */
	public function archive_by_day( $year = '', $month = '', $day = '', $offset = 0 )
	{
		if ( $year == NULL || $month == NULL || $day == NULL )
		{
			$year = date('Y');
			$month = date('m');
			$day = date('d');
		}

		$start = mktime( 0, 0, 0, $month, $day, $year );
		$end = mktime( 0, 0, 0, $month, $day+1, $year );

		$this->_post_data = $this->querycache->get( 'post', 'get_posttime_between', $start, $end, config_item( 'per_page' ) , $offset );
		// 分栏
		$this->_post_data = $this->_format_by_col( $this->_post_data, 2 );
		$data['posts_data_by_col'] = $this->_post_data;

		// 標記是歸檔
		$data['is_archive'] = TRUE;

		$this->load->view( 'fragment', $data );
	}

	/**
	 * 按照文章類別歸檔
	 * @param string $category 類別名
	 * @param int $offset 游標
	 */
	public function archive_by_category( $category, $offset = 0 )
	{
		// 根據類別名稱獲取類別數據
		$category_data = $this->querycache->get( 'category', 'get_by_name', $category );
		if ( empty( $category_data ) ) page_not_found();

		$this->_post_data = $this->querycache->get( 'post', 'get_by_category', $category_data['id'], config_item( 'per_page' ), $offset );
		// 分栏
		$this->_post_data = $this->_format_by_col( $this->_post_data, 2 );
		$data['posts_data_by_col'] = $this->_post_data;

		// 標記是歸檔
		$data['is_archive'] = TRUE;

		$this->load->view( 'fragment', $data );
	}

	/**
	 * 最近文章
	 * @param int $limit 获取条数
	 */
	public function latest( $limit = 5 )
	{
		$limit = intval( $limit );
		if ( empty( $limit ) ) $limit = 5;

		$data = array( );

		$this->_post_data = $this->querycache->get( 'post', 'get_all', 5 );
		$data['posts_data'] = $this->_post_data;

		$this->load->view( 'latest', $data );
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
	private function _get_by_postid_or_urltitle( $postid_or_urltitle )
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
		$post_data = $this->_get_by_postid_or_urltitle( $postid_or_urltitle );

		// 没有找到
		if ( empty( $post_data ) ) show_404();
		$this->_post_data = $post_data;

		// 加工
		$this->_prepare( FALSE );

		$post_data = $this->_post_data;

		// 註冊邊欄文章圖片挂入點
		$this->hook->register( 'post_images', 'module_post/common/images', $post_data['id'] );

		$data = array(
			'post' => $post_data,
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

	/**
	 * 獲取指定ID文章的附帶圖片列表
	 * @param int $post_id 文章ID
	 */
	public function images( $post_id )
	{
		$data = array( );
		$post_id = intval( $post_id );

		// 根據文章ID獲取文章圖片數據
		$post_images = $this->querycache->get( 'post', 'get_images', $post_id );

		// 總數
		$data['total'] = count( $post_images );
		$data['post_images'] = $this->_format_by_col( $post_images, 3 );

		$this->load->view( 'images', $data );
	}

}

// end of common