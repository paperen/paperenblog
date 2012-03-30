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

}

// end of Tag_model