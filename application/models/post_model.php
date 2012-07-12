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
	 * 所有文章總數
	 * @return int
	 */
	public function total()
	{
		return $this->db->where( 'ispublic', TRUE )
						->count_all_results( $this->_tables['post'] );
	}

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
						 p.bad,p.posttime,p.ispublic,p.isdraft,p.istrash,
						 c.category,
						 u.name as author,
						 u.email as authoremail,
						 u.url as authorurl'
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
						 p.bad,p.posttime,p.ispublic,p.isdraft,
						 c.category,
						 u.name as author,
						 u.email as authoremail,
						 u.url as authorurl'
						)
						->from( "{$this->_tables['post']} as p" )
						->join( "{$this->_tables['category']} as c", 'c.id = p.categoryid' )
						->join( "{$this->_tables['user']} as u", 'u.id = p.authorid' )
						->where( 'p.ispublic', TRUE )
						->where( 'p.urltitle', $urltitle )
						->get()
						->row_array();
	}

	/**
	 * 根據作者ID獲取文章數據（不包括回收站的）
	 * @param int $author_id
	 * @return array
	 */
	public function get_by_authorid( $author_id, $per_page = 0, $offset = 0 )
	{
		$query = $this->db->select(
						'p.id,p.title,p.urltitle,
						 p.categoryid,p.content,
						 p.authorid,p.click,p.good,
						 p.bad,p.posttime,p.ispublic,p.isdraft,
						 c.category,
						 u.name as author'
				)
				->from( "{$this->_tables['post']} as p" )
				->join( "{$this->_tables['category']} as c", 'c.id = p.categoryid' )
				->join( "{$this->_tables['user']} as u", 'u.id = p.authorid' )
				->where( 'p.ispublic', TRUE )
				->where( 'p.authorid', $author_id );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'p.posttime', 'desc' )
						->get()
						->result_array();
	}

	/**
	 * 獲取指定作者放回收站的文章數據
	 * @param int $author_id
	 * @param int $per_page
	 * @param int $offset
	 * @return array
	 */
	public function get_trash_by_authorid( $author_id, $per_page = 0, $offset = 0 )
	{
		$query = $this->db->select(
						'p.id,p.title,p.urltitle,
						 p.categoryid,p.content,
						 p.authorid,p.click,p.good,
						 p.bad,p.posttime,p.ispublic,p.isdraft,
						 c.category,
						 u.name as author'
				)
				->from( "{$this->_tables['post']} as p" )
				->join( "{$this->_tables['category']} as c", 'c.id = p.categoryid' )
				->join( "{$this->_tables['user']} as u", 'u.id = p.authorid' )
				->where( 'p.istrash', TRUE )
				->where( 'p.authorid', $author_id );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'p.id', 'desc' )
						->get()
						->result_array();
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
						 u.name as author,
						 u.email as authoremail,
						 u.url as authorurl'
				)
				->from( "{$this->_tables['post']} as p" )
				->join( "{$this->_tables['category']} as c", 'c.id = p.categoryid' )
				->join( "{$this->_tables['user']} as u", 'u.id = p.authorid' )
				->where( 'p.ispublic', TRUE );
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
		return $this->db->select( 'a.id,a.name,a.suffix,a.size,a.addtime,a.isthumbnail' )
						->from( "{$this->_tables['post_attachment']} as pa" )
						->join( "{$this->_tables['attachment']} as a", 'a.id = pa.attachmentid' )
						->where( 'pa.postid', $post_id )
						->where( 'a.isimage', TRUE )
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
				->where( 'p.ispublic', TRUE )
				->where( 'c.id', $category_id );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'p.posttime', 'desc' )
						->get()
						->result_array();
	}

	/**
	 * 根據指定的標籤ID相關的文章數據
	 * @param int $tag_id 標籤ID
	 * @param int $per_page 每页显示条数
	 * @param int $offset 游标
	 * @return array
	 */
	public function get_by_tag( $tag_id, $per_page = 5, $offset = 0 )
	{
		$query = $this->db->select(
						'p.id,p.title,p.urltitle,
						 p.categoryid,p.content,
						 p.authorid,p.click,p.good,
						 p.bad,p.posttime,
						 c.category,
						 u.name as author'
				)
				->from( "{$this->_tables['post_tag']} as pt" )
				->join( "{$this->_tables['post']} as p", 'p.id = pt.postid' )
				->join( "{$this->_tables['category']} as c", 'c.id = p.categoryid' )
				->join( "{$this->_tables['user']} as u", 'u.id = p.authorid' )
				->where( 'p.ispublic', TRUE )
				->where( 'pt.tagid', $tag_id );
		if ( $per_page ) $query->limit( $per_page, $offset );
		return $query->order_by( 'p.posttime', 'desc' )
						->get()
						->result_array();
	}

    /**
	 * 獲取指定作者的文章總數
	 * @param int $author_id 作者ID
	 * @return int
	 */
    public function total_by_authorid( $author_id )
    {
        return $this->db->where( 'ispublic', TRUE )
						->where( 'authorid', $author_id )
						->count_all_results( $this->_tables['post'] );
    }

	/**
	 * 獲取指定類別的文章總數
	 * @param int $category_id 類別ID
	 * @return int
	 */
	public function total_by_category( $category_id )
	{
		return $this->db->where( 'ispublic', TRUE )
						->where( 'categoryid', $category_id )
						->count_all_results( $this->_tables['post'] );
	}

	/**
	 * 根據標籤ID獲取涉及的文章總數
	 * @param int $tag_id
	 * @return int
	 */
	public function total_by_tag( $tag_id )
	{
		return $this->db->from( "{$this->_tables['post_tag']} as pt" )
						->join( "{$this->_tables['post']} as p", 'p.id = pt.postid' )
						->where( 'pt.tagid', $tag_id )
						->where( 'p.ispublic', TRUE )
						->count_all_results();
	}

	/**
	 * 在某個時間區間的文章總數
	 * @param int $lower
	 * @param int $upper
	 * @return int
	 */
	public function total_posttime_between( $lower, $upper )
	{
		return $this->db->where( 'ispublic', TRUE )
						->where( 'posttime >=', $lower )
						->where( 'posttime <', $upper )
						->count_all_results( $this->_tables['post'] );
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
				->where( 'p.ispublic', TRUE )
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
				->where( 'p.ispublic', TRUE )
				->where( "p.good >", 0 )
				->order_by( 'p.good', 'desc' );
		if ( $limit ) $query->limit( $limit );
		return $query->get()->result_array();
	}

	/**
	 * 讓指定文章的好評數+1
	 * @param int $post_id
	 */
	public function update_good( $post_id )
	{
		$this->db->set( 'good', 'good+1', FALSE )
				->where( 'id', $post_id )
				->update( $this->_tables['post'] );
	}

	/**
	 * 讓指定文章的點擊數+1
	 * @param type $post_id
	 */
	public function update_click( $post_id )
	{
		$this->db->set( 'click', 'click+1', FALSE )
				->where( 'id', $post_id )
				->update( $this->_tables['post'] );
	}

	/**
	 * 讓指定文章的差評數+1
	 * @param int $post_id
	 */
	public function update_bad( $post_id )
	{
		$this->db->set( 'bad', 'bad+1', FALSE )
				->where( 'id', $post_id )
				->update( $this->_tables['post'] );
	}

	/**
	 * 標記指定文章為垃圾
	 * @param int $post_id
	 * @return int
	 */
	public function update_trash( $post_id )
	{
		$update_data = array(
			'istrash' => TRUE,
			'ispublic' => FALSE,
		);
		$this->db->where( 'id', $post_id )
				->update( $this->_tables['post'], $update_data );
		return $this->db->affected_rows();
	}

	/**
	 * 撤銷指定文章
	 * @param int $post_id
	 * @return int
	 */
	public function update_trash_revoke( $post_id )
	{
		$update_data = array(
			'istrash' => FALSE,
			'ispublic' => TRUE,
		);
		$this->db->where( 'id', $post_id )
				->update( $this->_tables['post'], $update_data );
		return $this->db->affected_rows();
	}

	/**
	 * 更新
	 * @param array $data
	 * @param int $post_id
	 * @return int 影響行數
	 */
	public function update( $data, $post_id )
	{
		$update_data = array(
			'title' => $data['title'],
			'urltitle' => $data['urltitle'],
			'categoryid' => $data['categoryid'],
			'content' => $data['content'],
			'ispublic' => isset( $data['ispublic'] ) ? $data['ispublic'] : 1,
			'savetime' => isset( $data['savetime'] ) ? $data['savetime'] : time(),
		);
		if ( isset( $data['authorid'] ) ) $update_data['authorid'] = $data['authorid'];
		if ( isset( $data['click'] ) ) $update_data['click'] = $data['click'];
		if ( isset( $data['good'] ) ) $update_data['good'] = $data['good'];
		if ( isset( $data['bad'] ) ) $update_data['bad'] = $data['bad'];
		if ( isset( $data['posttime'] ) ) $update_data['posttime'] = $data['posttime'];
		$this->db->where( 'id', $post_id )
				->update( $this->_tables['post'], $update_data );
		return $this->db->affected_rows();
	}

	/**
	 * 更新指定文章為不是草稿
	 * @param int $post_id 文章ID
	 */
	public function update_undraft( $post_id )
	{
		$update_data = array(
			'isdraft' => FALSE,
		);
		$this->db->where( 'id', $post_id )
				->update( $this->_tables['post'], $update_data );
		return $this->db->affected_rows();
	}

	/**
	 * 更新指定的類別文章變為另一個類別
	 * @param int $cur_category
	 * @param int $to_category
	 * @return int 影響行數
	 */
	public function update_category_to_category( $cur_category, $to_category )
	{
		$update_data = array(
			'categoryid' => $to_category,
		);
		$this->db->where( 'categoryid', $cur_category )
				->update( $this->_tables['post'], $update_data );
		return $this->db->affected_rows();
	}

	/**
	 * 插入文章數據
	 * @param array $data
	 * @return int
	 */
	public function insert( $data )
	{
		$insert_data = array(
			'title' => $data['title'],
			'urltitle' => $data['urltitle'],
			'categoryid' => $data['categoryid'],
			'content' => $data['content'],
			'authorid' => $data['authorid'],
			'ispublic' => isset( $data['ispublic'] ) ? $data['ispublic'] : 1,
			'click' => isset( $data['click'] ) ? $data['click'] : 0,
			'good' => isset( $data['good'] ) ? $data['good'] : 0,
			'bad' => isset( $data['bad'] ) ? $data['bad'] : 0,
			'savetime' => isset( $data['savetime'] ) ? $data['savetime'] : time(),
			'posttime' => isset( $data['posttime'] ) ? $data['posttime'] : time(),
			'isdraft' => isset( $data['isdraft'] ) ? $data['isdraft'] : 0,
		);
		$this->db->insert( $this->_tables['post'], $insert_data );
		return $this->db->insert_id();
	}

	/**
	 * 解除指定文章所有附件的關係
	 * @param int $post_id
	 * @return int
	 */
	public function delete_attachment( $post_id )
	{
		$this->db->where( 'postid', $post_id )
				->delete( $this->_tables['post_attachment'] );
		return $this->db->affected_rows();
	}

	/**
	 * 根據文章ID刪除該文章數據
	 * @param int $post_id 文章ID
	 * @return int 影響行數
	 */
	public function delete_by_id( $post_id )
	{
		$this->db->where( 'id', $post_id )
				->delete( $this->_tables['post'] );
		return $this->db->affected_rows();
	}

	/**
	 * 建立指定文章與指定文件的關係
	 * @param array $file_ids
	 * @param int $post_id
	 * @return int
	 */
	public function insert_attachment( $file_ids, $post_id )
	{
		$insert_data = array( );
		if ( is_array( $file_ids ) )
		{
			foreach ( $file_ids as $file_id )
				$insert_data[] = array(
					'postid' => $post_id,
					'attachmentid' => $file_id,
				);
		}
		else
		{
			$insert_data[] = array(
				'postid' => $post_id,
				'attachmentid' => $file_id,
			);
		}
		$this->db->insert_batch( $this->_tables['post_attachment'], $insert_data );
		return $this->db->affected_rows();
    }

	/**
	 * 獲取指定作者放入回收站的文章總數
	 * @param int $author_id
	 * @return int
	 */
	public function total_trash_by_authorid( $author_id )
	{
		return $this->db->where( 'authorid', $author_id )
						->where( 'istrash', TRUE )
						->count_all_results( $this->_tables['post'] );
	}

	/**
	 * 根據作者ID獲取其文章總數
	 * @param mixed $author_ids
	 * @return array
	 */
	public function total_by_authorids( $author_ids )
	{
		return $this->db->select('COUNT(*) as total,authorid', TRUE)
						->from( $this->_tables['post'] )
						->where_in('authorid', $author_ids)
						->where( 'istrash', FALSE )
						->group_by( 'authorid' )
						->get()
						->result_array();
	}

}

// end of Post_model