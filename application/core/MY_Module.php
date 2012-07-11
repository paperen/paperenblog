<?php

if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

/**
 * 繼承模塊類
 */
class MY_Module extends CI_Module
{

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library( 'adminverify' );

		// 非法用戶
		if ( !$this->adminverify->valid() ) redirect( base_url( 'login' ) );
	}

}

// END Module Class

/* End of file MY_Module.php */
/* Location: ./application/core/MY_Module.php */
