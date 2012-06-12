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

	/**
	 * 微博授权
	 */
	public function weibo_auth()
	{
		$token = $this->adminverify->token;
		if ( empty( $token ) )
		{
			$this->load->helper( 'saetv2' );

			$o = new SaeTOAuthV2( config_item( 'weibo_akey' ), config_item( 'weibo_skey' ) );
			$code_url = $o->getAuthorizeURL( config_item( 'weibo_callback' ) );
			$data['url'] = $code_url;
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

			$o = new SaeTOAuthV2( config_item( 'weibo_akey' ), config_item( 'weibo_skey' ) );
			if ( isset( $_REQUEST['code'] ) )
			{
				$keys = array( );
				$keys['code'] = $_REQUEST['code'];
				$keys['redirect_uri'] = config_item( 'weibo_callback' );
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
			$lat = rand( 0, 90 );
			$long = rand( 0, 180 );

			$this->load->helper( 'saetv2' );
			$c = new SaeTClientV2( config_item( 'weibo_akey' ), config_item( 'weibo_skey' ), $this->adminverify->token );
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

	/**
	 * 更新微博授权令牌
	 */
	public function _update_weibo_token( $token )
	{
		$this->querycache->execute( 'user', 'update_token', array( $token, $this->adminverify->id ) );
	}

}

// end of common