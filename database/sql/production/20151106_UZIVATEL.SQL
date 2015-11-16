CREATE TABLE uzivatel_role(
  id_uzivatel_role INTEGER NOT NULL AUTO_INCREMENT,
  id_uzivatel INTEGER NOT NULL,
  id_role INTEGER NOT NULL,
  PRIMARY KEY(id_uzivatel_role),
  UNIQUE UQ_uzivatel_role_id_uzivatel_role(id_uzivatel_role),
  KEY(id_role),
  KEY(id_uzivatel)
);
CREATE TABLE role(
  id_role INTEGER NOT NULL AUTO_INCREMENT,
  nazev VARCHAR(50),
  popis TEXT,
  PRIMARY KEY(id_role),
  UNIQUE UQ_role_id_role(id_role),
  UNIQUE UQ_role_nazev(nazev)
);
CREATE TABLE uzivatel(
  id_uzivatel INTEGER NOT NULL AUTO_INCREMENT,
  jmeno VARCHAR(50) NOT NULL,
  prijmeni VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  heslo VARCHAR(100) NOT NULL,
  PRIMARY KEY(id_uzivatel),
  UNIQUE UQ_uzivatel_email(email),
  UNIQUE UQ_uzivatel_id_uzivatel(id_uzivatel)
);
ALTER TABLE
  uzivatel_role ADD CONSTRAINT FK_uzivatel_role_role FOREIGN KEY(id_role) REFERENCES role(id_role) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE
  uzivatel_role ADD CONSTRAINT FK_uzivatel_role_uzivatel FOREIGN KEY(id_uzivatel) REFERENCES uzivatel(id_uzivatel) ON DELETE CASCADE ON UPDATE CASCADE;







INSERT INTO role (nazev,popis)
VALUES ('Administrator','Tento uživatel má právo na všechno');

INSERT INTO uzivatel (jmeno,prijmeni,email,heslo)
VALUES ('Anatoliy','Kybkalo','anatoliy.kybkalo@gmail.com','admin');

INSERT INTO uzivatel_role(id_uzivatel,id_role)
VALUES (1,1);