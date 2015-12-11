CREATE TABLE `komentar` ( 
	`id_komentar` INT NOT NULL AUTO_INCREMENT ,
	`id_uzivatel` INT NOT NULL ,
	`id_kandidat` INT NOT NULL ,
	`cas` DATETIME NOT NULL ,
	`text` TEXT NOT NULL ,
	PRIMARY KEY (`id_komentar`)
) ENGINE = InnoDB; 

ALTER TABLE komentar ADD CONSTRAINT FK_komentar_uzivatel 
	FOREIGN KEY (id_uzivatel) REFERENCES uzivatel (id_uzivatel)
;

ALTER TABLE komentar ADD CONSTRAINT FK_komentar_kandidat 
	FOREIGN KEY (id_kandidat ) REFERENCES kandidat  (id_kandidat )
;