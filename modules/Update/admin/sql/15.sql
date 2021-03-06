ALTER TABLE `[prefix]blogs_posts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]blogs_posts_sections` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]blogs_posts_tags` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]blogs_sections` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]blogs_tags` DROP INDEX `text`, ADD UNIQUE `text` (`text`(191));
ALTER TABLE `[prefix]blogs_tags` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]texts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]texts_data` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
REPAIR TABLE `[prefix]blogs_posts`;
OPTIMIZE TABLE `[prefix]blogs_posts`;
REPAIR TABLE `[prefix]blogs_posts_sections`;
OPTIMIZE TABLE `[prefix]blogs_posts_sections`;
REPAIR TABLE `[prefix]blogs_posts_tags`;
OPTIMIZE TABLE `[prefix]blogs_posts_tags`;
REPAIR TABLE `[prefix]blogs_sections`;
OPTIMIZE TABLE `[prefix]blogs_sections`;
REPAIR TABLE `[prefix]blogs_tags`;
OPTIMIZE TABLE `[prefix]blogs_tags`;
REPAIR TABLE `[prefix]texts`;
OPTIMIZE TABLE `[prefix]texts`;
REPAIR TABLE `[prefix]texts_data`;
OPTIMIZE TABLE `[prefix]texts_data`;
ALTER TABLE `[prefix]comments` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
REPAIR TABLE `[prefix]comments`;
OPTIMIZE TABLE `[prefix]comments`;
ALTER TABLE `[prefix]plupload_files` DROP INDEX `url`, ADD UNIQUE `url` (`url`(191));
ALTER TABLE `[prefix]plupload_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]plupload_files_tags` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `tag`(191));
ALTER TABLE `[prefix]plupload_files_tags` DROP INDEX `tag`, ADD INDEX `tag` (`tag`(191));
ALTER TABLE `[prefix]plupload_files_tags` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
REPAIR TABLE `[prefix]plupload_files`;
OPTIMIZE TABLE `[prefix]plupload_files`;
REPAIR TABLE `[prefix]plupload_files_tags`;
OPTIMIZE TABLE `[prefix]plupload_files_tags`;
ALTER TABLE `[prefix]static_pages` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]static_pages_categories` DROP INDEX `path`, ADD UNIQUE `path` (`path`(191));
ALTER TABLE `[prefix]static_pages_categories` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]texts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `[prefix]texts_data` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
REPAIR TABLE `[prefix]static_pages`;
OPTIMIZE TABLE `[prefix]static_pages`;
REPAIR TABLE `[prefix]static_pages_categories`;
OPTIMIZE TABLE `[prefix]static_pages_categories`;
REPAIR TABLE `[prefix]texts`;
OPTIMIZE TABLE `[prefix]texts`;
REPAIR TABLE `[prefix]texts_data`;
OPTIMIZE TABLE `[prefix]texts_data`;
