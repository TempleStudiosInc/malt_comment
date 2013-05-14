<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Comment extends Controller_Website {
	
	public function before()
	{
		parent::before();
	}
	
	public function after()
    {
    	parent::after();
    }
    
    public function action_load_comments()
    {
    	$view = View::factory('comment/embed/index');
		$view->user = $this->user;
    	$comment_id = Arr::get($_GET, 'comment_id', false);
		
		if ($comment_id)
		{
			$comment = ORM::factory('Comment', $comment_id);
			$view->comment = $comment;
		}
		else
		{
			die();
		}
        
        $this->template->body = $view;
    }
	
	public function action_load_comment_post_add_edit_form()
	{
		$post = $_POST;
		$comment_post_id = Arr::get($post, 'comment_post_id', false);
		$comment_id = Arr::get($post, 'comment_id', false);
		$parent_id = Arr::get($post, 'parent_id', false);
		$action = Arr::get($post, 'action', 'add');
		
		if ($action == 'edit')
		{
			$comment_post = ORM::factory('Comments_Post', $comment_post_id);
		}
		else
		{
			$comment_post = ORM::factory('Comments_Post');
			$comment_post->parent_id = $parent_id;
			$comment_post->comment_id = $comment_id;
		}
		
		$view = View::factory('comment/embed/comment_post_add_edit');
		$view->comment_post = $comment_post;
		$view->parent_id = $parent_id;
		$view->user = $this->user;
		$view->action = $action;
		
		$this->template->body = $view;
	}
	
	public function action_submit_post()
	{
		$post = $_POST;
		
		$comment_post_id = Arr::get($post, 'comment_post_id', false);
		if ($comment_post_id)
		{
			$comment_post = ORM::factory('Comments_Post', $comment_post_id);
		}
		else
		{
			$comment_post = ORM::factory('Comments_Post');
			$comment_post->status = 'active';
			$comment_post->date_posted = date('Y-m-d H:i:s');
		}
		$comment_post->comment_id = Arr::get($post, 'comment_id', 0);
		$comment_post->user_id = Arr::get($post, 'user_id', $this->user->id);
		$comment_post->parent_id = Arr::get($post, 'parent_id', 0);
		$comment_post->content = Arr::get($post, 'content', '');
		$comment_post->save();
		
		$view = View::factory('comment/embed/remove_post');
		$this->template->body = $view;
	}
	
	public function action_remove_post()
	{
		$post = $_POST;
		
		$comment_post= ORM::factory("Comments_Post", Arr::get($post, 'comment_post_id', 0));
		if ($comment_post->id > 0)
		{
			$comment_post->status = 'removed';
			$comment_post->save();
		}
		$view = View::factory('comment/embed/remove_post');
		$this->template->body = $view;
	}
	
	public function action_submit_flag()
	{
		$post = $_POST;
		
		$comment_post_flag = ORM::factory("Comments_Posts_Flag");
		$comment_post_flag->comments_post_id = Arr::get($post, 'comments_post_id', 0);
		$comment_post_flag->user_id = Arr::get($post, 'user_id', $this->user->id);
		$comment_post_flag->reason = Arr::get($post, 'reason', '');
		$comment_post_flag->comment = Arr::get($post, 'comment', '');
		$comment_post_flag->status = 'new';
		$comment_post_flag->date_posted = date('Y-m-d H:i:s');
		$comment_post_flag->date_modified = date('Y-m-d H:i:s');
		$comment_post_flag->save();
		
		$comment_post= ORM::factory("Comments_Post", Arr::get($post, 'comments_post_id', 0));
		$comment_post->status = 'flagged';
		$comment_post->save();
		
		$view = View::factory('comment/embed/submit_flag');
		$this->template->body = $view;
	}
}