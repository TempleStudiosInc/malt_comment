<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Comment extends ORM {
    
    protected $_has_many = array(
        'posts' => array(
            'model'   => 'Comments_Post'
        ),
    );
	
	public function get_pages($page = 1, $params = array())
	{
		$q = Arr::get($params, 'q', false);
		$page_limit = Arr::get($params, 'page_limit', 10);
        $order_by = Arr::get($params, 'order_by', 'id');
        $order_direction = Arr::get($params, 'order_direction', 'ASC');
        $offset = $page_limit*($page-1);
        $template = Arr::get($params, 'template', 'pagination/basic');
		
        $comments = ORM::factory('Comment');
		if ($q)
		{
			$comments->where('page_url', 'LIKE', '%'.$q.'%');
		}
        $total_items = $comments->count_all();
        
        $comments = ORM::factory('Comment')
            ->order_by($order_by, $order_direction)
            ->limit($page_limit)
            ->offset($offset);
		if ($q)
		{
			$comments->where('page_url', 'LIKE', '%'.$q.'%');
		}
		$comments = $comments->find_all();
        
        $pagination = Pagination::factory(array(
            'items_per_page' => $page_limit,
            'total_items' => $total_items,
            'view' => $template
        ));
        
        $return = new stdClass;
        $return->pagination = $pagination;
        $return->comments = $comments;
        
        return $return;
	}
}