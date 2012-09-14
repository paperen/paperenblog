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
class Third_party_Common_Module extends MY_Module
{

	private $_config;

	function __construct()
	{
		parent::__construct();
		$this->_config = config_item( 'weibo' );
	}

	private function _SaeTClientV2()
	{
		$this->load->helper( 'saetv2' );
		return new SaeTClientV2( $this->_config['weibo_akey'], $this->_config['weibo_skey'], $this->adminverify->token );
	}

	/**
	 * 微博授权
	 */
	public function weibo_auth()
	{
		$token = $this->adminverify->token;
		if ( empty( $token ) )
		{
			$this->load->helper( 'saetv2' );

			$o = new SaeTOAuthV2( $this->_config['weibo_akey'], $this->_config['weibo_skey'] );
			$code_url = $o->getAuthorizeURL( $this->_config['weibo_callback'] );
			redirect( $code_url );
		}
		else
		{
			$data['already_sync'] = TRUE;
		}
		$this->load->view( 'weibo_auth', $data );
	}

	/**
	 * 微博授权回调
	 */
	public function weibo_callback()
	{
		if ( empty( $this->adminverify->token ) )
		{
			$this->load->helper( 'saetv2' );

			$o = new SaeTOAuthV2( $this->_config['weibo_akey'], $this->_config['weibo_skey'] );
			if ( isset( $_REQUEST['code'] ) )
			{
				$keys = array( );
				$keys['code'] = $_REQUEST['code'];
				$keys['redirect_uri'] = $this->_config['weibo_callback'];
				try
				{
					$token = $o->getAccessToken( 'code', $keys );
				}
				catch ( OAuthException $e )
				{
					$data['err'] = $e->getMessage();
				}
			}

			if ( isset( $token ) && $token )
			{
				$this->adminverify->token = $token['access_token'];
				$this->_update_weibo_token( $token['access_token'] );
			}
		}
		$data['success'] = TRUE;
		$this->load->view( 'weibo_callback', $data );
	}

	/**
	 * 发送微博
	 */
	public function weibo_post()
	{
		if ( $this->input->post( 'submit_btn' ) && $this->form_validation->check_token() )
		{
			$post = $this->input->post( 'post' );
			$image = ( isset( $_FILES['image'] ) && $_FILES['image']['name'] ) ? $_FILES['image'] : $this->input->post( 'image_url' );
			$lat = 22.5;
			$long = 114;

			$c = $this->_SaeTClientV2();
			if ( $image )
			{
				$c->upload( $post, $image, $lat, $long );
			}
			else
			{
				$c->update( $post, $lat, $long );
			}
		}
		redirect( base_url( 'weibo_auth' ) );
	}

	public function weibo_api_update( $text, $image_or_url, $lat = NULL, $long = NULL )
	{
		try
		{
			$c = $this->_SaeTClientV2();
			if ( $image_or_url )
			{
				$c->upload( $text, $image_or_url, $lat, $long );
			}
			else
			{
				$c->update( $text, $lat, $long );
			}
			return TRUE;
		}
		catch( Exception $e )
		{
			return FALSE;
		}
	}

	/**
	 * 更新微博授权令牌
	 */
	public function _update_weibo_token( $token )
	{
		$this->querycache->execute( 'user', 'update_token', array( $token, $this->adminverify->id ) );
    }

}

// end of common