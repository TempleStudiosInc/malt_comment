<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Comment extends Controller_Admin_Website {
	
	public function before()
	{
		parent::before();
	}
	
	public function after()
    {
    	$sidebar_navigation_view = View::factory('comment/admin/navigation');
		
		$request = Request::initial();
		$requested_controller = str_replace('Admin_', '', $request->controller());
		$sidebar_navigation_view->requested_controller = $requested_controller;
		
		$comment = ORM::factory('Comment');
		$comment_post = ORM::factory("Comments_Post");
		
		$pages = $comment->get_pages(1, array('limit' => 3))->comments;
		$sidebar_navigation_view->pages = $pages;
		
		$posts = $comment_post->get_posts(1, array('limit' => 5, 'order_by' => 'date_posted', 'order_direction' => 'desc'))->posts;
		$sidebar_navigation_view->posts = $posts;
		
        $this->template->sidebar_navigation = $sidebar_navigation_view;
		
        parent::after();
    }
    
    public function action_index()
    {
        $this->action_posts();
    }
	
	public function action_posts()
    {
        $view = View::factory('comment/admin/posts');
		$content_title = 'Comments';
		$view->content_title = $content_title;
		
		$comment_post = ORM::factory("Comments_Post");
		
		$posts_params = array(
			'limit' => 20,
			'order_by' => 'date_posted',
			'order_direction' => 'desc'
		);
		$posts_params['flagged'] = Arr::get($_GET, 'flagged', false);
		$posts_params['parent_id'] = Arr::get($_GET, 'parent_id', false);
		$page = Arr::get($_GET, 'page', 1);
		$posts = $comment_post->get_posts($page, $posts_params);
		
		$pagination = $posts->pagination;
		$view->pagination = $pagination;
		
		$posts = $posts->posts;
		$view->posts = $posts;
		
        $this->template->body = $view;
    }
	
	public function action_add_comment()
	{
		$view = View::factory('comment/admin/add_edit_comment');
		$content_title = 'Add Comment';
		$view->content_title = $content_title;
		
		$comment = ORM::factory('Comment');
		$view->comment = $comment;
		
        $this->template->body = $view;
	}
	
	public function action_edit_comment()
	{
		$comment_id = $this->request->param('id');
		
		$view = View::factory('blog/admin/add_edit_comment');
		$content_title = 'Edit Comment';
		$view->content_title = $content_title;
		
		$comment = ORM::factory('Comment', $comment_id);
		$view->comment = $comment;
		
        $this->template->body = $view;
	}
	
	public function action_save_comment()
	{
		$post = Arr::get($_POST, 'comment', false);
		
		if ($post)
		{
			foreach ($post as $key => $value)
	        {
	            switch ($key)
	            {
	                case 'id':
	                    if ($value == 0)
	                    {
	                        $comment = ORM::factory('Comment');
	                    }
	                    else
	                    {
	                        $comment = ORM::factory('Comment', $value);
	                    }
	                    break;
	                default:
	                    $comment->$key = $value;
	                    break;
	            }
	        }
			$comment->save();
			
			Notice::add(Notice::SUCCESS, 'Comment Saved.');
        	$this->redirect('/admin_comment/comment/'.$comment->id);
		}
	}
	
	public function action_comment()
	{
		$comment_id = $this->request->param('id');
		
		$view = View::factory('comment/admin/posts');
		
		$comment = ORM::factory('Comment', $comment_id);
		$view->comment = $comment;
		
		$content_title = 'Page '.$comment->page_url;
		$view->content_title = $content_title;
		
		$comment_post = ORM::factory("Comments_Post");
		
		$posts_params = array(
			'limit' => 20,
			'order_by' => 'date_posted',
			'order_direction' => 'desc',
			'comment_id' => $comment_id,
			'parent_id' => 0
		);
		$posts_params['flagged'] = Arr::get($_GET, 'flagged', false);
		$page = Arr::get($_GET, 'page', 1);
		$posts = $comment_post->get_posts($page, $posts_params);
		
		$pagination = $posts->pagination;
		$view->pagination = $pagination;
		
		$posts = $posts->posts;
		$view->posts = $posts;
		
        $this->template->body = $view;
	}
	
	public function action_add_post()
	{
		$view = View::factory('comment/admin/add_edit_post');
		$content_title = 'Add Post';
		$view->content_title = $content_title;
		
		$statuses = array(
			'active' => 'Active',
			'flagged' => 'Flagged',
			'removed' => 'Removed',
		);
		$view->statuses = $statuses;
		
		$comment_id = Arr::get($_GET, 'comment_id', 0);
		if ($blog_id == 0)
		{
			$comment = ORM::factory('Comment')->find();
		}
		else
		{
			$comment = ORM::factory('Comment', $comment_id);
		}
		
		$post = ORM::factory('Comments_Post');
		$post->comment = $comment;
		$view->post = $post;
		
        $this->template->body = $view;
	}
	
	public function action_edit_post()
	{
		$post_id = $this->request->param('id');
		
		$view = View::factory('comment/admin/add_edit_post');
		$content_title = 'Edit Post';
		$view->content_title = $content_title;
		
		$statuses = array(
			'active' => 'Active',
			'flagged' => 'Flagged',
			'Removed' => 'Removed',
		);
		$view->statuses = $statuses;
		
		$post = ORM::factory('Comments_Post', $post_id);
		$view->post = $post;
		
        $this->template->body = $view;
	}
	
	public function action_save_post()
	{
		$post = $_POST;
		
		$id = Arr::get($post, 'id', false);
		if ($id)
        {
        	$comments_post = ORM::factory('Comments_Post', $id);
        }
        else
        {
            $comments_post = ORM::factory('Comments_Post');
			$comments_post->user_id = $this->user->id;
			$comments_post->date_posted = date('Y-m-d H:i:s');
        }
        $comments_post->comment_id = Arr::get($post, 'comment_id', 0);
		$comments_post->parent_id = Arr::get($post, 'parent_id', null);
		$comments_post->content = Arr::get($post, 'content', '');
		$comments_post->date_modified = date('Y-m-d H:i:s');
		$comments_post->status = Arr::get($post, 'status', 'active');
		
		$comments_post->save();
		
		Notice::add(Notice::SUCCESS, 'Post Saved.');
    	$this->redirect('/admin_comment/comment/'.$comments_post->comment->id);
	}
	
	public function action_delete_post()
	{
		$post_id = $this->request->param('id');
		
		$comments_post = ORM::factory('Comments_Post', $post_id);
        $comments_post->status = 'removed';
        $comments_post->save();
		
		Notice::add(Notice::SUCCESS, 'Post Removed.');
    	$this->redirect('/admin_comment/comment/'.$comments_post->comment->id);
	}
}