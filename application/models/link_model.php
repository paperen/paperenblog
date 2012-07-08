<?php

/**
 * 2012-3-18 17:34:28
 * 友链模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/models/
 */
class Link_model extends CI_Model
{

	/**
	 * 表映射
	 * @var array
	 */
	private $_tables = array(
		'link' => 'link',
		'user' => 'user',
		'attachment' => 'attachment',
	);

	/**
	 * 
	 */
	public function get_by_id( $id )
	{
		return $this->db->select( 'l.id,l.name,l.url,l.image,l.meta,l.email,l.order' )
				->from( "{$this->_tables['link']} as l" )
				->where('l.id', $id)
				->get()
				->row_array();
	}

	/**
	 * 获取所有友链
	 * @param int $per_page 每页显示条数
	 * @param int $offset 游标
	 * @return array
	 */
	public function get_all( $per_page = 5, $offset = 0 )
	{
		$query = $this->db->select( 'l.id,l.name,l.url,l.image,l.meta,l.email,l.order' )
				->from( "{$this->_tables['link']} as l" )
				->order_by( 'l.`order`', 'desc' );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->get()->result_array();
	}
	
	/**
	 * 链接總數
	 * @return int
	 */
	public function total()
	{
		return $this->db->count_all_results( $this->_tables['link'] );
	}
	
	/**
	 * 插入链接
	 * @param array $data
	 * @return int 链接ID
	 */
	public function insert( $data )
	{
		$insert_data = array(
			'name' => $data['name'],
			'email' => $data['email'],
			'url' => $data['url'],
			'order' => $data['order'],
			'meta' => $data['meta'],
		);
		$this->db->insert( $this->_tables['link'], $insert_data );
		return $this->db->insert_id();
	}

}

// end of Link_model