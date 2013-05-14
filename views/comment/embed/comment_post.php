<?php 
	$comment_name = '';
	if ($post->user->first_name != '')
	{
		$comment_name = $post->user->first_name;
	}
	elseif($post->user->username != '')
	{
		$comment_name = $post->user->username;
	}
?>

<div class="comment_post" id="comment_post_<?php echo $post->id ?>" comment_post_id="<?php echo $post->id ?>">
	<div class="comment_post_header">
		<div class="comment_post_name"><?php echo $comment_name?></div>
		<div class="comment_post_date">
			<div class="comment_post_date_relative"><?php echo Format::relative_date($post->date_posted) ?></div>
			<div class="comment_post_date_absolute"><?php echo Format::readable_datetime($post->date_posted) ?></div>
		</div>
	</div>
	<div class="comment_post_content">
		<p>
		<?php
			if ($post->status != 'hidden')
			{
				echo $post->content;
			}
			else
			{
				echo 'User has removed this comment.';
			}
		?>
		</p>
	</div>
	<div class="comment_post_actions">
		<ul class="nav nav-pills">
		<?php
			if ($user->id == $post->user_id AND $post->status != 'hidden')
			{
				echo '<li>'.HTML::anchor('#', '<i class="icon-pencil"></i>&nbsp; Edit', array('class' => 'edit_post_btn')).'</li>';
				echo '<li>'.HTML::anchor('#', '<i class="icon-remove"></i>&nbsp; Remove', array('class' => 'remove_post_btn')).'</li>';
			}
			if ($user->id > 0)
			{
				if ($post->parent_id == '' OR $post->parent_id == 0)
				{
					echo '<li>'.HTML::anchor('#', '<i class="icon-comment"></i>&nbsp; Comment', array('class' => 'comment_post_btn')).'</li>';
				}
				if ($user->id != $post->user_id)
				{
					echo '<li>'.HTML::anchor('#', '<i class="icon-flag"></i>&nbsp; Flag', array('class' => 'flag_post_btn')).'</li>';
				}
			}
		?>
		</ul>
	</div>
	<?php
		if ($post->parent_id == '' OR $post->parent_id == 0)
		{
	?>
	<div class="comment_post_subposts">
		<?php
			$page = 1;
			$params = array(
				'comment_id' => $comment->id,
				'order_by' => 'date_posted',
				'order_direction' => 'asc',
				'parent_id' => $post->id
			);
			
			$sub_posts = $post->get_posts($page, $params);
			$pagination = $sub_posts->pagination;
			$sub_posts = $sub_posts->posts;
			
			foreach ($sub_posts as $sub_post)
			{
				$view = View::factory('comment/embed/comment_post');
				$view->comment = $comment;
				$view->post = $sub_post;
				$view->user = $user;
				echo $view;
			}
		?>
	</div>
	<?php
		}
	?>
</div>