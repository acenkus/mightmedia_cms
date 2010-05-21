ALTER TABLE `nustatymai` ADD UNIQUE (`key`);
INSERT INTO `nustatynai` (`key`, `val`) VALUES ('F_urls', ';');
INSERT INTO `nustatymai` (`key`, `val`) VALUES ('galorder', 'data');
INSERT INTO `nustatymai` (`key`, `val`) VALUES ('galorder_type', 'DESC');
ALTER TABLE `page` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `pavadinimas` , ADD INDEX ( `lang` );
ALTER TABLE `panel` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `panel` , ADD INDEX ( `lang` );
ALTER TABLE `siuntiniai` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `apie` , ADD INDEX ( `lang` );
ALTER TABLE `straipsniai` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `f_text` , ADD INDEX ( `lang` );
ALTER TABLE `nuorodos` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `apie` , ADD INDEX ( `lang` );
ALTER TABLE `naujienos` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `daugiau` , ADD INDEX ( `lang` );
ALTER TABLE `grupes` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `aprasymas` , ADD INDEX ( `lang` );
ALTER TABLE `galerija` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `apie` , ADD INDEX ( `lang` );
ALTER TABLE `d_temos` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `aprasymas` , ADD INDEX ( `lang` );
ALTER TABLE `d_straipsniai` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `pav` , ADD INDEX ( `lang` );
ALTER TABLE `d_forumai` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `pav` , ADD INDEX ( `lang` );
ALTER TABLE `duk` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `atsakymas` , ADD INDEX ( `lang` );
/*ALTER TABLE `balsavimas` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `penktas` , ADD INDEX ( `lang` );*/
CREATE TABLE `poll_answers` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `question_id` int(255) NOT NULL DEFAULT '0',
  `answer` text NOT NULL,
  `lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

CREATE TABLE `poll_questions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `radio` int(1) NOT NULL DEFAULT '0',
  `shown` int(1) NOT NULL DEFAULT '0',
  `only_guests` int(1) NOT NULL,
  `author_id` int(11) NOT NULL DEFAULT '1',
  `author_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'Admin',
  `lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

CREATE TABLE `poll_votes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL DEFAULT '0',
  `question_id` int(255) NOT NULL DEFAULT '0',
  `answer_id` int(255) NOT NULL DEFAULT '0',
  `lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

