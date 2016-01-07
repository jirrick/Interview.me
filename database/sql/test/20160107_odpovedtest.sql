SET foreign_key_checks = 0;

ALTER TABLE `odpoved`
CHANGE `id_vyplnena_odpoved` `id_vyplnena_odpoved` INT(11) NOT NULL AUTO_INCREMENT,
CHANGE `id_moznost` `id_moznost` INT(11) NULL DEFAULT NULL,
CHANGE `vyplneno` `vyplneno` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `slovni_odpoved` `slovni_odpoved` TEXT CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
CHANGE `id_otazka` `id_otazka` INT(11) NOT NULL,
CHANGE `spravne` `spravne` TINYINT(1) NULL DEFAULT NULL; 

SET foreign_key_checks = 1;