<?php

/**
 * 2012-3-18 17:34:28
 * 靜態模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/tag/controllers/
 */
class Static_Common_Module extends CI_Module
{

	/**
	 * 404頁面
	 */
	public function not_found()
	{
		$data = array( );
		$this->load->view( '404', $data );
	}

	/**
	 * 錯誤頁面
	 */
	public function error()
	{
		$data = array( );
		$this->load->view( 'error', $data );
	}

	/**
	 * 輸出kindeditor的配置
	 */
	public function kindeditor_config()
	{
		$config = array(
			'width' => '100%',
			'height' => '650px',
			'items' => array(
				'fullscreen', 'source', '|', 'preview',
				'justifyleft', 'justifycenter', 'justifyright',
				'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
				'superscript', 'clearhtml', '|',
				'formatblock', 'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold',
				'italic', 'underline', 'strikethrough', 'removeformat', '|', 'image',
				'table', 'hr', 'map', 'code', 'pagebreak', 'anchor', 'link', 'unlink'
			),
			'resizeType' => 1,
			'uploadJson' => '../upload/',
			'fileManagerJson' => './file_manager/',
			'allowFileManager' => true,
			'allowUpload' => true,
			'newlineTag' => 'p',
		);
		echo '<script>var DEFAULT_OPTIONS = ' . stripslashes( json_encode( $config ) ) . ';</script>';
	}

	public function kindeditor_mini_config()
	{
		$config = array(
			'width' => '100%',
			'height' => '350px',
			'items' => array(
				'clearhtml',
				'formatblock', 'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold',
				'italic', 'underline', 'strikethrough', 'removeformat', 'code',
			),
			'resizeType' => 0,
			'allowFileManager' => false,
			'allowUpload' => false,
			'newlineTag' => 'p',
		);
		echo '<script>var DEFAULT_OPTIONS = ' . stripslashes( json_encode( $config ) ) . ';</script>';
	}

}

// end of common