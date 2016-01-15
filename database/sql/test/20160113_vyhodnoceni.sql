SET foreign_key_checks = 0;

ALTER TABLE `prirazeny_test` ADD `kontrola` VARCHAR(24) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL AFTER `odkaz`;

SET foreign_key_checks = 1;