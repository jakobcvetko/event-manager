/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50515
Source Host           : localhost:3306
Source Database       : ovks_db

Target Server Type    : MYSQL
Target Server Version : 50515
File Encoding         : 65001

Date: 2012-01-22 23:00:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `dogodki`
-- ----------------------------
DROP TABLE IF EXISTS `dogodki`;
CREATE TABLE `dogodki` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organizator_id` int(10) unsigned NOT NULL,
  `naziv` varchar(45) NOT NULL,
  `kraj` varchar(45) NOT NULL,
  `drzava` varchar(45) NOT NULL,
  `opomba` varchar(255) DEFAULT NULL,
  `javni` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_pos_dogodki_uporabniki` (`organizator_id`),
  CONSTRAINT `fk_pos_dogodki_uporabniki` FOREIGN KEY (`organizator_id`) REFERENCES `uporabniki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dogodki
-- ----------------------------
INSERT INTO `dogodki` VALUES ('8', '3', 'all night party', 'Maribor', 'Slovenija', null, '0');
INSERT INTO `dogodki` VALUES ('10', '1', 'Zagovor Vaje pri OVKS', 'Maribor Feri', 'Slovenija', null, '0');
INSERT INTO `dogodki` VALUES ('11', '1', 'Periodični Dogodek', 'Rogaška Slatina', 'Slovenija', null, '0');

-- ----------------------------
-- Table structure for `dogodki_po_skupinah`
-- ----------------------------
DROP TABLE IF EXISTS `dogodki_po_skupinah`;
CREATE TABLE `dogodki_po_skupinah` (
  `skupina_id` int(10) unsigned NOT NULL,
  `dogodek_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`skupina_id`,`dogodek_id`),
  KEY `fk_dogodki_has_skupine_dog_skupine_dog1` (`skupina_id`),
  KEY `fk_dogodki_has_skupine_dog_dogodki1` (`dogodek_id`),
  CONSTRAINT `fk_dogodki_has_skupine_dog_dogodki1` FOREIGN KEY (`dogodek_id`) REFERENCES `dogodki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dogodki_has_skupine_dog_skupine_dog1` FOREIGN KEY (`skupina_id`) REFERENCES `skupine_dog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dogodki_po_skupinah
-- ----------------------------

-- ----------------------------
-- Table structure for `komentarji_dog`
-- ----------------------------
DROP TABLE IF EXISTS `komentarji_dog`;
CREATE TABLE `komentarji_dog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dogodek_id` int(10) unsigned NOT NULL,
  `uporabnik_id` int(10) unsigned NOT NULL,
  `komentar` varchar(255) NOT NULL,
  `datum` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_komentarji_dog_dogodki1` (`dogodek_id`),
  KEY `fk_komentarji_dog_uporabniki1` (`uporabnik_id`),
  CONSTRAINT `fk_komentarji_dog_dogodki1` FOREIGN KEY (`dogodek_id`) REFERENCES `dogodki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_komentarji_dog_uporabniki1` FOREIGN KEY (`uporabnik_id`) REFERENCES `uporabniki` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of komentarji_dog
-- ----------------------------
INSERT INTO `komentarji_dog` VALUES ('7', '8', '1', 'Party Night !', '2012-01-19 03:09:26');
INSERT INTO `komentarji_dog` VALUES ('8', '10', '1', 'Lol', '2012-01-20 14:55:04');

-- ----------------------------
-- Table structure for `komentarji_org`
-- ----------------------------
DROP TABLE IF EXISTS `komentarji_org`;
CREATE TABLE `komentarji_org` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uporabnik_id` int(10) unsigned NOT NULL,
  `komentator_id` int(10) unsigned NOT NULL,
  `komentar` varchar(255) NOT NULL,
  `datum` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_komentarji_org_organizatorji1` (`uporabnik_id`),
  KEY `fk_komentarji_org_uporabniki1` (`komentator_id`),
  CONSTRAINT `fk_komentarji_org_organizatorji1` FOREIGN KEY (`uporabnik_id`) REFERENCES `uporabniki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_komentarji_org_uporabniki1` FOREIGN KEY (`komentator_id`) REFERENCES `uporabniki` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of komentarji_org
-- ----------------------------
INSERT INTO `komentarji_org` VALUES ('1', '1', '2', 'Komentar!!', '2012-01-18 11:19:51');
INSERT INTO `komentarji_org` VALUES ('2', '1', '2', 'Dober dan!', '2012-01-18 11:20:03');
INSERT INTO `komentarji_org` VALUES ('3', '1', '1', 'LoLzz', '2012-01-18 11:20:18');
INSERT INTO `komentarji_org` VALUES ('4', '1', '2', 'wot', '2012-01-18 11:20:24');
INSERT INTO `komentarji_org` VALUES ('5', '2', '2', 'Nema nema', '2012-01-18 11:21:20');
INSERT INTO `komentarji_org` VALUES ('6', '1', '3', 'yo', '2012-01-19 01:00:23');
INSERT INTO `komentarji_org` VALUES ('7', '3', '1', 'LooL!!', '2012-01-19 01:07:26');
INSERT INTO `komentarji_org` VALUES ('8', '2', '2', 'ima ima\r\n', '2012-01-19 13:12:54');
INSERT INTO `komentarji_org` VALUES ('9', '1', '1', 'test\r\n', '2012-01-19 18:38:20');

-- ----------------------------
-- Table structure for `osebe`
-- ----------------------------
DROP TABLE IF EXISTS `osebe`;
CREATE TABLE `osebe` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uporabnik_id` int(10) unsigned NOT NULL,
  `ime` varchar(75) NOT NULL,
  `priimek` varchar(75) NOT NULL,
  `datum_rojstva` date NOT NULL,
  `spol` varchar(1) DEFAULT NULL,
  `opomba` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_udelezenci_uporabniki1` (`uporabnik_id`),
  CONSTRAINT `fk_udelezenci_uporabniki1` FOREIGN KEY (`uporabnik_id`) REFERENCES `uporabniki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osebe
-- ----------------------------
INSERT INTO `osebe` VALUES ('5', '1', 'Jakob', 'Cvetko', '1991-04-21', 'm', 'Pa toti je care!');
INSERT INTO `osebe` VALUES ('6', '1', 'Matija', 'Žolek', '1992-01-22', 'm', null);
INSERT INTO `osebe` VALUES ('7', '1', 'Urban', 'Rotnik', '1991-08-13', 'm', null);
INSERT INTO `osebe` VALUES ('8', '1', 'Sabina', 'Mlinarič', '1991-10-16', 'z', null);
INSERT INTO `osebe` VALUES ('9', '2', 'Jakobe', 'Olee', '1990-02-01', 'm', null);
INSERT INTO `osebe` VALUES ('10', '2', 'Kr en', 'Čapac', '1990-02-01', 'm', null);
INSERT INTO `osebe` VALUES ('11', '2', 'Jakec', 'Yo', '1990-02-01', 'm', null);
INSERT INTO `osebe` VALUES ('12', '2', 'Čapac', 'WTF', '1990-02-01', 'm', null);
INSERT INTO `osebe` VALUES ('13', '2', 'ALO Bre', 'Bre', '1990-02-01', 'm', null);
INSERT INTO `osebe` VALUES ('14', '3', 'kmet', 'ftw', '1990-02-01', 'm', null);

-- ----------------------------
-- Table structure for `osebe_po_skupinah`
-- ----------------------------
DROP TABLE IF EXISTS `osebe_po_skupinah`;
CREATE TABLE `osebe_po_skupinah` (
  `skupina_id` int(10) unsigned NOT NULL,
  `oseba_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`skupina_id`,`oseba_id`),
  KEY `fk_skupine_has_udelezenci_udelezenci1` (`oseba_id`),
  KEY `fk_skupine_has_udelezenci_skupine1` (`skupina_id`),
  CONSTRAINT `fk_skupine_has_udelezenci_skupine1` FOREIGN KEY (`skupina_id`) REFERENCES `skupine_oseb` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_skupine_has_udelezenci_udelezenci1` FOREIGN KEY (`oseba_id`) REFERENCES `osebe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osebe_po_skupinah
-- ----------------------------
INSERT INTO `osebe_po_skupinah` VALUES ('4', '5');
INSERT INTO `osebe_po_skupinah` VALUES ('4', '6');
INSERT INTO `osebe_po_skupinah` VALUES ('4', '7');
INSERT INTO `osebe_po_skupinah` VALUES ('5', '8');
INSERT INTO `osebe_po_skupinah` VALUES ('6', '9');
INSERT INTO `osebe_po_skupinah` VALUES ('6', '10');
INSERT INTO `osebe_po_skupinah` VALUES ('8', '14');

-- ----------------------------
-- Table structure for `prijateljstvo`
-- ----------------------------
DROP TABLE IF EXISTS `prijateljstvo`;
CREATE TABLE `prijateljstvo` (
  `posiljatelj_id` int(10) unsigned NOT NULL,
  `prejemnik_id` int(10) unsigned NOT NULL,
  `datum` datetime NOT NULL,
  `potrditev` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`posiljatelj_id`,`prejemnik_id`),
  KEY `fk_organizatorji_has_organizatorji_organizatorji2` (`prejemnik_id`),
  KEY `fk_organizatorji_has_organizatorji_organizatorji1` (`posiljatelj_id`),
  CONSTRAINT `fk_organizatorji_has_organizatorji_organizatorji1` FOREIGN KEY (`posiljatelj_id`) REFERENCES `uporabniki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_organizatorji_has_organizatorji_organizatorji2` FOREIGN KEY (`prejemnik_id`) REFERENCES `uporabniki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of prijateljstvo
-- ----------------------------
INSERT INTO `prijateljstvo` VALUES ('2', '1', '2012-01-18 03:13:28', '1');
INSERT INTO `prijateljstvo` VALUES ('3', '1', '2012-01-19 00:59:53', '1');
INSERT INTO `prijateljstvo` VALUES ('3', '2', '2012-01-19 01:11:24', '1');

-- ----------------------------
-- Table structure for `rezultati`
-- ----------------------------
DROP TABLE IF EXISTS `rezultati`;
CREATE TABLE `rezultati` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oseba_id` int(10) unsigned NOT NULL,
  `termin_id` int(10) unsigned NOT NULL,
  `naziv` varchar(45) NOT NULL,
  `rezultat` varchar(45) NOT NULL,
  `opomba` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rezultati_udelezba1` (`oseba_id`,`termin_id`),
  CONSTRAINT `fk_rezultati_udelezba1` FOREIGN KEY (`oseba_id`, `termin_id`) REFERENCES `udelezba` (`oseba_id`, `termin_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rezultati
-- ----------------------------
INSERT INTO `rezultati` VALUES ('6', '5', '242', 'Ocena', '10', null);

-- ----------------------------
-- Table structure for `skupine_dog`
-- ----------------------------
DROP TABLE IF EXISTS `skupine_dog`;
CREATE TABLE `skupine_dog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uporabnik_id` int(10) unsigned NOT NULL,
  `naziv` varchar(45) NOT NULL,
  `opomba` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_skupine_dog_organizatorji1` (`uporabnik_id`),
  CONSTRAINT `fk_skupine_dog_organizatorji1` FOREIGN KEY (`uporabnik_id`) REFERENCES `uporabniki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of skupine_dog
-- ----------------------------

-- ----------------------------
-- Table structure for `skupine_oseb`
-- ----------------------------
DROP TABLE IF EXISTS `skupine_oseb`;
CREATE TABLE `skupine_oseb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uporabnik_id` int(10) unsigned NOT NULL,
  `naziv` varchar(45) NOT NULL,
  `opomba` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_skupine_uporabniki1` (`uporabnik_id`),
  CONSTRAINT `fk_skupine_uporabniki1` FOREIGN KEY (`uporabnik_id`) REFERENCES `uporabniki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of skupine_oseb
-- ----------------------------
INSERT INTO `skupine_oseb` VALUES ('4', '1', 'Moška skupina', 'Testna skupina');
INSERT INTO `skupine_oseb` VALUES ('5', '1', 'Ženska Skupina', 'Testna Skupina');
INSERT INTO `skupine_oseb` VALUES ('6', '2', 'Skupinica', '');
INSERT INTO `skupine_oseb` VALUES ('8', '3', 'kmet', 'ftw');

-- ----------------------------
-- Table structure for `termini_dog`
-- ----------------------------
DROP TABLE IF EXISTS `termini_dog`;
CREATE TABLE `termini_dog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dogodek_id` int(10) unsigned NOT NULL,
  `datum` date NOT NULL,
  `cas` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_termini_dog_dogodki1` (`dogodek_id`),
  CONSTRAINT `fk_termini_dog_dogodki1` FOREIGN KEY (`dogodek_id`) REFERENCES `dogodki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of termini_dog
-- ----------------------------
INSERT INTO `termini_dog` VALUES ('233', '8', '2012-01-19', '22:00:00');
INSERT INTO `termini_dog` VALUES ('234', '8', '2012-01-20', '22:00:00');
INSERT INTO `termini_dog` VALUES ('235', '8', '2012-01-21', '22:00:00');
INSERT INTO `termini_dog` VALUES ('236', '8', '2012-01-22', '22:00:00');
INSERT INTO `termini_dog` VALUES ('237', '8', '2012-01-23', '22:00:00');
INSERT INTO `termini_dog` VALUES ('238', '8', '2012-01-24', '22:00:00');
INSERT INTO `termini_dog` VALUES ('239', '8', '2012-01-25', '22:00:00');
INSERT INTO `termini_dog` VALUES ('240', '8', '2012-01-26', '22:00:00');
INSERT INTO `termini_dog` VALUES ('242', '10', '2012-01-19', '18:30:00');
INSERT INTO `termini_dog` VALUES ('243', '11', '2012-01-22', '12:00:00');
INSERT INTO `termini_dog` VALUES ('244', '11', '2012-01-30', '12:00:00');
INSERT INTO `termini_dog` VALUES ('245', '11', '2012-02-07', '12:00:00');
INSERT INTO `termini_dog` VALUES ('246', '11', '2012-02-15', '12:00:00');
INSERT INTO `termini_dog` VALUES ('247', '11', '2012-02-23', '12:00:00');
INSERT INTO `termini_dog` VALUES ('248', '11', '2012-03-02', '12:00:00');
INSERT INTO `termini_dog` VALUES ('249', '11', '2012-03-10', '12:00:00');
INSERT INTO `termini_dog` VALUES ('250', '11', '2012-03-18', '12:00:00');
INSERT INTO `termini_dog` VALUES ('251', '11', '2012-03-26', '12:00:00');
INSERT INTO `termini_dog` VALUES ('252', '11', '2012-04-03', '12:00:00');
INSERT INTO `termini_dog` VALUES ('253', '11', '2012-04-11', '12:00:00');
INSERT INTO `termini_dog` VALUES ('254', '11', '2012-04-19', '12:00:00');
INSERT INTO `termini_dog` VALUES ('255', '11', '2012-04-27', '12:00:00');
INSERT INTO `termini_dog` VALUES ('256', '11', '2012-05-05', '12:00:00');
INSERT INTO `termini_dog` VALUES ('257', '11', '2012-05-13', '12:00:00');
INSERT INTO `termini_dog` VALUES ('258', '11', '2012-05-21', '12:00:00');
INSERT INTO `termini_dog` VALUES ('259', '11', '2012-05-29', '12:00:00');
INSERT INTO `termini_dog` VALUES ('260', '11', '2012-06-06', '12:00:00');

-- ----------------------------
-- Table structure for `udelezba`
-- ----------------------------
DROP TABLE IF EXISTS `udelezba`;
CREATE TABLE `udelezba` (
  `oseba_id` int(10) unsigned NOT NULL,
  `termin_id` int(10) unsigned NOT NULL,
  `rezultat` varchar(45) DEFAULT NULL,
  `opomba` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`oseba_id`,`termin_id`),
  KEY `fk_dogodki_has_udelezenci_udelezenci1` (`oseba_id`),
  KEY `fk_udelezba_termini_dog1` (`termin_id`),
  CONSTRAINT `fk_dogodki_has_udelezenci_udelezenci1` FOREIGN KEY (`oseba_id`) REFERENCES `osebe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_udelezba_termini_dog1` FOREIGN KEY (`termin_id`) REFERENCES `termini_dog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of udelezba
-- ----------------------------
INSERT INTO `udelezba` VALUES ('5', '235', null, null);
INSERT INTO `udelezba` VALUES ('5', '242', null, null);
INSERT INTO `udelezba` VALUES ('5', '243', null, null);
INSERT INTO `udelezba` VALUES ('6', '234', null, null);
INSERT INTO `udelezba` VALUES ('6', '235', null, null);
INSERT INTO `udelezba` VALUES ('6', '239', null, null);
INSERT INTO `udelezba` VALUES ('7', '234', null, null);
INSERT INTO `udelezba` VALUES ('7', '239', null, null);
INSERT INTO `udelezba` VALUES ('7', '243', null, null);
INSERT INTO `udelezba` VALUES ('8', '239', null, null);
INSERT INTO `udelezba` VALUES ('14', '233', null, null);

-- ----------------------------
-- Table structure for `uporabniki`
-- ----------------------------
DROP TABLE IF EXISTS `uporabniki`;
CREATE TABLE `uporabniki` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `priimek` varchar(75) NOT NULL,
  `ime` varchar(75) NOT NULL,
  `email` varchar(75) NOT NULL,
  `up_ime` varchar(45) NOT NULL,
  `geslo` varchar(40) NOT NULL,
  `datum_rojstva` date DEFAULT NULL,
  `spol` varchar(1) DEFAULT 'm',
  `naslov` varchar(255) DEFAULT NULL,
  `telefon` varchar(45) DEFAULT NULL,
  `slika` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `up_ime_UNIQUE` (`up_ime`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uporabniki
-- ----------------------------
INSERT INTO `uporabniki` VALUES ('1', 'Boss', 'Jakob', 'jakob.boss@gmail.com', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '1991-04-21', 'm', 'Celjska cesta 37<br />3250 Rogaška Slatina<br />Slovenija', '040 / 371 - 711', 'uporabnik_1.jpg');
INSERT INTO `uporabniki` VALUES ('2', 'Žolek', 'Matija', 'matija.zolek@gmail.com', 'matija', 'f732dfdbd0aed62727f958cccca9ec3a5cb13eda', null, 'm', null, null, 'uporabnik_2.jpg');
INSERT INTO `uporabniki` VALUES ('3', 'lol', 'lol', 'lol@lol.com', 'nniicc', '403926033d001b5279df37cbbe5287b7c7c267fa', '1944-01-22', 'm', null, null, null);

-- ----------------------------
-- View structure for `dogodki_view`
-- ----------------------------
DROP VIEW IF EXISTS `dogodki_view`;
CREATE VIEW `dogodki_view` AS select `d`.`id` AS `id`,`d`.`organizator_id` AS `organizator_id`,`d`.`naziv` AS `naziv`,`d`.`kraj` AS `kraj`,`d`.`drzava` AS `drzava`,`d`.`opomba` AS `opomba`,`d`.`javni` AS `javni`,`u`.`up_ime` AS `up_ime` from (`dogodki` `d` join `uporabniki` `u`) where (`d`.`organizator_id` = `u`.`id`) ;

-- ----------------------------
-- View structure for `termini_view`
-- ----------------------------
DROP VIEW IF EXISTS `termini_view`;
CREATE VIEW `termini_view` AS select `t`.`id` AS `termin_id`,`t`.`datum` AS `datum`,`t`.`cas` AS `cas`,`d`.`id` AS `dogodek_id`,`d`.`naziv` AS `naziv`,`u`.`id` AS `organizator_id`,`u`.`up_ime` AS `up_ime` from ((`termini_dog` `t` join `dogodki` `d`) join `uporabniki` `u`) where ((`t`.`dogodek_id` = `d`.`id`) and (`d`.`organizator_id` = `u`.`id`)) order by `t`.`datum` ;
