<?php

class Plance_Registry
{
	private static $data = array();
	private static $plugin;

	/**
	 * Set plugin
	 * @param string $plugin
	 */
	public static function setPlugin($plugin)
	{
		self::$plugin = $plugin;
	}
	
	/**
	 * Set data
	 * @param string $index
	 * @param mixed $value
	 */
	public static function set($index, $value)
	{
		self::$data[self::$plugin][$index] = $value;
	}
	
	/**
	 * Get data
	 * @param string $index
	 * @param mixed $default
	 * @return mixed
	 */
	public static function get($index, $default = false)
	{
		if(isset(self::$data[self::$plugin][$index]))
		{
			return self::$data[self::$plugin][$index];
		}
		return $default;
	}
	
	/**
	 * Get all data Registry
	 * @return array
	 */
	public static function all()
	{
		return self::$data[self::$plugin];
	}
	
	/**
	 * Clean data
	 */
	public static function clean()
	{
		self::$plugin = '';
		self::$data = array();
	}
}