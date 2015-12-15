CREATE TABLE odpoved
(
	id_vyplnena_odpoved INTEGER NOT NULL AUTO_INCREMENT,
	id_prirazeny_test INTEGER NOT NULL,
	id_moznost INTEGER NOT NULL,
	vyplneno BOOL NOT NULL,
	slovni_odpoved TEXT,
	id_otazka INTEGER,
	spravne BOOL,
	PRIMARY KEY (id_vyplnena_odpoved),
	UNIQUE UQ_vyplnena_odpoved_id_vyplnena_odpoved(id_vyplnena_odpoved),
	KEY (id_moznost),
	KEY (id_otazka),
	KEY (id_prirazeny_test)
) 
;


CREATE TABLE prirazeny_test
(
	id_prirazeny_test INTEGER NOT NULL AUTO_INCREMENT,
	id_test INTEGER NOT NULL,
	id_kandidat INTEGER NOT NULL,
	odkaz TEXT NOT NULL,
	id_status INTEGER NOT NULL,
	hodnoceni INTEGER,
	otevren BOOL,
	datum_vyplneni DATE,
	komentar TEXT,
	id_kdo_priradil INTEGER,
	PRIMARY KEY (id_prirazeny_test),
	UNIQUE UQ_prirazeny_test_id_prirazeny_test(id_prirazeny_test),
	KEY (id_kandidat),
	KEY (id_status),
	KEY (id_test),
	KEY (id_kdo_priradil)
) 
;


CREATE TABLE moznost
(
	id_moznost INTEGER NOT NULL AUTO_INCREMENT,
	obsah TEXT NOT NULL,
	komentar TEXT,
	id_otazka INTEGER NOT NULL,
	spravnost BOOL NOT NULL,
	PRIMARY KEY (id_moznost),
	UNIQUE UQ_moznost_id_moznost(id_moznost),
	KEY (id_otazka)
) 
;


CREATE TABLE otazka
(
	id_otazka INTEGER NOT NULL AUTO_INCREMENT,
	obsah TEXT NOT NULL,
	komentar TEXT,
	id_test INTEGER NOT NULL,
	PRIMARY KEY (id_otazka),
	UNIQUE UQ_otazka_id_otazka(id_otazka),
	KEY (id_test)
) 
;


CREATE TABLE test
(
	id_test INTEGER NOT NULL AUTO_INCREMENT,
	nazev VARCHAR(50) NOT NULL,
	id_technologie INTEGER NOT NULL,
	popis TEXT,
	PRIMARY KEY (id_test),
	UNIQUE UQ_test_id_test(id_test),
	KEY (id_technologie)
) 
;


ALTER TABLE uzivatel ADD id_fotografie INTEGER NULL AFTER admin;
ALTER TABLE uzivatel ADD KEY (id_fotografie);


ALTER TABLE odpoved ADD CONSTRAINT FK_odpoved_moznost 
	FOREIGN KEY (id_moznost) REFERENCES moznost (id_moznost)
;

ALTER TABLE odpoved ADD CONSTRAINT FK_odpoved_otazka 
	FOREIGN KEY (id_otazka) REFERENCES otazka (id_otazka)
;

ALTER TABLE odpoved ADD CONSTRAINT FK_odpoved_prirazeny_test 
	FOREIGN KEY (id_prirazeny_test) REFERENCES prirazeny_test (id_prirazeny_test)
;

ALTER TABLE prirazeny_test ADD CONSTRAINT FK_prirazeny_test_kandidat 
	FOREIGN KEY (id_kandidat) REFERENCES kandidat (id_kandidat)
;

ALTER TABLE prirazeny_test ADD CONSTRAINT FK_prirazeny_test_status 
	FOREIGN KEY (id_status) REFERENCES status (id_status)
;

ALTER TABLE prirazeny_test ADD CONSTRAINT FK_prirazeny_test_test 
	FOREIGN KEY (id_test) REFERENCES test (id_test)
;

ALTER TABLE prirazeny_test ADD CONSTRAINT FK_prirazeny_test_uzivatel 
	FOREIGN KEY (id_kdo_priradil) REFERENCES uzivatel (id_uzivatel)
;

ALTER TABLE moznost ADD CONSTRAINT FK_moznost_otazka 
	FOREIGN KEY (id_otazka) REFERENCES otazka (id_otazka)
;

ALTER TABLE otazka ADD CONSTRAINT FK_otazka_test 
	FOREIGN KEY (id_test) REFERENCES test (id_test)
;

ALTER TABLE test ADD CONSTRAINT FK_test_technologie 
	FOREIGN KEY (id_technologie) REFERENCES technologie (id_technologie)
;

ALTER TABLE uzivatel ADD CONSTRAINT FK_uzivatel_fotografie 
	FOREIGN KEY (id_fotografie) REFERENCES fotografie (id_foto)
;


INSERT INTO status (nazev,popis) VALUES ('Zadán','');
INSERT INTO status (nazev,popis) VALUES ('Vyplněn','');
INSERT INTO status (nazev,popis) VALUES ('Ohodnocen','');


SET foreign_key_checks = 0;
DROP TABLE `uzivatel_role`;
DROP TABLE `role`;
SET foreign_key_checks = 1;
