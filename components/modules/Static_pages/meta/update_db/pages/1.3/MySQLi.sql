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
