ALTER TABLE `[prefix]sessions` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`);
ALTER TABLE `[prefix]sessions` ADD INDEX (`expire`);
ALTER TABLE `[prefix]users` DROP INDEX `password_hash`;
ALTER TABLE `[prefix]users` CHANGE `password_hash` `password_hash` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
