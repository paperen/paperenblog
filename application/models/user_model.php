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

	/**
	 * 所有用戶數據
	 * @param int $per_page
	 * @param int $offset
	 * @return array
	 */
	public function get_all( $per_page = 0, $offset = 0 )
	{
		return $this->db->select(
								'u.id,u.name,u.email,u.url,u.lastlogin,u.lastip,u.identity,u.role'
						)
						->from( "{$this->_tables['user']} as u" )
						->order_by( 'u.id', 'desc' )
						->get()
						->result_array();
	}

	/**
	 * 更新最近登陸時間與IP
	 * @param array $data
	 * @param int $userid
	 * @return int
	 */
	public function update_lastlogin( $data, $userid )
	{
		$update_data = array(
			'lastlogin' => $data['lastlogin'],
			'lastip' => $data['lastip'],
		);
		$this->db->where( 'id', $userid )
				->update( $this->_tables['user'], $update_data );
		return $this->db->affected_rows();
	}

	public function update_token( $token, $userid )
	{
		$update_data = array(
			'token' => $token,
		);
		$this->db->where( 'id', $userid )
				->update( $this->_tables['user'], $update_data );
		return $this->db->affected_rows();
	}
	
	/**
	 * 用戶總數
	 * @return int
	 */
	public function total()
	{
		return $this->db->count_all_results( $this->_tables['user'] );
	}

}

// end of Tag_model