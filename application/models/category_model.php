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

}

// end of Tag_model