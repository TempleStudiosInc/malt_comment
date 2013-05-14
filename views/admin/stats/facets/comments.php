<?php
	$date_range = explode(' - ', Arr::get($params, 'date_range'));
	$start_date = date('Y-m-d 00:00:00', strtotime($date_range[0]));
	$end_date = date('Y-m-d 23:59:59', strtotime($date_range[1]));
?>

<?php
	$comments_orm = ORM::factory('Comments_Post');
	$comments_orm->select(DB::expr('DATE_FORMAT(date_posted, "%m-%d-%Y") AS date_posted_group'));
	$comments_orm->select(DB::expr('COUNT(id) AS comment_count'));
	$comments_orm->where('date_posted', 'BETWEEN', array($start_date, $end_date));
	$comments_orm->where('status', '=', 'active');
	$comments_orm->group_by(DB::expr('YEAR(date_posted)'));
	$comments_orm->group_by(DB::expr('MONTH(date_posted)'));
	$comments_orm->group_by(DB::expr('DAY(date_posted)'));
	$comments_orm = $comments_orm->find_all();
	
	$comments = array();
	foreach ($comments_orm as $comment)
	{
		$comments[$comment->date_posted_group] = (int) $comment->comment_count;
	}
?>
<div class="span4">
	<h5>Comments by Date</h5>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Date</th>
				<th style="text-align:right;">Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($comments as $date => $comment_count)
			{
				echo '<tr>';
				echo '<td>'.$date.'</td>';
				echo '<td style="text-align:right;">'.number_format($comment_count).'</td>';
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
</div>

<?php
	$comments_orm = ORM::factory('Comments_Post');
	$comments_orm->select(DB::expr('COUNT(comments_post.id) AS comment_count'));
	$comments_orm->join('comments');
	$comments_orm->on('comments.id', '=', 'comments_post.comment_id');
	$comments_orm->where('date_posted', 'BETWEEN', array($start_date, $end_date));
	$comments_orm->where('comments_post.status', '=', 'active');
	$comments_orm->group_by('comments.page_url');
	$comments_orm->order_by('comment_count', 'DESC');
	$comments_orm->limit(10);
	$comments_orm = $comments_orm->find_all();
	
	$comments = array();
	foreach ($comments_orm as $comment)
	{
		$comments[$comment->comment->page_url] = (int) $comment->comment_count;
	}
?>
<div class="span4">
	<h5>Most Commented Pages</h5>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>URL</th>
				<th style="text-align:right;">Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($comments as $page_url => $comment_count)
			{
				echo '<tr>';
				echo '<td>'.$page_url.'</td>';
				echo '<td style="text-align:right;">'.number_format($comment_count).'</td>';
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
</div>

<?php
	$comments_orm = ORM::factory('Comments_Post');
	$comments_orm->select(DB::expr('COUNT(comments_post.id) AS comment_count'));
	$comments_orm->where('date_posted', 'BETWEEN', array($start_date, $end_date));
	$comments_orm->where('comments_post.status', '=', 'active');
	$comments_orm->group_by('user_id');
	$comments_orm->order_by('comment_count', 'DESC');
	$comments_orm->limit(10);
	$comments_orm = $comments_orm->find_all();
	
	$comments = array();
	foreach ($comments_orm as $comment)
	{
		$comments[$comment->user->email] = (int) $comment->comment_count;
	}
?>
<div class="span4">
	<h5>Most Comments by Users</h5>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>User</th>
				<th style="text-align:right;">Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($comments as $user => $comment_count)
			{
				echo '<tr>';
				echo '<td>'.$user.'</td>';
				echo '<td style="text-align:right;">'.number_format($comment_count).'</td>';
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
</div>