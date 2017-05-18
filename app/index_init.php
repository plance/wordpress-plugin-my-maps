<?php

/**
 * InIt user
 */
class Plance_MSM_Index_INIT
{
	public $Validate;
	
    /**
     * Create
     */
    public function __construct()
    {
		add_shortcode("my-map", function ($atts){
			global $wpdb;
			
			$a = shortcode_atts(array(
				'id' => 0,
				'width' => 640,
				'height' => 480,
				'zoom' => 10,
			),$atts);
			
			$a['id'] = (int) $a['id'];
			
			if($a['id'] > 0)
			{
				$data_ar = $wpdb -> get_row("SELECT `title`, `address`
					FROM `{$wpdb -> prefix}plance_msm_maps`
					WHERE `id` = ".$a['id']."
					LIMIT 1", 
				ARRAY_A);

				if($data_ar !== null)
				{
					return '<img src="https://maps.googleapis.com/maps/api/staticmap?size='.$a['width'].'x'.$a['height'].'&zoom='.$a['zoom'].'&sensor=false&maptype=roadmap&markers=color:red|label:A|'.$data_ar['address'].'" width="'.$a['width'].'" height="'.$a['height'].'" alt="'.esc_attr($data_ar['title']).'">';
				}
			}
		});
	}
}