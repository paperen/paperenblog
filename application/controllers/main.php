<?php

/**
 * 2012-3-18 17:04:25
 * 单一控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/controllers
 */
class Main extends MY_Controller
{

	/**
	 * 首页
	 * @param int $offset 偏移
	 */
	public function index( $offset = 0 )
	{
		$data = array(
			'offset' => $offset,
		);
		$this->load->view( 'home', $data );
	}

	/**
	 * 404頁面
	 */
	public function not_found()
	{
		echo '404';
	}

	/**
	 * 显示指定ID或者URL标题的文章
	 * @param string $postid_or_urltitle ID或者URL标题
	 */
	public function post( $postid_or_urltitle )
	{
		$post_data = $this->load->module( 'post/common/get_by_postid_or_urltitle', $postid_or_urltitle, TRUE );

		// 如果文章數據為空 404
		if ( empty( $post_data ) ) show_404();

		$data = array(
			'postid_or_urltitle' => $postid_or_urltitle,
			'post_data' => $post_data,
		);
		$this->load->view( 'post', $data );
	}

	/**
	 * 獲取指定ID的附件數據
	 * @param int $attachment_id 附件ID
	 */
	public function file( $attachment_id )
	{
		try
		{
			// 附件ID
			$attachment_id = intval( $attachment_id );
			if ( empty( $attachment_id ) ) throw new Exception( '錯誤操作', 0 );

			// 附件數據
			$attachment_data = $this->querycache->get( 'attachment', 'get_by_id', $attachment_id );
			if ( empty( $attachment_data ) ) throw new Exception( '附件數據不存在', -1 );

			// 文件路徑
			$file_path = config_item( 'upload_path' ) . $attachment_data['path'];
			if ( !file_exists( $file_path ) ) throw new Exception( '附件路徑錯誤', -2 );

			$this->output->set_content_type( $attachment_data['suffix'] )->set_output( file_get_contents( $file_path ) );
		}
		catch ( Exception $e )
		{
			//@todo
		}
	}

	/**
	 * 發表評論接收器
	 */
	public function comment()
	{
		// 調用評論模塊處理
		$this->load->module( 'comment/common/add', array( ) );
	}

}

// end of home