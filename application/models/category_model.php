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
		return $this->db->select( 'c.id,c.category,c.pidlevel,c.pid,c.ispublic,c.userid,u.name,c.isdefault' )
						->from( "{$this->_tables['category']} as c" )
						->join( "{$this->_tables['user']} as u", 'u.id=c.userid', 'left' )
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
		return $this->db->select( 'c.id,c.category,c.pidlevel,c.pid,c.ispublic,c.userid,u.name,c.isdefault' )
						->from( "{$this->_tables['category']} as c" )
						->join( "{$this->_tables['user']} as u", 'u.id=c.userid', 'left' )
						->where( 'c.id', $id )
						->get()
						->row_array();
	}

	/**
	 * 根據ID獲取所有子類別數據
	 * @param int $parent_id]
	 * @return array
	 */
	public function get_by_pid( $parent_id )
	{
		$like = '-' . trim( $parent_id, '-' ) . '-';
		return $this->db->select( 'c.id,c.category,c.pidlevel,c.pid,c.ispublic,c.userid,u.name,c.isdefault' )
						->from( "{$this->_tables['category']} as c" )
						->join( "{$this->_tables['user']} as u", 'u.id=c.userid', 'left' )
						->like( 'pidlevel', $like )
						->get()
						->result_array();
	}

	/**
	 * 獲取默認類別數據
	 * @return array
	 */
	public function get_default()
	{
		return $this->db->select( 'c.id,c.category,c.pidlevel,c.pid,c.ispublic,c.userid,c.isdefault' )
						->from( "{$this->_tables['category']} as c" )
						->where( 'c.isdefault', TRUE )
						->limit( 1 )
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
		$query = $this->db->select( 'c.id,c.category,c.pidlevel,c.pid,c.ispublic,c.userid,u.name,c.isdefault' )
				->from( "{$this->_tables['category']} as c" )
				->where( 'c.ispublic', TRUE )
				->join( "{$this->_tables['user']} as u", 'u.id=c.userid', 'left' );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'c.id', 'asc' )
						->get()
						->result_array();
	}

	/**
	 * 獲取指定作者發佈的文章分類數據
	 * @param int $author_id 作者ID
	 * @param int $per_page
	 * @param int $offset
	 * @return array
	 */
	public function get_all_by_author( $author_id, $per_page = 0, $offset = 0 )
	{
		$query = $this->db->select( 'c.id,c.category,c.pidlevel,c.pid,c.ispublic,c.userid,u.name,c.isdefault' )
				->from( "{$this->_tables['category']} as c" )
				->join( "{$this->_tables['user']} as u", 'u.id=c.userid', 'left' )
				->where( 'c.userid', $author_id );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'c.pid', 'asc' )
						->get()
						->result_array();
	}

	/**
	 * 獲取指定作者的文章類別總數
	 * @param int $author_id 作者ID
	 * @return int
	 */
	public function total_by_authorid( $author_id )
	{
		return $this->db->where( 'userid', $author_id )
						->count_all_results( $this->_tables['category'] );
	}

	/**
	 * 判斷某類別是否存在
	 * @param string $category
	 * @param int $author_id
	 * @return int 數據數
	 */
	public function exists( $category, $author_id = '' )
	{
		if ( $author_id )
		{
			return $this->db->where( 'userid', $author_id )
							->where( 'category', $category )
							->count_all_results( $this->_tables['category'] );
		}
		else
		{
			return $this->db->where( 'category', $category )
							->count_all_results( $this->_tables['category'] );
		}
	}

	/**
	 * 插入
	 * @param array $data
	 * @return int
	 */
	public function insert( $data )
	{
		$insert_data = array(
			'category' => $data['category'],
			'pid' => isset( $data['pid'] ) ? $data['pid'] : 0,
			'pidlevel' => isset( $data['pidlevel'] ) ? $data['pidlevel'] : '0-',
			'ispublic' => isset( $data['ispublic'] ) ? $data['ispublic'] : TRUE,
			'userid' => $data['userid'],
			'isdefault' => isset( $data['isdefault'] ) ? $data['isdefault'] : FALSE,
		);
		$this->db->insert( $this->_tables['category'], $insert_data );
		return $this->db->insert_id();
	}

	/**
	 * 更新指定類別數據
	 * @param array $data
	 * @param int $id
	 * @return int 影響行數
	 */
	public function update( $data, $id )
	{
		$update_data = array(
			'category' => $data['category'],
			'pid' => isset( $data['pid'] ) ? $data['pid'] : 0,
			'pidlevel' => isset( $data['pidlevel'] ) ? $data['pidlevel'] : '0-',
		);
		$this->db->where( 'id', $id )
				->update( $this->_tables['category'], $update_data );
		return $this->db->affected_rows();
	}

	/**
	 * 根據pid更新pid……
	 * @param int $cur_pid
	 * @param int $to_pid
	 * @return 影響行數
	 */
	public function update_pid_to_pid( $cur_pid, $to_pid )
	{
		$update_data = array(
			'pid' => $to_pid,
		);
		$this->db->where( 'pid', $cur_pid )
				->update( $this->_tables['category'], $update_data );
		return $this->db->affected_rows();
	}

	/**
	 * 更新指定的类别pid数据
	 * @param array $data
	 * @param int $id
	 * @return int 影响行数
	 */
	public function update_pid( $data, $id )
	{
		$update_data = array(
			'pid' => $data['pid'],
			'pidlevel' => isset( $data['pidlevel'] ) ? $data['pidlevel'] : '0-',
		);
		$this->db->where( 'id', $id )
				->update( $this->_tables['category'], $update_data );
		return $this->db->affected_rows();
	}

	/**
	 * 删除指定的类别
	 * @param int $id
	 * @return int
	 */
	public function delete( $id )
	{
		$this->db->where( 'id', $id )
				->delete( $this->_tables['category'] );
		return $this->db->affected_rows();
	}

}

// end of Tag_model