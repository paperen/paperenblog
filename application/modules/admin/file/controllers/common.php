<?php

/**
 * 2012-4-25 00:44:28
 * 管理平台文件公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/admin/file/controllers/
 */
class Admin_File_Common_Module extends MY_Module
{

	private $_upload_image_path = './image/';
	private $_upload_file_path = './file/';
	private $_upload_subpath;
	private $_field_name = 'imgFile';

	/**
	 * 處理文件上傳
	 */
	public function upload()
	{
		$error = $file_url = $message = '';
		$this->load->library( 'upload' );

		// 是否是thumbnail
		$isthumbnail = $this->input->post( 'isthumbnail' );

		// 是否是圖片
		$isimage = $this->upload->is_image_before_upload( $this->_field_name );
		$this->_upload_subpath = ($isimage) ? $this->_upload_image_path : $this->_upload_file_path;
		$upload_path = $this->upload->upload_path . $this->_upload_subpath;
		// 設置準確的上傳位置
		$this->upload->set_upload_path( $upload_path );

		// 上傳
		if ( !$this->upload->do_upload( $this->_field_name ) )
		{
			// 上傳失敗
			$error = 1;
			$message = $this->upload->display_errors();
		}
		else
		{
			// 上傳成功
			$upload_data = $this->upload->data();
			$insert_data = array(
				'name' => get_file_rawname( $upload_data['client_name'] ),
				'path' => strtolower( $this->_upload_subpath . $upload_data['file_name'] ),
				'suffix' => trim( $upload_data['file_ext'], '.' ),
				'size' => $upload_data['file_size'],
				'isimage' => $upload_data['is_image'],
				'isthumbnail' => $isthumbnail,
				'userid' => $this->adminverify->id,
			);
			$attachment_id = $this->querycache->execute( 'attachment', 'insert', array( $insert_data ) );
			if ( $attachment_id > 0 )
			{
				// 插入數據庫成功
				$error = 0;
				$file_url = file_url( $attachment_id );
			}
			else
			{
				$error = 1;
				$message = '系統出錯了，請重試看看';
				$this->upload->rollback();
			}
		}
		echo json_encode( array(
			'error' => $error,
			'url' => $file_url,
			'message' => strip_tags( $message ),
		) );
		exit;
	}

	/**
	 * 顯示服務器上傳目錄文件列表
	 */
	public function manager()
	{
		$php_path = dirname( __FILE__ ) . '/';
		$php_url = dirname( $_SERVER['PHP_SELF'] ) . '/';

//根目录路径，可以指定绝对路径，比如 /var/www/attached/
		$root_path = $php_path . './upload/';
//根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
		$root_url = $php_url . './upload/';
//图片扩展名
		$ext_arr = array( 'gif', 'jpg', 'jpeg', 'png', 'bmp' );

//目录名
		$dir_name = empty( $_GET['dir'] ) ? '' : trim( $_GET['dir'] );
		if ( !in_array( $dir_name, array( '', 'image', 'flash', 'media', 'file' ) ) )
		{
			echo "Invalid Directory name.";
			exit;
		}
		if ( $dir_name !== '' )
		{
			$root_path .= $dir_name . "/";
			$root_url .= $dir_name . "/";
			if ( !file_exists( $root_path ) )
			{
				//mkdir( $root_path );
			}
		}

//根据path参数，设置各路径和URL
		if ( empty( $_GET['path'] ) )
		{
			$current_path = realpath( $root_path ) . '/';
			$current_url = $root_url;
			$current_dir_path = '';
			$moveup_dir_path = '';
		}
		else
		{
			$current_path = realpath( $root_path ) . '/' . $_GET['path'];
			$current_url = $root_url . $_GET['path'];
			$current_dir_path = $_GET['path'];
			$moveup_dir_path = preg_replace( '/(.*?)[^\/]+\/$/', '$1', $current_dir_path );
		}
		echo realpath( $root_path );
//排序形式，name or size or type
		$order = empty( $_GET['order'] ) ? 'name' : strtolower( $_GET['order'] );

//不允许使用..移动到上一级目录
		if ( preg_match( '/\.\./', $current_path ) )
		{
			echo 'Access is not allowed.';
			exit;
		}
//最后一个字符不是/
		if ( !preg_match( '/\/$/', $current_path ) )
		{
			echo 'Parameter is not valid.';
			exit;
		}
//目录不存在或不是目录
		if ( !file_exists( $current_path ) || !is_dir( $current_path ) )
		{
			echo 'Directory does not exist.';
			exit;
		}

//遍历目录取得文件信息
		$file_list = array( );
		if ( $handle = opendir( $current_path ) )
		{
			$i = 0;
			while ( false !== ($filename = readdir( $handle )) )
			{
				if ( $filename{0} == '.' ) continue;
				$file = $current_path . $filename;
				if ( is_dir( $file ) )
				{
					$file_list[$i]['is_dir'] = true; //是否文件夹
					$file_list[$i]['has_file'] = (count( scandir( $file ) ) > 2); //文件夹是否包含文件
					$file_list[$i]['filesize'] = 0; //文件大小
					$file_list[$i]['is_photo'] = false; //是否图片
					$file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
				}
				else
				{
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize( $file );
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower( array_pop( explode( '.', trim( $file ) ) ) );
					$file_list[$i]['is_photo'] = in_array( $file_ext, $ext_arr );
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename; //文件名，包含扩展名
				$file_list[$i]['datetime'] = date( 'Y-m-d H:i:s', filemtime( $file ) ); //文件最后修改时间
				$i++;
			}
			closedir( $handle );
		}

//排序
		function cmp_func( $a, $b )
		{
			global $order;
			if ( $a['is_dir'] && !$b['is_dir'] )
			{
				return -1;
			}
			else if ( !$a['is_dir'] && $b['is_dir'] )
			{
				return 1;
			}
			else
			{
				if ( $order == 'size' )
				{
					if ( $a['filesize'] > $b['filesize'] )
					{
						return 1;
					}
					else if ( $a['filesize'] < $b['filesize'] )
					{
						return -1;
					}
					else
					{
						return 0;
					}
				}
				else if ( $order == 'type' )
				{
					return strcmp( $a['filetype'], $b['filetype'] );
				}
				else
				{
					return strcmp( $a['filename'], $b['filename'] );
				}
			}
		}

		usort( $file_list, 'cmp_func' );

		$result = array( );
//相对于根目录的上一级目录
		$result['moveup_dir_path'] = $moveup_dir_path;
//相对于根目录的当前目录
		$result['current_dir_path'] = $current_dir_path;
//当前目录的URL
		$result['current_url'] = $current_url;
//文件数
		$result['total_count'] = count( $file_list );
//文件列表数组
		$result['file_list'] = $file_list;

//输出JSON字符串
		header( 'Content-type: application/json; charset=UTF-8' );
		echo json_encode( $result );
	}

	/**
	 * 我的附件
	 */
	public function my( $page = 1 )
	{
		$data = array( );

		// 每頁顯示條數
		$per_page = config_item( 'per_page' );

		// 當前人的附件總數
		$total = $this->querycache->get( 'attachment', 'total_by_userid', $this->adminverify->id );
		$data['total'] = $total;

		// 分頁
		$this->load->library( 'pagination' );
		$pagination_config = array(
			'base_url' => base_url( 'my_file' ),
			'total_rows' => $total,
			'per_page' => $per_page,
			'uri_segment' => 2,
		);
		$this->pagination->initialize( $pagination_config );
		$pagination = $this->pagination->create_links();
		$data['pagination'] = $pagination;

		// 數據
		$attachment_data = $this->querycache->get( 'attachment', 'get_by_authorid', $this->adminverify->id, $per_page, ( $this->pagination->get_cur_page() - 1 ) * $per_page );
		$data['attachment_data'] = $attachment_data;

		$this->load->view( 'list', $data );
	}

}

// end of common