<?php

/**
 * 2012-3-18 17:34:28
 * 標籤模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/tag/controllers/
 */
class Tag_Common_Module extends CI_Module
{

	public function index()
	{
		$data = array( );
		$tag_data = $this->querycache->tag('tag')->get( 'tag', 'get_all', 0 );
		$data['tag_data'] = $tag_data;
		$this->load->view( 'index', $data );
	}

}

// end of common