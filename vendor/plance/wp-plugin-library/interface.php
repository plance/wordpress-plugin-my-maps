<?php

/**
 * InIt form
 */
class Plance_Interface
{
	private $_controllers_ar = array();
	private $_menu_ar = array();
	private $_cnf_ar = array(
		'capability' => 'manage_options',
	);
	
    /**
     * Create
     */
    public function __construct($_cnf_ar = array())
    {
		$this -> _cnf_ar = $_cnf_ar + $this -> _cnf_ar;
        add_action('admin_menu', array($this, 'adminMenu'));
    }
 
	/**
	 * Add controlleer
	 * @param string $key
	 * @param string $class
	 * @param array $conditions_ar
	 */
	public function addController($key, $class, $conditions_ar = array())
	{
		$page = $class::page();
		
		$conditions_ar[]['page'] = $page;
		
		if(sizeof($conditions_ar) > 0)
		{
			foreach ($conditions_ar as $i => $condition_ar)
			{
				if(isset($condition_ar['page']) == false)
				{
					$conditions_ar[$i] = array('page' => $page) + $condition_ar;
				}
			}
		}
		
		$this -> _controllers_ar[$key] = array(
			'class'		=> $class,
			'page'		=> $page,
			'object'	=> new $class($conditions_ar),
		);
		
		return $this;
	}
	
	/**
	 * Set menu
	 * @param array $menu_ar
	 */
	public function setMenu(array $menu_ar)
	{
		$this -> _menu_ar = $menu_ar;
		
		return $this;
	}
	
    /**
     * Create menu
     */
    public function adminMenu()
    {
		if(isset($this -> _menu_ar['@']) == false)
		{
			return false;
		}
		
		add_menu_page(
			$this -> _menu_ar['@'][0], 
			$this -> _menu_ar['@'][1], 
			$this -> _cnf_ar['capability'], 
			$this -> _controllers_ar['@']['page']
		);
		
		foreach ($this -> _menu_ar as $key => $menu_ar)
		{
			$hook = add_submenu_page(
				$this -> _controllers_ar['@']['page'],
				$menu_ar[0], 
				$menu_ar[1], 
				$this -> _cnf_ar['capability'], 
				$this -> _controllers_ar[$key]['page'],
				array($this -> _controllers_ar[$key]['object'], 'view')
			);
			add_action('load-'.$hook, array($this -> _controllers_ar[$key]['object'], 'action'));
		}
    }
}