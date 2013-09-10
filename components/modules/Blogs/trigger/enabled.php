<?php
/**
 * @package		Blogs
 * @category	modules
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs\modules\Blogs;
use			cs\Config,
			cs\Trigger,
			cs\User;
Trigger::instance()->register(
	'api/Comments/add',
	function ($data) {
		$Comments	= null;
		Trigger::instance()->run(
			'Comments/instance',
			[
				'Comments'	=> &$Comments
			]
		);
		/**
		 * @var \cs\modules\Comments\Comments $Comments
		 */
		if (!(
			$data['module'] == 'Blogs' &&
			Config::instance()->module('Blogs')->enable_comments &&
			User::instance()->user() &&
			$Comments
		)) {
			return true;
		}
		if (Blogs::instance()->get($data['item'])) {
			$Comments->set_module('Blogs');
			$data['Comments']	= $Comments;
		}
		return false;
	}
);
Trigger::instance()->register(
	'api/Comments/edit',
	function ($data) {
		$Comments	= null;
		Trigger::instance()->run(
			'Comments/instance',
			[
				'Comments'	=> &$Comments
			]
		);
		/**
		 * @var \cs\modules\Comments\Comments $Comments
		 */
		$User		= User::instance();
		if (!(
			$data['module'] == 'Blogs' &&
			Config::instance()->module('Blogs')->enable_comments &&
			$User->user() &&
			$Comments
		)) {
			return true;
		}
		$Comments->set_module('Blogs');
		$comment	= $Comments->get($data['id']);
		if ($comment && ($comment['user'] == $User->id || $User->admin())) {
			$data['Comments']	= $Comments;
		}
		return false;
	}
);
Trigger::instance()->register(
	'api/Comments/delete',
	function ($data) {
		$Comments	= null;
		Trigger::instance()->run(
			'Comments/instance',
			[
				'Comments'	=> &$Comments
			]
		);
		/**
		 * @var \cs\modules\Comments\Comments $Comments
		 */
		$User		= User::instance();
		if (!(
			$data['module'] == 'Blogs' &&
			Config::instance()->module('Blogs')->enable_comments &&
			$User->user() &&
			$Comments
		)) {
			return true;
		}
		$Comments->set_module('Blogs');
		$comment	= $Comments->get($data['id']);
		if ($comment && ($comment['user'] == $User->id || $User->admin())) {
			$data['Comments']	= $Comments;
			if (
				$comment['parent'] &&
				(
					$comment = $Comments->get($comment['parent'])
				) && (
					$comment['user']  == $User->id || $User->admin()
				)
			) {
				$data['delete_parent']	= true;
			}
		}
		return false;
	}
);