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

}

// end of Post_model