<?php

/**
 * 2012-3-18 17:34:28
 * 头部模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/header/controllers/
 */
class Header_Common_Module extends CI_Module
{

	/**
	 * 导航栏
	 * @var array
	 */
	private $_nav;

	function __construct()
	{
		parent::__construct();
		$this->_nav = array(
			'home' => array(
				'text' => '首页',
				'url' => base_url(),
			),
			'archive' => array(
				'text' => '归档',
				'url' => base_url( 'archive' ),
			),
			'tag' => array(
				'text' => '标签',
				'url' => base_url( 'tag' ),
			),
			'author' => array(
				'text' => '作者',
				'url' => base_url('author'),
			),
			'about' => array(
				'text' => '关于',
				'url' => base_url( 'about' ),
			),
		);
	}

	/**
	 * 加载头部
	 * @param string $page_title 页面标题
	 * @param array $extra_css 额外加载css
	 */
	public function index( $active = 'home', $page_title = '', $extra_css = array( ) )
	{
		$data = array(
			'active' => $active,
			'page_title' => $page_title,
			'extra_css' => $extra_css,
			'nav' => $this->_nav,
		);
		$this->load->view( 'index', $data );
	}

}

// end of common