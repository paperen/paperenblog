<?php

/**
 * 2012-3-18 17:34:28
 * 作者模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/author/controllers/
 */
class Author_Common_Module extends CI_Module
{

	public function index( $page = 1 )
	{
		$data = array( );

		$this->load->library( 'level' );

		$author_data = $this->querycache->get( 'user', 'get_all_author' );
		// 反序列化附加數據
		foreach( $author_data as $k => $single )
		{
			$author_data[$k]['data'] = unserialize( $single['data'] );
		}

		// 附加文章數據
		$author_ids = array();
		foreach( $author_data as $single ) $author_ids[] = $single['id'];
		$author_post_num = $this->querycache->get( 'post', 'total_by_authorids', $author_ids );
		// 將文章數據使用用戶ID格式化
		$author_post_num_format = array();
		foreach( $author_post_num as $single )
		{
			$author_post_num_format[$single['authorid']] = $single['total'];
		}
		foreach( $author_data as $k => $single )
		{
			$author_data[$k]['postnum'] = isset( $author_post_num_format[$single['id']] ) ? $author_post_num_format[$single['id']] : 0;
		}

		$data['author_col_data'] = $this->_format_by_col( $author_data );
		$this->load->view( 'list', $data );
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

}

// end of common