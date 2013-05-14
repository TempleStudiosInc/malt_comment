<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Comments_Posts_Flag extends ORM {
	
	protected $_belongs_to = array(
		'user' => array(),
		'post' => array(
        	'model' => 'Comments_Post',
    	),
	);
}