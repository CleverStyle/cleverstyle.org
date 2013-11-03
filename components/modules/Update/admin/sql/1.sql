RENAME TABLE `[prefix]logins` TO `[prefix]sign_ins` ;
DELETE FROM `[prefix]users`
WHERE
	`login_hash`	= '' AND
	`email_hash`	= '' AND
	`password_hash`	= '' AND
	`status`		= '-1' AND
	`id`			!= 1 AND
	`id`			!= 2;
ALTER TABLE `[prefix]users` DROP `theme`;