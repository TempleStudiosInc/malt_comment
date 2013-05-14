<div class="well sidebar-nav">
    <ul class="nav nav-list">
    	<li class="nav-header">Quick Actions</li>
        <?php
        	echo '<li>';
        	echo HTML::anchor('/admin_comment/posts', 'All Comments');
			echo HTML::anchor('/admin_comment/posts?parent_id=0', 'Top Level Comments');
        	echo HTML::anchor('/admin_comment/posts?flagged=yes', 'Flagged Comments');
			echo '</li>';
        ?>
        <li class="divider"></li>
        <li class="nav-header">Pages</li>
        <?php
        	if (count($pages) > 0)
			{
            	foreach ($pages as $pages)
				{
					echo '<li>';
					echo HTML::anchor('/admin_comment/comment/'.$pages->id, $pages->page_url);
					echo '</li>';
				}
			}
			else
			{
				echo '<li>No pages exist.</li>';
			}
        ?>
        <li class="divider"></li>
        <li class="nav-header">Recent Comments</li>
        <?php
        	if (count($posts) > 0)
			{
				foreach ($posts as $post)
				{
					echo '<li>';
					echo HTML::anchor('/admin_comment/edit_post/'.$post->id, Text::limit_chars(strip_tags($post->content), 20));
					echo '</li>';
				}
			}
			else
			{
				echo '<li>';
				echo 'No recent posts.';
				echo '</li>';
			}
        ?>
    </ul>
</div><!--/.well -->