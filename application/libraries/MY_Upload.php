<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 擴展CodeIgniter自帶的上傳類
 * 添加rollback功能
 *
 */

class MY_Upload extends CI_Upload
{
	/**
	 * 在上傳前判斷是否是圖片
	 * @param string $field_name 文件控件名
	 */
	public function is_image_before_upload( $field_name )
	{
		if ( isset( $_FILES[$field_name] ) && $_FILES[$field_name] !== FALSE )
		{
			return !( strpos($_FILES[$field_name]['type'], 'image') === FALSE );
		}
		return FALSE;
	}

	/**
	 * 回滾（刪除上傳的文件）
	 */
	public function rollback()
	{
		@unlink( $this->upload_path.$this->file_name );
	}
}

/* End of file Upload.php */
/* Location: ./system/libraries/Upload.php */
