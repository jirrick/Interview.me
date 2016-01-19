DROP TABLE IF EXISTS `jazyk`;
CREATE TABLE `jazyk` (
    `id_jazyk` int(11) NOT NULL AUTO_INCREMENT,
    `kod` VARCHAR(20) NOT NULL ,
    `nazev` VARCHAR(20) NOT NULL ,
    PRIMARY KEY (`id_jazyk`))
;

INSERT INTO `jazyk` (`kod`, `nazev`) VALUES
('cpp','C++'),
('csharp', 'C#'),
('css', 'CSS'),
('java','Java'),
('javascript', 'JS'),
('markup', 'HTML'),
('objectivec','Objective-C'),
('php','PHP'),
('sql','SQL'),
('swift', 'Swift')
;

ALTER TABLE `otazka` ADD `id_jazyk` INT NULL DEFAULT NULL AFTER `revize`;
ALTER TABLE otazka ADD CONSTRAINT FK_otazka_jazyk
	FOREIGN KEY (id_jazyk) REFERENCES jazyk (id_jazyk)
;