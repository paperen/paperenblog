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
	private $_hook_points = array();

	/**
	 * CI實例
	 * @var object
	 */
	private $_CI;

	function __construct()
	{
		$this->_CI =& get_instance();
	}

	/**
	 * 註冊挂入点
	 * @param string $hp 挂入点
	 * @param string $module 模塊+控制器+方法名稱
	 * @param mixed $args 參數
	 */
	public function register_hook_point( $hp, $module, $args = array() )
	{
		$this->_hook_points[$hp][] = array(
			'mod' => $module,
			'args' => $args,
		);
	}

	/**
	 * 執行hook
	 * @param string $hp 挂入点
	 */
	public function hook( $hp )
	{

	}
}

// end of Hook