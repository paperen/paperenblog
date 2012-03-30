<?php

/**
 * 作為博客的鉤子，用於在其他模塊或者控制器中為其他模塊增加內容或元素
 * 依賴于CI模型
 * @author paperen
 */
class Hook
{

	/**
	 * 挂入点
	 * @var array
	 */
	private $_hook_points = array( );

	/**
	 * CI實例
	 * @var object
	 */
	private $_CI;

	function __construct()
	{
		$this->_CI = & get_instance();
	}

	/**
	 * 註冊挂入点
	 * @param string $hp 挂入点
	 * @param string $name 模塊+控制器(module_開頭) 其他 函數調用
	 * @param mixed $args 參數
	 */
	public function register( $hp, $name, $args = array( ) )
	{
		$this->_hook_points[$hp][] = array(
			'name' => $name,
			'args' => $args,
		);
	}

	/**
	 * 執行hook
	 * @param string $hp 挂入点
	 */
	public function trigger( $hp )
	{
		if ( !isset( $this->_hook_points[$hp] ) || empty( $this->_hook_points[$hp] ) ) return FALSE;
		foreach ( $this->_hook_points[$hp] as $single )
		{
			$name = $single['name'];
			$args = $single['args'];
			if ( strpos( $name, 'module_' ) !== FALSE )
			{
				// 模塊調用
				$this->_CI->load->module( str_replace( 'module_', '', $name ), $args );
			}
			else
			{
				// 函數調用
				call_user_func( $name, $args );
			}
		}
	}

}

// end of Hook