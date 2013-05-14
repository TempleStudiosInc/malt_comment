<?php
	if ($action == 'add')
	{
		$comment_post_id = 'new';
	}
	elseif ($action == 'edit')
	{
		$comment_post_id = $comment_post->id;
	}
	echo '<div id="add_edit_comment_post_'.$comment_post_id.'">';
	echo Form::open('comment/submit_post');
	echo Form::hidden('comment_id', $comment_post->comment_id);
	
	echo Form::hidden('user_id', $user->id);
	if ($action == 'edit')
	{
		echo Form::hidden('comment_post_id', $comment_post->id);
	}
	echo Form::hidden('parent_id', $comment_post->parent_id);
	echo Form::textarea('content', $comment_post->content, array('id' => 'add_edit_comment_post_content_'.$comment_post_id, 'class' => 'ckeditor'));
	echo Form::button('post_comment', 'Post Comment', array('class' => 'btn btn-mini post_comment_submit_btn'));
	echo '&nbsp;&nbsp;';
	echo HTML::anchor('#', 'cancel', array('class' => 'cancel_comment'));
	echo Form::close();
?>
</div>