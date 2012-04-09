<?php

/**
 * 2012-3-18 17:34:28
 * 文章模型
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/models/
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
		'post_attachment' => 'post_attachment',
		'attachment' => 'attachment',
	);

	/**
	 * 根據文章ID獲取文章數據
	 * @param int $post_id
	 * @return array
	 */
	public function get_by_id( $post_id )
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
				->where( 'p.id', $post_id )
				->get()
				->row_array();
	}

	/**
	 * 根據文章URL標題獲取文章數據
	 * @param string $urltitle
	 * @return array
	 */
	public function get_by_urltitle( $urltitle )
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
				->where( 'p.urltitle', $urltitle )
				->get()
				->row_array();
	}

	/**
	 * 查询所有文章
	 * @param int $per_page 每页显示条数
	 * @param int $offset 游标
	 * @return array
	 */
	public function get_all( $per_page = 0, $offset = 0 )
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
				->where( 'p.ispublic', 1 );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'p.posttime', 'desc' )
				->get()
				->result_array();
	}

	/**
	 * 根據文章ID獲取該文章的圖片
	 * @param int $post_id 文章ID
	 * @return array
	 */
	public function get_images( $post_id )
	{
		return $this->db->select('a.id,a.name,a.suffix,a.size,a.addtime,a.isthumbnail')
						->from("{$this->_tables['post_attachment']} as pa")
						->join("{$this->_tables['attachment']} as a", 'a.id = pa.attachmentid')
						->where('pa.postid', $post_id)
						->where('a.isimage', TRUE)
						->get()
						->result_array();
	}

	/**
	 * 根据文章ID获取相关的标签
	 * @param mixed $post_ids 单个ID或数组
	 * @return array
	 */
	public function get_tags( $post_ids )
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
	 * 按照類別名獲取類別數據
	 * @param string $category 類別名
	 * @return array
	 */
	public function get_category_by_name( $category )
	{
		return $this->db->select( 'c.id,c.category,c.pid,c.ispublic' )
				->from( "{$this->_tables['category']} as c" )
				->where( 'c.category', $category )
				->get()
				->row_array();
	}

	/**
	 * 根據指定的類別ID獲取相關的文章數據
	 * @param int $category_id 類別ID
	 * @param int $per_page 每页显示条数
	 * @param int $offset 游标
	 * @return array
	 */
	public function get_by_category( $category_id, $per_page = 5, $offset = 0 )
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
				->where( 'c.id', $category_id );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'p.id', 'desc' )
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
	 * 获取热门文章
	 * @param int $limit 显示文章数
	 * @return array
	 */
	public function get_hot( $limit = 5 )
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
				->where( "p.good >", 0 )
				->order_by( 'p.good', 'desc' );
		if ( $limit ) $query->limit( $limit );
		return $query->get()->result_array();
	}

}

// end of Post_model