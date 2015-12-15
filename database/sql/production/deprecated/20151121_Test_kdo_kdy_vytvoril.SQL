SET foreign_key_checks = 0;
ALTER TABLE test ADD datum_vytvoreni DATE NOT NULL AFTER popis;
ALTER TABLE test ADD id_kdo_vytvoril INTEGER NOT NULL AFTER datum_vytvoreni;
ALTER TABLE test ADD KEY (id_kdo_vytvoril);
ALTER TABLE test ADD CONSTRAINT FK_test_uzivatel 
	FOREIGN KEY (id_kdo_vytvoril) REFERENCES uzivatel (id_uzivatel);
SET foreign_key_checks = 1;