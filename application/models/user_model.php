<?php

/**
 * 2012-3-18 17:34:28
 * 用戶模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/models/
 */
class User_model extends CI_Model
{

	/**
	 * 表映射
	 * @var array
	 */
	private $_tables = array(
		'user' => 'user',
	);

	/**
	 * 根據用戶名獲取用戶數據
	 * @param string $name 用戶名
	 * @return array
	 */
	public function get_by_name( $name )
	{
		return $this->db->select(
								'u.id,u.name,u.password,u.email,u.url,u.lastlogin,u.lastip,u.identity,u.role'
						)
						->from( "{$this->_tables['user']} as u" )
						->where( 'u.name', $name )
						->get()
						->row_array();
	}

}

// end of Tag_model