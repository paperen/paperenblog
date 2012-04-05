<?php

/**
 * 2012-3-18 17:34:28
 * 评论模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/models/
 */
class Comment_model extends CI_Model
{

	/**
	 * 表映射
	 * @var array
	 */
	private $_tables = array(
		'post' => 'post',
		'user' => 'user',
		'comment' => 'comment',
	);

	/**
	 * 查询所有评论
	 * @param int $per_page 每页显示条数
	 * @param int $offset 游标
	 * @return array
	 */
	public function get_all( $per_page = 0, $offset = 0 )
	{
		$query = $this->db->select(
						'c.id,c.postid,c.userid,c.author,c.authoremail,
					c.authorurl,c.commenttime,c.content,c.pid,c.isneednotice,
					u.name as username,
					p.title,p.urltitle,p.authorid'
				)
				->from( "{$this->_tables['comment']} as c" )
				->join( "{$this->_tables['post']} as p", 'p.id = c.postid' )
				->join( "{$this->_tables['user']} as u", 'u.id = c.userid', 'left' )
				->where( 'c.ispublic', 1 )
				->order_by( 'c.id', 'desc' );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->get()->result_array();
	}

	/**
	 * 根據文章ID獲取總評論數
	 * @param int $post_id 文章ID
	 * @return int 總數
	 */
	public function total_by_postid( $post_id )
	{
		return $this->db->where( 'postid', $post_id )
						->where( 'ispublic', TRUE )
						->count_all_results( 'comment' );
	}

	/**
	 * 根据指定文章ID获取相关文章的评论次数
	 * @param array $post_ids 文章ID数组
	 */
	public function total_by_postids( $post_ids )
	{
		return $this->db->select( 'COUNT(`id`) as num, postid', FALSE )
						->from( "{$this->_tables['comment']} as c" )
						->group_by( 'postid' )
						->get()
						->result_array();
	}

}

// end of Comment_model