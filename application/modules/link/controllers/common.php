<?php

/**
 * 2012-3-18 17:34:28
 * 友鏈模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/sidebar/controllers/
 */
class Link_Common_Module extends CI_Module
{

	/**
	 * 友情链接
	 */
	public function all()
	{
		$data = array( );

		$links = $this->querycache->get( 'link', 'get_all', NULL );
		$data['links'] = $links;

		$this->load->view( 'all', $data );
	}

}
// end of common