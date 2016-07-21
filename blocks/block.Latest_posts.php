<?php
/**
 * @package        Last_posts
 * @category       blocks
 * @author         Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright      Copyright (c) 2014, Nazar Mokrynskyi
 * @license        MIT License, see license.txt
 */
namespace cs\modules\Blogs;
use
	h;

$Posts = Posts::instance();
$posts = $Posts->get(
	$Posts->get_latest_posts(1, 5)
);
/**
 * @var array $block
 */
echo h::{'h3 a'}(
	$block['title'],
	[
		'href' => 'Blogs'
	]
);
echo h::{'section.cs-blocks-last-posts article'}(
	array_map(
		function ($post) {
			return
				h::{'h3 a'}(
					$post['title'],
					[
						'href' => "Blogs/$post[path]:$post[id]"
					]
				).
				h::p(
					truncate($post['content'], 200)
				);
		},
		$posts
	)
);
