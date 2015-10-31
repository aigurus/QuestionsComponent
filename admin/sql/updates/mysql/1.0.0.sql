ALTER TABLE `#__questions_userprofile` ADD COLUMN `impressions` int(11) NOT NULL default 0 AFTER `blocked`;



CREATE TABLE `#__questions_favourite`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `ansfav` varchar(250) NOT NULL,
  `quesfav` varchar(250) NOT NULL,
  `userfav` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;