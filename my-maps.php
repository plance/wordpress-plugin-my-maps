<?php
/*
Plugin Name: My Maps
Plugin URI: http://wordpress.org/plugins/my-maps/
Description: Creating shortcode maps, using friendly interface
Version: 1.0
Author: Pavel
Author URI: http://plance.in.ua/
*/

defined('ABSPATH') or die('No script kiddies please!');

//Include language
load_plugin_textdomain('plance', false, basename(__DIR__).'/languages/');

if(class_exists('Plance_Include') == false)
{
	require_once(plugin_dir_path(__FILE__).'vendor/plance/wp-plugin-library/include.php');
}

Plance_Include::load(array(
	'index' => array(
		array(
			'class'=> 'Plance_Registry',
			'path' => plugin_dir_path(__FILE__).'vendor/plance/wp-plugin-library/registry.php',
			'call' => function() {
				add_action('plugins_loaded', function() {
					Plance_Registry::setPlugin(basename(__DIR__));
					Plance_Registry::set('path_to_plugin', plugin_dir_path(__FILE__));
					Plance_Registry::set('url_to_plugin', plugin_dir_url(__FILE__));
				});
			}
		),
		array(
			'class' => 'Plance_Flash',
			'path'	=> plugin_dir_path(__FILE__).'vendor/plance/wp-plugin-library/flash.php',
			'call' => function() {
				Plance_Flash::instance() -> init();
			}
		),
		'Plance_Validate'	=> plugin_dir_path(__FILE__).'vendor/plance/library/validate.php',
		'Plance_View'		=> plugin_dir_path(__FILE__).'vendor/plance/library/view.php',
		'Plance_Request'	=> plugin_dir_path(__FILE__).'vendor/plance/library/request.php',
		
		/*InIt*/
		plugin_dir_path(__FILE__).'app/index_init.php',
	),
	'admin' => array(
		/* System */
		'WP_List_Table'		=> ABSPATH.'wp-admin/includes/class-wp-list-table.php',
		
		'Plance_Interface'	=> plugin_dir_path(__FILE__).'vendor/plance/wp-plugin-library/interface.php',
		'Plance_Controller' => plugin_dir_path(__FILE__).'vendor/plance/wp-plugin-library/controller.php',
		'Plance_Validate'	=> plugin_dir_path(__FILE__).'vendor/plance/library/validate.php',
		
		/* InIt */
		plugin_dir_path(__FILE__).'app/db.php',
		plugin_dir_path(__FILE__).'app/admin_init.php',
		
		/* Controllers */
		plugin_dir_path(__FILE__).'app/controller/admin/Data.php',
		
		/* View */
		plugin_dir_path(__FILE__).'app/view/admin/data/index.php',
	),
));

add_action('plugins_loaded', function() {
	if(is_admin() == TRUE)
	{
		new Plance_MSM_Admin_INIT();
	}
	else
	{
		new Plance_MSM_Index_INIT();
	}
});
	
if(is_admin() == TRUE)
{
	register_activation_hook(__FILE__, 'Plance_MSM_DB::activate');
	register_uninstall_hook(__FILE__, 'Plance_MSM_DB::uninstall');
}

Plance_Registry::clean();