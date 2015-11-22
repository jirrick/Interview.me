SET foreign_key_checks = 0;
ALTER TABLE test ADD pocet_minut INTEGER NOT NULL;
ALTER TABLE test ADD pocet_otazek INTEGER NULL;
ALTER TABLE test ADD id_seniorita INTEGER NOT NULL;
ALTER TABLE test ADD KEY (id_seniorita);
ALTER TABLE test ADD CONSTRAINT FK_test_seniorita 
	FOREIGN KEY (id_seniorita) REFERENCES seniorita (id_seniorita);
SET foreign_key_checks = 1;