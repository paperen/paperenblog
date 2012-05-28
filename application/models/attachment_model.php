<?php

/**
 * 2012-3-18 17:34:28
 * 附件模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/models/
 */
class Attachment_model extends CI_Model
{

	/**
	 * 表映射
	 * @var array
	 */
	private $_tables = array(
		'attachment' => 'attachment',
		'user' => 'user',
		'post_attachment' => 'post_attachment',
	);

	/**
	 * 根據附件ID獲取附件數據
	 * @param int $attachment_id
	 * @return array
	 */
	public function get_by_id( $attachment_id )
	{
		return $this->db->select(
								'a.id,a.name,a.path,
						 a.suffix,a.size,
						 a.isimage,a.isthumbnail,
						 a.addtime,
						 u.name as uploader'
						)
						->from( "{$this->_tables['attachment']} as a" )
						->join( "{$this->_tables['user']} as u", 'u.id = a.userid' )
						->where( 'a.id', $attachment_id )
						->get()
						->row_array();
	}

	/**
	 * 根據文章ID獲取相關的附件數據
	 * @param int $post_id 文章ID
	 * @return array
	 */
	public function get_by_post_id( $post_id )
	{
		return $this->db->select(
								'a.id,a.name,a.path,
						 a.suffix,a.size,
						 a.isimage,a.isthumbnail,
						 a.addtime,
						 u.name as uploader'
						)
						->from( "{$this->_tables['post_attachment']} as pa" )
						->join( "{$this->_tables['attachment']} as a", 'a.id = pa.attachmentid' )
						->join( "{$this->_tables['user']} as u", 'u.id = a.userid' )
						->where( 'pa.postid', $post_id )
						->get()
						->result_array();
	}

	/**
	 * 獲取指定文章IDS的代表圖片
	 * @param array $post_ids 文章IDs
	 * @return array
	 */
	public function get_thumbnail_by_post_ids( $post_ids )
	{
		return $this->db->select(
								'a.id,a.name,a.path,
						 a.suffix,a.size,
						 a.addtime,
						 u.name as uploader,
						 pt.postid'
						)
						->from( "{$this->_tables['attachment']} as a" )
						->join( "{$this->_tables['post_attachment']} as pt", 'pt.attachmentid = a.id' )
						->join( "{$this->_tables['user']} as u", 'u.id = a.userid' )
						->where_in( 'pt.postid', $post_ids )
						->where( 'a.isthumbnail', TRUE )
						->where( 'a.isimage', TRUE )
						->get()
						->result_array();
	}

	/**
	 * 插入附件信息
	 * @param array $data
	 * @return int
	 */
	public function insert( $data )
	{
		$insert_data = array(
			'name' => $data['name'],
			'path' => $data['path'],
			'suffix' => $data['suffix'],
			'size' => $data['size'],
			'isimage' => $data['isimage'],
			'isthumbnail' => $data['isthumbnail'],
			'addtime' => time(),
			'userid' => $data['userid'],
		);
		$this->db->insert( $this->_tables['attachment'], $insert_data );
		return $this->db->insert_id();
	}

	/**
	 * 更新指定附件記錄為特色圖像
	 * @param int $attachment_id
	 */
	public function update_isthumbnail( $attachment_id )
	{
		$update_data = array(
			'isthumbnail' => TRUE,
		);
		$this->db->where( 'id', $attachment_id )
				->update( $this->_tables['attachment'], $update_data );
		return $this->db->affected_rows();
	}

	/**
	 * 根據附件ID刪除相應的附件數據
	 * @param int $ids
	 * @return int 影響行數
	 */
	public function delete_by_ids( $ids )
	{
		$this->db->where_in( 'id', $ids )
				->delete( $this->_tables['attachment'] );
		return $this->db->affected_rows();
	}

	/**
	 * 解除文章與附件的關係
	 * @param int $post_id
	 * @return int 影響行數
	 */
	public function delete_by_post_id( $post_id )
	{
		$this->db->where( 'postid', $post_id )
				->delete( $this->_tables['post_attachment'] );
		return $this->db->affected_rows();
	}

}

// end of Post_model