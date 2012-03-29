<?php

/**
 * 2012-3-30 0:23:36
 * 重寫CI異常類
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/core/
 */

class MY_Exceptions extends CI_Exceptions
{
	/**
	 * 404處理重定向
	 */
	public function show_404()
	{
		redirect( base_url('404') );
	}
}

// end of MY_Exceptions