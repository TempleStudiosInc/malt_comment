<script>
	$(function() {
		load_comments();
		function load_comments()
		{
			var ajax_data = {};
			ajax_data.comment_id = <?php echo $comment->id ?>;
			
			$.ajax({
				url: "/comment/load_comments",
				dataType: 'html',
				cache: false,
				data: ajax_data
			}).done(function( html ) {
				$("#comment<?php echo $comment ?> > .comment_container").html(html);
				$('#add_comment_post_content_<?php echo $comment ?>').ckeditor({ height: 200 });
				$("#comment<?php echo $comment ?> > .loader").fadeOut('fast', function() {
					$("#comment<?php echo $comment ?> > .comment_container").fadeIn('fast');
				})
			});
		}
		
		$('.edit_post_btn').live('click', function(event) {
			event.preventDefault();
			$(this).closest('.comment_post_actions').hide('fast');
			var comment_post_id = $(this).closest('.comment_post').attr('comment_post_id');
			
			var comment_post_content = $(this).closest('.comment_post').children('.comment_post_content');
			
			var ajax_data = {};
			ajax_data.comment_post_id = comment_post_id;
			ajax_data.action = 'edit';
			
			$.ajax({
				type: "POST",
				url: '/comment/load_comment_post_add_edit_form',
				data: ajax_data,
				dataType: 'html',
				success: function(html) {
					comment_post_content.children('p').hide('fast');
					comment_post_content.append(html);
					$('#add_edit_comment_post_content_'+comment_post_id).ckeditor({ height: 100 });
				}
			})
		})
		$('.comment_post_btn').live('click', function(event) {
			event.preventDefault();
			$(this).hide('fast');
			var comment_post_id = $(this).parents('.comment_post').attr('comment_post_id');
			
			var comment_post_content = $(this).closest('.comment_post').children('.comment_post_content');
			var comment_post_subposts = $(this).closest('.comment_post').children('.comment_post_subposts');
			
			var ajax_data = {};
			ajax_data.parent_id = comment_post_id;
			ajax_data.comment_id = <?php echo $comment->id ?>,
			ajax_data.action = 'add';
			
			$.ajax({
				type: "POST",
				url: '/comment/load_comment_post_add_edit_form',
				data: ajax_data,
				dataType: 'html',
				success: function(html) {
					// comment_post_content.children('p').hide('fast');
					html = '<div class="comment_post">'+html;
					html+= '</div>';
					comment_post_subposts.append(html);
					$('#add_edit_comment_post_content_new').ckeditor({ height: 100 });
				}
			})
		})
		
		$('.cancel_comment').live('click', function(event) {
			event.preventDefault();
			var comment_post_id = $(this).closest('.comment_post').attr('comment_post_id');
			
			if (comment_post_id) {
				var comment_post_content = $(this).closest('.comment_post').children('.comment_post_content');
				
				$('#add_edit_comment_post_'+comment_post_id).hide('fast', function() {
					comment_post_content.children('p').show('fast');
					$(this).remove();
				});
				$(this).closest('.comment_post').children('.comment_post_actions').show('fast');
			} else {
				var comment_post = $(this).closest('.comment_post');
				var comment_post_parent_comment_post = comment_post.parents('.comment_post');
				
				comment_post.hide('fast', function() {
					$(this).remove();
					comment_post_parent_comment_post.find('.comment_post_btn').show('fast');
				})
			}
		})
		
		$('.post_comment_submit_btn').live('click', function() {
			event.preventDefault();
			
			var this_form = $(this).closest('form');
			$.ajax({
				type: "POST",
				url: this_form.attr('action'),
				data: this_form.serialize(),
				dataType: 'html',
				success: function(html) {
					load_comments();
				}
			})
		})
		
		$('.remove_post_btn').live('click', function(event) {
			event.preventDefault();
			var comment_post_id = $(this).parents('.comment_post').attr('comment_post_id');
			$('.delete_delete_yes_button').attr('comment_post_id', comment_post_id);
			$('#delete_modal').modal('show');
		})
		$('.delete_delete_yes_button').live('click', function(event) {
			event.preventDefault();
			var comment_post_id = $(this).attr('comment_post_id');
			var ajax_data = {};
			ajax_data.comment_post_id = comment_post_id;
			
			$.ajax({
				type: "POST",
				url: '/comment/remove_post',
				data: ajax_data,
				dataType: 'html',
				success: function(html) {
					$('#delete_modal').modal('hide');
					load_comments();
				}
			})
		})
		
		$('.flag_post_btn').live('click', function(event) {
			event.preventDefault();
			var comment_post_id = $(this).parents('.comment_post').attr('comment_post_id');
			$('input[name="comments_post_id"]').val(comment_post_id);
			$('#flag_modal textarea').val('');
			$('#flag_modal .modal_body_form').show();
			$('#flag_modal .modal_body_results').hide();
			$('#flag_modal').modal('show');
		})
		$('.submit_flag').live('click', function(event) {
			event.preventDefault();
			
			$.ajax({
				type: "POST",
				url: $('#flag_form').attr('action'),
				data: $('#flag_form').serialize(),
				dataType: 'html',
				success: function(html) {
					$('#flag_modal .modal_body_form').hide('fast', function() {
						$('#flag_modal .modal_body_results').html(html);
						$('#flag_modal .modal_body_results').show('fast');
					})
				}
			})
		})
	});
</script>
<div id="comment<?php echo $comment ?>" style="clear: both;">
	<div class="loader"><?php echo HTML::image('_media/core/common/img/loader.gif') ?></div>
	<div class="comment_container"></div>
</div>