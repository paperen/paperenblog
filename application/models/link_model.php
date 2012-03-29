<?php

/**
 * 2012-3-18 17:34:28
 * 友链模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/post/models/
 */
class Link_model extends CI_Model
{

	/**
	 * 表映射
	 * @var array
	 */
	private $_tables = array(
		'link' => 'link',
		'attachment' => 'attachment',
	);

	/**
	 * 获取所有友链
	 * @param int $per_page 每页显示条数
	 * @param int $offset 游标
	 * @return array
	 */
	public function get_all( $per_page = 5, $offset = 0 )
	{
		$query = $this->db->select( '
			l.id,l.name,url,image,meta,
			a.name as imagename,a.path,a.suffix,a.size
			' )
				->from( "{$this->_tables['link']} as l" )
				->join( "{$this->_tables['attachment']} as a", 'a.id = l.image', 'left' )
				->order_by( 'l.`order`', 'asc' );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->get()->result_array();
	}

}

// end of Post_model