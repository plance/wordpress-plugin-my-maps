<?php

class Plance_Array
{
	/**
	 * Convert two dimensional array into one-dimensional, with the keys separated by dots
	 * @param type $array
	 * @return $array
	 */
	public static function convertArrayToObjectPoint($array)
	{
		//Sets
		$new_ar = array();
		
		foreach($array as $k => $v)
		{
			if(is_array($v))
			{
				foreach ($v as $k2 => $v2)
				{
					$new_ar[$k.':'.$k2] = $v2;
				}
			}
			else
			{
				$new_ar[$k] = $v;
			}
		}
		
		return $new_ar;
	}
	
	/**
	 * Converts a one-dimensional array with the keys separated by dots, two-dimensional array
	 * @param type $array
	 * @return $array
	 */
	public static function convertObjectPointToArray($array)
	{
		//Sets
		$new_ar = array();
		
		foreach($array as $k => $v)
		{
			if(strstr($k, ':') == TRUE)
			{
				$k_ar = explode(':', $k);
				$new_ar[$k_ar[0]][$k_ar[1]] = $v;
			}
			else
			{
				$new_ar[$k] = $v;
			}
		}
		
		return $new_ar;
	}
}