<?php

/**
 * 2012-3-18 17:34:28
 * 标签模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/models/
 */
class Tag_model extends CI_Model
{

	/**
	 * 表映射
	 * @var array
	 */
	private $_tables = array(
		'post_tag' => 'post_tag',
		'tag' => 'tag',
	);

	/**
	 * 根据文章ID获取相关的标签
	 * @param mixed $post_ids 单个ID或数组
	 * @return array
	 */
	public function get_by_post_ids( $post_ids )
	{
		return $this->db->select( 't.id,t.tag,pt.postid' )
						->from( "{$this->_tables['post_tag']} as pt" )
						->join( "{$this->_tables['tag']} as t", 't.id = pt.tagid' )
						->where_in( 'pt.postid', $post_ids )
						->order_by( 't.id', 'desc' )
						->get()
						->result_array();
	}

	/**
	 * 根据標籤名稱獲取數據
	 * @param string $tag
	 * @return array
	 */
	public function get_by_name( $tag )
	{
		return $this->db->select( 'id,tag' )
						->from( $this->_tables['tag'] )
						->where( 'tag', $tag )
						->get()
						->row_array();
	}

	/**
	 * 根据標籤名稱獲取數據
	 * @param array $tag
	 * @return array
	 */
	public function get_by_names( $tag )
	{
		return $this->db->select( 'id,tag' )
						->from( $this->_tables['tag'] )
						->where_in( 'tag', $tag )
						->get()
						->result_array();
	}

	/**
	 * 獲取所有標籤
	 * @param int $per_page
	 * @param int $offset
	 * @return array
	 */
	public function get_all( $per_page = 5, $offset = 0 )
	{
		$query = $this->db->select( 'id,tag' )
				->from( $this->_tables['tag'] );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->get()->result_array();
	}

	/**
	 * 添加新標籤
	 * @param string $tag
	 * @return int
	 */
	public function insert( $tag )
	{
		$insert_data = array(
			'tag' => $tag,
		);
		$this->db->insert( $this->_tables['tag'], $insert_data );
		return $this->db->insert_id();
	}

	/**
	 * 插入文章標籤關聯表
	 * @param array $tag_ids
	 * @param int $post_id
	 * @return int
	 */
	public function insert_post_tag( $tag_ids, $post_id )
	{
		$insert_data = array( );
		foreach ( $tag_ids as $id )
		{
			$insert_data[] = array(
				'postid' => $post_id,
				'tagid' => $id,
			);
		}
		$this->db->insert_batch( $this->_tables['post_tag'], $insert_data );
		return $this->db->affected_rows();
	}

	/**
	 * 刪除指定文章已經綁定的標籤
	 * @param int $post_id
	 * @return int
	 */
	public function delete_by_postid( $post_id )
	{
		$this->db->where( 'postid', $post_id )
				->delete( $this->_tables['post_tag'] );
		return $this->db->affected_rows();
	}

}

// end of Tag_model