<?php

/**
 * 2012-12-2 21:16:28
 * 消费记录模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/consume/controllers/
 */
class Admin_Consume_Common_Module extends MY_Module
{

	const YEAR_INTERVAL = 10;

	private $_current_year;
	private $_current_month;

	function __construct() {
		parent::__construct();
		$this->load->helper( array(
			'open_flash_chart',
			'open_flash_chart_object',
		) );

		$time = time();
		$this->_current_year = date( 'Y', $time );
		$this->_current_month = date( 'm', $time );
	}

	private function _filter( $data, $type = 'year' ) {
		$rules = array(
			'month' => array( 'min' => 1, 'max' => 12 ),
			'day' => array( 'min' => 1, 'max' => 31 ),
		);
		$data = ( $data ) ? intval( $data ) : intval( $this->input->get( $type ) );
		$default = "_current_{$type}";
		if ( isset( $rules[$type] ) ) {
			$data = ( $rules[$type]['min'] <= $data && $rules[$type]['max'] >= $data ) ? $data : $this->$default;
		} else {
			$data = empty( $data ) ? $this->$default : $data;
		}
		return $data;
	}

	/**
	 * 验证天数的合法性
	 * @param int $day 日
	 * @param int $month 月
	 * @param int $year 年
	 * @return int 日
	 */
	private function _vlidation_day( $day, $month = '', $year = '' ) {
		$month = ( $month ) ? $month : $this->_current_month;
		$year = ( $year ) ? $year : $this->_current_year;
		$max_day = date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
		$day = ( $day > $max_day ) ? $max_day : $day;
		$day = ( $day < 1 ) ? 1 : $day;
		return $day;
	}

	private function _init_selectbox( &$data ) {
		$options_year = array( );
		for ( $i = 0; $i <= self::YEAR_INTERVAL; $i++ )
			$options_year[] = $this->_current_year - $i;
		$data['options_year'] = $options_year;

		$options_month = array( );
		for ( $i = 1; $i <= 12; $i++ )
			$options_month[] = $i;
		$data['options_month'] = $options_month;
	}

	/**
	 *
	 * @param int $year 年
	 * @param int $month 月
	 */
	public function index( $year = '', $month = '' ) {
		$data = array( );
		$year = $this->_filter( $month, 'year' );
		$month = $this->_filter( $month, 'month' );
		$data['year'] = $year;
		$data['month'] = $month;

		$this->_init_selectbox( $data );

		$time_interval = $this->_time_interval( $year, $month );

		// 饼图
		$result = $this->_caculate_pie_data( $time_interval['min'], $time_interval['max'] );
		$data['total'] = $result['total'];
		$data['pie_data'] = $result['pie_data'];

		// 条形
		$result = $this->_caculate_bar_data( $time_interval['min'], $time_interval['max'] );
		$data['bar_data'] = $result['bar_data'];

		$this->load->view( 'index', $data );
	}

	/**
	 * 返回指定的年月日区间时间戳
	 * @param int $year
	 * @param int $month
	 * @oaram int $day
	 * @return array
	 */
	private function _time_interval( $year, $month = '', $day = '' ) {
		if ( $year && $month && $day ) {
			// 年月日
			$lower_interval = mktime( 0, 0, 0, $month, $day, $year );
			$higher_interval = mktime( 0, 0, 0, $month, $day + 1, $year );
		} else if ( $year && $month ) {
			// 年月
			$lower_interval = mktime( 0, 0, 0, $month, 1, $year );
			$higher_interval = mktime( 0, 0, 0, $month + 1, 1, $year );
		} else {
			// 年月
			$lower_interval = mktime( 0, 0, 0, 1, 1, $year );
			$higher_interval = mktime( 0, 0, 0, 1, 1, $year + 1 );
		}
		return array(
			'min' => $lower_interval,
			'max' => $higher_interval,
		);
	}

	/**
	 * 计算饼图统计数据
	 * @param int $min 时间区间下限
	 * @param int $max 时间区间上限
	 * @return array $data
	 */
	private function _caculate_pie_data( $min, $max ) {
		$data = array(
			'total' => 0,
			'pie_swf_data' => array( ),
			'pie_data' => array( ),
		);

		// 消费总额
		$consume_total = $this->querycache->get( 'consume', 'sum', $min, $max );
		$data['total'] = $consume_total;

		// 格式化
		$consume_data = $this->querycache->get( 'consume', 'get_between', $min, $max );
		if ( empty( $consume_data ) ) return $data;
		foreach ( $consume_data as $single ) {
			if ( !isset( $data['pie_swf_data'][$single['type']] ) ) $data['pie_swf_data'][$single['type']] = 0;
			$data['pie_swf_data'][$single['type']] += $single['money'];
		}
		// 转为百分比
		foreach ( $data['pie_swf_data'] as $type => $money ) {
			$percent = number_format( ( $money / $consume_total) * 100, 1 );
			$data['pie_data'][] = array(
				'type' => $type,
				'money' => $money,
				'percent' => $percent,
			);
			$data['pie_swf_data'][$type] = $percent;
		}

		return $data;
	}

	/**
	 * 饼图
	 * @param int $year 查询年份
	 * @param int $month 查询月份
	 */
	public function pie( $year = '', $month = '' ) {

		$year = $this->_filter( $year, 'year' );
		$month = $this->_filter( $month, 'month' );
		$time_interval = $this->_time_interval( $year, $month );

		$data = $this->_caculate_pie_data( $time_interval['min'], $time_interval['max'] );
		$data = $data['pie_swf_data'];

		$g = new graph();
		$g->bg_colour = '#FFFFFF';
		$g->pie( 60, '#000000', '', true, false );
		$g->pie_values( array_values( $data ), array_keys( $data ) );
		$g->pie_slice_colours( array( '#8BB2CF', '#DD3719', '#66CC00', '#E5CA00', '#D255B5', '#000000' ) );
		$g->set_tool_tip( '#x_label# 类别 #val#%' );
		$g->title( "{$year}年{$month}月 消费饼图", '{font-size:18px; color: #000000;margin-bottom:20px;}' );
		echo $g->render();
		unset( $g );
	}

	/**
	 * 柱状图
	 * @param int $year 年
	 * @param int $month 月
	 */
	public function bar( $year = '', $month = '' ) {
		$year = $this->_filter( $year, 'year' );
		$month = $this->_filter( $month, 'month' );
		$time_interval = $this->_time_interval( $year, $month );

		$data = $this->_caculate_bar_data( $time_interval['min'], $time_interval['max'] );
		$data = $data['bar_swf_data'];

		$bar = new bar_sketch( 55, 6, '#66CC00', '#000000' );
		$bar->key( "消费金额", 12 );
		$bar->data = array_values( $data );

		$g = new graph();
		$g->bg_colour = '#FFFFFF';
		$g->data_sets[] = $bar;
		$g->x_axis_colour( '#e0e0e0', '#e0e0e0' );
		$g->set_x_tick_size( 9 );
		$g->y_axis_colour( '#e0e0e0', '#e0e0e0' );
		$g->set_x_labels( array_keys( $data ) );
		$g->set_x_label_style( 11, '#303030', 2 );
		$g->set_y_label_style( 11, '#303030', 2 );
		$g->set_y_max( 100 );
		$g->y_label_steps( 5 );
		echo $g->render();

		unset( $g );
	}

	/**
	 * 计算出柱状图数据
	 * @param int $min 时间区间下限
	 * @param int $max 时间区间上限
	 * @return array
	 */
	private function _caculate_bar_data( $min, $max ) {
		$data = array(
			'total' => 0,
			'bar_swf_data' => array( ),
			'bar_data' => array( ),
		);

		// 消费总额
		$consume_total = $this->querycache->get( 'consume', 'sum', $min, $max );
		$data['total'] = $consume_total;

		// 统计整合每天的金额
		$consume_data = $this->querycache->get( 'consume', 'get_between', $min, $max );
		if ( empty( $consume_data ) ) return $data;
		foreach ( $consume_data as $single ) {
			$day = date( 'j', $single['time'] );
			if ( !isset( $data['bar_swf_data'][$day] ) ) {
				$data['bar_swf_data'][$day] = $single['money'];
			} else {
				$data['bar_swf_data'][$day] += $single['money'];
			}
		}

		// 最后整合
		$result = $data['bar_swf_data'];
		foreach ( $result as $day => $money ) {
			$percent = number_format( ($money / $consume_total) * 100, 1 );
			$data['bar_swf_data'][$day] = $percent;
			$data['bar_data'][] = array(
				'day' => $day,
				'money' => $money,
				'percent' => $percent,
			);
		}

		return $data;
	}

}

// end of common