<?php

/**
 * 2012-3-18 17:34:28
 * 文件模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/post/controllers/
 */
class File_Common_Module extends CI_Module
{
	/**
	 * 獲取指定ID的附件數據
	 * @param int $attachment_id 附件ID
	 */
	public function get( $attachment_id )
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
}

// end of common