<?php

/**
 * InIt Admin
 */
class Plance_MSM_Admin_INIT
{
    /**
     * Create
     */
    public function __construct()
    {
		add_action('admin_enqueue_scripts', function()
		{
			wp_enqueue_style('style-msm-admin', Plance_Registry::get('url_to_plugin').'assets/admin/image/style.css');
			wp_enqueue_script('script-msm-admin', Plance_Registry::get('url_to_plugin').'assets/admin/javascript/script.js', array('jquery', 'script-msm-maps-google', 'script-msm-maps-yandex'));
			wp_enqueue_script('script-msm-maps-google', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places');
			wp_enqueue_script('script-msm-maps-yandex', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU');
		});
		
		$AdminInit = new Plance_Interface();
		$AdminInit -> addController('@', 'Plance_MSM_Controller_Admin_Data', array(
			array('action' => 'add'),
			array('action' => 'edit'),
			array('action' => 'delete'),
		));

		$AdminInit -> setMenu(array(
			'@' => array(__('My maps', 'plance'), __('My maps', 'plance')),
		));
    }
}
