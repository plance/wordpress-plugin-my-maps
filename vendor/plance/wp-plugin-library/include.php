<?php

class Plance_Include
{
	public static function autoload($prefix_class, $path)
	{
		spl_autoload_register(function($class) use($prefix_class, $path)
		{
			if(strstr($class, $prefix_class) == true)
			{
				include $path.strtolower(str_replace($prefix_class, '', $class)).'.php';
			}
		});
	}
	
	/**
	 * Load class
	 * @param array $class_ar
	 */
	public static function load(array $class_ar)
	{
		if(isset($class_ar['index']))
		{
			self::_include($class_ar['index']);
		}
		if(isset($class_ar['admin']) && is_admin())
		{
			self::_include($class_ar['admin']);
		}	
	}
	
	/**
	 * Include class
	 * @param array $class_ar
	 */
	private static function _include(array $class_ar)
	{
		foreach ($class_ar as $k => $v)
		{
			if(is_int($k) && is_array($v))
			{
				if(isset($v['class']) && isset($v['path']) && class_exists($v['class']) == FALSE)
				{
					require_once($v['path']);
				}
				if(isset($v['call']))
				{
					call_user_func($v['call']);
				}
			}
			else if(is_string($k) && is_string($v))
			{
				if(class_exists($k) == FALSE)
				{
					require_once($v);
				}
			}
			else
			{
				require_once($v);
			}
		}
	}
}