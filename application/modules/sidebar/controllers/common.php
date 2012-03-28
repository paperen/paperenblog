<?php

/**
 * 2012-3-18 17:34:28
 * 边栏模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/sidebar/controllers/
 */
class Sidebar_Common_Module extends CI_Module
{

	/**
	 * 加载边栏
	 */
	public function index()
	{
		$data = array(
		);
		$this->load->view( 'index', $data );
	}

	/**
	 * 加载博客日历
	 * @param int $year 年份
	 * @param int $month 月份
	 */
	public function calendar( $year = '', $month = '' )
	{
		$year = intval( $year );
		$month = intval( $month );

		$year = empty( $year ) ? date( 'Y' ) : $year;
		$month = empty( $month ) ? date( 'm' ) : $month;

		// 当月的最后一天
		$last_day = date( 't' );
		// 下限时间戳
		$lower = strtotime( "{$year}-{$month}-1" );
		// 上限时间戳
		$upper = strtotime( "{$year}-{$month}-{$last_day}" );

		// 当月的文章数据
		$this->load->model( 'post_model' );
		$post_data = $this->post_model->get_posttime_between( $lower, $upper, 0 );

		$extra = array( );
		foreach ( $post_data as $post )
		{
			$extra[date( 'd', $post['posttime'] )] = array(
				'url' => base_url( 'archive/' . date( 'Y-m-d', $post['posttime'] ) ),
				'original' => '亲，这一天有发表文章哦',
			);
		}

		$data = array( );

		$this->load->library( 'calendar' );
		$calendar = $this->calendar->generate( $year, $month, $extra );
		$data['calendar'] = $calendar;

		$this->load->view( 'calendar', $data );
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

		$this->load->model( 'post_model' );
		$latest_posts = $this->post_model->get_latest( $limit );
		$data['posts_data'] = $latest_posts;

		$this->load->view( 'latest_posts', $data );
	}

	/**
	 * 热门文章
	 * @param int $limit 显示篇数
	 */
	public function hot_posts( $limit = 5 )
	{
		$limit = intval( $limit );
		if ( empty( $limit ) ) $limit = 5;

		$data = array( );

		$this->load->model( 'post_model' );
		$hot_posts = $this->post_model->get_hot( $limit );
		$data['posts_data'] = $hot_posts;

		$this->load->view( 'hot_posts', $data );
	}

	/**
	 * 最近评论
	 * @param int $limit 显示条数
	 */
	public function recent_comments( $limit = 5 )
	{
		$limit = intval( $limit );
		if ( empty( $limit ) ) $limit = 5;

		$data = array( );

		$this->load->model( 'comment_model' );
		$comments_data = $this->comment_model->all( $limit );
		$data['comments_data'] = $comments_data;

		$this->load->view( 'recent_comments', $data );
	}
}

// end of common