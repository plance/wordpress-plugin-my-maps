<?php

class Plance_Controller
{
	public  $_conditions_ar = array();

	/**
	 * Construct class
	 * @param array $conditions_ar
	 */
    public function __construct($conditions_ar = array())
    {
		$this -> _conditions_ar = $conditions_ar;
		
		if(method_exists($this, 'setScreenOption'))
		{
			add_filter('set-screen-option', array($this, 'setScreenOption'), 10, 3);
		}
		
		if(method_exists($this, 'style'))
		{
			add_action('admin_head', array($this, 'style'));
		}
    }
	
	//===========================================================
	
	/**
	 * Call screen options methods
	 */
	public function action()
	{
		$this -> _call(__FUNCTION__);
	}
	
	/**
	 * Call admin head methods
	 */
	public function style()
	{
		$this -> _call(__FUNCTION__);
	}
	
	/**
	 * Call actions methods
	 */
	public function view()
	{
		$this -> _call(__FUNCTION__);
	}
	
	/********************************************************************************************************************/
	/************************************************** STATIC METHODS **************************************************/
	/********************************************************************************************************************/
	
	/**
	 * Get page
	 * @return string
	 */
	public static function page()
	{
		return strtolower(
			preg_replace('/_{1,}/i', '-', static::PAGE)
		);
	}
	
	/********************************************************************************************************************/
	/************************************************* PRIVATE METHODS **************************************************/
	/********************************************************************************************************************/
	
	/**
	 * Call functions
	 * @param string $pref
	 * @return boolean
	 */
	private function _call($pref)
	{
		foreach($this -> _conditions_ar as $condition_ar)
		{
			if(isset($condition_ar['action']) == false)
			{
				$condition_ar['action'] = 'index';
			}

			if(isset($condition_ar['@method']))
			{
				$method = $pref.$condition_ar['@method'];
				unset($condition_ar['@method']);
			}
			else
			{
				$method = $pref.ucfirst($condition_ar['action']);
			}

			$i = 0;
			$get = '';
			foreach($condition_ar as $k => $v)
			{
				$get = isset($_GET[$k]) ? $_GET[$k] : 'index';
				if($get == $v)					
				{
					$i++;
				}
			}
			if($i == sizeof($condition_ar))
			{
				if(method_exists($this, $method))
				{
					call_user_func(array($this, $method));
					return true;
				}
			}
		}
		return false;
	}
}