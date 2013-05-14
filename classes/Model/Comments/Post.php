<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Comments_Post extends ORM {
    
	protected $_belongs_to = array(
		'user' => array(),
		'comment' => array(
        	'model' => 'Comment',
    	),
    	'parent_post' => array(
	        'model'       => 'Comments_post',
	        'foreign_key' => 'parent_id',
	    ),
	);
	
    protected $_has_many = array(
        'flags' => array(
            'model'   => 'Comments_Posts_Flag'
        ),
    );
	
	public function save(Validation $validation = null)
	{
		$this->date_modified = date('Y-m-d H:i:s');
		parent::save($validation);
	}
	
	public function get_posts($page = 1, $params = array())
	{
		$q = Arr::get($params, 'q', false);
		$comment_id = Arr::get($params, 'comment_id', 'noid');
		$parent_id = Arr::get($params, 'parent_id', false);
		$flagged = Arr::get($params, 'flagged', false);
		$page_limit = Arr::get($params, 'page_limit', 10);
        $order_by = Arr::get($params, 'order_by', 'id');
        $order_direction = Arr::get($params, 'order_direction', 'ASC');
        $offset = $page_limit*($page-1);
        $template = Arr::get($params, 'template', 'pagination/basic');
		
        $posts = ORM::factory('Comments_Post');
		if ($q)
		{
			$posts->where('content', 'LIKE', '%'.$q.'%');
		}
		if ($comment_id)
		{
			$posts->where('comment_id', '=', $comment_id);
		}
		
		if ($flagged)
		{
			$posts->where('status', '=', 'flagged');
		}
		if ($parent_id !== false)
		{
			$posts->where('parent_id', '=', $parent_id);
		}
		$posts->where('status', '!=', 'removed');
        $total_items = $posts->count_all();
        
        $posts = ORM::factory('Comments_Post')
            ->order_by($order_by, $order_direction)
            ->limit($page_limit)
            ->offset($offset);
		if ($q)
		{
			$posts->where('content', 'LIKE', '%'.$q.'%');
		}
		if ($comment_id)
		{
			$posts->where('comment_id', '=', $comment_id);
		}
		if ($flagged)
		{
			$posts->where('status', '=', 'flagged');
		}
		if ($parent_id !== false)
		{
			$posts->where('parent_id', '=', $parent_id);
		}
		$posts->where('status', '!=', 'removed');
		$posts = $posts->find_all();
        
        $pagination = Pagination::factory(array(
            'items_per_page' => $page_limit,
            'total_items' => $total_items,
            'view' => $template
        ));
        
        $return = new stdClass;
        $return->pagination = $pagination;
        $return->posts = $posts;
        
        return $return;
	}
}