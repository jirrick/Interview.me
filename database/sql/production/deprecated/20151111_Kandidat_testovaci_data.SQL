INSERT INTO 
`kandidat` (`id_kandidat`, `jmeno`, `prijmeni`, `datum_narozeni`, `id_pozice`, `id_seniorita`, `komentar`, `id_status`, `datum_aktualizace`, `datum_pohovoru`, `id_foto`)
VALUES
(1, 'Barrack', 'Obama', '2014-12-16', '2', '3', NULL, '1', '2015-11-09', '2016-01-07', NULL),
(2, 'Vladimir', 'Putin', '2015-05-25', '1', '1', NULL, '2', '2015-11-10', '2016-02-27', NULL),
(3, 'Miloš', 'Zeman', '2013-04-22', '3', '3', NULL, '3', '2015-11-08', '2016-01-23', NULL);


INSERT INTO 
`kandidat_technologie` (`id_kandidat`, `id_technologie`)
VALUES
('1', '6'),
('1', '4'),
('1', '2'),
('2', '1'),
('2', '3'),
('3', '5'),
('3', '1');