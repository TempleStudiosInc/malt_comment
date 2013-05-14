<?php
    echo HTML::script('_media/core/admin/js/libs/ckeditor/ckeditor.js');
?>

<h3><?php echo $content_title ?></h3>
<div class="well">
    <div class="form medium_form">
        <?php
            echo Form::open('/admin_comment/save_post');
            echo Form::hidden('id', $post->id);
        ?>
        <div class="form_field">
            <?php 
                echo Form::label('comment_id', 'Blog');
				echo Form::hidden('comment_id', $post->comment->id);
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('content', 'Content');
                echo Form::textarea('content', $post->content, array('class' => 'span6 ckeditor'));
            ?>
        </div>
		<div class="form_field">
            <?php 
                echo Form::label('date_posted', 'Date Posted');
                echo Form::input('date_posted', date('m/d/Y h:i A', strtotime($post->date_posted)), array('class' => '', 'readonly' => 'readonly'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('date_modified', 'Date Modified');
                echo Form::input('date_modified', date('m/d/Y h:i A', strtotime($post->date_modified)), array('class' => '', 'readonly' => 'readonly'));
            ?>
        </div>
        <div class="form_field">
        	<?php
        		$posts_params = array(
					'limit' => 1000,
					'order_by' => 'date_posted',
					'order_direction' => 'asc',
					'parent_id' => $post->id
				);
				$sub_posts = $post->get_posts(1, $posts_params)->posts;
				
				if (count($sub_posts) > 0)
				{
					echo '<div class="well" style="background:white;">';
					echo '<h4>Replies</h4>';
					echo '<table class="table table-striped table-hover">';
					echo '<thead><tr>';
					echo '<th>User Name</th>';
					echo '<th>Comment</th>';
					echo '<th>Date Posted</th>';
					echo '<th></th>';
					echo '</tr></thead>';
					echo '<tbody>';
					
					foreach ($sub_posts as $sub_post)
	                {
	                    echo '<tr>';
						echo '<td>'.$sub_post->user->first_name.' '.$sub_post->user->last_name.'</td>';
						echo '<td>'.Text::limit_chars($sub_post->content, 100).'</td>';
						echo '<td>'.date('m/d/Y h:i A', strtotime($sub_post->date_posted)).'</td>';
						echo '<td>';
			?>
						<div class="buttons pull-right">
							<div class="btn-group">
			<?php
					echo HTML::anchor('/admin_comment/edit_post/'.$sub_post->id, '<i class="icon-pencil"></i> Edit Post', array('class' => 'btn btn-small'));
					echo HTML::anchor('/admin_comment/delete_post/'.$sub_post->id, '<i class="icon-trash icon-white"></i> Delete Post', array('class' => 'delete btn btn-danger btn-small'));
			?>
							</div>
						</div>
			<?php
						echo '</td>';
	                    echo '</tr>';
	                }
	                echo '</tbody>';
					echo '</table>';
					echo '</div>';
				}
			?>
        </div>
        
        <div class="form_field">
        	<?php
        		$flags = $post->flags->order_by('date_posted', 'asc')->find_all();
				
				if (count($flags) > 0)
				{
					echo '<div class="well" style="background:white;">';
					echo '<h4>Flags</h4>';
					echo '<table class="table table-striped table-hover">';
					echo '<thead><tr>';
					echo '<th>User Name</th>';
					echo '<th>Reason</th>';
					echo '<th>Comment</th>';
					echo '<th>Date Flagged</th>';
					echo '</tr></thead>';
					echo '<tbody>';
					foreach ($flags as $flag)
					{
						echo '<tr>';
						echo '<td>'.$flag->user->first_name.' '.$flag->user->last_name.'</td>';
						echo '<td>'.$flag->reason.'</td>';
						echo '<td>'.$flag->comment.'</td>';
						echo '<td>'.date('m/d/Y h:i A', strtotime($flag->date_posted)).'</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
					echo '</div>';
				}
			?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('status', 'Status');
                echo Form::select('status', $statuses, $post->status, array('class' => ''));
            ?>
        </div>
        <div class="buttons">
            <?php echo Form::button(NULL, 'Save', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
            or
            <?php echo HTML::anchor('/admin_comment/', 'cancel', array('class' => '')) ?>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>

<div class="modal hide dialog" id="delete_dialog">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>Confirmation Required</h3>
    </div>
    <div class="modal-body">
        <p>Are you sure you want to delete this?</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn modal_hide">No</a>
        <a href="#" class="btn btn-primary modal_delete_yes_button">Yes</a>
    </div>
</div>