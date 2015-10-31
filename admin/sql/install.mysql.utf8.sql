DROP TABLE IF EXISTS `#__questions_core`;
CREATE TABLE IF NOT EXISTS `#__questions_core` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `alias` text NOT NULL,
  `text` text NOT NULL,
  `submitted` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `userid_creator` int(11) NOT NULL,
  `userid_modifier` int(11) DEFAULT NULL,
  `question` int(11) NOT NULL,
  `votes_positive` int(11) NOT NULL,
  `votes_negative` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `impressions` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL,
  `chosen` int(11) NOT NULL,
  `name` text,
  `ip` text,
  `email` text,
  `refurl1` varchar(255) NOT NULL,
  `refurl2` varchar(255) NOT NULL,
  `refurl3` varchar(255) NOT NULL,
  `groups` text NOT NULL,
  `catid` int(11) NOT NULL DEFAULT '0',
  `users_voted` text,
  `qtags` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
REPLACE INTO `#__questions_core` (
`id` ,
`title` ,
`text` ,
`submitted` ,
`modified` ,
`userid_creator` ,
`userid_modifier` ,
`question` ,
`votes_positive` ,
`votes_negative` ,
`parent` ,
`impressions` ,
`published` ,
`chosen` ,
`name`,
`ip`,
`email`,
`refurl1` ,
`refurl2` ,
`refurl3` ,
`groups` ,
`catid`,
`users_voted`,
`qtags`
)
VALUES (
'1', 'Demo Question', 'Demo Question Text', '2011-03-01 20:56:09', NULL , '0', NULL , '1', '0', '0', '0', '0', '1', '0', 'Unknown', '127.0.0.1', 'example@example.com','','','','', '0', NULL, NULL
), (
'2', 'Demo Answer', 'Demo Answer Text', '2011-03-01 20:56:55', NULL , '0', NULL , '0', '0', '0', '1', '0', '1', '0', 'Unknown', '127.0.0.1', 'example@examle.com','http://google.com','http://yahoo.com','http://microsoft.com','','0', NULL, NULL
);

DROP TABLE IF EXISTS `#__questions_favourite`;
CREATE TABLE IF NOT EXISTS `#__questions_favourite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `ansfav` varchar(250) NOT NULL,
  `quesfav` varchar(250) NOT NULL,
  `userfav` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_groups`
--
DROP TABLE IF EXISTS `#__questions_groups`;
CREATE TABLE IF NOT EXISTS `#__questions_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `moderators` text NOT NULL,
  `requestsent` text NOT NULL,
  `requestreceived` text NOT NULL,
  `friendsid` text NOT NULL,
  `published` int(2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_notification`
--
DROP TABLE IF EXISTS `#__questions_notification`;
CREATE TABLE IF NOT EXISTS `#__questions_notification` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `to_user` int(10) NOT NULL DEFAULT '0',
  `from_user` int(10) NOT NULL DEFAULT '0',
  `reference` int(10) NOT NULL DEFAULT '0',
  `type` enum('groupadd','repin') NOT NULL,
  `seen` tinyint(4) NOT NULL DEFAULT '0',
  `timestamp` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `to_user` (`to_user`),
  KEY `from_user` (`from_user`),
  KEY `reference` (`reference`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_ranks`
--
DROP TABLE IF EXISTS `#__questions_ranks`;
CREATE TABLE IF NOT EXISTS `#__questions_ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rank` text NOT NULL,
  `pointsreq` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

REPLACE INTO `#__questions_ranks` (
  `id`,
  `rank`,
  `pointsreq`
)
VALUES ('1', 'starter', '20'), ('2', 'intermidiate', '50'), ('3', 'expert', '100'), ('4', 'boss', '200'), ('5', 'guru', '300'), ('6', 'genius', '400'), ('7', 'champion', '500'), ('8', 'ace', '600'), ('9', 'master', '700'), ('10', 'laser', '800'), ('11', 'crooker', '900'), ('12', 'grandpa', '1000');

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_reports`
--
DROP TABLE IF EXISTS `#__questions_reports`;
CREATE TABLE IF NOT EXISTS `#__questions_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `bugreport` text NOT NULL,
  `qareport` text NOT NULL,
  `qid` int(11) NOT NULL,
  `submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` text,
  `email` text,
  `title` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_userlocation`
--
DROP TABLE IF EXISTS `#__questions_userlocation`;
CREATE TABLE IF NOT EXISTS `#__questions_userlocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `address` varchar(80) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_userprofile`
--
DROP TABLE IF EXISTS `#__questions_userprofile`;
CREATE TABLE IF NOT EXISTS `#__questions_userprofile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `username` text NOT NULL,
  `answered` int(11) NOT NULL,
  `asked` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `rank` text NOT NULL,
  `chosen` int(11) NOT NULL,
  `logdate` date DEFAULT NULL,
  `email` text,
  `groups` varchar(255) NOT NULL,
  `blocked` int(11) NOT NULL,
  `impressions` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;