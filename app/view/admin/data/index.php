<?php
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Table
 */
class Plance_MSM_View_Admin_Data_Index extends WP_List_Table
{
	public $_page;
	public $_per_page;
	
    /**
     * Подготавливаем колонки таблицы для их отображения
     *
     */
    public function prepare_items()
    {
		global $wpdb;
		
		/* Определяем общее количество записей в БД */
		$total_items = $wpdb -> get_var("
			SELECT COUNT(`id`)
			FROM `{$wpdb -> prefix}plance_msm_maps`
			{$this -> _getSqlWhere()}
		");
		
		//Sets
		$per_page = $this -> get_items_per_page($this -> _per_page, 10);
		
		/* Устанавливаем данные для пагинации */
        $this -> set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page
        ));

		/* Получаем данные для формирования таблицы */
        $data = $this -> table_data();
		
		$this -> _column_headers = $this -> get_column_info();
		
		/* Устанавливаем данные таблицы */
        $this -> items = $data;
    }
 
    /**
     * Название колонок таблицы
     *
     * @return array
     */
    public function get_columns()
    {
        return array(
            'id'		 => __('ID', 'plance'),
            'title'		 => __('Title', 'plance'),
            'address'	 => __('Address', 'plance'),
            'shortcode'	 => __('Shortcode', 'plance'),
            'date_create'=> __('Date create', 'plance'),
        );
    }
 
    /**
     * Массив названий колонок по которым выполняется сортировка
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        return array(
			'id'		 => array('id', false),
			'title'		 => array('title', false),
			'address'	 => array('address', false),
			'shortcode'	 => array('id', false),
			'date_create'=> array('date_create', false),
		);
    }
 
    /**
     * Данные таблицы
     *
     * @return array
     */
    private function table_data()
    {
		global $wpdb;
		
		//Sets
		$per_page = $this -> get_pagination_arg('per_page');
		$order_ar = $this -> get_sortable_columns();
		$orderby = 'date_create';
		$order = 'DESC';

		if(isset($_GET['orderby']) && isset($order_ar[$_GET['orderby']]))
		{
			$orderby = $_GET['orderby'];
		}

		if(isset($_GET['order']))
		{
			$order = $_GET['order'] == 'asc' ? 'asc' : 'desc';
		}

		$sql = "SELECT *
			FROM `{$wpdb -> prefix}plance_msm_maps`
			{$this -> _getSqlWhere()}
			ORDER BY `{$orderby}` {$order}
			LIMIT ".(($this -> get_pagenum() - 1) * $per_page).", {$per_page}
		";

		return $wpdb -> get_results($sql, ARRAY_A);
    }
 
	/**
	 * Отображается в случае отсутствии данных
	 */
	public function no_items()
	{
	  echo __('Data not found', 'plance');
	}
	
    /**
     * Возвращает содержимое колонки
     *
     * @param  array $item массив данных таблицы
     * @param  string $column_name название текущей колонки
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch($column_name)
		{
			case 'id':
			case 'address':
				return $item[$column_name] ? $item[$column_name] : '-';
        }
    }
	
	public function column_title($item)
	{
		return $item['title'].$this -> row_actions(array(
			'edit' => '<a href="?page='.$this -> _page.'&action=edit&id='.$item['id'].'">'.__('edit', '_page').'</a>',
			'delete' => '<a href="?page='.$this -> _page.'&action=delete&id='.$item['id'].'" onclick="return confirm(\''.__('Is Deleted?', '_page').'\')">'.__('delete', '_page').'</a>',
		));
	}
	
	public function column_shortcode($item)
	{
		return '[my-map id="'.$item['id'].'"]';
	}
	
	public function column_date_create($item)
	{
		return date(get_option('date_format', 'd.m.Y').' '.get_option('time_format', 'H:i'), $item['date_create']);
	}
	
	/********************************************************************************************************************/
	/************************************************* PRIVATE METHODS **************************************************/
	/********************************************************************************************************************/
	
	/**
	 * Get "where" for sql
	 * @global wpdb $wpdb
	 * @return string
	 */
	private function _getSqlWhere()
	{
		global $wpdb;
		
		$where = '';
		
		if(isset($_GET['s']) && $_GET['s'])
		{
			$where = 'WHERE '.join(' OR ', array(
				"`title` LIKE  '%".$wpdb -> _real_escape($_GET['s'])."%'",
				"`address` LIKE  '%".$wpdb -> _real_escape($_GET['s'])."%'",
			));
		}
		
		return $where;
	}
}