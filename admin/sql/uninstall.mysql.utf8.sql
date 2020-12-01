DROP TABLE IF EXISTS `#__questions_core`;
DROP TABLE IF EXISTS `#__questions_userprofile`;
DROP TABLE IF EXISTS `#__questions_ranks`;
DROP TABLE IF EXISTS `#__questions_reports`;
DROP TABLE IF EXISTS `#__questions_favourite`;
DROP TABLE IF EXISTS `#__questions_userlocation`;
DROP TABLE IF EXISTS `#__questions_notification`;
DROP TABLE IF EXISTS `#__questions_groups`;
DELETE FROM `#__content_types` WHERE `type_alias` = 'com_questions.category';