<?php

/**
 * level
 * 多权限分配类
 * @author 高阳 yunnysunny@sohu.com qq243853184
 * @version 1.2
 * @copyright 任何人都可以使用本类的全部和部分代码，但是要保留以上信息
 */
class Level
{

	private $mLevelNums;//当前角色个数
	public $NONE = -1;//没有权限
	public static $ADMIN = 2;//管理员
	public static $EDITOR = 1;//编辑
	public static $USER = 0;//普通用户

	public function GetAllRole()
	{
		return array(
			//self::$USER => '普通用户',
			self::$EDITOR => '编辑',
			self::$ADMIN => '管理员',
		);
	}

	public function __construct()
	{
		$obj = & get_instance();
		$this->mLevelNums = 3;
	}

	/* 函数名:CheckLevel()
	  //参数:$levelString--------数据库中权限十进制
	  //作用：检测权限
	  //返回值：1.执行成功返回权限数组
	  //		  2.执行不成功，返回0
	 */

	public function GetLevel( $level )
	{
		$levelString = decbin( $level );//十进制转化二进制
		$levelArray = str_split( $levelString );
		$len = count( $levelArray );
		if ( $len < $this->mLevelNums )
		{
			$head = array( );
			for ( $i = 0; $i < $this->mLevelNums - $len; $i++ )
			{
				$head[$i] = 0;
			}
		}
		else
		{
			$head = array( );
		}
		$levelArray = array_merge( $head, $levelArray );
		$j = 0;
		//echo $this->mLevelNums;
		for ( $i = $this->mLevelNums - 1; $i >= 0; $i-- )
		{
			if ( $levelArray[$i] == 1 )
			{//拥有$i号权限
				$returnArray[$j] = $this->mLevelNums - $i - 1;
				$j++;
			}
			else
			{//不存在$i号权限
				continue;
			}
		}
		if ( $j == 1 ) return $returnArray[0];
		return ($j > 0) ? $returnArray : -1;
	}

	/*
	  函数名：SetSingleLevel()
	  参数：$singleLevel---------单个权限值，取值为0~$mLevelNums
	  作用：设置一个权限,可用在初始化新用户时使用
	  返回值：返回权限二进制


	 */

	public function SetSingleLevel( $singleLevel )
	{

		$level = "";
		for ( $i = 0; $i < $this->mLevelNums; $i++ )
		{
			if ( $i != $singleLevel )
			{//数字前移
				$level = "0" . $level;
			}
			else
			{
				$level = "1" . $level;
			}
		}
		return $level;
	}

	/*
	  函数名：SetLevel()
	  参数：$levelData---------取单个值权限为0~$mLevelNums-1
	  作用：设置单个或多个权限限值，可用在初始化新用户或给用户添加新的权限
	  返回值：返回权限十进制
	 */

	public function SetLevel( $levelData )
	{
		if ( !is_array( $levelData ) )
		{//单个值
			//$levelData=decbin($levelData);
			return bindec( $this->SetSingleLevel( $levelData ) );//将二进制转化为十进制
		}
		else
		{//数组
			$level = "0";
			foreach ( $levelData as $data )
			{
				//$data=decbin($data);
				$level = $level | $this->SetSingleLevel( $data );
			}
			return bindec( $level );
		}
	}

	public function GetSingleLevelString( $singleString )
	{
		switch ( $singleString )
		{
			case 2:
				return '管理员';
				break;
			case 1:
				return '作者';
				break;
			case 0:
				return '讀者';
				break;
			default:
				return '获取权限错误';
		}
	}

	/**
	  函数名：GetLevelAsString
	  参数：$level-------------数据库中权限十进制
	  返回值：权限的字符串说明

	 */
	public function GetLevelAsString( $level )
	{
		//$levelString=decbin($level);
		$result = $this->GetLevel( $level );
		$returnString = '';
		if ( $result == -1 )
		{
			$returnString = '无任何权限';
		}
		else
		{
			if ( !is_array( $result ) )
			{
				$returnString = $this->GetSingleLevelString( $result );
			}
			else
			{
				foreach ( $result as $single )
				{
					$returnString = $this->GetSingleLevelString( $single ) . "|" . $returnString;
				}
			}
		}
		return $returnString;
	}

	/*
	  函数名： DelLevel
	  参数：$levelNum------------取数值（0~$mLevelNums-1）
	  作用：返回权限的反向数字0010---1101
	  返回值：十进制数
	 */

	public function ContraryLevel( $levelNum )
	{
		for ( $i = 0; $i < $this->mLevelNums; $i++ )
		{
			$level = "";
			if ( $i == $levelNum )
			{
				$level = '0' . $level;
			}
			else
			{
				$level = '1' . $level;
			}
		}
		return bindec( $level );
	}

	/*
	  函数名：CheckRole
	  参数：$level-----数据库中的十进制权限值
	  作用：根据权限值判断角色范围
	  返回值：
	  -1------非法权限
	  0------讀者
	  1------作者
	  2------管理员
	 */

	public function CheckRole( $level )
	{
		switch ( true )
		{
			case ($level <= 0):
				return -1;
				break;
			case ($level == 1):
				return 0;
				break;
			case ($level > 1 && $level < 16):
				return 1;
				break;
			case ($level >= 16 && $level < 512):
				return 2;
				break;
			case ($level >= 512 && $level < 8192):
				return 3;
				break;
			case ($level >= 8192 && $level < 16384) :
				return 4;
				break;
			default:
				return 5;
		}
	}

	public function HasRole( $selfLevel, $checkLevel )
	{
		return $selfLevel & $checkLevel;
	}

}

?>