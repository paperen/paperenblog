<?php

/**
 * 在CI表单函数基础上扩展更多元素
 * @author 梁子恩
 * @version 0.0
 * @package ocean
 * @subpackage application/helpers
 */
function form_submit_button( $data = '', $content = '', $extra = '' )
{
	$defaults = array( 'name' => ((!is_array( $data )) ? $data : ''), 'type' => 'submit' );

	if ( is_array( $data ) AND isset( $data['content'] ) )
	{
		$content = $data['content'];
		unset( $data['content'] ); // content is not an attribute
	}

	return "<button " . _parse_form_attributes( $data, $defaults ) . $extra . ">" . $content . "</button>";
}
/**
 * 以链接形式呈现按钮
 * @param array $data
 * @param string $content
 * @param string $extra
 * @return string
 */
function form_link_button( $data = '', $content = '', $extra = '' )
{
	$defaults = array( 'href' => ((!is_array( $data )) ? $data : 'javascript:void(0);') );

	if ( is_array( $data ) AND isset( $data['content'] ) )
	{
		$content = $data['content'];
		unset( $data['content'] ); // content is not an attribute
	}

	return "<a " . _parse_form_attributes( $data, $defaults ) . $extra . ">" . $content . "</a>";
}

/**
 * 隐藏域输出
 * @param string $name 隐藏域名字
 * @param string $value 隐藏域值
 * @param string $extra 额外参数
 * @param bool $recursing
 * @return string
 */
function form_hidden($name, $value = '', $extra = '', $recursing = FALSE)
{
    static $form;
    if ($recursing === FALSE)
    {
        $form = "\n";
    }
    if (is_array($name))
    {
        foreach ($name as $key => $val)
        {
            form_hidden($key, $val, $extra, TRUE);
        }
        return $form;
    }
    if ( ! is_array($value))
    {
	$form .= '<input type="hidden" name="'.$name.'" value="'.form_prep($value, $name).'" ';

	if(!is_array($extra))
	{
		$form .= $extra;
	} else {
		foreach($extra as $k => $v)
		{
			$form .= ' '.$k.'="'.$v.'" ';
		}
	}
	$form .= ' />'."\n";
    }
    else
    {
        foreach ($value as $k => $v)
        {
            $k = (is_int($k)) ? '' : $k;
            form_hidden($name.'['.$k.']', $v, $extra, TRUE);
        }
    }
    return $form;
}

?>
