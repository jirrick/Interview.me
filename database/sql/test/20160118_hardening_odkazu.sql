SET foreign_key_checks = 0;

ALTER TABLE `prirazeny_test` DROP COLUMN `odkaz`;
ALTER TABLE `prirazeny_test` ADD `odkaz` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL AFTER `id_kandidat`;

UPDATE `prirazeny_test` 
SET `odkaz` = MD5(RAND());

ALTER TABLE `prirazeny_test` DROP COLUMN `kontrola`;
ALTER TABLE `prirazeny_test` ADD `kontrola` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL AFTER `odkaz`;

UPDATE `prirazeny_test` 
SET `kontrola` = MD5(RAND())
WHERE `otevren` = 0;

SET foreign_key_checks = 1;