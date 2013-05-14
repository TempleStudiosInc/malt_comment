<script type="text/javascript">
	var CKEDITOR_BASEPATH = '/_media/core/common/js/ckeditor/';
</script>
<?php
	echo HTML::script('/_media/core/common/js/ckeditor/ckeditor.js');
	echo HTML::script('/_media/core/common/js/ckeditor/adapters/jquery.js');
	
	$comments_post = ORM::factory('Comments_Post');
	
	$page = 1;
	$params = array(
		'comment_id' => $comment->id,
		'order_by' => 'date_posted',
		'order_direction' => 'desc',
		'parent_id' => 0
	);
	
	$posts = $comments_post->get_posts($page, $params);
	$pagination = $posts->pagination;
	$posts = $posts->posts;
?>

<div class="comments">
	<h4>
	<?php
		echo $pagination->total_items;
		echo ' comment';
		if ($pagination->total_items != 1)
		{
			echo 's';
		}
	?>
	</h4>
	<div>
	<?php
		if ($user->id > 0)
		{
			echo Form::open('comment/submit_post');
			echo Form::hidden('comment_id', $comment->id);
			echo Form::hidden('user_id', $user->id);
			echo Form::hidden('parent_id', 0);
			echo Form::textarea('content', '', array('id' => 'add_comment_post_content_'.$comment->id, 'class' => ''));
			echo Form::button('post_comment', 'Post Comment', array('class' => 'btn btn-small post_comment_submit_btn'));
			echo Form::close();
		}
		else
		{
			$textarea_params['disabled'] = 'disabled';
			echo '<div class="alert alert-info">';
			echo HTML::anchor('/login', 'Please log in to post a comment');
			echo '</div>';
		}
	?>
	</div>
	<div>
		<?php
			if (count($posts) > 0)
			{
				foreach ($posts as $post)
				{
					$view = View::factory('comment/embed/comment_post');
					$view->comment = $comment;
					$view->post = $post;
					$view->user = $user;
					echo $view;
				}
			}
		?>
	</div>
</div>

<div class="modal hide fade" id="delete_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Confirmation Required</h3>
    </div>
    <div class="modal-body">
        <p>Are you sure you want to remove this comment?</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</a>
        <a href="#" class="btn btn-primary delete_delete_yes_button">Yes</a>
    </div>
</div>

<div id="flag_modal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Flag Comment</h3>
	</div>
	<div class="modal-body">
		<div class="modal_body_form">
		<?php
			$reasons = array(
				'Spam' => 'Spam',
				'Offensive' => 'Offensive',
				'Disagree' => 'Disagree',
				'Off Topic' => 'Off Topic'
			);
			
			echo Form::open('/comment/submit_flag', array('id' => 'flag_form'));
			echo Form::hidden('comments_post_id');
			echo Form::hidden('user_id', $user->id);
			
			echo Form::label('reason', 'Flag As');
			echo Form::select('reason', $reasons);
			
			echo Form::label('comment', 'Comment');
			echo Form::textarea('comment', '', array('style' => 'width: 95%; height: 100px;'));
			echo Form::close();
			
			echo HTML::anchor('#', 'Submit Flag', array('class' => 'btn btn-primary submit_flag'));
		?>
		</div>
		<div class="modal_body_results"></div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Close</a>
	</div>
</div>