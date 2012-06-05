<?php

/**
 * 2012-3-18 17:34:28
 * 第三方应用模块公用控制器
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/modules/third_party/controllers/
 */
class Third_party_Common_Module extends CI_Module
{

	/**
	 * 微博授权
	 */
	public function weibo_auth()
	{
		session_start();

		$this->load->helper('saetv2');

		$o = new SaeTOAuthV2( config_item( 'weibo_akey' ), config_item( 'weibo_skey' ) );
		$code_url = $o->getAuthorizeURL( config_item( 'weibo_callback' ) );
		echo "<a href=\"{$code_url}\">go</a>";
	}

	/**
	 * 微博授权回调
	 */
	public function weibo_callback()
	{
		session_start();

		$this->load->helper('saetv2');

		$o = new SaeTOAuthV2( config_item( 'weibo_akey' ), config_item( 'weibo_skey' ) );
		if ( isset( $_REQUEST['code'] ) ) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = config_item( 'weibo_callback' );
			try {
				$token = $o->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}
		
		if ( isset( $token ) && $token )
		{
			$_SESSION['token'] = $token;
			$c = new SaeTClientV2( config_item( 'weibo_akey' ), config_item( 'weibo_skey' ) , $_SESSION['token']['access_token'] );
			$ms  = $c->home_timeline();
			print_r( $ms );
		}
		else
		{
			exit('failed');
		}
	}

}

// end of common