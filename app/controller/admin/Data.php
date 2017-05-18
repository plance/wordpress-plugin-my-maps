<?php

class Plance_MSM_Controller_Admin_Data extends Plance_Controller
{
	const PAGE = __CLASS__;

	/**
	 * Table class
	 */
	private $Table;
	
	/**
	 * Validate form
	 */
	private $Validate;
	
	/**
	 * Save screen options
	 */
	public function setScreenOption($status, $option, $value)
	{
		if(strtolower(__CLASS__) == $option )
		{
			return $value;
		}

		return $status;
	}
	
	//===========================================================
	// Actions
	//===========================================================
	
	/**
	 * List data
	 */
	public function actionIndex()
	{
		$per_page = strtolower(__CLASS__);
				
		add_screen_option('per_page', array(
			'label'		=> __('Records', 'plance'),
			'default'	=> 10,
			'option'	=> $per_page
		));
		
		$this -> Table = new Plance_MSM_View_Admin_Data_Index;
		$this -> Table -> _page		= $this -> page();
		$this -> Table -> _per_page = $per_page;
	}
	
	/**
	 * Data create
	 * 
	 * @global wpdb $wpdb
	 */
	public function actionAdd()
	{
		global $wpdb;
		
		$this -> Validate = $this -> _validate();

		if(Plance_Request::isPost() && $this -> Validate -> validate())
		{
			$data_ar = $this -> Validate -> getData();
			
			$wpdb -> insert(
				$wpdb -> prefix.'plance_msm_maps',
				array(
					'title'   => $data_ar['msm_title'],
					'address'   => $data_ar['msm_address'],
					'date_create' => time(),
				),
				array('%s', '%s', '%d')
			);
			
			Plance_Flash::instance() -> redirect('?page='.$this -> page().'&action=add', __('Data created', 'plance'));
		}
		
		if($this -> Validate -> isErrors())
		{
			Plance_Flash::instance() -> show('error', $this -> Validate -> getErrors());
		}
	}
	
	/**
	 * Data update
	 * 
	 * @global wpdb $wpdb
	 */
	public function actionEdit()
	{
		global $wpdb;
		
		//Sets
		$id = Plance_Request::get('id', 0, 'int');
		
		$this -> Validate = $this -> _validate();
		
		if(Plance_Request::isPost() && $this -> Validate -> validate())
		{
			$data_ar = $this -> Validate -> getData();
			
			$wpdb -> update(
				$wpdb -> prefix.'plance_msm_maps',
				array(
					'title' => $data_ar['msm_title'],
					'address' => $data_ar['msm_address'],
				),
				array('id' => $id),
				array('%s', '%s'),
				array('%d')
			);
			
			Plance_Flash::instance() -> redirect('?page='.$this -> page().'&action=edit&id='.$id, __('Data updated', 'plance'));
		}
		else if(Plance_Request::isPost() == false)
		{
			$data_ar = $wpdb -> get_row("SELECT *
				FROM `{$wpdb -> prefix}plance_msm_maps`
				WHERE `id` = ".$id."
				LIMIT 1", 
			ARRAY_A);

			if($data_ar === null)
			{
				wp_die(__('Page not found', 'plance'));
			}

			$this -> Validate -> setData(array(
				'msm_title'   => $data_ar['title'],
				'msm_address' => $data_ar['address'],
			));
		}
		
		if($this -> Validate -> isErrors())
		{
			Plance_Flash::instance() -> show('error', $this -> Validate -> getErrors());
		}
	}
	
	/**
	 * Delete
	 * 
	 * @global wpdb $wpdb
	 */
	public function actionDelete()
	{
		global $wpdb;
		
		$wpdb -> delete(
			$wpdb -> prefix.'plance_msm_maps',
			array('id' => Plance_Request::get('id', 0, 'int')),
			array('%d')
		);
		
		Plance_Flash::instance() -> redirect('?page='.$this -> page(), __('Data deleted', 'plance'));
	}
	
	//===========================================================
	// Views
	//===========================================================
	
	/**
	 * List data
	 */
	public function viewIndex()
	{
        $this -> Table -> prepare_items();
        ?>
            <div class="wrap">
                <h2>
					<?php echo __('List maps', 'plance') ?>
					<a href="?page=<?php echo $this -> page().'&action=add' ?>" class="page-title-action"><?php echo __('Add map', 'plance') ?></a>
				</h2>
				<form method="get">
					<input type="hidden" name="page" value="<?php echo $this -> page() ?>" />
					<?php $this -> Table -> search_box(__('Search', 'plance'), 'search_id'); ?>
					<?php $this -> Table -> display(); ?>
				</form>
				<p><?php echo __('Copy and paste your shortcode in post or page.', 'plance') ?></p>
				<p>
					<?php echo __('Additional attributes:', 'plance') ?>
					<ul>
						<li>- <?php echo __('Width - width map', 'plance') ?></li>
						<li>- <?php echo __('Height - height map', 'plance') ?></li>
						<li>- <?php echo __('Zoom - zoom map', 'plance') ?></li>
					</ul>
					<?php echo __('Example - [my-map id="map_id" width="320" height="240" zoom="15"]', 'plance') ?>
				</p>
            </div>
        <?php
	}
	
	/**
	 * Data create
	 */
	public function viewAdd()
	{
		echo Plance_View::get(Plance_Registry::get('path_to_plugin').'app/view/admin/data/add', array(
			'page_title' => __('Map creating', 'plance'),
			'form_actiion' => '?page='.$this -> page().'&action=add',
			'Validate' => $this -> Validate,
			'_page' => $this -> page(),
		));
	}

	/**
	 * Data edit
	 */
	public function viewEdit()
	{
		echo Plance_View::get(Plance_Registry::get('path_to_plugin').'app/view/admin/data/add', array(
			'page_title' => __('Map editing', 'plance'),
			'form_actiion' => '?page='.$this -> page().'&action=edit&id='.Plance_Request::get('id', 0, 'int'),
			'Validate' => $this -> Validate,
			'_page' => $this -> page(),
		));
	}
	
	//===========================================================
	// Validate
	//===========================================================
	
	/**
	 * Validate
	 * @return PlanceValidate
	 */
	private function _validate()
	{
		return Plance_Validate::factory(wp_unslash($_POST))
		-> setLabels(array(
			'msm_title' => __('Title', 'plance'),
			'msm_address' => __('Address', 'plance'),
			'_wpnonce'	=> __('wpnonce field', 'plance'),
		))
		
		-> setFilters('msm_title', array(
			'trim' => array(),
			'strip_tags' => array(),
		))
		-> setFilters('msm_address', array(
			'trim' => array(),
			'strip_tags' => array(),
		))
		
		-> setRules('msm_title', array(
			'required' => array(),
			'max_length' => array(225),
		))
		-> setRules('msm_address', array(
			'required' => array(),
			'max_length' => array(225),
		))
		-> setRules('_wpnonce', array(
			__CLASS__.'::validateWpNonce' => array(),
		))
						
		-> setMessages(array(
			'required'	=> __('"{field}" must not be empty', 'plance'),
			'max_length'=> __('"{field}" must not exceed {param1} characters long', 'plance'),
			__CLASS__.'::validateWpNonce' => __('Wrong form data', 'plance'),
		));
	}
	
	/**
	 * Verify Nonce
	 * @param string $_wpnonce
	 * @return bool
	 */
	public static function validateWpNonce($_wpnonce)
	{
		return wp_verify_nonce($_wpnonce, 'form-msm');
	}
}