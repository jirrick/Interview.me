SET foreign_key_checks = 0;

ALTER TABLE `otazka` ADD `revize` INT NULL DEFAULT NULL AFTER `id_test`; 
ALTER TABLE otazka ADD CONSTRAINT FK_otazka_revize 
	FOREIGN KEY (revize) REFERENCES otazka (id_otazka)
;

ALTER TABLE `moznost` ADD `revize` INT NULL DEFAULT NULL AFTER `spravnost`;
ALTER TABLE moznost ADD CONSTRAINT FK_moznost_revize 
	FOREIGN KEY (revize) REFERENCES moznost (id_moznost)
;

UPDATE `status` SET `kod` = 'DECLINED', `nazev` = 'Offer declined' WHERE `status`.`kod` = 'REJECTED';
UPDATE `status` SET `kod` = 'ACCEPTED', `nazev` = 'Accepted' WHERE `status`.`kod` = 'ACCEPTABLE' ;
UPDATE `status` SET `kod` = 'REJECTED', `nazev` = 'Rejcted' WHERE `status`.`kod` = 'UNACCEPTABLE' ;

SET foreign_key_checks = 1;