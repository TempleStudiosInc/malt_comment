<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'admin' => array(
		'content' => array(
			'title' => 'Content',
			'url' => '/admin_content',
			'controller' => 'Content',
			'permission' => 'admin',
			'submenu' => array(
				'comment' => array(
					'title' => 'Comments',
					'url' => '/admin_comment',
					'controller' => 'Comment',
					'permission' => 'comment',
					'icon' => 'icon-comments'
				),
			)
		)
	)
);
