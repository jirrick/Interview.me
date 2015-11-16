ALTER TABLE `uzivatel` ADD `admin` BOOLEAN NOT NULL AFTER `heslo`;
UPDATE `uzivatel` SET `admin` = '1'