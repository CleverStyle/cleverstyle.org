<?php
/**
 * @package		Last_posts
 * @category	blocks
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2014, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs\modules\Blogs;
use			h,
			cs\Config,
			cs\DB,
			cs\Language;
$Config	= Config::instance();
$L		= Language::instance();
$module	= path($L->Blogs);
/**
 * Show administration, new post, draft actions
 */
$Blogs	= Blogs::instance();
$cdb	= DB::instance()->{$Config->module('Blogs')->db('posts')};
$posts	= $cdb->qfas(
	"SELECT `id`
	FROM `[prefix]blogs_posts`
	WHERE `draft` = 0
	ORDER BY `date` DESC
	LIMIT 5"
);
/**
 * @var array $block
 */
echo h::{'section.uk-panel.uk-panel-box.uk-panel-header.uk-margin-bottom'}(
	h::{'h2.uk-panel-title a'}(
		$block['title'],
		[
			'href'	=> $module
		]
	).
	implode('<hr>',
		array_map(
			function ($post) use ($module) {
				return h::{'article.cs-block-latest-posts'}(
					h::{'h3 a'}(
						$post['title'],
						[
							'href'	=> "$module/$post[path]:$post[id]"
						]
					).
					h::p(
						truncate($post['content'], 200)
					)
				);
			},
			$Blogs->get($posts)
		)
	)
);
