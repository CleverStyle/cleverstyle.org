<?php
/**
 * @package   Last_posts
 * @category  blocks
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2014-2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
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
echo h::{'div.cs-side-block'}(
	h::a(
		h::h3($block['title']),
		[
			'href' => 'Blogs'
		]
	).
	h::{'section.cs-blocks-last-posts article'}(
		array_map(
			function ($post) {
				return
					h::a(
						h::h3($post['title']),
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
	)
);
