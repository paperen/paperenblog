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
	 * 列顯示
	 */
	const DISPLAY_COLUMN = 'column';
	/**
	 * 行顯示
	 */
	const DISPLAY_ROW = 'row';

	/**
	 * 顯示方式的cookie鍵值
	 * @var string
	 */
	private $_display_cookie_key = 'display_type';

	/**
	 * cookie過期時間(7天)
	 * @var int
	 */
	private $_display_cookie_expired = 604800;

	/**
	 * 顯示方式
	 * @var string
	 */
	private $_display_type;
	private $_session_ding_key = 'ding';
	private $_session_cai_key = 'cai';
	private $_session_click_key = 'click';

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

		// 獲取文章代表圖片
		$post_thumbnails = $this->querycache->get( 'attachment', 'get_thumbnail_by_post_ids', $post_ids );
		$post_thumbnails_format = array( );
		foreach ( $post_thumbnails as $single )
			$post_thumbnails_format[$single['postid']] = $single['id'];

		// 拼合文章数据
		$result = array( );
		foreach ( $this->_post_data as $single )
		{
			// 拼入标签
			$single['tags'] = isset( $post_tags_format[$single['id']] ) ? $post_tags_format[$single['id']] : array( );
			// 拼入评论数
			$single['commentnum'] = isset( $post_comments_format[$single['id']] ) ? $post_comments_format[$single['id']] : 0;

			// 拼入代表圖片
			$single['thumbnail'] = isset( $post_thumbnails_format[$single['id']] ) ? $post_thumbnails_format[$single['id']] : '';

			$result[] = $single;
		}
		$this->_post_data = (!$multi ) ? array_shift( $result ) : $result;

		// 多篇文章 && 列顯示
		if ( $multi && $this->_display_get() == self::DISPLAY_COLUMN )
		{
			$this->_post_data = $this->_format_by_col( $this->_post_data, 2 );
		}
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
	 * @param int $page 頁數
	 */
	public function fragment( $page = 1 )
	{
		$data = array( );

		// 總數
		$total = $this->querycache->get( 'post', 'total' );
		// 每頁顯示條數
		$per_page = config_item( 'per_page' );
		$pagination_config = array(
			'base_url' => base_url( 'page' ),
			'total_rows' => $total,
			'per_page' => $per_page,
			'uri_segment' => 2,
		);
		$this->pagination->initialize( $pagination_config );
		$data['pagination'] = $this->pagination->create_pages();
		$this->_post_data = $this->querycache->get( 'post', 'get_all', $per_page, ( $this->pagination->get_cur_page() - 1 ) * $per_page );
		// 加工
		$this->_prepare();

		$data['posts_data'] = $this->_post_data;

		// 顯示方式
		$data['display'] = $this->_display_get();

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
	 * @param int $page
	 */
	public function archive_by_year( $year = '', $page = 1 )
	{
		if ( empty( $year ) ) $year = date( 'Y' );
		$data = array( );

		$start = mktime( 0, 0, 0, 1, 1, $year );
		$end = mktime( 0, 0, 0, 1, 1, $year + 1 );

		$total = $this->querycache->get( 'post', 'total_posttime_between', $start, $end );

		// 分頁
		$per_page = config_item( 'per_page' );
		$pagination_config = array(
			'base_url' => archive_url( $year ) . '/page',
			'total_rows' => $total,
			'per_page' => $per_page,
			'uri_segment' => 4,
		);
		$this->pagination->initialize( $pagination_config );
		$data['pagination'] = $this->pagination->create_pages();

		$this->_post_data = $this->querycache->get( 'post', 'get_posttime_between', $start, $end, config_item( 'per_page' ), ( $this->pagination->get_cur_page() - 1 ) * $per_page );
		// 準備文章
		$this->_prepare();
		$data['posts_data'] = $this->_post_data;

		// 標記是歸檔
		$data['is_archive'] = TRUE;

		// 顯示方式
		$data['display'] = $this->_display_get();

		//
		$data['by_time'] = "{$year}年";

		$this->load->view( 'fragment', $data );
	}

	/**
	 * 按照月份進行文章的歸檔
	 * @param int $year 年份
	 * @param int $month 月份
	 * @param int $page 頁
	 */
	public function archive_by_month( $year = '', $month = '', $page = 1 )
	{
		if ( $year == NULL || $month == NULL )
		{
			$year = date( 'Y' );
			$month = date( 'm' );
		}

		$start = mktime( 0, 0, 0, $month, 1, $year );
		$end = mktime( 0, 0, 0, $month + 1, 1, $year );

		$total = $this->querycache->get( 'post', 'total_posttime_between', $start, $end );

		// 分頁
		$per_page = config_item( 'per_page' );
		$pagination_config = array(
			'base_url' => archive_url( $year ) . '/page',
			'total_rows' => $total,
			'per_page' => $per_page,
			'uri_segment' => 5,
		);
		$this->pagination->initialize( $pagination_config );
		$data['pagination'] = $this->pagination->create_pages();

		$this->_post_data = $this->querycache->get( 'post', 'get_posttime_between', $start, $end, $per_page, ( $this->pagination->get_cur_page() - 1 ) * $per_page );
		$this->_prepare();
		$data['posts_data'] = $this->_post_data;

		// 標記是歸檔
		$data['is_archive'] = TRUE;

		// 顯示方式
		$data['display'] = $this->_display_get();

		//
		$data['by_time'] = "{$year}年{$month}月";

		$this->load->view( 'fragment', $data );
	}

	/**
	 * 按照日進行文章的歸檔
	 * @param int $year 年份
	 * @param int $month 月份
	 * @param int $day 日
	 * @param int $page 頁
	 */
	public function archive_by_day( $year = '', $month = '', $day = '', $page = 1 )
	{
		if ( $year == NULL || $month == NULL || $day == NULL )
		{
			$year = date( 'Y' );
			$month = date( 'm' );
			$day = date( 'd' );
		}

		$start = mktime( 0, 0, 0, $month, $day, $year );
		$end = mktime( 0, 0, 0, $month, $day + 1, $year );

		$total = $this->querycache->get( 'post', 'total_posttime_between', $start, $end );

		// 分頁
		$per_page = config_item( 'per_page' );
		$pagination_config = array(
			'base_url' => archive_url( $year ) . '/page',
			'total_rows' => $total,
			'per_page' => $per_page,
			'uri_segment' => 6,
		);
		$this->pagination->initialize( $pagination_config );
		$data['pagination'] = $this->pagination->create_pages();

		$this->_post_data = $this->querycache->get( 'post', 'get_posttime_between', $start, $end, $per_page, ( $this->pagination->get_cur_page() - 1 ) * $per_page );

		$this->_prepare();
		$data['posts_data'] = $this->_post_data;

		// 標記是歸檔
		$data['is_archive'] = TRUE;

		// 顯示方式
		$data['display'] = $this->_display_get();

		//
		$data['by_time'] = "{$year}年{$month}月{$day}日";

		$this->load->view( 'fragment', $data );
	}

	/**
	 * 按照文章類別歸檔
	 * @param string $category 類別名
	 * @param int $page 頁
	 */
	public function archive_by_category( $category, $page = 1 )
	{
		// 類別名
		$category = htmlspecialchars( urldecode( $category ) );
		$data['by_category'] = $category;
		// 根據類別名稱獲取類別數據
		$category_data = $this->querycache->get( 'category', 'get_by_name', $category );
		if ( $category_data )
		{
			$total = $this->querycache->get( 'post', 'total_by_category', $category_data['id'] );

			// 分頁
			$per_page = config_item( 'per_page' );
			$pagination_config = array(
				'base_url' => category_url( $category_data['category'] ) . '/page',
				'total_rows' => $total,
				'per_page' => $per_page,
				'uri_segment' => 4,
			);
			$this->pagination->initialize( $pagination_config );
			$data['pagination'] = $this->pagination->create_pages();

			// 不為空
			$this->_post_data = $this->querycache->get( 'post', 'get_by_category', $category_data['id'], $per_page, ( $this->pagination->get_cur_page() - 1 ) * $per_page );

			$this->_prepare();
			$data['posts_data'] = $this->_post_data;
		}
		// 標記是歸檔
		$data['is_archive'] = TRUE;

		// 顯示方式
		$data['display'] = $this->_display_get();

		$this->load->view( 'fragment', $data );
	}

	/**
	 * 按照标签歸檔
	 * @param string $category 類別名
	 * @param int $page 頁
	 */
	public function archive_by_tag( $tag, $page = 1 )
	{
		// 标签
		$tag = htmlspecialchars( urldecode( $tag ) );
		$data['by_tag'] = $tag;
		// 根據類別名稱獲取類別數據
		$tag_data = $this->querycache->get( 'tag', 'get_by_name', $tag );
		if ( $tag_data )
		{
			$total = $this->querycache->get( 'post', 'total_by_tag', $tag_data['id'] );

			// 分頁
			$per_page = config_item( 'per_page' );
			$pagination_config = array(
				'base_url' => tag_url( $tag_data['tag'] ) . '/page',
				'total_rows' => $total,
				'per_page' => $per_page,
				'uri_segment' => 4,
			);
			$this->pagination->initialize( $pagination_config );
			$data['pagination'] = $this->pagination->create_pages();

			// 不為空
			$this->_post_data = $this->querycache->get( 'post', 'get_by_tag', $tag_data['id'], $per_page, ( $this->pagination->get_cur_page() - 1 ) * $per_page );

			$this->_prepare();
			$data['posts_data'] = $this->_post_data;
		}
		// 標記是歸檔
		$data['is_archive'] = TRUE;

		// 顯示方式
		$data['display'] = $this->_display_get();

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
		if ( $post_data['ispublic'] ) return $post_data;
		return FALSE;
	}

	/**
	 * 對文章進行頂踩評價
	 * @param int $post_id
	 * @param string $act
	 */
	public function feedback( $post_id, $act = 'ding' )
	{
		try
		{
			if ( !$this->input->is_ajax_request() ) throw new Exception( '親~請不要進行非法操作', 0 );

			// 驗證文章
			$post_id = intval( $post_id );
			$post_data = $this->querycache->get( 'post', 'get_by_id', $post_id );
			if ( empty( $post_data ) || !$post_data['ispublic'] ) throw new Exception( '親~請不要進行非法操作', -1 );

			// 判斷是否重複頂或踩
			$already_ding = $this->session->userdata( $this->_session_ding_key );
			$already_cai = $this->session->userdata( $this->_session_cai_key );

			if ( $act == 'cai' )
			{
				// 踩
				if ( $already_cai && in_array( $post_id, $already_cai ) ) throw new Exception( "親~不用踩這麼重，{$post_data['author']}快被踩扁了…" );
				$this->querycache->execute( 'post', 'update_bad', array( $post_id ) );
				$post_data['bad']++;
				$data['message'] = '多謝您的反饋與批評';

				// 記錄
				$already_cai[] = $post_id;
				$this->session->set_userdata( $this->_session_cai_key, array_unique( $already_cai ) );
			}
			else
			{
				// 頂
				if ( $already_ding && in_array( $post_id, $already_ding ) ) throw new Exception( "親~不要頂這麼激烈，我頂唔順啦~" );

				$this->querycache->execute( 'post', 'update_good', array( $post_id ) );
				$post_data['good']++;
				$data['message'] = '多謝您的支持與鼓勵';

				// 記錄
				$already_ding[] = $post_id;
				$this->session->set_userdata( $this->_session_ding_key, array_unique( $already_ding ) );
			}

			$data['post_data'] = $post_data;
		}
		catch ( Exception $e )
		{
			$error_message = $e->getMessage();
			$error_code = $e->getCode();
			$data['error'] = $error_message;
		}
		$this->load->view( 'feedback', $data );
	}

	/**
	 * 更新指定文章的點擊數
	 * @param int $post_id
	 */
	private function _update_post_clicknum( $post_id )
	{
		$already_click = $this->session->userdata( $this->_session_click_key );
		// 已经阅读过
		if ( !empty( $already_click ) && in_array( $post_id, $already_click ) ) return FALSE;

		$this->querycache->execute( 'post', 'update_click', array( $post_id ) );
		$already_click[] = $post_id;
		$this->session->set_userdata( $this->_session_click_key, array_unique( $already_click ) );
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

		// 更新阅读数
		$this->_update_post_clicknum( $post_data['id'] );

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

	/**
	 * 顯示方式選擇欄
	 */
	public function display_bar()
	{
		$data = array( );
		$data['display'] = $this->_display_get();
		$this->load->view( 'display', $data );
	}

	/**
	 * 獲取顯示方式
	 * @return string
	 */
	private function _display_get()
	{
		$display_type = $this->input->cookie( $this->_display_cookie_key );
		if ( empty( $display_type ) )
		{
			$this->display_set( self::DISPLAY_COLUMN );
			return self::DISPLAY_COLUMN;
		}
		return $display_type;
	}

	/**
	 * 設置顯示方式
	 */
	public function display_set( $type = self::DISPLAY_COLUMN )
	{
		$this->input->set_cookie( $this->_display_cookie_key, $type, $this->_display_cookie_expired );
	}

	/**
	 * RSS
	 */
	public function rss()
	{
		$this->load->library( 'rss2' );
		$channel = $this->rss2->new_channel();
		$channel->atom_link( rss_url() );
		$channel->set_title( config_item( 'sitename' ) );
		$channel->set_link( base_url() );
		$channel->set_description( config_item( 'description' ) );
		$post_data = $this->querycache->get( 'post', 'get_all' );
		foreach ( $post_data as $post )
		{
			$item = $channel->new_item();
			$item->set_title( $post['title'] );
			$item->set_link( post_permalink( $post['urltitle'] ) );
			$item->set_guid( $post['id'] );
			$item->set_attribute( 'pubDate', date( 'Y-m-d H:i', $post['posttime'] ) );
			$item->set_description( get_post_fragment( $post['content'] ) );
			$item->set_author( $post['author'] );
			$channel->add_item( $item );
		}

		$this->rss2->pack( $channel );
		header( $this->rss2->headers() );
		echo $this->rss2->render();
		exit();
	}

	/**
	 * 404頁面
	 */
	public function not_found()
	{
		$data = array( );
		$this->load->view( '404', $data );
	}

}

// end of common