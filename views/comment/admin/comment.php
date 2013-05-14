<h3><?php echo $content_title ?></h3>

<div class="well">
	<h4>Posts</h4>
	
	<?php echo $pagination ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Comment</th>
                <th>Date Posted</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
				if (count($posts) > 0)
				{
	                foreach ($posts as $post)
	                {
	                	$name = '';
						if($post->user->first_name != '')
						{
							$name = $post->user->first_name;
						}
						elseif ($post->user->username != '')
						{
							$name = $post->user->username;
						}
						
	                    echo '<tr>';
						echo '<td>'.$name.'</td>';
	                    echo '<td>'.Text::limit_chars(strip_tags($post->content), 100).'</td>';
	                    echo '<td>'.date('m/d/Y h:i A', strtotime($post->date_posted)).'</td>';
						echo '<td>';
			?>
						<div class="buttons pull-right">
							<div class="btn-group">
			<?php
				echo HTML::anchor('/admin_comment/edit_post/'.$post->id, '<i class="icon-pencil"></i> Edit Post', array('class' => 'btn btn-small'));
				echo HTML::anchor('/admin_comment/delete_post/'.$post->id, '<i class="icon-trash icon-white"></i> Delete Post', array('class' => 'delete btn btn-danger btn-small'));
			?>
							</div>
						</div>
			<?php
						echo '</td>';
	                    echo '</tr>';
	                }
				}
				else
				{
					echo '<tr>';
                    echo '<td colspan="2">No recent posts.</td>';
                    echo '</tr>';
				}
            ?>
        </tbody>
    </table>
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