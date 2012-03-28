<?php

/**
 * 2012-3-18 17:34:28
 * 文章模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/post/models/
 */
class Post_model extends CI_Model
{

	/**
	 * 表映射
	 * @var array
	 */
	private $_tables = array(
		'category' => 'category',
		'post' => 'post',
		'post_tag' => 'post_tag',
		'tag' => 'tag',
		'user' => 'user',
		'comment' => 'comment',
	);

	/**
	 * 查询所有文章
	 * @param int $per_page 每页显示条数
	 * @param int $offset 游标
	 * @return array
	 */
	public function all( $per_page = 0, $offset = 0 )
	{
		$query = $this->db->select(
						'p.id,p.title,p.urltitle,
						 p.categoryid,p.content,
						 p.authorid,p.click,p.good,
						 p.bad,p.posttime,
						 c.category,
						 u.name as author'
				)
				->from( "{$this->_tables['post']} as p" )
				->join( "{$this->_tables['category']} as c", 'c.id = p.categoryid' )
				->join( "{$this->_tables['user']} as u", 'u.id = p.authorid' );
		if ( $per_page ) $query->limit( $per_page, $offset );

		// 文章数据
		$post_data = $query->order_by( 'p.id', 'desc' )
				->get()
				->result_array();
		if ( empty( $post_data ) ) return array( );

		// 文章ID
		$post_ids = array( );
		foreach ( $post_data as $single )
			$post_ids[] = $single['id'];

		// 查询文章的标签
		$post_tags = $this->tags( $post_ids );
		$post_tags_format = array( );
		foreach ( $post_tags as $single )
			$post_tags_format[$single['postid']][] = $single['tag'];

		// 获取评论数
		$post_comments = $this->comments_num( $post_ids );
		$post_comments_format = array( );
		foreach ( $post_comments as $single )
			$post_comments_format[$single['postid']] = $single['num'];

		// 拼合文章数据
		$result = array( );
		foreach ( $post_data as $single )
		{
			// 拼入标签
			$single['tags'] = isset( $post_tags_format[$single['id']] ) ? $post_tags_format[$single['id']] : array( );
			// 拼入评论数
			$single['commentnum'] = isset( $post_comments_format[$single['id']] ) ? $post_comments_format[$single['id']] : 0;

			$result[] = $single;
		}
		return $result;
	}

	/**
	 * 根据文章ID获取相关的标签
	 * @param mixed $post_ids 单个ID或数组
	 * @return array
	 */
	public function tags( $post_ids )
	{
		return $this->db->select( 't.id,t.tag,pt.postid' )
				->from( "{$this->_tables['post_tag']} as pt" )
				->join( "{$this->_tables['tag']} as t", 't.id = pt.tagid' )
				->where_in( 'pt.postid', $post_ids )
				->order_by( 't.id', 'desc' )
				->get()
				->result_array();
	}

	/**
	 * 根据指定文章ID获取相关文章的评论次数
	 * @param array $post_ids 文章ID数组
	 */
	public function comments_num( $post_ids )
	{
		return $this->db->select( 'COUNT(`id`) as num, postid', FALSE )
				->from( "{$this->_tables['comment']} as c" )
				->group_by( 'postid' )
				->get()
				->result_array();
	}

	/**
	 * 根据发布时间戳区间获取符合的文章数据
	 * @param int $lower 时间下限
	 * @param int $upper 时间上限
	 * @param int $per_page 每页显示条数
	 * @param int $offset 游标
	 * @return array
	 */
	public function get_posttime_between( $lower, $upper, $per_page = 5, $offset = 0 )
	{
		$query = $this->db->select(
						'p.id,p.title,p.urltitle,
						 p.categoryid,p.content,
						 p.authorid,p.click,p.good,
						 p.bad,p.posttime,
						 c.category,
						 u.name as author'
				)
				->from( "{$this->_tables['post']} as p" )
				->join( "{$this->_tables['category']} as c", 'c.id = p.categoryid' )
				->join( "{$this->_tables['user']} as u", 'u.id = p.authorid' )
				->where( 'p.posttime >=', $lower )
				->where( 'p.posttime <', $upper );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'p.id', 'desc' )
				->get()
				->result_array();
	}

	/**
	 * 获取最近文章
	 * @param int $limit 显示文章数
	 * @return array
	 */
	public function get_latest( $limit = 5 )
	{
		return $this->db->select(
						'p.id,p.title,p.urltitle,
						 p.categoryid,p.content,
						 p.authorid,p.click,p.good,
						 p.bad,p.posttime,
						 c.category,
						 u.name as author'
				)
				->from( "{$this->_tables['post']} as p" )
				->join( "{$this->_tables['category']} as c", 'c.id = p.categoryid' )
				->join( "{$this->_tables['user']} as u", 'u.id = p.authorid' )
				->order_by( 'p.id', 'desc' )
				->limit( $limit )
				->get()
				->result_array();
	}

}

// end of Post_model