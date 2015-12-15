ALTER TABLE `prirazeny_test` CHANGE `datum_vyplneni` `datum_vyplneni` DATETIME NULL DEFAULT NULL; 
ALTER TABLE `prirazeny_test` ADD `datum_zahajeni` DATETIME NULL AFTER `datum_prirazeni`; 