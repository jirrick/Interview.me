SET foreign_key_checks = 0;

DROP TABLE status;

CREATE TABLE status
(
	id_status INTEGER NOT NULL AUTO_INCREMENT,
	kod VARCHAR(20) NOT NULL,
	nazev VARCHAR(50) NOT NULL,
	popis TEXT,
	PRIMARY KEY (id_status),
	UNIQUE UQ_status_id_status(id_status),
	UNIQUE UQ_status_kod(kod)
) 
;

INSERT INTO `status` (`kod`, `nazev`, `popis`) VALUES
('INVITED','Invited to interview', ''),
('WAITING', 'Waiting for response', ''),
('ACCEPTABLE', 'Acceptable', ''),
('UNACCEPTABLE','Unacceptable', ''),
('HIRED', 'Hired', ''),
('REJECTED', 'Offer rejected', ''),
('ASSIGNED','Assigned', ''),
('SUBMITTED', 'Submitted', ''),
('EVALUATED', 'Evaluated', '')
;


ALTER TABLE `prirazeny_test` CHANGE `odkaz` `odkaz` VARCHAR(24) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL;
ALTER TABLE `prirazeny_test` ADD INDEX( `odkaz`);
ALTER TABLE `prirazeny_test` ADD `datum_prirazeni` DATE NOT NULL AFTER `otevren`;

SET foreign_key_checks = 1;

