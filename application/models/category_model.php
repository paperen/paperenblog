<?php

/**
 * 2012-4-13 17:34:28
 * 文章類別模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/models/
 */
class Category_model extends CI_Model
{

	/**
	 * 表映射
	 * @var array
	 */
	private $_tables = array(
		'category' => 'category',
		'user' => 'user',
	);

	/**
	 * 按照類別名獲取類別數據
	 * @param string $category 類別名
	 * @return array
	 */
	public function get_by_name( $category )
	{
		return $this->db->select( 'c.id,c.category,c.pid,c.ispublic' )
						->from( "{$this->_tables['category']} as c" )
						->where( 'c.category', $category )
						->get()
						->row_array();
	}

	/**
	 * 根據類別ID獲取類別數據
	 * @param int $id
	 * @return array
	 */
	public function get_by_id( $id )
	{
		return $this->db->select( 'c.id,c.category,c.pid,c.ispublic' )
						->from( "{$this->_tables['category']} as c" )
						->where( 'c.id', $id )
						->get()
						->row_array();
	}

	/**
	 * 獲取所有文章分類數據
	 * @param int $per_page
	 * @param int $offset
	 * @return array
	 */
	public function get_all( $per_page = 0, $offset = 0 )
	{
		$query = $this->db->select( 'c.id,c.category,c.pid,c.ispublic,c.userid,u.name' )
				->from( "{$this->_tables['category']} as c" )
				->join( "{$this->_tables['user']} as u", 'u.id=c.userid' );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'c.id', 'asc' )
						->get()
						->result_array();
	}

}

// end of Tag_model