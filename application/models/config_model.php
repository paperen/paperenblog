<?php

/**
 * 2012-3-18 17:34:28
 * 配置模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/models/
 */
class Config_model extends CI_Model
{

	/**
	 * 表映射
	 * @var array
	 */
	private $_tables = array(
		'config' => 'config',
	);

	public function all()
	{
		return $this->db->from( $this->_tables['config'] )
							->get()
							->result_array();
	}

	public function update( $key, $value )
	{
		$this->db->where('key', $key)
					->update( $this->_tables['config'], array( 'value' => $value ) );
		return $this->db->affected_rows();
	}

	/**
	 * 根据键值获取相应数据
	 * @param string $key 键值
	 * @return array
	 */
	public function get_by_key( $key )
	{
		return $this->db->where('key', $key)
						->from( $this->_tables['config'] )
						->get()
						->row_array();
	}

}

// end of Tag_model
