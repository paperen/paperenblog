<?php

/**
 * 作為查詢緩存，避免不同模塊執行相同SQL導致性能下降一個組件
 * 依賴于CI模型
 * @author paperen
 */
class Querycache
{

	private $_CI;
	private $_cache;

	function __construct()
	{
		$this->_CI = & get_instance();
	}

	/**
	 * 直接執行模型操作
	 * @param string $model_name 模型名
	 * @param string $method 方法
	 * @param mixed $args 參數
	 * @return mixed
	 */
	public function execute( $model, $method, $args )
	{
		$model_name = "{$model}_model";
		if ( !isset( $this->_CI->$model_name ) ) $this->_CI->load->model( $model_name );
		return call_user_func_array( array( $this->_CI->$model_name, $method ), $args );
	}

	/**
	 *
	 * @param string $model 模型名(不需要加_model)
	 * @param string $method 方法
	 * @return mixed
	 */
	public function get( $model, $method )
	{
		// 获取参数数据
		$args = func_get_args();
		$model_and_method = array_shift( $args ) . '_' . array_shift( $args );

		// 索引
		$hash = "{$model_and_method}_" . serialize( $args );

		// 存在缓存直接返回
		if ( isset( $this->_cache[$hash] ) ) return $this->_cache[$hash];

		// 通过模型获取数据
		$result = $this->execute( $model, $method, $args, TRUE );

		// 缓存起来
		$this->_cache[$hash] = $result;
		return $result;
	}

}

// end of Querycache