<?php defined('SYSPATH') or die('No direct script access.');

class Comments {
    public static function embed()
    {
    	$uri = Request::detect_uri();
		
        $view = View::factory('comment/embed/embed');
		
		$comment = ORM::factory('Comment')->where('page_id', '=', md5($uri))->find();
		if ($comment->id == 0)
		{
			$comment = ORM::factory('Comment')->where('page_url', '=', $uri)->find();
			if ($comment->id == 0)
			{
				$comment = ORM::factory('Comment');
				$comment->page_title = Format::page_title('');
				$comment->page_id = md5($uri);
				$comment->page_url = $uri;
				$comment->status = 'active';
				$comment->save();
			}
		}
		
		$view->comment = $comment;
		
		return $view;
    }
	
	public static function get_comment_count_by_url($url = false)
	{
		if ($url == false)
		{
			$url = Request::detect_uri();
		}
		
		$comment = ORM::factory('Comment')->where('page_url', '=', $url)->find();
		$page = 1;
		$params = array(
			'comment_id' => $comment->id,
			'parent_id' => 0,
			'page_limit' => 1000
		);
		$posts = ORM::factory('Comments_Post')->get_posts($page = 1, $params);
		
		$pagination = $posts->pagination;
		$view = View::factory('comment/embed/count');
		$view->comment_count = $pagination->total_items;
		$view->uri_id = md5($url);
		return $view;
	}
}