<?php

/**
 * 2012-3-18 17:34:28
 * 日曆模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/sidebar/controllers/
 */
class Calendar_Common_Module extends CI_Module
{

	/**
	 * 加载博客軌跡日历
	 * @param int $year 年份
	 * @param int $month 月份
	 */
	public function locus( $year = '', $month = '' )
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
		$post_data = $this->querycache->get('post', 'get_posttime_between', $lower, $upper, 0 );

		$extra = array( );
		foreach ( $post_data as $post )
		{
			$extra[date( 'd', $post['posttime'] )] = array(
				'url' => archive_url( $year, $month, date('d', $post['posttime']) ),
				'original' => '亲，这一天有发表文章哦',
			);
		}

		$data = array( );

		$this->load->library( 'calendar' );
		$calendar = $this->calendar->generate( $year, $month, $extra );
		$data['calendar'] = $calendar;

		$this->load->view( 'locus', $data );
	}

}

// end of common