<?php
defined('ABSPATH') or die('No script kiddies please!');

/**
 * DB class
 */
class Plance_MSM_DB
{
    /**
	 * Plugin Activate
	 */
    public static function activate()
    {
		global $wpdb;
		
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		
		dbDelta("CREATE TABLE IF NOT EXISTS `{$wpdb -> prefix}plance_msm_maps` (
			`id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`title` VARCHAR(255) NOT NULL,
			`address` TEXT NOT NULL,
			`date_create` INT(10) UNSIGNED NOT NULL
		) {$wpdb -> get_charset_collate()};");
		
        return TRUE;
    }
	
    /**
	 * Plugin Uninstall
	 */
    public static function uninstall()
    {
		global $wpdb;
		
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		
		$wpdb -> query("DROP TABLE IF EXISTS `{$wpdb -> prefix}plance_msm_maps`");
		
		return TRUE;
    }
}