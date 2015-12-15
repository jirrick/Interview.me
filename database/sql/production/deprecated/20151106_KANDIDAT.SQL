CREATE TABLE kandidat_technologie
(
	id_kandidat_technologie INTEGER NOT NULL AUTO_INCREMENT,
	id_kandidat INTEGER NOT NULL,
	id_technologie INTEGER NOT NULL,
	PRIMARY KEY (id_kandidat_technologie),
	UNIQUE UQ_kandidat_technologie_id_kandidat_technologie(id_kandidat_technologie),
	KEY (id_kandidat),
	KEY (id_technologie)
) 
;


CREATE TABLE kandidat_priloha
(
	id_kandidat_priloha INTEGER NOT NULL AUTO_INCREMENT,
	id_priloha INTEGER NOT NULL,
	id_kandidat INTEGER NOT NULL,
	PRIMARY KEY (id_kandidat_priloha),
	UNIQUE UQ_kandidat_priloha_id_kandidat_priloha(id_kandidat_priloha),
	KEY (id_kandidat),
	KEY (id_priloha)
) 
;



CREATE TABLE priloha
(
	id_priloha INTEGER NOT NULL AUTO_INCREMENT,
	priloha mediumblob	 NOT NULL,
	PRIMARY KEY (id_priloha),
	UNIQUE UQ_priloha_id_priloha(id_priloha)
) 
;


CREATE TABLE fotografie
(
	id_foto INTEGER NOT NULL AUTO_INCREMENT,
	foto mediumblob	 NOT NULL,
	PRIMARY KEY (id_foto),
	UNIQUE UQ_fotografie_id_foto(id_foto)
) 
;


CREATE TABLE status
(
	id_status INTEGER NOT NULL AUTO_INCREMENT,
	nazev VARCHAR(50) NOT NULL,
	popis TEXT,
	PRIMARY KEY (id_status),
	UNIQUE UQ_status_id_status(id_status)
) 
;


CREATE TABLE seniorita
(
	id_seniorita INTEGER NOT NULL AUTO_INCREMENT,
	nazev VARCHAR(50) NOT NULL,
	popis TEXT,
	PRIMARY KEY (id_seniorita),
	UNIQUE UQ_seniorita_id_seniorita(id_seniorita)
) 
;


CREATE TABLE technologie
(
	id_technologie INTEGER NOT NULL AUTO_INCREMENT,
	nazev VARCHAR(50) NOT NULL,
	popis TEXT,
	PRIMARY KEY (id_technologie),
	UNIQUE UQ_technologie_id_technologie(id_technologie)
) 
;


CREATE TABLE pozice
(
	id_pozice INTEGER NOT NULL AUTO_INCREMENT,
	nazev VARCHAR(50) NOT NULL,
	popis TEXT,
	PRIMARY KEY (id_pozice),
	UNIQUE UQ_pozice_id_pozice(id_pozice),
	UNIQUE UQ_pozice_nazev(nazev)
) 
;


CREATE TABLE kandidat
(
	id_kandidat INTEGER NOT NULL AUTO_INCREMENT,
	jmeno VARCHAR(50) NOT NULL,
	prijmeni VARCHAR(50) NOT NULL,
	datum_narozeni DATE,
	id_pozice INTEGER NOT NULL,
	id_technologie INTEGER NOT NULL,
	id_seniorita INTEGER NOT NULL,
	komentar TEXT,
	id_status INTEGER NOT NULL  ,
	datum_aktualizace DATE NOT NULL,
	datum_pohovoru DATE NOT NULL,
	id_foto INTEGER,
	PRIMARY KEY (id_kandidat),
	UNIQUE UQ_kandidat_id_kandidat(id_kandidat),
	KEY (id_foto),
	KEY (id_pozice),
	KEY (id_seniorita),
	KEY (id_status)
) 
;





ALTER TABLE kandidat_technologie ADD CONSTRAINT FK_kandidat_technologie_kandidat 
	FOREIGN KEY (id_kandidat) REFERENCES kandidat (id_kandidat)
	ON DELETE CASCADE ON UPDATE CASCADE
;

ALTER TABLE kandidat_technologie ADD CONSTRAINT FK_kandidat_technologie_technologie 
	FOREIGN KEY (id_technologie) REFERENCES technologie (id_technologie)
	ON DELETE CASCADE ON UPDATE CASCADE
;

ALTER TABLE kandidat_priloha ADD CONSTRAINT FK_kandidat_priloha_kandidat 
	FOREIGN KEY (id_kandidat) REFERENCES kandidat (id_kandidat)
	ON DELETE CASCADE ON UPDATE CASCADE
;

ALTER TABLE kandidat_priloha ADD CONSTRAINT FK_kandidat_priloha_priloha 
	FOREIGN KEY (id_priloha) REFERENCES priloha (id_priloha)
	ON DELETE CASCADE ON UPDATE CASCADE
;

ALTER TABLE kandidat ADD CONSTRAINT FK_kandidat_fotografie 
	FOREIGN KEY (id_foto) REFERENCES fotografie (id_foto)
	ON DELETE CASCADE ON UPDATE CASCADE
;

ALTER TABLE kandidat ADD CONSTRAINT FK_kandidat_pozice 
	FOREIGN KEY (id_pozice) REFERENCES pozice (id_pozice)
	ON DELETE CASCADE ON UPDATE CASCADE
;

ALTER TABLE kandidat ADD CONSTRAINT FK_kandidat_seniorita 
	FOREIGN KEY (id_seniorita) REFERENCES seniorita (id_seniorita)
	ON DELETE CASCADE ON UPDATE CASCADE
;

ALTER TABLE kandidat ADD CONSTRAINT FK_kandidat_status 
	FOREIGN KEY (id_status) REFERENCES status (id_status)
	ON DELETE CASCADE ON UPDATE CASCADE
;







INSERT INTO status (nazev,popis) VALUES ('Pozván na pohovor','');
INSERT INTO status (nazev,popis) VALUES ('Èeká na odpovìï','');
INSERT INTO status (nazev,popis) VALUES ('Vyhovující','');
INSERT INTO status (nazev,popis) VALUES ('Nevyhovující','');
INSERT INTO status (nazev,popis) VALUES ('Nastoupil','');
INSERT INTO status (nazev,popis) VALUES ('Odmítnul nabídku','');


INSERT INTO seniorita (nazev,popis) VALUES ('Junior','');
INSERT INTO seniorita (nazev,popis) VALUES ('Middle','');
INSERT INTO seniorita (nazev,popis) VALUES ('Senior','');

INSERT INTO technologie (nazev,popis) VALUES ('.NET','');
INSERT INTO technologie (nazev,popis) VALUES ('JAVA','');
INSERT INTO technologie (nazev,popis) VALUES ('Javascript','');
INSERT INTO technologie (nazev,popis) VALUES ('iOS','');
INSERT INTO technologie (nazev,popis) VALUES ('Android','');
INSERT INTO technologie (nazev,popis) VALUES ('C++','');
INSERT INTO technologie (nazev,popis) VALUES ('PHP','');
INSERT INTO technologie (nazev,popis) VALUES ('Drupal','');
INSERT INTO technologie (nazev,popis) VALUES ('CAD','');
INSERT INTO technologie (nazev,popis) VALUES ('UI/UX','');
INSERT INTO technologie (nazev,popis) VALUES ('QA','');
INSERT INTO technologie (nazev,popis) VALUES ('PM','');
INSERT INTO technologie (nazev,popis) VALUES ('Marketing','');
INSERT INTO technologie (nazev,popis) VALUES ('Sales','');
INSERT INTO technologie (nazev,popis) VALUES ('Administration','');


INSERT INTO pozice (nazev,popis) VALUES ('Developer','');
INSERT INTO pozice (nazev,popis) VALUES ('Tester','');
INSERT INTO pozice (nazev,popis) VALUES ('Manager','');
INSERT INTO pozice (nazev,popis) VALUES ('Assistant','');