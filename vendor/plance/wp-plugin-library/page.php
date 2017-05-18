<?php

class Plance_Page
{
	protected function createPage($post_title, $post_content)
	{
		global $wp_query;
		
		$posts[] =
			(object) array(
				'ID'                    => '9999999',
				'post_author'           => '1',
				'post_date'             => '2001-01-01 01:01:01',
				'post_date_gmt'         => '2001-01-01 01:01:01',
				'post_content'          => $post_content,
				'post_title'            => $post_title,
				'post_excerpt'          => '',
				'post_status'           => 'publish',
				'comment_status'        => 'closed',
				'ping_status'           => 'closed',
				'post_password'         => '',
				'to_ping'               => '',
				'pinged'                => '',
				'post_modified'         => '2001-01-01 01:01:01',
				'post_modified_gmt'     => '2001-01-01 01:01:01',
				'post_content_filtered' => '',
				'post_parent'           => '0',
				'menu_order'            => '0',
				'post_type'             => 'page',
				'post_mime_type'        => '',
				'post_category'         => '0',
				'comment_count'         => '0',
				'filter'                => 'raw',
				'guid'                  => get_bloginfo('url').'/?page_id=9999999',
				'post_name'             => get_bloginfo('url').'/?page_id=9999999',
				'ancestors'             => array()
			);

		$wp_query -> is_page   = true;
		$wp_query -> is_single = false;
		$wp_query -> is_home   = false;
		$wp_query -> comments  = false;

		unset($wp_query -> query['error']);
		$wp_query -> query_vars['error'] = '';
		$wp_query -> is_404              = false;
		
		remove_filter('the_content', 'wpautop');
		
		return $posts;
	}
}